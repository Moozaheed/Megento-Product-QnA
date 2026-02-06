# Changelog# Changelog



All notable changes to the Magento 2 Product Q&A module will be documented in this file.All notable changes to the Magento 2 Product Q&A Module will be documented in this file.



The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),

and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).



## [2.0.0] - 2026-02-06## [1.0.0] - 2026-02-03



### Added### Added

- **AI-Powered Answer Generation**: Integration with AI service for automatic answer generation- Initial release of Product Q&A module

- **Customer Choice System**: Radio buttons allowing customers to choose between AI Assistant and Store Admin answers- Customer question submission on product pages

- **AI Service**: Node.js Express server with Transformers.js integration- AJAX-based modal form for asking questions

  - Rule-based mode (default): Intelligent template-based responses- Admin panel for managing questions and answers

  - AI Model mode (optional): Qwen 2.5-3B-Instruct with WebGPU support- UI Component grid with advanced filtering

- **AI Answer Metadata Table**: New `vendor_product_qna_ai_answer` table to track AI-generated answers- Question workflow management (Pending, Approved, Answered, Archived)

- **Product Context Extraction**: Enhanced context including custom attributes, categories, and full product details- Answer creation and editing functionality

- **Visual Indicators**: Badges and animations showing answer source (🤖 AI Assistant or 👨‍💼 Store Admin)- Admin attribution tracking (shows who answered questions)

- **CLI Command**: `productqna:retry-ai-answers` to regenerate failed AI answers- Product linking in admin grid

- **AI Service Health Check**: `/health` endpoint for monitoring- Status-based action buttons

- **Answer Preference Column**: Added `answer_preference` to questions table- ACL permissions for admin access

- **AI Generated Flag**: Added `is_ai_generated` and `ai_answer_id` to answers table- Database schema with 3 tables (questions, answers, helpful votes)

- **Console Logging**: Comprehensive JavaScript debugging for form submission and AI requests- Frontend Q&A tab on product pages

- Responsive UI design

### Changed- Question approval/rejection system

- **Module Version**: Updated from 1.0.0 to 2.0.0- Archive functionality to hide questions from frontend

- **Database Schema**: Enhanced with AI-related columns and foreign keys- Restore archived questions (Approve or Set to Pending)

- **Modal UI**: Improved with customer choice radio buttons and animations- Delete questions with cascade answer removal

- **Product Context**: Expanded to include all relevant product information for AI- Answer editing capability

- **Answer Service**: Refactored to support both AI and manual answer workflows- Customer email and name tracking

- Timestamp tracking for questions and answers

### Fixed- Visibility controls (Public/Private)

- **Foreign Key Constraint**: Changed `admin_user_id` to allow NULL for AI-generated answers- Helpful count tracking (foundation for future voting feature)

- **Unique Constraint Handling**: Added check for existing AI answers before creating duplicates

- **Modal Z-Index**: Increased to 99999 to appear above all page elements including product images### Technical Features

- **Docker Network Connectivity**: Documentation for using Docker gateway IP instead of localhost- RESTful API interfaces (QuestionInterface, AnswerInterface)

- **Firewall Configuration**: Added instructions for UFW firewall rules- Proper dependency injection configuration

- Resource models and collections

### Technical Details- UI Component data provider with SearchResultInterface

- **AI Service Location**: `app/code/Vendor/ProductQnA/ai-service/`- Foreign key constraints with CASCADE and SET NULL

- **AI Service Port**: 3000 (configurable)- Admin routing configuration

- **Node.js Version**: Requires 20.x or higher- Frontend routing configuration

- **Transformers.js**: Optional dependency for AI model mode- Block classes for data retrieval

- **Default AI Model**: Qwen/Qwen2.5-3B-Instruct (when AI model mode is enabled)- Controller actions for all operations

- Admin menu integration

### Database Migration- Layout XML configurations

```sql- Template files (phtml) for frontend and admin

-- Added columns to vendor_product_qna_question- Source models for dropdown options

ALTER TABLE vendor_product_qna_question - Composer.json for dependency management

ADD COLUMN answer_preference VARCHAR(10) DEFAULT 'admin';- Module registration

- ACL XML for permissions

-- Added columns to vendor_product_qna_answer

ALTER TABLE vendor_product_qna_answer ### Workflow States

ADD COLUMN is_ai_generated TINYINT(1) DEFAULT 0,1. **Pending (0)**: Initial state when customer submits question

ADD COLUMN ai_answer_id INT(10) UNSIGNED NULL;   - Actions: Approve, Answer, Archive, Delete

   

-- Created new table vendor_product_qna_ai_answer2. **Approved (1)**: Question approved and visible on frontend without answer

CREATE TABLE vendor_product_qna_ai_answer (   - Actions: Answer, Archive, Delete

  ai_answer_id INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,   

  question_id INT(10) UNSIGNED NOT NULL,3. **Answered (3)**: Question has been answered by admin

  ai_model_name VARCHAR(100) DEFAULT 'Qwen/Qwen2.5-3B-Instruct',   - Actions: Edit Answer, Archive, Delete

  ai_answer_text TEXT NOT NULL,   - Automatically set when admin submits an answer

  processing_time_ms INT(11),   

  status SMALLINT(5) UNSIGNED NOT NULL DEFAULT 0,4. **Archived (4)**: Question hidden from frontend

  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,   - Actions: Approve, Set to Pending, Delete

  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,   - Not visible to customers

  PRIMARY KEY (ai_answer_id),   

  UNIQUE KEY (question_id),5. **Rejected (2)**: Question rejected by admin

  FOREIGN KEY (question_id) REFERENCES vendor_product_qna_question(question_id) ON DELETE CASCADE   - Not actively used in current workflow

);   - Reserved for future enhancement

```

