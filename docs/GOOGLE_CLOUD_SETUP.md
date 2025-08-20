# Google Cloud Setup Guide for ArRahnu Auction Online

## Overview
This guide explains how to set up Google Cloud services for the ArRahnu Auction Online application after removing MySQL from Docker.

## Prerequisites
- Google Cloud account
- Google Cloud SDK (gcloud) installed
- Docker installed
- Domain name (optional)

## 1. Google Cloud Project Setup

### Create a new project
```bash
gcloud projects create arrahnu-auction-online --name="ArRahnu Auction Online"
gcloud config set project arrahnu-auction-online
```

### Enable billing
```bash
gcloud billing projects link arrahnu-auction-online --billing-account=YOUR_BILLING_ACCOUNT_ID
```

## 2. Database Options

### Option A: Google Cloud SQL (MySQL/PostgreSQL)
```bash
# Enable Cloud SQL Admin API
gcloud services enable sqladmin.googleapis.com

# Create MySQL instance
gcloud sql instances create arrahnu-mysql \
    --database-version=MYSQL_8_0 \
    --tier=db-f1-micro \
    --region=asia-southeast1 \
    --root-password=YOUR_ROOT_PASSWORD

# Create database
gcloud sql databases create arrahnu_auction --instance=arrahnu-mysql

# Create user
gcloud sql users create arrahnu_user \
    --instance=arrahnu-mysql \
    --password=YOUR_USER_PASSWORD
```

### Option B: Firestore (NoSQL)
```bash
# Enable Firestore API
gcloud services enable firestore.googleapis.com

# Create Firestore database
gcloud firestore databases create --location=asia-southeast1
```

## 3. File Storage

### Google Cloud Storage
```bash
# Enable Storage API
gcloud services enable storage.googleapis.com

# Create storage bucket
gsutil mb -l asia-southeast1 gs://arrahnu-auction-storage

# Make bucket public (if needed)
gsutil iam ch allUsers:objectViewer gs://arrahnu-auction-storage
```

## 4. Caching & Sessions

### Cloud Memorystore (Redis)
```bash
# Enable Redis API
gcloud services enable redis.googleapis.com

# Create Redis instance
gcloud redis instances create arrahnu-redis \
    --size=1 \
    --region=asia-southeast1 \
    --redis-version=redis_6_x
```

## 5. Queue System

### Cloud Pub/Sub
```bash
# Enable Pub/Sub API
gcloud services enable pubsub.googleapis.com

# Create topics
gcloud pubsub topics create email-queue
gcloud pubsub topics create notification-queue

# Create subscriptions
gcloud pubsub subscriptions create email-subscription --topic=email-queue
gcloud pubsub subscriptions create notification-subscription --topic=notification-queue
```

## 6. Application Deployment

### Build and Deploy
```bash
# Make deployment script executable
chmod +x deploy-google-cloud.sh

# Set environment variables
export GOOGLE_CLOUD_PROJECT_ID=arrahnu-auction-online
export GOOGLE_CLOUD_REGION=asia-southeast1
export GOOGLE_CLOUD_ZONE=asia-southeast1-a

# Run deployment
./deploy-google-cloud.sh
```

## 7. Environment Configuration

### Update .env file
Copy `.env-google-cloud` to `.env` and update the values:

```bash
cp .env-google-cloud .env
```

### Key variables to update:
- `GOOGLE_CLOUD_PROJECT_ID`: Your project ID
- `GOOGLE_CLOUD_SQL_INSTANCE`: Your SQL instance name
- `GOOGLE_CLOUD_STORAGE_BUCKET`: Your storage bucket name
- `REDIS_HOST`: Your Redis instance IP
- `MAIL_USERNAME` and `MAIL_PASSWORD`: Your email credentials

## 8. SSL and Domain Setup

### Custom Domain
```bash
# Map custom domain to Cloud Run service
gcloud run domain-mappings create \
    --service=arrahnu-auction \
    --domain=your-domain.com \
    --region=asia-southeast1
```

### SSL Certificate
SSL is automatically configured by Cloud Run.

## 9. Monitoring and Logging

### View logs
```bash
# Application logs
gcloud logs tail --service=arrahnu-auction --region=asia-southeast1

# Cloud SQL logs
gcloud sql logs tail --instance=arrahnu-mysql
```

### Set up monitoring
```bash
# Enable monitoring API
gcloud services enable monitoring.googleapis.com

# Create alerting policies (optional)
gcloud alpha monitoring policies create --policy-from-file=monitoring-policy.yaml
```

## 10. Cost Optimization

### Recommendations:
- Use `db-f1-micro` for development
- Set up budget alerts
- Use Cloud Storage lifecycle policies
- Monitor resource usage

## 11. Security Best Practices

### IAM Roles
```bash
# Create service account for application
gcloud iam service-accounts create arrahnu-app \
    --display-name="ArRahnu Application Service Account"

# Grant necessary permissions
gcloud projects add-iam-policy-binding arrahnu-auction-online \
    --member="serviceAccount:arrahnu-app@arrahnu-auction-online.iam.gserviceaccount.com" \
    --role="roles/cloudsql.client"
```

### Network Security
- Use VPC for private instances
- Configure firewall rules
- Enable Cloud Armor for DDoS protection

## 12. Backup and Recovery

### Database Backup
```bash
# Enable automated backups
gcloud sql instances patch arrahnu-mysql \
    --backup-start-time=02:00 \
    --backup-retention-days=7
```

### Storage Backup
```bash
# Create backup bucket
gsutil mb -l asia-southeast1 gs://arrahnu-auction-backup

# Set up lifecycle policy
gsutil lifecycle set lifecycle-policy.json gs://arrahnu-auction-backup
```

## Troubleshooting

### Common Issues:
1. **Permission denied**: Check IAM roles
2. **Connection timeout**: Verify firewall rules
3. **High costs**: Review resource usage and optimize

### Support:
- Google Cloud Console: https://console.cloud.google.com
- Documentation: https://cloud.google.com/docs
- Community: https://cloud.google.com/community

## Next Steps
1. Test the application with Google Cloud services
2. Set up monitoring and alerting
3. Configure automated backups
4. Implement CI/CD pipeline
5. Set up staging environment
