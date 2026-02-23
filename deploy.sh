#!/bin/bash

# ============================================
# FEECS Deployment Script
# ============================================
# Usage: ./deploy.sh [environment] [action]
# Environments: staging, production
# Actions: deploy, rollback, status

set -e

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
ENVIRONMENT=${1:-staging}
ACTION=${2:-deploy}
TIMESTAMP=$(date +%Y%m%d_%H%M%S)

# Load environment-specific configuration
load_config() {
    case $ENVIRONMENT in
        staging)
            REMOTE_HOST=${STAGING_HOST:-"staging.feecs.local"}
            REMOTE_USER=${STAGING_USER:-"deploy"}
            REMOTE_PATH=${STAGING_PATH:-"/var/www/staging.feecs"}
            ;;
        production)
            REMOTE_HOST=${PRODUCTION_HOST:-"feecs.local"}
            REMOTE_USER=${PRODUCTION_USER:-"deploy"}
            REMOTE_PATH=${PRODUCTION_PATH:-"/var/www/feecs"}
            ;;
        *)
            echo -e "${RED}Invalid environment: $ENVIRONMENT${NC}"
            echo "Usage: $0 [staging|production] [deploy|rollback|status]"
            exit 1
            ;;
    esac
}

# Print banner
print_banner() {
    echo -e "${BLUE}"
    echo "============================================"
    echo "  FEECS Deployment Script"
    echo "============================================"
    echo -e "${NC}"
    echo -e "Environment: ${YELLOW}$ENVIRONMENT${NC}"
    echo -e "Action:      ${YELLOW}$ACTION${NC}"
    echo -e "Host:        ${YELLOW}$REMOTE_HOST${NC}"
    echo -e "Path:        ${YELLOW}$REMOTE_PATH${NC}"
    echo ""
}

# Pre-deployment checks
pre_deploy_checks() {
    echo -e "${BLUE}Running pre-deployment checks...${NC}"
    
    # Check if git is clean
    if [[ -n $(git status -s) ]]; then
        echo -e "${YELLOW}Warning: You have uncommitted changes${NC}"
        git status -s
        read -p "Continue anyway? (y/n) " -n 1 -r
        echo
        if [[ ! $REPLY =~ ^[Yy]$ ]]; then
            exit 1
        fi
    fi
    
    # Check if tests pass
    echo "Running tests..."
    if ! composer test; then
        echo -e "${RED}Tests failed! Aborting deployment.${NC}"
        exit 1
    fi
    
    echo -e "${GREEN}Pre-deployment checks passed!${NC}"
}

# Deploy to remote server
deploy() {
    echo -e "${BLUE}Starting deployment...${NC}"
    
    # Get current branch and commit
    BRANCH=$(git rev-parse --abbrev-ref HEAD)
    COMMIT=$(git rev-parse HEAD)
    
    echo -e "Branch: ${YELLOW}$BRANCH${NC}"
    echo -e "Commit: ${YELLOW}$COMMIT${NC}"
    
    # SSH into server and deploy
    ssh $REMOTE_USER@$REMOTE_HOST << EOF
        set -e
        
        echo "Creating backup..."
        BACKUP_DIR="${REMOTE_PATH}_backups/${TIMESTAMP}"
        mkdir -p \$BACKUP_DIR
        cp -r ${REMOTE_PATH}/* \$BACKUP_DIR/ 2>/dev/null || true
        
        echo "Navigating to application directory..."
        cd ${REMOTE_PATH}
        
        echo "Pulling latest changes..."
        git fetch origin
        git reset --hard ${COMMIT}
        
        echo "Installing dependencies..."
        composer install --prefer-dist --no-dev --optimize-autoloader --no-scripts
        
        echo "Running migrations..."
        php spark migrate --all
        
        echo "Clearing cache..."
        php spark cache:clear
        
        echo "Setting permissions..."
        chmod -R 755 writable
        chown -R www-data:www-data writable
        
        echo "Updating version file..."
        echo "${COMMIT}" > VERSION
        echo "$(date -u +"%Y-%m-%dT%H:%M:%SZ")" >> VERSION
        
        echo "Deployment completed successfully!"
EOF
    
    echo -e "${GREEN}Deployment completed!${NC}"
}

# Rollback to previous version
rollback() {
    echo -e "${YELLOW}Starting rollback...${NC}"
    
    ssh $REMOTE_USER@$REMOTE_HOST << EOF
        set -e
        
        BACKUP_DIR="${REMOTE_PATH}_backups"
        
        echo "Available backups:"
        ls -lt \$BACKUP_DIR | head -5
        
        echo ""
        read -p "Enter backup timestamp to restore (e.g., 20240101_120000): " BACKUP_TIMESTAMP
        
        RESTORE_DIR="\${BACKUP_DIR}/\${BACKUP_TIMESTAMP}"
        
        if [ -d "\$RESTORE_DIR" ]; then
            echo "Restoring from: \$RESTORE_DIR"
            
            # Create current backup before rollback
            CURRENT_BACKUP="${REMOTE_PATH}_backups/pre_rollback_${TIMESTAMP}"
            cp -r ${REMOTE_PATH}/* \$CURRENT_BACKUP/ 2>/dev/null || true
            
            # Restore from backup
            rm -rf ${REMOTE_PATH}/*
            cp -r \$RESTORE_DIR/* ${REMOTE_PATH}/
            
            # Set permissions
            cd ${REMOTE_PATH}
            chmod -R 755 writable
            chown -R www-data:www-data writable
            
            echo "Rollback completed successfully!"
        else
            echo "Backup not found: \$RESTORE_DIR"
            exit 1
        fi
EOF
    
    echo -e "${GREEN}Rollback completed!${NC}"
}

# Check deployment status
status() {
    echo -e "${BLUE}Checking deployment status...${NC}"
    
    ssh $REMOTE_USER@$REMOTE_HOST << EOF
        cd ${REMOTE_PATH}
        
        echo "Current git status:"
        git log -1 --oneline
        
        echo ""
        echo "Version file:"
        cat VERSION 2>/dev/null || echo "No version file found"
        
        echo ""
        echo "Last modified files:"
        ls -lt --time-style=long-iso | head -10
        
        echo ""
        echo "Writable directory permissions:"
        ls -la writable/
EOF
}

# Main execution
main() {
    load_config
    print_banner
    
    case $ACTION in
        deploy)
            pre_deploy_checks
            deploy
            ;;
        rollback)
            rollback
            ;;
        status)
            status
            ;;
        *)
            echo -e "${RED}Invalid action: $ACTION${NC}"
            echo "Usage: $0 [staging|production] [deploy|rollback|status]"
            exit 1
            ;;
    esac
}

main
