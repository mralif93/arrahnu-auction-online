# Auction Status Management

## Overview
The ArRahnu Auction Online system automatically manages auction statuses based on their start and end dates. This document explains how the system determines whether an auction should be live, completed, or scheduled.

## Current Auction Configuration

### Live Auction (1)
- **January 2025 Live Gold & Jewelry Auction** (ACTIVE)
  - Start: June 12, 2025 15:26
  - End: June 19, 2025 15:26
  - Status: ✅ Currently accepting bids

### Completed Auctions (7)
All completed auctions have proper historical dates in the past:
- December 2024 Premium Collection (ended May 15, 2025)
- November 2024 Diamond Showcase (ended April 30, 2025)
- October 2024 Silver Collection (ended April 15, 2025)
- September 2024 Mixed Assets Auction (ended March 31, 2025)
- August 2024 Luxury Items Sale (ended March 16, 2025)
- February 2025 Valentine Collection (ended March 1, 2025)
- March 2025 Spring Preview (ended February 14, 2025)

### Scheduled Auctions (2)
- **April 2025 Easter Jewelry Sale** (starts June 21, 2025)
- **Draft Items Collection** (starts July 5, 2025)

### Cancelled Auctions (2)
- July 2024 Precious Metals Auction (cancelled)
- June 2024 Summer Collection (cancelled)

### Rejected Auctions (2)
- May 2024 Spring Jewelry Sale (rejected)
- April 2024 Easter Special (rejected)

## Status Logic Rules

### 1. SCHEDULED → ACTIVE
An auction automatically becomes ACTIVE when:
- Current status is `scheduled`
- Current time >= `start_datetime`
- Current time <= `end_datetime`

### 2. ACTIVE → COMPLETED
An auction automatically becomes COMPLETED when:
- Current status is `active`
- Current time > `end_datetime`

### 3. SCHEDULED → COMPLETED (Skip Active)
An auction can go directly to COMPLETED when:
- Current status is `scheduled`
- Current time > `end_datetime` (missed the active window)

## Auction Model Methods

### Status Check Methods
```php
// Check if auction is currently live
$auction->isActive(); // Returns true if status=active AND within date range

// Check if auction is scheduled to start
$auction->isScheduled(); // Returns true if status=scheduled AND before start time

// Check if auction has ended
$auction->hasEnded(); // Returns true if status=completed OR past end time
```

### Status Transition Methods
```php
// Start a scheduled auction
$auction->start(); // Changes status to 'active' and updates collaterals

// Complete an active auction
$auction->complete(); // Changes status to 'completed' and processes results

// Cancel an auction
$auction->cancel(); // Changes status to 'cancelled'
```

### Query Scopes
```php
// Get only active auctions (with date validation)
Auction::active()->get();

// Get only scheduled auctions (with date validation)
Auction::scheduled()->get();

// Get only completed auctions
Auction::completed()->get();
```

## Automatic Status Updates

### Artisan Command
Use the custom command to update auction statuses:

```bash
# Check what would be updated (dry run)
php artisan auctions:update-statuses --dry-run

# Apply status updates
php artisan auctions:update-statuses
```

### Command Features
- ✅ Automatically starts scheduled auctions when start time is reached
- ✅ Automatically completes active auctions when end time is reached
- ✅ Handles edge cases (scheduled auctions that missed their active window)
- ✅ Provides detailed logging of all changes
- ✅ Dry-run mode for testing
- ✅ Status summary report

### Recommended Scheduling
Set up a cron job to run the status update command regularly:

```bash
# Every 5 minutes
*/5 * * * * php /path/to/artisan auctions:update-statuses

# Or every hour for less frequent updates
0 * * * * php /path/to/artisan auctions:update-statuses
```

## Date Validation Rules

### Creating New Auctions
- `start_datetime` must be in the future
- `end_datetime` must be after `start_datetime`
- Minimum auction duration: 1 hour (recommended)
- Maximum auction duration: 30 days (recommended)

### Editing Auctions
- Only `draft` and `rejected` auctions can be edited
- Date changes must still follow validation rules
- Status automatically resets to `pending_approval` after editing

## Collateral Status Integration

When auction status changes, related collaterals are automatically updated:

### Auction Starts (scheduled → active)
- Collaterals change from `ready_for_auction` → `auctioning`

### Auction Completes (active → completed)
- Collaterals with bids: `auctioning` → `sold`
- Collaterals without bids: `auctioning` → `unsold`
- Auction results are created for sold items

### Auction Cancelled
- All collaterals revert to `active` status

## Best Practices

### 1. Date Management
- Always use proper timezone handling
- Set realistic auction durations (3-7 days typical)
- Allow buffer time between consecutive auctions

### 2. Status Monitoring
- Run status update command regularly
- Monitor auction transitions in logs
- Set up alerts for failed status updates

### 3. Testing
- Use dry-run mode before applying changes
- Test date edge cases in development
- Verify collateral status updates

### 4. Data Integrity
- Ensure start_datetime < end_datetime
- Validate timezone consistency
- Check for overlapping auction schedules

## Troubleshooting

### Common Issues

**Auction not starting automatically:**
- Check if start_datetime is in the past
- Verify auction status is 'scheduled'
- Run status update command manually

**Auction not completing:**
- Check if end_datetime has passed
- Verify auction status is 'active'
- Look for errors in application logs

**Date inconsistencies:**
- Verify server timezone settings
- Check database timezone configuration
- Ensure consistent date formatting

### Manual Status Updates
If automatic updates fail, you can manually update statuses:

```php
// In tinker or controller
$auction = Auction::find('auction-id');

// Start auction
$auction->start();

// Complete auction
$auction->complete();

// Cancel auction
$auction->cancel();
```

## Current System Status

✅ **All auction dates are properly configured**
✅ **1 active auction currently accepting bids**
✅ **7 completed auctions with historical data**
✅ **2 scheduled auctions for future dates**
✅ **Automatic status management system in place**
✅ **Date validation working correctly**

The system is now properly configured with realistic auction timing that accurately reflects live vs completed auction status based on start and end dates. 