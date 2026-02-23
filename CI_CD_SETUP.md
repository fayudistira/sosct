# CI/CD Pipeline Setup Guide

This document provides comprehensive instructions for setting up and using the CI/CD pipeline for the FEECS application.

## Table of Contents

1. [Overview](#overview)
2. [Prerequisites](#prerequisites)
3. [GitHub Actions Workflows](#github-actions-workflows)
4. [Required Secrets Configuration](#required-secrets-configuration)
5. [Deployment Process](#deployment-process)
6. [Manual Deployment](#manual-deployment)
7. [Rollback Procedures](#rollback-procedures)
8. [Best Practices](#best-practices)

---

## Overview

The CI/CD pipeline is built using **GitHub Actions** and provides:

- **Continuous Integration (CI)**: Automated testing, code quality checks, and build artifacts
- **Continuous Deployment (CD)**: Automated deployment to staging and production environments

### Pipeline Architecture

```
┌─────────────────────────────────────────────────────────────────┐
│                    Developer Push/PR                             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    CI Pipeline (ci.yml)                          │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐          │
│  │ Code Quality │→ │    Tests     │→ │    Build     │          │
│  │   & Security │  │  (PHP 8.1-8.3)│  │   Artifacts  │          │
│  └──────────────┘  └──────────────┘  └──────────────┘          │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    CD Pipeline (deploy.yml)                      │
│  ┌──────────────┐                    ┌──────────────┐          │
│  │   Staging    │                    │  Production  │          │
│  │   (develop)  │                    │   (tags v*)  │          │
│  └──────────────┘                    └──────────────┘          │
└─────────────────────────────────────────────────────────────────┘
```

---

## Prerequisites

### Server Requirements

- PHP 8.1 or higher
- MySQL 8.0 or higher
- Composer
- Git
- SSH access with key-based authentication
- Web server (Apache/Nginx)

### GitHub Repository Requirements

- Repository hosted on GitHub
- GitHub Actions enabled
- Appropriate repository permissions

---

## GitHub Actions Workflows

### 1. CI Workflow ([`.github/workflows/ci.yml`](.github/workflows/ci.yml))

**Triggers:**

- Push to `main` or `develop` branches
- Pull requests to `main` or `develop` branches

**Jobs:**

| Job            | Description                  | Runs On           |
| -------------- | ---------------------------- | ----------------- |
| `code-quality` | Syntax check, security audit | Ubuntu Latest     |
| `test`         | PHPUnit tests with MySQL     | PHP 8.1, 8.2, 8.3 |
| `code-style`   | PHP-CS-Fixer check           | Ubuntu Latest     |
| `build`        | Create deployment artifact   | Ubuntu Latest     |

### 2. CD Workflow ([`.github/workflows/deploy.yml`](.github/workflows/deploy.yml))

**Triggers:**

- Push of version tags (`v*`)
- Manual workflow dispatch

**Jobs:**

| Job                 | Description                  | Environment |
| ------------------- | ---------------------------- | ----------- |
| `deploy-staging`    | Deploy to staging            | Staging     |
| `deploy-production` | Deploy to production         | Production  |
| `rollback`          | Rollback to previous version | Any         |

---

## Required Secrets Configuration

Configure the following secrets in your GitHub repository:

### Staging Environment

| Secret            | Description             | Example                              |
| ----------------- | ----------------------- | ------------------------------------ |
| `STAGING_HOST`    | Staging server hostname | `staging.feecs.com`                  |
| `STAGING_USER`    | SSH username            | `deploy`                             |
| `STAGING_SSH_KEY` | Private SSH key         | `-----BEGIN RSA PRIVATE KEY-----...` |
| `STAGING_PORT`    | SSH port (optional)     | `22`                                 |
| `STAGING_PATH`    | Application path        | `/var/www/staging.feecs`             |

### Production Environment

| Secret               | Description                | Example                              |
| -------------------- | -------------------------- | ------------------------------------ |
| `PRODUCTION_HOST`    | Production server hostname | `feecs.com`                          |
| `PRODUCTION_USER`    | SSH username               | `deploy`                             |
| `PRODUCTION_SSH_KEY` | Private SSH key            | `-----BEGIN RSA PRIVATE KEY-----...` |
| `PRODUCTION_PORT`    | SSH port (optional)        | `22`                                 |
| `PRODUCTION_PATH`    | Application path           | `/var/www/feecs`                     |

### Optional Secrets

| Secret          | Description                        |
| --------------- | ---------------------------------- |
| `CODECOV_TOKEN` | Codecov token for coverage reports |

### Setting Up Secrets

1. Navigate to your GitHub repository
2. Go to **Settings** → **Secrets and variables** → **Actions**
3. Click **New repository secret**
4. Add each secret with its corresponding value

### Setting Up Variables

1. Navigate to your GitHub repository
2. Go to **Settings** → **Secrets and variables** → **Actions**
3. Click **Variables** tab
4. Add the following variables:

| Variable         | Description                | Example                     |
| ---------------- | -------------------------- | --------------------------- |
| `STAGING_URL`    | Staging environment URL    | `https://staging.feecs.com` |
| `PRODUCTION_URL` | Production environment URL | `https://feecs.com`         |

---

## Deployment Process

### Automatic Deployment

#### Staging Deployment

- Automatically triggered when code is pushed to the `develop` branch
- CI tests must pass before deployment

#### Production Deployment

- Automatically triggered when a version tag is pushed
- Format: `v1.0.0`, `v1.2.3`, etc.

```bash
# Create and push a release tag
git tag -a v1.0.0 -m "Release version 1.0.0"
git push origin v1.0.0
```

### Manual Deployment

1. Go to **Actions** tab in GitHub
2. Select **CD** workflow
3. Click **Run workflow**
4. Select environment (`staging` or `production`)
5. Click **Run workflow**

---

## Manual Deployment Script

A deployment script ([`deploy.sh`](deploy.sh)) is provided for local deployment:

```bash
# Make the script executable
chmod +x deploy.sh

# Deploy to staging
./deploy.sh staging deploy

# Deploy to production
./deploy.sh production deploy

# Check deployment status
./deploy.sh staging status

# Rollback to previous version
./deploy.sh production rollback
```

### Environment Variables for Local Deployment

Set these environment variables before running the script:

```bash
export STAGING_HOST="staging.feecs.com"
export STAGING_USER="deploy"
export STAGING_PATH="/var/www/staging.feecs"

export PRODUCTION_HOST="feecs.com"
export PRODUCTION_USER="deploy"
export PRODUCTION_PATH="/var/www/feecs"
```

---

## Rollback Procedures

### Automatic Rollback via GitHub Actions

1. Go to **Actions** tab in GitHub
2. Select **CD** workflow
3. Click **Run workflow**
4. Select environment
5. The workflow will list available backups and prompt for selection

### Manual Rollback via SSH

```bash
# SSH into the server
ssh deploy@feecs.com

# Navigate to backups directory
cd /var/www/feecs_backups

# List available backups
ls -lt

# Restore from backup
BACKUP_DIR="20240101_120000"
rm -rf /var/www/feecs/*
cp -r /var/www/feecs_backups/$BACKUP_DIR/* /var/www/feecs/

# Set permissions
cd /var/www/feecs
chmod -R 755 writable
chown -R www-data:www-data writable
```

---

## Best Practices

### Branch Strategy

```
main (production)
  │
  ├── develop (staging)
  │     │
  │     ├── feature/xxx
  │     ├── bugfix/xxx
  │     └── hotfix/xxx
  │
  └── release/v1.0.0
```

### Commit Message Convention

Follow conventional commits for better release notes:

```
feat: add new feature
fix: fix a bug
docs: update documentation
style: formatting changes
refactor: code refactoring
test: add tests
chore: maintenance tasks
```

### Version Tagging

Use semantic versioning:

- `v1.0.0` - Major release (breaking changes)
- `v1.1.0` - Minor release (new features)
- `v1.1.1` - Patch release (bug fixes)

### Pre-Deployment Checklist

- [ ] All tests pass locally
- [ ] Code review completed
- [ ] CHANGELOG.md updated
- [ ] Database migrations tested
- [ ] Environment variables configured
- [ ] Backup created

### Security Considerations

1. **Never commit sensitive data** to the repository
2. Use **GitHub Secrets** for all credentials
3. **Rotate SSH keys** periodically
4. Enable **branch protection** rules
5. Require **PR reviews** before merging

---

## Troubleshooting

### Common Issues

#### 1. SSH Connection Failed

```
Error: Permission denied (publickey)
```

**Solution:** Ensure the SSH key is correctly added to GitHub Secrets and the server's authorized_keys.

#### 2. Database Migration Failed

```
Error: SQLSTATE[HY000] [2002] Connection refused
```

**Solution:** Check database credentials and ensure MySQL service is running.

#### 3. Composer Install Failed

```
Error: Could not find a matching version of package
```

**Solution:** Run `composer update` locally and commit the updated `composer.lock` file.

### Viewing Logs

- **GitHub Actions Logs**: Go to Actions tab → Select workflow run → View job logs
- **Server Logs**: Check `/var/log/apache2/error.log` or `/var/log/nginx/error.log`
- **Application Logs**: Check `writable/logs/` directory

---

## Support

For issues or questions regarding the CI/CD pipeline:

1. Check the [GitHub Actions documentation](https://docs.github.com/en/actions)
2. Review the workflow files in `.github/workflows/`
3. Contact the development team

---

## Changelog

| Version | Date       | Changes             |
| ------- | ---------- | ------------------- |
| 1.0.0   | 2024-01-01 | Initial CI/CD setup |
