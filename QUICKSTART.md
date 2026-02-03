# 🚀 Quick Start Guide - 5 Minutes to Product Q&A

Get your Product Q&A module up and running in 5 minutes!

## Prerequisites

- ✅ Magento 2.4.x installed
- ✅ Composer access
- ✅ Terminal/SSH access

---

## 🎯 Installation (2 minutes)

### Option A: Composer (Recommended)
```bash
composer require vendor/magento2-productqna:^1.0
php bin/magento module:enable Vendor_ProductQnA
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

### Option B: Manual
```bash
# 1. Copy module files to app/code/Vendor/ProductQnA/
# 2. Run:
php bin/magento module:enable Vendor_ProductQnA
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento cache:flush
```

### Option C: Docker
```bash
docker-compose run --rm deploy magento-command module:enable Vendor_ProductQnA
docker-compose run --rm deploy magento-command setup:upgrade
docker-compose run --rm deploy magento-command setup:di:compile
docker-compose run --rm deploy magento-command cache:flush
```

---

## ✅ Verification (1 minute)

### Check Module Status
```bash
php bin/magento module:status Vendor_ProductQnA
```
Expected: `Module is enabled`

### Check Database
```bash
php bin/magento setup:db:status
```
Expected: No pending updates

---

## 🎨 First Use (2 minutes)

### Admin Setup

1. **Login to Admin Panel**
   - Navigate to your admin URL

2. **Set Permissions** (if needed)
   - Go to: **System → Permissions → User Roles**
   - Edit your role
   - Find: **Vendor_ProductQnA**
   - Enable permissions
   - Save

3. **Access Questions Grid**
   - Go to: **Product Q&A → Manage Questions**
   - You'll see the grid (empty initially)

### Frontend Test

1. **Visit Any Product Page**
   - Go to your store
   - Click on any product

2. **Find Q&A Tab**
   - Scroll to product tabs
   - Click "Questions & Answers" tab

3. **Ask a Question**
   - Click "Ask a Question" button
   - Modal opens
   - Fill in:
     - Your Name
     - Your Email
     - Your Question
   - Click "Submit Question"
   - Success message appears

### Admin Answer

1. **View New Question**
   - Go to: **Admin → Product Q&A → Manage Questions**
   - See your question with status "Pending"

2. **Answer the Question**
   - Click "Answer" button
   - See question details
   - Enter your answer
   - Click "Submit Answer"
   - Status changes to "Answered"

3. **View on Frontend**
   - Go back to product page
   - Refresh page
   - See your question with answer!
   - Admin name shows as answerer

---

## 🎉 You're Done!

Your Q&A system is now live and working!

---

## 📚 Common Tasks

### Approve a Question
1. Admin → Product Q&A → Manage Questions
2. Find question
3. Click "Approve"
4. Shows on frontend (without answer yet)

### Archive a Question
1. Admin → Product Q&A → Manage Questions
2. Find answered question
3. Click "Archive"
4. Hidden from frontend

### Edit an Answer
1. Admin → Product Q&A → Manage Questions
2. Find answered question
3. Click "Edit Answer"
4. Modify text
5. Click "Update Answer"

---

## 🔧 Troubleshooting

### Module Not Showing?
```bash
php bin/magento module:enable Vendor_ProductQnA
php bin/magento cache:flush
```

### Grid Not Loading?
```bash
php bin/magento setup:di:compile
php bin/magento cache:flush
```

### Frontend Tab Missing?
```bash
rm -rf var/cache/* var/page_cache/*
php bin/magento cache:flush
```

### Permission Errors?
```bash
chmod -R 777 var/ generated/ pub/static/
# For production:
chmod -R 755 var/ generated/ pub/static/
chown -R www-data:www-data var/ generated/ pub/static/
```

---

## 📖 Next Steps

- Read [README.md](README.md) for full documentation
- Check [INSTALLATION.md](INSTALLATION.md) for detailed setup
- Review [CONTEXT.md](CONTEXT.md) for technical details
- See [CHANGELOG.md](CHANGELOG.md) for version history

---

## 🆘 Need Help?

- **Documentation:** Comprehensive guides included
- **Issues:** GitHub Issues for bugs
- **Support:** Open GitHub Discussion

---

**Happy Q&A-ing! 🎊**

Your store now has a complete question and answer system!
