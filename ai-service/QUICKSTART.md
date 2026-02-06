# Quick Start Guide - AI Service Setup

## ✅ What You Have Now:

Your implementation uses **Transformers.js with WebGPU** (100% local, no third-party APIs), exactly as planned!

### Architecture:
```
Customer asks question
    ↓
Magento saves question (auto-approved for AI)
    ↓
Magento calls Node.js AI Service (localhost:3000)
    ↓
Transformers.js + WebGPU generates answer using Qwen 2.5-3B
    ↓
Answer saved to database
    ↓
"AI is generating..." replaced with actual answer
```

## 🚀 Step-by-Step Setup:

### 1. Install Node.js 20+
```bash
# Check version
node --version

# If older than v20, install from:
# https://nodejs.org/
```

### 2. Set Up AI Service
```bash
cd /home/bs01233/Documents/Megento/project-community-edition/app/code/Vendor/ProductQnA/ai-service

# Install dependencies
npm install

# This will download ~6GB model on first run
npm start
```

**Expected output:**
```
🤖 Loading Qwen 2.5-3B-Instruct model...
⚡ Using WebGPU for acceleration
✅ Model loaded successfully!
🚀 AI Service running on http://localhost:3000
```

### 3. Update Magento Database
```bash
cd /home/bs01233/Documents/Megento/project-community-edition

# Start Docker
docker-compose up -d

# Run setup upgrade
docker-compose exec deploy bin/magento setup:upgrade
docker-compose exec deploy bin/magento cache:flush
```

### 4. Configure in Magento Admin
1. Login to admin panel
2. Go to: **Stores → Configuration → Product Q&A → AI Answer Settings**
3. Settings:
   - **Enable AI Answers**: Yes
   - **AI Service URL**: http://localhost:3000
   - **Request Timeout**: 30
4. Save Config

### 5. Test It!
1. Go to any product page
2. Click "Ask a Question"
3. Select "🤖 AI Assistant"
4. Ask: "What is this product made of?"
5. Submit
6. Watch the magic! ✨

## 🎯 Key Differences from Your Original Plan:

### ✅ Using (As You Wanted):
- **Transformers.js** (not Python Transformers)
- **WebGPU** for acceleration (not CUDA)
- **Node.js** service (not Python FastAPI)
- **100% Local** (no third-party APIs)
- **Qwen 2.5-3B-Instruct** model

### ❌ NOT Using:
- ~~Python microservice~~
- ~~Hugging Face Inference API~~
- ~~Accuracy/confidence scoring~~ (simplified approach)
- ~~Relevancy checking~~ (simplified approach)
- ~~Queue/Cron processing~~ (direct/sync calls for simplicity)

### 📝 Simplified Workflow:
According to your V2-SIMPLIFIED-AI-PLAN.md and V2-CUSTOMER-CHOICE-PLAN.md:

1. Customer chooses AI or Admin
2. If AI: Auto-approve + call AI service immediately
3. Show "generating..." placeholder
4. AI generates answer using product context
5. Save and display answer
6. **No accuracy threshold** (initially)
7. **No relevancy filtering** (initially)
8. Admin can edit AI answers later if needed

## 🔧 Configuration

### AI Service (ai-service/server.js)
```javascript
max_new_tokens: 200,      // Length of answer
temperature: 0.7,          // Creativity (0.1-1.0)
top_p: 0.9,                // Diversity
repetition_penalty: 1.2    // Avoid repetition
```

### Magento (Admin Config)
- **Enable AI**: Yes/No toggle
- **Service URL**: http://localhost:3000
- **Timeout**: 30 seconds

## 📊 What Gets Sent to AI:

Full product context:
```json
{
  "question": "Customer's question",
  "product_name": "Product Name",
  "product_sku": "SKU-123",
  "product_price": 99.99,
  "product_type": "simple",
  "product_description": "Full description",
  "product_short_description": "Short desc",
  "product_color": "Blue",
  "product_size": "M",
  "product_material": "Cotton"
}
```

## 🎨 Frontend Experience:

### While AI is generating:
- Rotating 🤖 emoji
- "AI is generating your answer..."
- Pulsing "Processing" badge
- Purple gradient styling

### After AI generates:
- Actual answer text
- "AI Assistant" badge (purple)
- "Answered by AI" attribution
- Timestamp

## 🐛 Troubleshooting:

### AI Service won't start?
```bash
# Check Node version
node --version  # Need 20+

# Check disk space
df -h  # Need 10GB free

# Check port
lsof -i :3000  # Port 3000 should be free
```

### Magento can't connect?
```bash
# Test AI service manually
curl http://localhost:3000/health

# Check Magento logs
tail -f var/log/system.log

# Verify config
docker-compose exec deploy bin/magento config:show productqna/ai/service_url
```

### Model loading error?
```bash
# Clear cache and retry
rm -rf ai-service/models/
npm start
```

## 📈 Next Steps (Future Enhancements):

From your original plan, these can be added later:

1. **Accuracy Scoring**: Add confidence threshold
2. **Relevancy Filtering**: Filter spam/irrelevant questions
3. **Queue Processing**: Move to async for better performance
4. **Admin Analytics**: Dashboard showing AI performance
5. **Customer Feedback**: Thumbs up/down on AI answers
6. **A/B Testing**: Compare AI vs human answers

## 🎯 Current Status:

✅ Database schema complete
✅ Models and interfaces complete
✅ Frontend form with AI/Admin choice
✅ Beautiful "generating..." animation
✅ AI service ready (Transformers.js + WebGPU)
✅ Full product context sent to AI
✅ Auto-approval for AI questions
✅ Admin configuration panel

**Ready to test!** 🚀
