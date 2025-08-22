#!/bin/bash

echo "🔧 Organizing Environment Files..."
echo "=================================="

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Function to backup current .env
backup_env() {
    if [ -f .env ]; then
        echo -e "${BLUE}📋 Backing up current .env file...${NC}"
        cp .env .env.backup.$(date +%Y%m%d_%H%M%S)
        echo -e "${GREEN}✅ Backup created: .env.backup.$(date +%Y%m%d_%H%M%S)${NC}"
    else
        echo -e "${YELLOW}⚠️  No .env file found to backup${NC}"
    fi
}

# Function to create organized .env
create_organized_env() {
    echo -e "${BLUE}📝 Creating organized .env file...${NC}"
    
    if [ -f .env-organized ]; then
        cp .env-organized .env
        echo -e "${GREEN}✅ Organized .env file created!${NC}"
    else
        echo -e "${RED}❌ .env-organized template not found${NC}"
        exit 1
    fi
}

# Function to show current .env structure
show_current_structure() {
    echo -e "${BLUE}📊 Current .env file structure:${NC}"
    echo ""
    
    if [ -f .env ]; then
        echo "Sections found:"
        grep "^# ==" .env | sed 's/# =============================================================================//' | sed 's/^# //' | sed '/^$/d'
        echo ""
        echo "Total configuration lines: $(grep -c "=" .env)"
        echo "Total sections: $(grep -c "^# ==" .env)"
    else
        echo -e "${YELLOW}No .env file found${NC}"
    fi
}

# Function to validate .env file
validate_env() {
    echo -e "${BLUE}🔍 Validating .env file...${NC}"
    
    if [ -f .env ]; then
        # Check for required variables
        REQUIRED_VARS=("APP_NAME" "APP_ENV" "APP_KEY" "DB_HOST" "DB_DATABASE" "REDIS_HOST")
        MISSING_VARS=()
        
        for var in "${REQUIRED_VARS[@]}"; do
            if ! grep -q "^${var}=" .env; then
                MISSING_VARS+=("$var")
            fi
        done
        
        if [ ${#MISSING_VARS[@]} -eq 0 ]; then
            echo -e "${GREEN}✅ All required variables are present${NC}"
        else
            echo -e "${RED}❌ Missing required variables: ${MISSING_VARS[*]}${NC}"
        fi
        
        # Check for duplicates
        DUPLICATES=$(grep "^[^#]" .env | cut -d'=' -f1 | sort | uniq -d)
        if [ -z "$DUPLICATES" ]; then
            echo -e "${GREEN}✅ No duplicate variables found${NC}"
        else
            echo -e "${YELLOW}⚠️  Duplicate variables found:${NC}"
            echo "$DUPLICATES"
        fi
        
        # Check for empty values
        EMPTY_VALUES=$(grep "^[^#].*=$" .env | cut -d'=' -f1)
        if [ -z "$EMPTY_VALUES" ]; then
            echo -e "${GREEN}✅ No empty values found${NC}"
        else
            echo -e "${YELLOW}⚠️  Variables with empty values:${NC}"
            echo "$EMPTY_VALUES"
        fi
        
    else
        echo -e "${RED}❌ .env file not found${NC}"
    fi
}

# Function to show environment templates
show_templates() {
    echo -e "${BLUE}📚 Available Environment Templates:${NC}"
    echo ""
    
    if [ -f .env-organized ]; then
        echo -e "${GREEN}✅ .env-organized - Clean, organized template${NC}"
    fi
    
    if [ -f .env-docker-basic ]; then
        echo -e "${GREEN}✅ .env-docker-basic - Basic Docker template${NC}"
    fi
    
    if [ -f .env-google-cloud-sql ]; then
        echo -e "${GREEN}✅ .env-google-cloud-sql - Google Cloud SQL template${NC}"
    fi
    
    echo ""
    echo "Use: cp <template> .env"
}

# Function to clean up old environment files
cleanup_env_files() {
    echo -e "${BLUE}🧹 Cleaning up old environment files...${NC}"
    
    # Remove old backup files (keep only last 3)
    BACKUP_COUNT=$(ls -1 .env.backup.* 2>/dev/null | wc -l)
    if [ $BACKUP_COUNT -gt 3 ]; then
        echo "Removing old backup files..."
        ls -1 .env.backup.* | head -n $(($BACKUP_COUNT - 3)) | xargs rm -f
        echo -e "${GREEN}✅ Old backups cleaned up${NC}"
    fi
    
    # Remove template files if .env is already organized
    if [ -f .env ] && [ -f .env-organized ]; then
        echo "Template files can be removed if no longer needed:"
        echo "  rm .env-organized"
        echo "  rm .env-docker-basic"
        echo "  rm .env-google-cloud-sql"
    fi
}

# Main execution
case "${1:-help}" in
    organize)
        backup_env
        create_organized_env
        validate_env
        ;;
    backup)
        backup_env
        ;;
    validate)
        validate_env
        ;;
    structure)
        show_current_structure
        ;;
    templates)
        show_templates
        ;;
    cleanup)
        cleanup_env_files
        ;;
    help|*)
        echo "Usage: $0 {organize|backup|validate|structure|templates|cleanup|help}"
        echo ""
        echo "Commands:"
        echo "  organize  - Backup current .env and create organized version"
        echo "  backup    - Create backup of current .env file"
        echo "  validate  - Validate current .env file for issues"
        echo "  structure - Show current .env file structure"
        echo "  templates - Show available environment templates"
        echo "  cleanup   - Clean up old backup and template files"
        echo "  help      - Show this help message"
        echo ""
        echo "Examples:"
        echo "  $0 organize    # Organize your .env file"
        echo "  $0 validate    # Check for issues"
        echo "  $0 structure   # View current structure"
        ;;
esac