### Database Schema

### Security- `vendor_product_qna_question`: 11 columns with foreign key to catalog_product_entity

- **Local AI Processing**: All AI operations run locally, no third-party API calls- `vendor_product_qna_answer`: 7 columns with foreign key to vendor_product_qna_question

- **Input Validation**: Enhanced validation for customer input- `vendor_product_qna_helpful`: 5 columns for tracking helpful votes (foundation)

- **XSS Prevention**: Proper escaping of user-generated content

- **CSRF Protection**: Form tokens for AJAX submissions### Frontend Features

- Questions & Answers tab on product pages

### Performance- Modal popup for question submission

- **Rule-based Mode**: 50-100ms average response time- AJAX form submission (no page reload)

- **AI Model Mode**: 500ms-1s response time (after initial load)- Display approved and answered questions

- **Database Indexes**: Optimized queries with proper indexing- Show answers with admin attribution

- **Caching**: Compatible with Magento full page cache- Responsive design

- Clean, modern UI

## [1.0.0] - 2025-01-15- Question count display

- Date formatting

### Added

- Initial release of Product Q&A module### Admin Features

- Customer question submission on product pages- Dedicated menu item: Product Q&A → Manage Questions

- Admin management interface for questions and answers- Grid columns: ID, Product (with link), Question, Customer Name, Email, Status, Created Date, Actions

- Question approval workflow (Pending → Approved → Answered)- Advanced filtering by all columns

- Email notifications for customers and admins- Sorting capabilities

- Admin answer attribution- Pagination

- Responsive modal design- Mass actions ready

- Grid filters and search in admin panel- Product edit page links

- Status management system- Answer form with question details

- Product association with questions- Answer history display

- Customer information capture (name, email)- Admin user name display in answers

- SEO-friendly Q&A display on product pages- Success/error messages

- Confirmation dialogs for critical actions

### Database Tables

- `vendor_product_qna_question`: Store customer questions### Security

- `vendor_product_qna_answer`: Store admin answers- Form key validation

- Foreign key constraints with proper cascading- ACL permissions

- Admin authentication required

### Features- Input sanitization

- Frontend question submission form- XSS protection via escapeHtml

- Admin grid with sorting and filtering- SQL injection protection via ORM

- Answer management interface- CSRF protection

- Email notification system

- Integration with Magento catalog## [Unreleased]

- ACL (Access Control List) for admin permissions

- Configuration options in Magento admin### Planned Features

- Email notifications to customers when answered

---- Helpful voting system for questions

- Answer helpful voting

## Upgrade Instructions- Rich text editor for answers

- Image attachment support

### From 1.x to 2.0.0- Question categories

- FAQ auto-generation from popular questions

1. **Backup your database**:- Question search functionality on frontend

   ```bash- Question moderation queue

   php bin/magento setup:backup --db- Bulk approval/rejection

   ```- Export questions to CSV

- Import questions from CSV

2. **Update the code**:- Question analytics dashboard

   ```bash- Customer question history

   git pull origin main- Response time tracking

   ```- SLA management for question responses

- Auto-response templates

3. **Run upgrade**:- Question tagging

   ```bash- Related questions display

   php bin/magento setup:upgrade- Question duplicate detection

   php bin/magento setup:di:compile- Social sharing integration

   php bin/magento cache:flush- SEO optimization for Q&A content

   ```- GraphQL API support

- REST API endpoints

4. **Install AI service (optional)**:- Multi-store support enhancements

   ```bash- Multi-language support

   cd app/code/Vendor/ProductQnA/ai-service- Question translation features

   npm install- Admin answer templates

   node server.js- Private answers (visible only to asker)

   ```- Verified purchase badge

- Expert badges for admins

5. **Configure AI service URL** in Magento admin if using Docker or custom setup.- Question pinning

- Featured questions

---- Question recommendation engine



## Breaking Changes### Known Issues

- None reported in version 1.0.0

### v2.0.0

- **Database Schema**: New columns added to existing tables (backward compatible)### Compatibility

- **New Table**: `vendor_product_qna_ai_answer` created- Magento 2.4.x

- **AI Service**: Optional but recommended for full functionality- PHP 8.1, 8.2, 8.3

- **Node.js Required**: For AI service functionality- MySQL 5.7+, MariaDB 10.4+

- **Configuration**: New AI Service URL setting in admin

---

**Note**: All v1.0.0 features remain fully functional. AI features are opt-in via customer choice.

## Version History

---

- **1.0.0** (2026-02-03): Initial release with complete Q&A functionality

## Deprecations

None yet.

---

## Known Issues

### v2.0.0
- AI model download (~6GB) can take time on first run with AI model mode enabled
- WebGPU support varies by browser and hardware
- Docker environments require gateway IP configuration for AI service access

### Workarounds
- Use rule-based mode for instant setup without model download
- Use Chrome/Edge for best WebGPU support
- Follow Docker setup instructions in README for network configuration

---

**For detailed installation and usage instructions, see [README.md](README.md)**
