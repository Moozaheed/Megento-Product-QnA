# Quick Installation Guide - Magento 2 Product Q&A v2.0.0

## Quick Install (5 minutes)

### 1. Download & Install Module
```bash
cd /path/to/magento2
mkdir -p app/code/Vendor/ProductQnA
git clone https://github.com/Moozaheed/Megento-Product-QnA.git app/code/Vendor/ProductQnA
php bin/magento module:enable Vendor_ProductQnA
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

### 2. Install AI Service (Optional)
```bash
cd app/code/Vendor/ProductQnA/ai-service
npm install
node server.js &
```

### 3. Configure
- Admin: **Stores > Configuration > Catalog > Product Q&A**
- Set AI Service URL: `http://localhost:3000`

### 4. Test
- Visit any product page
- Click "Ask a Question"
- Choose "AI Assistant"
- Submit question
- Watch AI generate answer instantly!

## Docker Setup

If using Docker:
```bash
# Find Docker gateway IP
docker network inspect <network> | grep Gateway

# Update AI Service URL in admin to:
# http://172.23.0.1:3000 (use your gateway IP)

# Allow firewall
sudo ufw allow from 172.23.0.0/16 to any port 3000
```

## Full Documentation
See [README.md](README.md) for complete installation guide.

## Troubleshooting
- AI service not running? Check: `curl http://localhost:3000/health`
- Questions not showing? Run: `php bin/magento cache:flush`
- Modal behind images? Clear browser cache with Ctrl+Shift+R

## Support
- GitHub Issues: https://github.com/Moozaheed/Megento-Product-QnA/issues
- Documentation: https://github.com/Moozaheed/Megento-Product-QnA
