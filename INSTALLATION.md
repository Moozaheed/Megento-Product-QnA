# Installation Guide - Magento 2 Product Q&A Module

## Prerequisites

Before installing this module, ensure you have:

- ✅ Magento 2.4.x installed and running
- ✅ PHP 8.1, 8.2, or 8.3
- ✅ MySQL 5.7+ or MariaDB 10.4+
- ✅ Composer installed
- ✅ SSH/Terminal access to your server
- ✅ Admin access to Magento backend

## Installation Methods

### Method 1: Composer Installation (Recommended)

This is the recommended method for production environments.

#### Step 1: Install via Composer

```bash
cd <magento_root>
composer require vendor/magento2-productqna:^1.0
```

#### Step 2: Enable the Module

```bash
php bin/magento module:enable Vendor_ProductQnA
```

#### Step 3: Run Magento Setup

```bash
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento indexer:reindex
php bin/magento cache:flush
```

#### Step 4: Set Permissions

```bash
chmod -R 755 var/ generated/ pub/static/
chown -R www-data:www-data var/ generated/ pub/static/
```

**Note:** Replace `www-data` with your web server user (e.g., `nginx`, `apache`, etc.)

---

### Method 2: Manual Installation

Use this method for development or if you don't have access to Packagist.

#### Step 1: Download the Module

Download the module files from GitHub or copy them manually.

#### Step 2: Create Module Directory

```bash
cd <magento_root>/app/code
mkdir -p Vendor/ProductQnA
```

#### Step 3: Copy Module Files

Copy all module files into `app/code/Vendor/ProductQnA/`

Your structure should look like:
```
app/code/Vendor/ProductQnA/
├── Api/
├── Block/
├── Controller/
├── etc/
├── Model/
├── Ui/
├── view/
├── composer.json
├── registration.php
└── README.md
```

#### Step 4: Enable the Module

```bash
cd <magento_root>
php bin/magento module:enable Vendor_ProductQnA
```

#### Step 5: Run Magento Setup

```bash
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy -f
php bin/magento indexer:reindex
php bin/magento cache:flush
```

#### Step 6: Set Permissions

```bash
chmod -R 755 var/ generated/ pub/static/
chown -R www-data:www-data var/ generated/ pub/static/
```

---

### Method 3: Docker Installation

If you're running Magento in Docker containers.

#### Step 1: Copy Module to Docker Volume

```bash
# Copy module files to app/code/Vendor/ProductQnA in your Magento Docker volume
```

#### Step 2: Enter PHP Container

```bash
docker exec -it <your-php-container-name> bash
```

#### Step 3: Enable and Install

