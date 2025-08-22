# Navigate to your project directory
cd /opt/arrahnu/bm-gc-repo-arrahnu-stg/ArRahnu_02

# Fix ownership for the container user (UID 1000)
sudo chown -R 1000:1000 .

# Set proper permissions for web files
sudo chmod -R 755 .
sudo chmod -R 775 storage/
sudo chmod -R 775 bootstrap/cache/

# Ensure public directory is accessible
sudo chmod -R 755 public/
sudo chmod 644 public/index.php

# Fix specific asset files
sudo chmod 644 public/css/*
sudo chmod 644 public/js/*
sudo chmod 644 public/build/*

# Ensure Laravel can read its files
sudo chmod 644 composer.json
sudo chmod 644 composer.lock