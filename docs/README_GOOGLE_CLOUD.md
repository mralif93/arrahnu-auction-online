# ArRahnu Auction Online - Google Cloud Setup

## 🚀 Quick Start

This project has been updated to remove MySQL from Docker and prepare for Google Cloud deployment.

## 📁 What Changed

### Removed from Docker:
- ❌ MySQL database container
- ❌ PostgreSQL database container  
- ❌ pgAdmin container
- ❌ Database management tools

### Added for Google Cloud:
- ✅ Google Cloud deployment script
- ✅ Google Cloud environment configuration
- ✅ Google Cloud optimized Dockerfile
- ✅ Comprehensive setup guide

## 🛠️ Files Added

- `deploy-google-cloud.sh` - Deployment script for Google Cloud
- `.env-google-cloud` - Environment configuration for Google Cloud
- `Dockerfile.google-cloud` - Optimized Dockerfile for Google Cloud
- `nginx/google-cloud.conf` - Nginx configuration for Google Cloud
- `docker/google-cloud-startup.sh` - Startup script for Google Cloud
- `docs/GOOGLE_CLOUD_SETUP.md` - Complete setup guide

## 🚀 Deploy to Google Cloud

1. **Install Google Cloud SDK**
   ```bash
   # Follow: https://cloud.google.com/sdk/docs/install
   ```

2. **Set up your project**
   ```bash
   export GOOGLE_CLOUD_PROJECT_ID=your-project-id
   export GOOGLE_CLOUD_REGION=asia-southeast1
   ```

3. **Deploy**
   ```bash
   chmod +x deploy-google-cloud.sh
   ./deploy-google-cloud.sh
   ```

## 📚 Documentation

- **Complete Guide**: `docs/GOOGLE_CLOUD_SETUP.md`
- **Environment Config**: `.env-google-cloud`
- **Deployment Script**: `deploy-google-cloud.sh`

## 🔧 Local Development

For local development without database:
```bash
docker-compose -f docker-compose.local.yaml up -d
```

## 🌐 Services Used

- **Database**: Google Cloud SQL or Firestore
- **Storage**: Google Cloud Storage
- **Caching**: Cloud Memorystore (Redis)
- **Queues**: Cloud Pub/Sub
- **Deployment**: Cloud Run

## 💡 Benefits

- ✅ No local database management
- ✅ Scalable cloud infrastructure
- ✅ Automatic SSL certificates
- ✅ Built-in monitoring and logging
- ✅ Cost-effective for production
- ✅ Easy scaling and deployment

## 📖 Next Steps

1. Read `docs/GOOGLE_CLOUD_SETUP.md`
2. Set up your Google Cloud project
3. Configure environment variables
4. Deploy using `deploy-google-cloud.sh`
5. Set up custom domain and SSL

## 🆘 Support

- Google Cloud Documentation: https://cloud.google.com/docs
- Laravel Documentation: https://laravel.com/docs
- Project Issues: Check the repository issues
