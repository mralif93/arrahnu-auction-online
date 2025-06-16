# Cron Job Setup for Automatic Auction Management

## Overview
This document explains how to set up and manage cron jobs for automatic auction status updates in the ArRahnu Auction Online system. The cron job will automatically:

- ✅ Start scheduled auctions when their start time is reached
- ✅ Complete active auctions when their end time is reached  
- ✅ Handle edge cases (auctions that missed their active window)
- ✅ Log all status changes for monitoring

## Quick Setup

### Option 1: Using the Setup Script (Recommended)
```bash
# Make the script executable (if not already)
chmod +x scripts/setup-cron.sh

# Install cron job to run every 5 minutes (recommended)
./scripts/setup-cron.sh install 5min

# Or install to run every minute (most responsive)
./scripts/setup-cron.sh install 1min

# View current cron jobs
./scripts/setup-cron.sh show

# Test the command
./scripts/setup-cron.sh test
```

### Option 2: Manual Setup
```bash
# Edit crontab
crontab -e

# Add this line for every 5 minutes:
*/5 * * * * cd /path/to/your/project && php artisan auctions:update-statuses >> /path/to/your/project/storage/logs/cron-auction-status.log 2>&1

# Or for every minute (more responsive):
* * * * * cd /path/to/your/project && php artisan auctions:update-statuses >> /path/to/your/project/storage/logs/cron-auction-status.log 2>&1
```

## Current Configuration

### Active Cron Job
```
*/5 * * * * cd /Users/administrator/Desktop/Project/Github/arrahnu-auction-online && /Users/administrator/.config/herd-lite/bin/php artisan auctions:update-statuses >> /Users/administrator/Desktop/Project/Github/arrahnu-auction-online/storage/logs/cron-auction-status.log 2>&1
```

**What this means:**
- **`*/5 * * * *`**: Run every 5 minutes
- **`cd /path/to/project`**: Change to project directory
- **`php artisan auctions:update-statuses`**: Run the auction status update command
- **`>> logfile 2>&1`**: Append output and errors to log file

## Frequency Options

### Every Minute (Most Responsive)
```bash
* * * * * [command]
```
- **Pros**: Auctions start/end within 1 minute of scheduled time
- **Cons**: Higher server load, more log entries
- **Best for**: High-traffic auction sites, time-critical auctions

### Every 5 Minutes (Recommended)
```bash
*/5 * * * *
```
- **Pros**: Good balance of responsiveness and server load
- **Cons**: Up to 5-minute delay in status changes
- **Best for**: Most auction sites, general use

### Every 15 Minutes
```bash
*/15 * * * *
```
- **Pros**: Lower server load
- **Cons**: Up to 15-minute delay in status changes
- **Best for**: Low-traffic sites, less time-critical auctions

### Every Hour
```bash
0 * * * *
```
- **Pros**: Minimal server load
- **Cons**: Up to 1-hour delay in status changes
- **Best for**: Development/testing environments

## Management Commands

### Using the Setup Script

```bash
# Show available commands
./scripts/setup-cron.sh

# Install cron job with different frequencies
./scripts/setup-cron.sh install 1min     # Every minute
./scripts/setup-cron.sh install 5min     # Every 5 minutes (default)
./scripts/setup-cron.sh install 15min    # Every 15 minutes
./scripts/setup-cron.sh install hourly   # Every hour

# Remove cron job
./scripts/setup-cron.sh remove

# Show current cron jobs
./scripts/setup-cron.sh show

# Test the auction command
./scripts/setup-cron.sh test

# View recent log entries
./scripts/setup-cron.sh logs           # Last 20 lines
./scripts/setup-cron.sh logs 50        # Last 50 lines

# Monitor logs in real-time
./scripts/setup-cron.sh monitor
```

### Manual Commands

```bash
# View current cron jobs
crontab -l

# Edit cron jobs
crontab -e

# Remove all cron jobs (be careful!)
crontab -r

# Test the auction command manually
php artisan auctions:update-statuses --dry-run
php artisan auctions:update-statuses
```

## Monitoring and Logs

### Log File Location
```
storage/logs/cron-auction-status.log
```

### Viewing Logs
```bash
# View recent log entries
tail -n 20 storage/logs/cron-auction-status.log

# Monitor logs in real-time
tail -f storage/logs/cron-auction-status.log

# Search for specific events
grep "Updated.*auction" storage/logs/cron-auction-status.log
grep "ERROR" storage/logs/cron-auction-status.log
```

### Log Rotation
To prevent log files from growing too large, set up log rotation:

