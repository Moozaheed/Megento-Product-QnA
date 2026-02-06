# Magento 2 Product Q&A with AI Assistant# Magento 2 Product Q&A Module



![Version](https://img.shields.io/badge/version-2.0.0-blue.svg)A comprehensive Product Questions & Answers module for Magento 2 with AI-powered automatic answering using Transformers.js and WebGPU.

![Magento](https://img.shields.io/badge/Magento-2.4.x-orange.svg)

![PHP](https://img.shields.io/badge/PHP-8.1%2B-777BB4.svg)## 🚀 Version 2.0.0 - AI Integration

![License](https://img.shields.io/badge/license-MIT-green.svg)

**NEW in v2.0.0:**

A powerful Magento 2 module that enables customers to ask questions on product pages and receive instant AI-generated answers or admin-reviewed responses.- 🤖 **AI-Powered Answers**: Automatic question answering using Qwen 2.5-3B model

- ⚡ **WebGPU Acceleration**: Fast inference using local GPU

## ✨ Features- 🎯 **Customer Choice**: Let customers choose between AI or Admin answers

- 💜 **Beautiful UI**: Animated "AI is generating..." placeholder

### v2.0.0 - AI-Powered Answers 🤖- 🔒 **100% Local**: No third-party APIs, complete privacy

- **AI Assistant Integration**: Instant AI-generated answers using advanced language models- 📦 **Self-Contained**: AI service included in module (`ai-service/` folder)

- **Customer Choice**: Let customers choose between AI Assistant or Store Admin answers

- **Smart Product Context**: AI receives full product information (name, SKU, description, price, attributes, categories)## 📸 Screenshots

- **Rule-Based Mode**: Intelligent template-based responses without requiring AI model download

- **Optional AI Model**: Support for Qwen 2.5-3B-Instruct with WebGPU acceleration### Admin Side View

- **Visual Indicators**: Beautiful badges showing answer source (🤖 AI Assistant or 👨‍💼 Store Admin)![Admin Grid View](Images/Magento-Admin-Side-View.png)

- **Retry Command**: CLI command to regenerate failed AI answers

### Admin Dashboard

### Core Features![Admin Question Management](Images/Megento-QnA-Dashboard.png)

- ✅ Customer questions on product pages

- ✅ Admin answer management dashboard### Frontend - Product Page

- ✅ Question approval workflow![Questions on Product Page](Images/View%20In%20UI.png)

- ✅ Email notifications (customers & admins)

- ✅ Status management (Pending, Approved, Answered)### Frontend - Ask Question Modal

- ✅ Customer name & email validation![Question Submission Form](Images/Add%20a%20question%20modal.png)

- ✅ Responsive modal design

- ✅ SEO-friendly Q&A display## �📋 Features

- ✅ Grid filters and search in admin panel

### Customer Features (v1.0.0)

## 📋 Requirements- ✅ Ask questions on product pages

- ✅ View approved and answered questions with answers

- **Magento**: 2.4.x- ✅ See admin attribution on answers (shows who answered)

- **PHP**: 8.1, 8.2, or 8.3- ✅ Modern modal-based question submission form

- **Node.js**: 20.x or higher (for AI service)- ✅ AJAX form submission (no page reload)

- **Database**: MySQL 5.7+ or MariaDB 10.4+- ✅ Clean, responsive UI design

- **Optional**: GPU with WebGPU support (for AI model acceleration)

### Customer Features (v2.0.0 - AI)

## 🚀 Installation- ✅ **Choose Answer Type**: AI Assistant or Store Admin

- ✅ **Instant AI Answers**: Get answers in seconds

### Step 1: Install the Module- ✅ **Real-time Status**: "AI is generating..." animation

- ✅ **AI Attribution**: Clear "Answered by AI" badge

```bash- ✅ **Product Context-Aware**: AI uses full product information

# Navigate to your Magento root directory

cd /path/to/magento2### Admin Features

- ✅ Comprehensive question management grid

# Create module directory- ✅ Filter and search questions

mkdir -p app/code/Vendor/ProductQnA- ✅ View product links directly from grid

- ✅ Answer questions with rich text editor

# Clone or download the module- ✅ Edit existing answers

git clone https://github.com/Moozaheed/Megento-Product-QnA.git app/code/Vendor/ProductQnA- ✅ Complete workflow management:

  - **Pending** → Approve, Answer, Archive

# Or download and extract manually  - **Approved** → Answer, Archive

# Then copy to app/code/Vendor/ProductQnA  - **Answered** → Edit Answer, Archive

```  - **Archived** → Approve, Set to Pending

- ✅ Admin attribution tracking (records who answered)

### Step 2: Enable the Module- ✅ Bulk actions support

- ✅ Question approval/rejection system

```bash

# Enable the module### Technical Features

php bin/magento module:enable Vendor_ProductQnA- ✅ Full database schema with proper relationships

- ✅ RESTful API interfaces

# Run setup upgrade- ✅ UI Component grid with advanced filtering

php bin/magento setup:upgrade- ✅ Dependency injection configuration

- ✅ ACL (Access Control List) support

# Compile dependency injection- ✅ Multiple status states (Pending, Approved, Answered, Archived)

php bin/magento setup:di:compile- ✅ Foreign key constraints with cascade delete

- ✅ Helpful count tracking (future enhancement ready)

# Deploy static content (production mode)

php bin/magento setup:static-content:deploy -f## 📦 Installation



# Clear cache### Method 1: Manual Installation (Recommended for Development)

php bin/magento cache:flush

```1. **Download/Clone the module:**

   ```bash

### Step 3: Set Up AI Service (Optional but Recommended)   cd <magento_root>/app/code

   mkdir -p Vendor/ProductQnA

The AI service can run in two modes:   # Copy all module files to app/code/Vendor/ProductQnA

1. **Rule-Based Mode** (default): No AI model required, instant responses   ```

2. **AI Model Mode**: Download and use Qwen 2.5-3B-Instruct for higher quality answers

2. **Enable the module:**

#### Install AI Service Dependencies   ```bash

   php bin/magento module:enable Vendor_ProductQnA

```bash   php bin/magento setup:upgrade

# Navigate to AI service directory   php bin/magento setup:di:compile

cd app/code/Vendor/ProductQnA/ai-service   php bin/magento setup:static-content:deploy -f

   php bin/magento cache:flush

# Install Node.js dependencies   ```

npm install

```3. **Set proper permissions:**

   ```bash

#### Configure AI Service   chmod -R 777 var/ generated/ pub/static/

   ```

Edit `app/code/Vendor/ProductQnA/ai-service/server.js`:

### Method 2: Composer Installation (For Production)

```javascript

// For rule-based mode (recommended for quick setup)1. **Add repository to composer.json** (if publishing to Packagist):

const USE_AI_MODEL = false;   ```bash

   composer require vendor/module-productqna:^1.0

// For AI model mode (requires ~6GB download)   ```

const USE_AI_MODEL = true;

```2. **Enable and install:**

   ```bash

#### Start AI Service   php bin/magento module:enable Vendor_ProductQnA

   php bin/magento setup:upgrade

```bash   php bin/magento setup:di:compile

# Start the AI service   php bin/magento setup:static-content:deploy -f

node server.js   php bin/magento cache:flush

   ```

# Or run in background

nohup node server.js > ai-service.log 2>&1 &### Docker Installation



# Or use PM2 for productionIf running Magento in Docker:

npm install -g pm2```bash

pm2 start server.js --name productqna-aidocker-compose run --rm deploy magento-command module:enable Vendor_ProductQnA

pm2 savedocker-compose run --rm deploy magento-command setup:upgrade

```docker-compose run --rm deploy magento-command setup:di:compile

docker-compose run --rm deploy magento-command cache:flush

The AI service will run on `http://localhost:3000````



#### Docker Environment Setup### 🤖 AI Service Setup (v2.0.0)



If you're using Docker (like docker-compose), you need to configure network access:**Requirements:**

- Node.js 20.0.0 or higher

1. **Find Docker gateway IP**:- 8GB RAM minimum

```bash- 10GB free disk space (for AI model cache)

docker network inspect <network_name> | grep Gateway

# Example output: "Gateway": "172.23.0.1"**Setup Steps:**

```

1. **Navigate to AI service folder:**

2. **Update AI service URL** in Magento admin:   ```bash

   - Go to: Stores > Configuration > Catalog > Product Q&A   cd app/code/Vendor/ProductQnA/ai-service

   - Set AI Service URL to: `http://172.23.0.1:3000` (use your gateway IP)   ```



3. **Allow firewall access**:2. **Install dependencies:**

```bash   ```bash

# Allow Docker network to access AI service port   npm install

sudo ufw allow from 172.23.0.0/16 to any port 3000   ```

```

3. **Start the AI service:**

### Step 4: Configure the Module   ```bash

   npm start

1. Log in to Magento Admin Panel   ```

2. Navigate to: **Stores > Configuration > Catalog > Product Q&A**   

3. Configure settings:   **First run:** Model will download (~6GB). Wait for:

   - Enable/disable the module   ```

   - Set notification email recipients   🤖 Loading Qwen 2.5-3B-Instruct model...

   - Configure AI service URL (default: `http://localhost:3000`)   ⚡ Using WebGPU for acceleration

   - Set moderation options   ✅ Model loaded successfully!

   🚀 AI Service running on http://localhost:3000

### Step 5: Verify Installation   ```



```bash4. **Enable AI in Magento Admin:**

# Check module status   - Go to: **Stores → Configuration → Product Q&A → AI Answer Settings**

php bin/magento module:status Vendor_ProductQnA   - Set **Enable AI Answers**: Yes

   - Set **AI Service URL**: http://localhost:3000

# Check database tables   - Set **Request Timeout**: 30

php bin/magento setup:db:status   - Click **Save Config**



# Test AI service (if enabled)5. **Test it:**

curl http://localhost:3000/health   - Visit any product page

# Should return: {"status":"ok","mode":"rule-based",...}   - Click "Ask a Question"

```   - Select "🤖 AI Assistant"

   - Submit a question and watch the magic! ✨

## 📖 Usage

**For production:**

### For Customers```bash

# Install PM2 process manager

1. Navigate to any product pagenpm install -g pm2

2. Click **"Ask a Question"** button

3. Choose answer preference:# Start service with PM2

   - 🤖 **AI Assistant**: Get instant AI-generated answerpm2 start server.js --name productqna-ai

   - 👨‍💼 **Store Admin**: Get manually reviewed answer from store teampm2 startup

4. Fill in your question, name, and emailpm2 save

5. Submit and receive notification when answered```



### For AdminsSee `ai-service/README.md` for detailed documentation.



#### Managing Questions## 🗄️ Database Schema



1. Go to: **Catalog > Product Q&A > Manage Questions**The module creates 3 tables:

2. View all customer questions

3. Filter by status, product, date### 1. vendor_product_qna_question

4. Approve or reject questions| Column | Type | Description |

5. Answer questions manually|--------|------|-------------|

| question_id | INT | Primary Key |

#### Retry Failed AI Answers| product_id | INT | Foreign Key to catalog_product_entity |

| customer_id | INT | Customer ID (nullable) |

If some AI answers failed to generate, use the retry command:| customer_name | VARCHAR(255) | Customer name |

| customer_email | VARCHAR(255) | Customer email |

```bash| question_text | TEXT | Question content |

php bin/magento productqna:retry-ai-answers| status | TINYINT | 0=Pending, 1=Approved, 2=Rejected, 3=Answered, 4=Archived |

```| helpful_count | INT | Number of helpful votes |

| visibility | TINYINT | 1=Public, 0=Private |

This will:| created_at | TIMESTAMP | Creation timestamp |

- Find all questions with `answer_preference='ai'` that haven't been answered| updated_at | TIMESTAMP | Update timestamp |

- Retry AI answer generation

- Update question status### 2. vendor_product_qna_answer

| Column | Type | Description |

## 🗄️ Database Schema|--------|------|-------------|

| answer_id | INT | Primary Key |

### v2.0.0 Tables| question_id | INT | Foreign Key to vendor_product_qna_question |

| admin_user_id | INT | Admin user who answered |

#### `vendor_product_qna_question`| answer_text | TEXT | Answer content |

- Added: `answer_preference` (varchar) - 'ai' or 'admin'| status | TINYINT | 0=Pending, 1=Published |

- Stores customer questions with their preferred answer type| created_at | TIMESTAMP | Creation timestamp |

| updated_at | TIMESTAMP | Update timestamp |

#### `vendor_product_qna_answer`

- Added: `is_ai_generated` (tinyint) - 1 for AI, 0 for admin### 3. vendor_product_qna_helpful

- Added: `ai_answer_id` (int) - References AI answer details| Column | Type | Description |

|--------|------|-------------|

#### `vendor_product_qna_ai_answer` (NEW)| helpful_id | INT | Primary Key |

- Stores AI-generated answer metadata| question_id | INT | Foreign Key to vendor_product_qna_question |

- Fields: `ai_answer_id`, `question_id`, `ai_model_name`, `ai_answer_text`, `processing_time_ms`, `status`| customer_id | INT | Customer who voted |

| ip_address | VARCHAR(45) | IP address |

## 🛠️ Configuration| created_at | TIMESTAMP | Creation timestamp |



### Admin Configuration Path## 🎯 Usage

**Stores > Configuration > Catalog > Product Q&A**

### For Customers

### Configuration Options

1. Navigate to any product page

| Option | Description | Default |2. Click on the "Questions & Answers" tab

|--------|-------------|---------|3. Click "Ask a Question" button

| Enable Module | Enable/disable the module | Yes |4. Fill in the modal form with your question

| AI Service URL | URL of the AI answer service | http://localhost:3000 |5. Submit and wait for admin approval/answer

| Notification Email | Email for new question alerts | store email |

| Auto-approve Questions | Skip admin approval | No |### For Administrators

| Email Notifications | Send email to customers | Yes |

1. **Access the module:**

### AI Service Configuration   - Navigate to: **Admin Panel → Product Q&A → Manage Questions**



Edit `ai-service/server.js`:2. **Question Workflow:**



```javascript   **For Pending Questions:**

// Server configuration   - Click "Approve" to make visible on frontend

const PORT = 3000;   - Click "Answer" to provide an answer (auto-approves)

const USE_AI_MODEL = false; // true for AI model, false for rule-based   - Click "Archive" to hide from frontend

   - Click "Delete" to remove permanently

// AI Model configuration (if USE_AI_MODEL = true)

const MODEL_NAME = 'Qwen/Qwen2.5-3B-Instruct';   **For Approved Questions:**

```   - Click "Answer" to provide an answer

   - Click "Archive" to hide from frontend

## 🔧 CLI Commands   - Click "Delete" to remove permanently



### Retry AI Answers   **For Answered Questions:**

```bash   - Click "Edit Answer" to modify your answer

php bin/magento productqna:retry-ai-answers   - Click "Archive" to hide from frontend

```   - Click "Delete" to remove permanently

Retries AI answer generation for questions that failed.

   **For Archived Questions:**

### Module Commands   - Click "Approve" to restore and make visible

```bash   - Click "Set to Pending" to move back to review

# Enable module   - Click "Delete" to remove permanently

php bin/magento module:enable Vendor_ProductQnA

3. **Answering Questions:**

# Disable module   - Click "Answer" or "Edit Answer" button

php bin/magento module:disable Vendor_ProductQnA   - View question details and product information

   - See existing answers (if any)

# Check status   - Enter your answer in the text area

php bin/magento module:status Vendor_ProductQnA   - Click "Submit Answer" or "Update Answer"

```   - Your name will be displayed as the answerer on frontend



## 🐛 Troubleshooting## 🔧 Configuration



### AI Service Issues### ACL Permissions

The module includes ACL configuration. You can assign permissions via:

**Problem**: AI service returns 503 errors**System → Permissions → User Roles**

```bash

# Check if service is runningResource: `Vendor_ProductQnA::questions`

curl http://localhost:3000/health

## 📁 File Structure

# Check logs

tail -f app/code/Vendor/ProductQnA/ai-service/ai-service.log```

app/code/Vendor/ProductQnA/

# Restart service├── Api/

pkill -f "node server.js"│   └── Data/

node server.js│       ├── AnswerInterface.php

```│       └── QuestionInterface.php

├── Block/

**Problem**: Docker containers can't reach AI service│   ├── Adminhtml/

```bash│   │   └── Question/

# Use Docker gateway IP instead of localhost│   │       └── Answer.php

# Find gateway IP:│   ├── Product/

docker network inspect your_network | grep Gateway│   │   └── View/

│   │       └── Questions.php

# Update config to use gateway IP (e.g., 172.23.0.1:3000)│   └── Question/

```│       └── Form.php

├── Controller/

**Problem**: Firewall blocking AI service│   ├── Adminhtml/

```bash│   │   └── Question/

# Allow Docker network│   │       ├── Answer.php

sudo ufw allow from 172.23.0.0/16 to any port 3000│   │       ├── Approve.php

│   │       ├── Archive.php

# Or allow from localhost only│   │       ├── Delete.php

sudo ufw allow from 127.0.0.1 to any port 3000│   │       ├── EditAnswer.php

```│   │       ├── Index.php

│   │       ├── Pending.php

### Module Issues│   │       └── SaveAnswer.php

│   └── Question/

**Problem**: Questions not appearing on product page│       ├── Form.php

```bash│       └── Save.php

# Clear cache├── etc/

php bin/magento cache:flush│   ├── adminhtml/

│   │   ├── menu.xml

# Recompile│   │   └── routes.xml

php bin/magento setup:di:compile│   ├── frontend/

│   │   └── routes.xml

# Deploy static content│   ├── acl.xml

php bin/magento setup:static-content:deploy -f│   ├── db_schema.xml

```│   ├── di.xml

│   └── module.xml

**Problem**: Foreign key constraint error├── Model/

```bash│   ├── ResourceModel/

# This was fixed in v2.0.0│   │   ├── Answer/

# Run upgrade to get the fix:│   │   │   └── Collection.php

php bin/magento setup:upgrade│   │   ├── Question/

```│   │   │   ├── Collection.php

│   │   │   └── Grid/

## 📊 Performance│   │   │       └── Collection.php

│   │   ├── Answer.php

- **Rule-based mode**: ~50-100ms response time│   │   └── Question.php

- **AI model mode**: ~2-5 seconds (first load), ~500ms-1s (subsequent)│   ├── Source/

- **Database**: Optimized indexes on frequently queried columns│   │   └── QuestionStatus.php

- **Caching**: Full page cache compatible│   ├── Answer.php

│   └── Question.php

## 🔄 Upgrade from v1.x to v2.0.0├── Ui/

│   └── Component/

```bash│       └── Listing/

# Backup database first!│           └── Column/

php bin/magento setup:backup --db│               ├── ProductLink.php

│               └── QuestionActions.php

# Pull latest code├── view/

git pull origin main│   ├── adminhtml/

│   │   ├── layout/

# Run upgrade│   │   │   ├── productqna_question_answer.xml

php bin/magento setup:upgrade│   │   │   └── productqna_question_index.xml

│   │   ├── templates/

# This will:│   │   │   └── question/

# - Add answer_preference column to questions table│   │   │       └── answer.phtml

# - Add is_ai_generated, ai_answer_id to answers table│   │   └── ui_component/

# - Create vendor_product_qna_ai_answer table│   │       └── productqna_question_listing.xml

# - Set existing questions to answer_preference='admin'│   └── frontend/

│       ├── layout/

# Clear cache│       │   └── catalog_product_view.xml

php bin/magento cache:flush│       └── templates/

│           ├── product/

# Recompile│           │   └── view/

php bin/magento setup:di:compile│           │       └── questions.phtml

│           └── question/

# Deploy static content│               └── form.phtml

php bin/magento setup:static-content:deploy -f├── composer.json

├── registration.php

# Install AI service (optional)└── README.md

cd app/code/Vendor/ProductQnA/ai-service```

npm install

node server.js## 🚀 Publishing to GitHub/Packagist

```

### 1. Create GitHub Repository

## 🤝 Contributing

```bash

Contributions are welcome! Please feel free to submit a Pull Request.cd app/code/Vendor/ProductQnA

git init

1. Fork the repositorygit add .

2. Create your feature branch (`git checkout -b feature/AmazingFeature`)git commit -m "Initial commit: Magento 2 Product Q&A Module v1.0.0"

3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)git branch -M main

4. Push to the branch (`git push origin feature/AmazingFeature`)git remote add origin https://github.com/yourusername/magento2-productqna.git

5. Open a Pull Requestgit push -u origin main

```

## 📝 Changelog

### 2. Create Release Tag

### v2.0.0 (2026-02-06)

- ✨ **NEW**: AI-powered answer generation```bash

- ✨ **NEW**: Customer choice between AI and Admin answersgit tag -a v1.0.0 -m "Release version 1.0.0"

- ✨ **NEW**: Rule-based answer mode (no AI model required)git push origin v1.0.0

- ✨ **NEW**: Optional AI model integration (Qwen 2.5-3B-Instruct)```

- ✨ **NEW**: AI answer tracking and metadata

- ✨ **NEW**: CLI command to retry failed AI answers### 3. Publish to Packagist

- ✨ **NEW**: Visual badges for answer source

- 🐛 **FIX**: Foreign key constraint issues1. Go to https://packagist.org

- 🐛 **FIX**: Modal z-index for better visibility2. Click "Submit"

- 📚 **DOCS**: Comprehensive installation guide3. Enter your GitHub repository URL

- 🔧 **IMPROVE**: Enhanced product context for AI4. Packagist will automatically sync releases



### v1.0.0### 4. Update composer.json for Packagist

- Initial release

- Basic Q&A functionalityChange the name to match your GitHub username:

- Admin management panel```json

- Email notifications{

    "name": "yourusername/magento2-productqna",

## 📄 License    "description": "Product Questions & Answers module for Magento 2",

    "type": "magento2-module",

This project is licensed under the MIT License.    "version": "1.0.0",

    "license": "MIT"

## 👨‍💻 Author}

```

**Vendor**

- GitHub: [@Moozaheed](https://github.com/Moozaheed)## 🔄 Updates & Changelog

- Repository: [Megento-Product-QnA](https://github.com/Moozaheed/Megento-Product-QnA)

### Version 1.0.0 (Initial Release)

## 🙏 Acknowledgments- Complete question and answer system

- Admin panel with grid management

- Magento 2 Community- Frontend question submission form

- Transformers.js for AI capabilities- Answer workflow with admin attribution

- All contributors and users- Status-based workflow (Pending → Approved → Answered → Archived)

- Product linking in admin grid

## 📞 Support- AJAX form submission

- Responsive UI design

For issues, questions, or suggestions:

- Open an issue on GitHub## 🐛 Troubleshooting

- Email: support@vendor.com

### Module not showing in admin

---```bash

php bin/magento module:enable Vendor_ProductQnA

**⭐ If you find this module helpful, please star the repository!**php bin/magento setup:upgrade

php bin/magento cache:flush
```

### Permission issues
```bash
chmod -R 777 var/ generated/ pub/static/
chown -R www-data:www-data var/ generated/ pub/static/
```

### Grid not loading
```bash
php bin/magento setup:di:compile
php bin/magento cache:flush
```

### Frontend not showing questions tab
- Clear cache
- Check if questions exist with status "Approved" or "Answered"
- Check browser console for JavaScript errors

## � Author

**G. M. Mozahed**  
Software Engineer at Brain Station 23

## 🤝 Support

For issues, questions, or contributions, please contact:  
📧 Email: giyasmahmudmozahed@gmail.com

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## 🙏 Credits

Built with Magento 2 best practices and standards.
