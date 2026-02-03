# Changelog

All notable changes to the Magento 2 Product Q&A Module will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [1.0.0] - 2026-02-03

### Added
- Initial release of Product Q&A module
- Customer question submission on product pages
- AJAX-based modal form for asking questions
- Admin panel for managing questions and answers
- UI Component grid with advanced filtering
- Question workflow management (Pending, Approved, Answered, Archived)
- Answer creation and editing functionality
- Admin attribution tracking (shows who answered questions)
- Product linking in admin grid
- Status-based action buttons
- ACL permissions for admin access
- Database schema with 3 tables (questions, answers, helpful votes)
- Frontend Q&A tab on product pages
- Responsive UI design
- Question approval/rejection system
- Archive functionality to hide questions from frontend
- Restore archived questions (Approve or Set to Pending)
- Delete questions with cascade answer removal
- Answer editing capability
- Customer email and name tracking
- Timestamp tracking for questions and answers
- Visibility controls (Public/Private)
- Helpful count tracking (foundation for future voting feature)

### Technical Features
- RESTful API interfaces (QuestionInterface, AnswerInterface)
- Proper dependency injection configuration
- Resource models and collections
- UI Component data provider with SearchResultInterface
- Foreign key constraints with CASCADE and SET NULL
- Admin routing configuration
- Frontend routing configuration
- Block classes for data retrieval
- Controller actions for all operations
- Admin menu integration
- Layout XML configurations
- Template files (phtml) for frontend and admin
- Source models for dropdown options
- Composer.json for dependency management
- Module registration
- ACL XML for permissions

### Workflow States
1. **Pending (0)**: Initial state when customer submits question
   - Actions: Approve, Answer, Archive, Delete
   
2. **Approved (1)**: Question approved and visible on frontend without answer
   - Actions: Answer, Archive, Delete
   
3. **Answered (3)**: Question has been answered by admin
   - Actions: Edit Answer, Archive, Delete
   - Automatically set when admin submits an answer
   
4. **Archived (4)**: Question hidden from frontend
   - Actions: Approve, Set to Pending, Delete
   - Not visible to customers
   
5. **Rejected (2)**: Question rejected by admin
   - Not actively used in current workflow
   - Reserved for future enhancement

### Database Schema
- `vendor_product_qna_question`: 11 columns with foreign key to catalog_product_entity
- `vendor_product_qna_answer`: 7 columns with foreign key to vendor_product_qna_question
- `vendor_product_qna_helpful`: 5 columns for tracking helpful votes (foundation)

### Frontend Features
- Questions & Answers tab on product pages
- Modal popup for question submission
- AJAX form submission (no page reload)
- Display approved and answered questions
- Show answers with admin attribution
- Responsive design
- Clean, modern UI
- Question count display
- Date formatting

### Admin Features
- Dedicated menu item: Product Q&A → Manage Questions
- Grid columns: ID, Product (with link), Question, Customer Name, Email, Status, Created Date, Actions
- Advanced filtering by all columns
- Sorting capabilities
- Pagination
- Mass actions ready
- Product edit page links
- Answer form with question details
- Answer history display
- Admin user name display in answers
- Success/error messages
- Confirmation dialogs for critical actions

### Security
- Form key validation
- ACL permissions
- Admin authentication required
- Input sanitization
- XSS protection via escapeHtml
- SQL injection protection via ORM
- CSRF protection

## [Unreleased]

### Planned Features
- Email notifications to customers when answered
- Helpful voting system for questions
- Answer helpful voting
- Rich text editor for answers
- Image attachment support
- Question categories
- FAQ auto-generation from popular questions
- Question search functionality on frontend
- Question moderation queue
- Bulk approval/rejection
- Export questions to CSV
- Import questions from CSV
- Question analytics dashboard
- Customer question history
- Response time tracking
- SLA management for question responses
- Auto-response templates
- Question tagging
- Related questions display
- Question duplicate detection
- Social sharing integration
- SEO optimization for Q&A content
- GraphQL API support
- REST API endpoints
- Multi-store support enhancements
- Multi-language support
- Question translation features
- Admin answer templates
- Private answers (visible only to asker)
- Verified purchase badge
- Expert badges for admins
- Question pinning
- Featured questions
- Question recommendation engine

### Known Issues
- None reported in version 1.0.0

### Compatibility
- Magento 2.4.x
- PHP 8.1, 8.2, 8.3
- MySQL 5.7+, MariaDB 10.4+

---

## Version History

- **1.0.0** (2026-02-03): Initial release with complete Q&A functionality