```bash
# Create logrotate configuration
sudo nano /etc/logrotate.d/arrahnu-auction

# Add this content:
/path/to/your/project/storage/logs/cron-auction-status.log {
    daily
    rotate 30
    compress
    delaycompress
    missingok
    notifempty
    create 644 www-data www-data
}
```

## What the Cron Job Does

### Automatic Status Transitions

1. **SCHEDULED → ACTIVE**
   - When: Current time >= start_datetime AND <= end_datetime
   - Action: Changes auction status to 'active'
   - Side effects: Updates related collaterals to 'auctioning' status

2. **ACTIVE → COMPLETED**
   - When: Current time > end_datetime
   - Action: Changes auction status to 'completed'
   - Side effects: 
     - Processes all bids
     - Updates collaterals to 'sold' or 'unsold'
     - Creates auction results for sold items

3. **SCHEDULED → COMPLETED** (Skip Active)
   - When: Scheduled auction's end_datetime has already passed
   - Action: Changes auction status directly to 'completed'
   - Reason: Auction missed its active window

### Example Log Output
```
🔄 Checking auction statuses...
Current time: 2025-06-14 15:32:33

📅 January 2025 Live Gold & Jewelry Auction
   Current: active → New: completed
   Reason: End time reached
   Start: 2025-06-12 15:26
   End: 2025-06-14 15:31
   ✅ Completed auction

✅ Updated 1 auction(s) successfully.

📊 Current Auction Status Summary:
   🟢 active: 0
   🔵 scheduled: 2
   ✅ completed: 8
   🟡 cancelled: 2
   🔴 rejected: 2
```

## Troubleshooting

### Common Issues

**Cron job not running:**
```bash
# Check if cron service is running
sudo service cron status

# Check system logs
sudo tail -f /var/log/syslog | grep CRON

# Verify cron job syntax
crontab -l
```

**PHP path issues:**
```bash
# Find PHP path
which php

# Update cron job with full PHP path
*/5 * * * * cd /path/to/project && /full/path/to/php artisan auctions:update-statuses >> /path/to/logs 2>&1
```

**Permission issues:**
```bash
# Ensure log directory is writable
chmod 755 storage/logs
touch storage/logs/cron-auction-status.log
chmod 644 storage/logs/cron-auction-status.log
```

**Command not found:**
```bash
# Ensure you're in the correct directory
cd /full/path/to/your/laravel/project

# Test the command manually
php artisan auctions:update-statuses --dry-run
```

### Debugging Steps

1. **Test the command manually:**
   ```bash
   cd /path/to/project
   php artisan auctions:update-statuses --dry-run
   ```

2. **Check cron job syntax:**
   ```bash
   crontab -l
   ```

3. **Monitor cron execution:**
   ```bash
   tail -f /var/log/syslog | grep CRON
   ```

4. **Check application logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

5. **Verify file permissions:**
   ```bash
   ls -la storage/logs/
   ```

## Security Considerations

### File Permissions
- Log files should be readable/writable by the web server user
- Cron jobs run as the user who created them
- Ensure proper directory permissions for storage/logs

### Log File Security
- Log files may contain sensitive information
- Consider log rotation to prevent files from growing too large
- Restrict access to log files in production

### Error Handling
- The command includes error redirection (`2>&1`)
- Failed executions are logged for debugging
- The system gracefully handles database connection issues

## Production Deployment

### Recommended Setup for Production

1. **Use every 5-minute frequency** (good balance of responsiveness and performance)
2. **Set up log rotation** to prevent disk space issues
3. **Monitor cron job execution** with system monitoring tools
4. **Set up alerts** for failed auction status updates
5. **Use proper file permissions** and security measures

### Example Production Cron Job
```bash
*/5 * * * * cd /var/www/arrahnu-auction && /usr/bin/php artisan auctions:update-statuses >> /var/www/arrahnu-auction/storage/logs/cron-auction-status.log 2>&1
```

## Testing the Setup

### Verification Steps

1. **Install the cron job:**
   ```bash
   ./scripts/setup-cron.sh install 5min
   ```

2. **Verify installation:**
   ```bash
   ./scripts/setup-cron.sh show
   ```

3. **Test the command:**
   ```bash
   ./scripts/setup-cron.sh test
   ```

4. **Wait 5 minutes and check logs:**
   ```bash
   ./scripts/setup-cron.sh logs
   ```

5. **Monitor real-time execution:**
   ```bash
   ./scripts/setup-cron.sh monitor
   ```

## Current Status

✅ **Cron job is installed and running every 5 minutes**
✅ **Automatic auction status updates are working**
✅ **Logging is configured and working**
✅ **Management scripts are available**
✅ **System tested and verified**

Your auction system now automatically manages auction lifecycles based on start and end dates! 