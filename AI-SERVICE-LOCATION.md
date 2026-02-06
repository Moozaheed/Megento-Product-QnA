# AI Service - Part of ProductQnA Module

The AI service is now **integrated within the ProductQnA module** at:
```
app/code/Vendor/ProductQnA/ai-service/
```

## 📁 Module Structure:

```
app/code/Vendor/ProductQnA/
├── ai-service/              ← AI Service (Node.js + Transformers.js)
│   ├── server.js            ← Main AI service (WebGPU-powered)
│   ├── package.json         ← Dependencies
│   ├── README.md            ← AI service documentation
│   ├── QUICKSTART.md        ← Quick setup guide
│   └── .gitignore           ← Git ignore (models/, node_modules/)
│
├── Api/                     ← Data interfaces
├── Block/                   ← Block classes
├── Controller/              ← Controllers (handles AI calls)
├── Model/                   ← Models (Question, Answer, AiAnswer)
├── Service/                 ← Services (AiClient, AiAnswerService)
├── etc/                     ← Configuration
├── view/                    ← Templates & layouts
├── Ui/                      ← UI components
├── Images/                  ← Screenshots
├── README.md                ← Main module documentation
└── registration.php         ← Module registration
```

## 🚀 Quick Start:

### 1. Install AI Service Dependencies
```bash
cd app/code/Vendor/ProductQnA/ai-service
npm install
```

### 2. Start AI Service
```bash
npm start
```

### 3. Configure Magento
Admin Panel → Stores → Configuration → Product Q&A → AI Settings
- Enable AI: Yes
- Service URL: http://localhost:3000
- Timeout: 30

## 💡 Why Inside the Module?

✅ **Self-Contained**: Everything in one place
✅ **Easy Distribution**: Clone the module and you get AI service too
✅ **Version Control**: AI service versioned with the module
✅ **Easy Updates**: Update module = update AI service
✅ **Simpler Deployment**: One directory to deploy

## 📦 What Gets Installed:

When you run `npm install` in `ai-service/`:
- express (HTTP server)
- @xenova/transformers (AI library)
- cors (for Magento requests)

**First run:** Qwen 2.5-3B model (~6GB) downloads to:
```
ai-service/models/
```

This is gitignored, so not committed to repository.

## 🔄 Development Workflow:

```bash
# Terminal 1: Start AI Service
cd app/code/Vendor/ProductQnA/ai-service
npm start

# Terminal 2: Magento development
php bin/magento cache:flush
# ... develop ...
```

## 📤 Publishing/Distribution:

When publishing to GitHub/Packagist:

1. **Include `ai-service/` folder** (without node_modules/)
2. **Document in README**: Mention Node.js requirement
3. **Installation instructions**: Must run `npm install` in ai-service/

Users will:
```bash
# Clone your module
git clone https://github.com/Moozaheed/Megento-Product-QnA

# Install Magento module
php bin/magento module:enable Vendor_ProductQnA
php bin/magento setup:upgrade

# Install AI service
cd app/code/Vendor/ProductQnA/ai-service
npm install
npm start

# Done! 🎉
```

## 🎯 Benefits:

1. **Single Repository**: One repo for both Magento + AI
2. **Atomic Updates**: Module and AI always in sync
3. **Easy Onboarding**: Clone once, have everything
4. **Version Matching**: Module v2.0.0 = AI service v2.0.0
5. **Simplified CI/CD**: Deploy one folder

Perfect for your use case! 🚀
