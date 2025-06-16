<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use App\Traits\ApiResponse;

class SettingsController extends Controller
{
    use ApiResponse;

    /**
     * Display the admin settings page.
     */
    public function index()
    {
        $settings = $this->getSystemSettings();
        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Get current system settings.
     */
    public function getSettings()
    {
        $settings = $this->getSystemSettings();
        
        return $this->successResponse($settings, 'Settings retrieved successfully');
    }

    /**
     * Update system settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'two_factor_enabled' => 'required|boolean',
            'two_factor_code_expiry' => 'required|integer|min:300|max:3600', // 5 minutes to 1 hour
            'session_lifetime' => 'nullable|integer|min:60|max:43200', // 1 minute to 12 hours
            'max_login_attempts' => 'nullable|integer|min:3|max:10',
            'lockout_duration' => 'nullable|integer|min:60|max:3600', // 1 minute to 1 hour
        ]);

        try {
            // Update 2FA settings
            $this->updateEnvValue('TWO_FACTOR_ENABLED', $request->two_factor_enabled ? 'true' : 'false');
            $this->updateEnvValue('TWO_FACTOR_CODE_EXPIRY', $request->two_factor_code_expiry);

            // Update session settings if provided
            if ($request->has('session_lifetime')) {
                $this->updateEnvValue('SESSION_LIFETIME', $request->session_lifetime);
            }

            // Update security settings if provided
            if ($request->has('max_login_attempts')) {
                $this->updateEnvValue('LOGIN_MAX_ATTEMPTS', $request->max_login_attempts);
            }

            if ($request->has('lockout_duration')) {
                $this->updateEnvValue('LOGIN_LOCKOUT_DURATION', $request->lockout_duration);
            }

            // Clear config cache to apply changes
            Artisan::call('config:clear');
            Cache::forget('system_settings');

            return $this->successResponse($this->getSystemSettings(), 'Settings updated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to update settings: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Toggle 2FA feature on/off.
     */
    public function toggle2FA(Request $request)
    {
        $request->validate([
            'enabled' => 'required|boolean'
        ]);

        try {
            $enabled = $request->boolean('enabled');
            
            // Update environment variable
            $this->updateEnvValue('TWO_FACTOR_ENABLED', $enabled ? 'true' : 'false');
            
            // Clear config cache
            Artisan::call('config:clear');
            Cache::forget('system_settings');

            $message = $enabled ? '2FA has been enabled' : '2FA has been disabled';
            
            return $this->successResponse($message, [
                'two_factor_enabled' => $enabled,
                'updated_at' => now()->toISOString()
            ]);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to toggle 2FA: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get system settings from various sources.
     */
    private function getSystemSettings()
    {
        return Cache::remember('system_settings', 300, function () {
            return [
                'security' => [
                    'two_factor_enabled' => env('TWO_FACTOR_ENABLED', true),
                    'two_factor_code_expiry' => env('TWO_FACTOR_CODE_EXPIRY', 750),
                    'session_lifetime' => env('SESSION_LIFETIME', 120),
                    'max_login_attempts' => env('LOGIN_MAX_ATTEMPTS', 5),
                    'lockout_duration' => env('LOGIN_LOCKOUT_DURATION', 300),
                ],
                'system' => [
                    'app_name' => env('APP_NAME', 'ArRahnu Auction'),
                    'app_env' => env('APP_ENV', 'production'),
                    'app_debug' => env('APP_DEBUG', false),
                    'app_url' => env('APP_URL', 'http://localhost'),
                ],
                'mail' => [
                    'mail_mailer' => env('MAIL_MAILER', 'smtp'),
                    'mail_host' => env('MAIL_HOST', 'smtp.mailtrap.io'),
                    'mail_port' => env('MAIL_PORT', 2525),
                    'mail_from_address' => env('MAIL_FROM_ADDRESS', 'noreply@arrahnu.com'),
                ],
                'database' => [
                    'db_connection' => env('DB_CONNECTION', 'mysql'),
                    'db_host' => env('DB_HOST', '127.0.0.1'),
                    'db_port' => env('DB_PORT', '3306'),
                    'db_database' => env('DB_DATABASE', 'arrahnu_auction'),
                ],
                'cache' => [
                    'cache_driver' => env('CACHE_DRIVER', 'file'),
                    'session_driver' => env('SESSION_DRIVER', 'file'),
                    'queue_connection' => env('QUEUE_CONNECTION', 'sync'),
                ],
                'meta' => [
                    'last_updated' => Cache::get('settings_last_updated', now()->toISOString()),
                    'updated_by' => Cache::get('settings_updated_by', 'System'),
                ]
            ];
        });
    }

    /**
     * Update environment variable in .env file.
     */
    private function updateEnvValue($key, $value)
    {
        $envFile = base_path('.env');
        
        if (!File::exists($envFile)) {
            throw new \Exception('.env file not found');
        }

        $envContent = File::get($envFile);
        
        // Convert boolean to string
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }

        // Escape special characters in value
        $value = '"' . str_replace('"', '\"', $value) . '"';

        // Check if key exists
        if (preg_match("/^{$key}=.*/m", $envContent)) {
            // Update existing key
            $envContent = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $envContent);
        } else {
            // Add new key
            $envContent .= "\n{$key}={$value}";
        }

        File::put($envFile, $envContent);

        // Update cache metadata
        Cache::put('settings_last_updated', now()->toISOString(), 3600);
        Cache::put('settings_updated_by', auth()->user()->full_name ?? 'Admin', 3600);
    }

    /**
     * Reset settings to default values.
     */
    public function resetToDefaults(Request $request)
    {
        try {
            // Default security settings
            $this->updateEnvValue('TWO_FACTOR_ENABLED', 'true');
            $this->updateEnvValue('TWO_FACTOR_CODE_EXPIRY', '750');
            $this->updateEnvValue('SESSION_LIFETIME', '120');
            $this->updateEnvValue('LOGIN_MAX_ATTEMPTS', '5');
            $this->updateEnvValue('LOGIN_LOCKOUT_DURATION', '300');

            // Clear caches
            Artisan::call('config:clear');
            Cache::forget('system_settings');

            return $this->successResponse($this->getSystemSettings(), 'Settings reset to defaults successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to reset settings: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get system status for settings page.
     */
    public function getSystemStatus()
    {
        try {
            $status = [
                'php_version' => PHP_VERSION,
                'laravel_version' => app()->version(),
                'environment' => env('APP_ENV', 'production'),
                'debug_mode' => env('APP_DEBUG', false),
                'timezone' => config('app.timezone'),
                'locale' => config('app.locale'),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
                'mail_driver' => config('mail.default'),
                'database_connection' => config('database.default'),
                'storage_writable' => is_writable(storage_path()),
                'config_cached' => file_exists(base_path('bootstrap/cache/config.php')),
                'routes_cached' => file_exists(base_path('bootstrap/cache/routes-v7.php')),
                'views_cached' => is_dir(storage_path('framework/views')) && count(glob(storage_path('framework/views').'/*')) > 0,
            ];

            return $this->successResponse($status, 'System status retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to get system status: ' . $e->getMessage(), 500);
        }
    }
}
