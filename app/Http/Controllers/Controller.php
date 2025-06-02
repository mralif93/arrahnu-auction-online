<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

abstract class Controller
{
    /**
     * Handle controller action with try-catch wrapper.
     */
    protected function handleAction(callable $action, Request $request, string $successMessage = null, string $errorMessage = null): JsonResponse|RedirectResponse
    {
        try {
            $result = $action();

            $message = $successMessage ?? 'Operation completed successfully.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'data' => $result ?? null
                ]);
            }

            return redirect()->back()->with('success', $message);

        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed.',
                    'errors' => $e->errors()
                ], 422);
            }

            throw $e;

        } catch (\Exception $e) {
            Log::error('Controller action failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            $message = $errorMessage ?? 'An error occurred while processing your request.';

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            return redirect()->back()->with('error', $message);
        }
    }

    /**
     * Handle redirect with proper route resolution.
     */
    protected function redirectToIndex(string $route, string $message, string $type = 'success'): RedirectResponse
    {
        return redirect()->route($route)->with($type, $message);
    }

    /**
     * Handle JSON response for API endpoints.
     */
    protected function jsonResponse(bool $success, string $message, $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'message' => $message,
            'data' => $data
        ], $status);
    }

    /**
     * Get common validation messages.
     */
    protected function getValidationMessages(): array
    {
        return [
            'required' => 'The :attribute field is required.',
            'string' => 'The :attribute must be a string.',
            'email' => 'The :attribute must be a valid email address.',
            'unique' => 'The :attribute has already been taken.',
            'max' => 'The :attribute may not be greater than :max characters.',
            'min' => 'The :attribute must be at least :min characters.',
            'numeric' => 'The :attribute must be a number.',
            'date' => 'The :attribute must be a valid date.',
            'after' => 'The :attribute must be a date after :date.',
            'exists' => 'The selected :attribute is invalid.',
            'in' => 'The selected :attribute is invalid.',
            'confirmed' => 'The :attribute confirmation does not match.',
            'image' => 'The :attribute must be an image.',
            'mimes' => 'The :attribute must be a file of type: :values.',
        ];
    }
}