```bash
cd /var/www/html  # or your Magento root in container
php bin/magento module:enable Vendor_ProductQnA
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

**OR using docker-compose:**

```bash
docker-compose run --rm deploy magento-command module:enable Vendor_ProductQnA
docker-compose run --rm deploy magento-command setup:upgrade
docker-compose run --rm deploy magento-command setup:di:compile
docker-compose run --rm deploy magento-command cache:flush
```

---

## Post-Installation Steps

### 1. Verify Installation

Check if the module is enabled:

```bash
php bin/magento module:status Vendor_ProductQnA
```

Expected output:
```
Module is enabled
```

### 2. Verify Database Tables

Check if tables were created:

```bash
mysql -u<db_user> -p<db_password> <db_name> -e "SHOW TABLES LIKE 'vendor_product_qna%';"
```

Expected output:
```
vendor_product_qna_answer
vendor_product_qna_helpful
vendor_product_qna_question
```

### 3. Configure Admin Permissions

1. Log in to Magento Admin
2. Go to **System → Permissions → User Roles**
3. Edit your admin role
4. Under **Role Resources**, find **Vendor_ProductQnA**
5. Enable permissions for Q&A management
6. Save the role

### 4. Access the Module

1. Log in to Magento Admin
2. Navigate to **Product Q&A → Manage Questions**
3. You should see the questions grid (empty initially)

### 5. Test Frontend Display

1. Visit any product page on your store
2. Scroll down to the product tabs
3. You should see a **"Questions & Answers"** tab
4. Click "Ask a Question" to test the form

---

## Verification Checklist

After installation, verify the following:

- [ ] Module shows as enabled: `php bin/magento module:status Vendor_ProductQnA`
- [ ] Database tables created (3 tables)
- [ ] Admin menu item visible: **Product Q&A → Manage Questions**
- [ ] Frontend Q&A tab appears on product pages
- [ ] "Ask a Question" button is clickable
- [ ] Modal form opens when clicking "Ask a Question"
- [ ] No JavaScript errors in browser console
- [ ] Admin grid loads without errors
- [ ] Permissions are set correctly

---

## Troubleshooting

### Issue: Module not appearing in admin

**Solution:**
```bash
php bin/magento module:enable Vendor_ProductQnA
php bin/magento setup:upgrade
php bin/magento cache:flush
```

### Issue: Database tables not created

**Solution:**
```bash
php bin/magento setup:upgrade --keep-generated
php bin/magento setup:db-schema:upgrade
```

### Issue: Permission denied errors

**Solution:**
```bash
chmod -R 777 var/ generated/ pub/static/
# Then set proper permissions after testing
chmod -R 755 var/ generated/ pub/static/
chown -R www-data:www-data var/ generated/ pub/static/
```

### Issue: 404 errors on admin routes

**Solution:**
```bash
php bin/magento cache:flush
php bin/magento setup:di:compile
```

### Issue: Frontend tab not showing

**Solution:**
1. Clear all caches:
   ```bash
   php bin/magento cache:flush
   rm -rf var/cache/* var/page_cache/* var/view_preprocessed/*
   ```
2. Check browser console for errors
3. Verify layout XML is loaded:
   ```bash
   php bin/magento setup:upgrade
   ```

### Issue: Grid not loading in admin

**Solution:**
```bash
php bin/magento setup:di:compile
php bin/magento cache:flush
php bin/magento indexer:reindex
```

### Issue: Static content errors

**Solution:**
```bash
rm -rf pub/static/* var/view_preprocessed/*
php bin/magento setup:static-content:deploy -f
php bin/magento cache:flush
```

### Issue: Dependency injection errors

**Solution:**
```bash
rm -rf generated/*
php bin/magento setup:di:compile
php bin/magento cache:flush
```

---

## Uninstallation

If you need to remove the module:

### Step 1: Disable the Module

```bash
php bin/magento module:disable Vendor_ProductQnA
php bin/magento setup:upgrade
```

### Step 2: Remove Module Files

**For Composer installation:**
```bash
composer remove vendor/magento2-productqna
```

**For Manual installation:**
```bash
rm -rf app/code/Vendor/ProductQnA
```

### Step 3: Remove Database Tables (Optional)

**⚠️ WARNING: This will delete all Q&A data!**

```bash
mysql -u<db_user> -p<db_password> <db_name>
```

Then run:
```sql
DROP TABLE IF EXISTS vendor_product_qna_helpful;
DROP TABLE IF EXISTS vendor_product_qna_answer;
DROP TABLE IF EXISTS vendor_product_qna_question;
```

### Step 4: Clear Cache

```bash
php bin/magento cache:flush
php bin/magento setup:di:compile
```

---

## Getting Help

If you encounter issues during installation:

1. Check the [Troubleshooting](#troubleshooting) section above
2. Review Magento logs:
   - `var/log/system.log`
   - `var/log/exception.log`
   - `var/log/debug.log`
3. Check web server error logs
4. Open an issue on GitHub
5. Contact support

---

## Next Steps

After successful installation:

1. Read the [README.md](README.md) for usage instructions
2. Configure admin permissions
3. Test the question submission flow
4. Test the answer workflow
5. Customize templates if needed
6. Set up email notifications (if implemented)

---

## System Requirements

| Component | Requirement |
|-----------|-------------|
| Magento | 2.4.x |
| PHP | 8.1, 8.2, or 8.3 |
| MySQL | 5.7+ |
| MariaDB | 10.4+ |
| Composer | 2.x |
| Memory | 2GB+ recommended |

---

**Installation complete! 🎉**

Visit your admin panel and start managing product questions!
