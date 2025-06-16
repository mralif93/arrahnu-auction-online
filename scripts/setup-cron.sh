#!/bin/bash

# ArRahnu Auction Online - Cron Job Setup Script
# This script sets up automatic auction status management

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Get the directory where this script is located
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_DIR="$(dirname "$SCRIPT_DIR")"

# Get PHP path
PHP_PATH=$(which php)
if [ -z "$PHP_PATH" ]; then
    # Try Herd Lite path
    PHP_PATH="/Users/$(whoami)/.config/herd-lite/bin/php"
    if [ ! -f "$PHP_PATH" ]; then
        echo -e "${RED}❌ PHP not found. Please install PHP or update the path in this script.${NC}"
        exit 1
    fi
fi

echo -e "${BLUE}🚀 ArRahnu Auction Cron Job Setup${NC}"
echo "=================================="
echo "Project Directory: $PROJECT_DIR"
echo "PHP Path: $PHP_PATH"
echo ""

# Create logs directory
echo -e "${YELLOW}📁 Creating logs directory...${NC}"
mkdir -p "$PROJECT_DIR/storage/logs"
touch "$PROJECT_DIR/storage/logs/cron-auction-status.log"

# Function to install cron job
install_cron() {
    local frequency=$1
    local cron_expression=""
    
    case $frequency in
        "1min")
            cron_expression="* * * * *"
            echo -e "${YELLOW}⏰ Setting up cron job to run every minute (most responsive)${NC}"
            ;;
        "5min")
            cron_expression="*/5 * * * *"
            echo -e "${YELLOW}⏰ Setting up cron job to run every 5 minutes (recommended)${NC}"
            ;;
        "15min")
            cron_expression="*/15 * * * *"
            echo -e "${YELLOW}⏰ Setting up cron job to run every 15 minutes${NC}"
            ;;
        "hourly")
            cron_expression="0 * * * *"
            echo -e "${YELLOW}⏰ Setting up cron job to run every hour${NC}"
            ;;
        *)
            echo -e "${RED}❌ Invalid frequency. Use: 1min, 5min, 15min, or hourly${NC}"
            exit 1
            ;;
    esac
    
    # Create the cron job entry
    CRON_JOB="$cron_expression cd $PROJECT_DIR && $PHP_PATH artisan auctions:update-statuses >> $PROJECT_DIR/storage/logs/cron-auction-status.log 2>&1"
    
    # Add to crontab
    (crontab -l 2>/dev/null | grep -v "auctions:update-statuses"; echo "$CRON_JOB") | crontab -
    
    echo -e "${GREEN}✅ Cron job installed successfully!${NC}"
    echo ""
    echo "Cron job details:"
    echo "  Command: php artisan auctions:update-statuses"
    echo "  Frequency: $frequency"
    echo "  Log file: storage/logs/cron-auction-status.log"
}

# Function to remove cron job
remove_cron() {
    echo -e "${YELLOW}🗑️  Removing auction cron jobs...${NC}"
    crontab -l 2>/dev/null | grep -v "auctions:update-statuses" | crontab -
    echo -e "${GREEN}✅ Cron jobs removed successfully!${NC}"
}

# Function to show current cron jobs
show_cron() {
    echo -e "${BLUE}📋 Current cron jobs:${NC}"
    crontab -l 2>/dev/null | grep -E "(auctions:update-statuses|^#.*[Aa]uction)" || echo "No auction-related cron jobs found."
}

# Function to test the command
test_command() {
    echo -e "${YELLOW}🧪 Testing auction status update command...${NC}"
    cd "$PROJECT_DIR"
    $PHP_PATH artisan auctions:update-statuses --dry-run
    echo ""
    echo -e "${GREEN}✅ Command test completed!${NC}"
}

# Function to show logs
show_logs() {
    local lines=${1:-20}
    echo -e "${BLUE}📄 Last $lines lines of cron log:${NC}"
    if [ -f "$PROJECT_DIR/storage/logs/cron-auction-status.log" ]; then
        tail -n "$lines" "$PROJECT_DIR/storage/logs/cron-auction-status.log"
    else
        echo "Log file not found. Cron job may not have run yet."
    fi
}

# Function to monitor logs in real-time
monitor_logs() {
    echo -e "${BLUE}👀 Monitoring cron logs (Press Ctrl+C to stop):${NC}"
    if [ -f "$PROJECT_DIR/storage/logs/cron-auction-status.log" ]; then
        tail -f "$PROJECT_DIR/storage/logs/cron-auction-status.log"
    else
        echo "Log file not found. Creating and monitoring..."
        touch "$PROJECT_DIR/storage/logs/cron-auction-status.log"
        tail -f "$PROJECT_DIR/storage/logs/cron-auction-status.log"
    fi
}

# Main menu
case "${1:-menu}" in
    "install")
        install_cron "${2:-5min}"
        ;;
    "remove")
        remove_cron
        ;;
    "show")
        show_cron
        ;;
    "test")
        test_command
        ;;
    "logs")
        show_logs "${2:-20}"
        ;;
    "monitor")
        monitor_logs
        ;;
    "menu"|*)
        echo -e "${BLUE}Available commands:${NC}"
        echo "  ./setup-cron.sh install [1min|5min|15min|hourly]  - Install cron job (default: 5min)"
        echo "  ./setup-cron.sh remove                            - Remove cron job"
        echo "  ./setup-cron.sh show                              - Show current cron jobs"
        echo "  ./setup-cron.sh test                              - Test the auction command"
        echo "  ./setup-cron.sh logs [lines]                      - Show recent log entries"
        echo "  ./setup-cron.sh monitor                           - Monitor logs in real-time"
        echo ""
        echo -e "${YELLOW}Examples:${NC}"
        echo "  ./setup-cron.sh install 5min    # Run every 5 minutes (recommended)"
        echo "  ./setup-cron.sh install 1min    # Run every minute (most responsive)"
        echo "  ./setup-cron.sh logs 50         # Show last 50 log entries"
        ;;
esac 