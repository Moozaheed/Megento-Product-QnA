# Code Quality & Testing Guide

This guide explains how to check and maintain code quality for the Magento 2 Product Q&A module using industry-standard tools.

## 📋 Table of Contents

1. [Setup & Installation](#setup--installation)
2. [Magento Coding Standards](#magento-coding-standards)
3. [PHP Code Sniffer (PHPCS)](#php-code-sniffer-phpcs)
4. [PHP Mess Detector (PHPMD)](#php-mess-detector-phpmd)
5. [PHP Static Analysis (PHPStan)](#php-static-analysis-phpstan)
6. [Automated Testing](#automated-testing)
7. [Quick Reference](#quick-reference)

---

## 🚀 Setup & Installation

### Prerequisites

- Magento 2.4.x installed
- Composer installed
- PHP 8.1 or higher

### Install Code Quality Tools

```bash
# Navigate to Magento root
cd /path/to/magento/root

# Install Magento Coding Standard (includes PHPCS)
composer require --dev magento/magento-coding-standard

# Install PHP Mess Detector
composer require --dev phpmd/phpmd

# Install PHPStan for static analysis
composer require --dev phpstan/phpstan
composer require --dev bitexpert/phpstan-magento

# Install PHP Copy/Paste Detector
composer require --dev sebastian/phpcpd
```

### For Docker Environment

```bash
# Install inside FPM container
docker-compose exec -T fpm composer require --dev magento/magento-coding-standard
docker-compose exec -T fpm composer require --dev phpmd/phpmd
docker-compose exec -T fpm composer require --dev phpstan/phpstan
docker-compose exec -T fpm composer require --dev bitexpert/phpstan-magento
```

---

## 📏 Magento Coding Standards

### What to Check

Magento has strict coding standards covering:

1. **PSR-12 Compliance**: Basic PHP coding style
2. **Magento-Specific Rules**: 
   - Proper use of dependency injection
   - No direct object instantiation (avoid `new` keyword)
   - Constructor parameter ordering
   - DocBlock requirements
   - File structure and naming conventions

### Configure PHP CodeSniffer

```bash
# Set Magento standards as default
vendor/bin/phpcs --config-set installed_paths vendor/magento/magento-coding-standard
vendor/bin/phpcs --config-set default_standard Magento2
```

### Check Your Module

**Check entire module:**
```bash
vendor/bin/phpcs --standard=Magento2 app/code/Vendor/ProductQnA/
```

**Check specific file:**
```bash
vendor/bin/phpcs --standard=Magento2 app/code/Vendor/ProductQnA/Controller/Question/Save.php
```

**Check with detailed report:**
```bash
vendor/bin/phpcs --standard=Magento2 --report=full app/code/Vendor/ProductQnA/
```

**Check and show warnings:**
```bash
vendor/bin/phpcs --standard=Magento2 -w app/code/Vendor/ProductQnA/
```

### Auto-Fix Issues

PHP Code Beautifier and Fixer (PHPCBF) can automatically fix many issues:

```bash
# Auto-fix entire module
vendor/bin/phpcbf --standard=Magento2 app/code/Vendor/ProductQnA/

# Auto-fix specific file
vendor/bin/phpcbf --standard=Magento2 app/code/Vendor/ProductQnA/Controller/Question/Save.php
```

### Docker Commands

```bash
# Check module in Docker
docker-compose exec -T fpm vendor/bin/phpcs --standard=Magento2 app/code/Vendor/ProductQnA/

# Auto-fix in Docker
docker-compose exec -T fpm vendor/bin/phpcbf --standard=Magento2 app/code/Vendor/ProductQnA/
```

---

## 🔍 PHP Code Sniffer (PHPCS)

### What It Checks

- Code formatting (indentation, spacing, brackets)
- Naming conventions (classes, methods, variables)
- DocBlock comments
- Line length (max 120 characters)
- File structure
- Import statements ordering

### Common Issues to Fix

1. **Missing DocBlocks**
   ```php
   // ❌ Bad
   public function save($data) {
   
   // ✅ Good
   /**
    * Save question data
    *
    * @param array $data
    * @return bool
    */
   public function save(array $data): bool {
   ```

2. **Direct Object Instantiation**
   ```php
   // ❌ Bad
   $model = new Question();
   
   // ✅ Good
   public function __construct(
       private readonly QuestionFactory $questionFactory
   ) {}
   
   $model = $this->questionFactory->create();
   ```

3. **Long Lines**
   ```php
   // ❌ Bad (over 120 characters)
   $result = $this->someVeryLongMethodName($parameter1, $parameter2, $parameter3, $parameter4);
   
   // ✅ Good
   $result = $this->someVeryLongMethodName(
       $parameter1,
       $parameter2,
       $parameter3,
       $parameter4
   );
   ```

### Generate Reports

```bash
# Summary report
vendor/bin/phpcs --standard=Magento2 --report=summary app/code/Vendor/ProductQnA/

# Full report
vendor/bin/phpcs --standard=Magento2 --report=full app/code/Vendor/ProductQnA/

# JSON report (for CI/CD)
vendor/bin/phpcs --standard=Magento2 --report=json app/code/Vendor/ProductQnA/

# XML report
vendor/bin/phpcs --standard=Magento2 --report=xml app/code/Vendor/ProductQnA/

# HTML report
vendor/bin/phpcs --standard=Magento2 --report=html --report-file=phpcs-report.html app/code/Vendor/ProductQnA/
```

---

## 🔬 PHP Mess Detector (PHPMD)

### What It Checks

- Code complexity (cyclomatic complexity)
- Unused variables and parameters
- Code duplication
- Naming conventions
- Design issues

### Run PHPMD

```bash
# Basic check
vendor/bin/phpmd app/code/Vendor/ProductQnA/ text cleancode,codesize,controversial,design,naming,unusedcode

# Generate HTML report
vendor/bin/phpmd app/code/Vendor/ProductQnA/ html cleancode,codesize,controversial,design,naming,unusedcode --reportfile phpmd-report.html

# XML report for CI/CD
vendor/bin/phpmd app/code/Vendor/ProductQnA/ xml cleancode,codesize,controversial,design,naming,unusedcode > phpmd-report.xml
```

### Rule Sets Explained

- **cleancode**: Clean code principles
- **codesize**: Long methods, classes, parameter lists
- **controversial**: Controversial coding practices
- **design**: Design issues (depth of inheritance, coupling)
- **naming**: Naming conventions
- **unusedcode**: Unused variables, methods, parameters

### Create Custom PHPMD Config

Create `phpmd.xml` in module root:

```xml
<?xml version="1.0"?>
<ruleset name="ProductQnA PHPMD Rules">
    <description>PHPMD rules for Product Q&A module</description>
    
    <!-- Import standard rule sets -->
    <rule ref="rulesets/cleancode.xml" />
    <rule ref="rulesets/codesize.xml" />
    <rule ref="rulesets/controversial.xml" />
    <rule ref="rulesets/design.xml" />
    <rule ref="rulesets/naming.xml" />
    <rule ref="rulesets/unusedcode.xml" />
    
    <!-- Exclude test files -->
    <exclude-pattern>*/Test/*</exclude-pattern>
</ruleset>
```

Then run:
```bash
vendor/bin/phpmd app/code/Vendor/ProductQnA/ text phpmd.xml
```

---

## 🎯 PHP Static Analysis (PHPStan)

### What It Checks

- Type safety
- Undefined variables
- Method calls on null
- Incorrect return types
- Missing properties
- Dead code

### Configure PHPStan

Create `phpstan.neon` in module root:

```neon
parameters:
    level: 8
    paths:
        - .
    excludePaths:
        - */Test/*
        - */tests/*
    scanDirectories:
        - ../../../../../../vendor/magento
    treatPhpDocTypesAsCertain: false
    checkGenericClassInNonGenericObjectType: false
```

### Run PHPStan

```bash
# Basic analysis (level 0-8, 8 is strictest)
vendor/bin/phpstan analyse app/code/Vendor/ProductQnA/ --level=5

# With custom config
vendor/bin/phpstan analyse -c app/code/Vendor/ProductQnA/phpstan.neon

# Generate baseline (ignore existing issues)
vendor/bin/phpstan analyse app/code/Vendor/ProductQnA/ --level=8 --generate-baseline

# Memory limit for large projects
vendor/bin/phpstan analyse app/code/Vendor/ProductQnA/ --memory-limit=2G
```

---

## 🧪 Automated Testing

### Unit Tests

```bash
# Run module tests
vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist app/code/Vendor/ProductQnA/Test/Unit/

# With coverage
vendor/bin/phpunit -c dev/tests/unit/phpunit.xml.dist --coverage-html coverage/ app/code/Vendor/ProductQnA/Test/Unit/
```

### Integration Tests

```bash
# Run integration tests
vendor/bin/phpunit -c dev/tests/integration/phpunit.xml.dist app/code/Vendor/ProductQnA/Test/Integration/
```

---

## 📊 Quick Reference

### Essential Commands

```bash
# 1. PHPCS Check
vendor/bin/phpcs --standard=Magento2 app/code/Vendor/ProductQnA/

# 2. PHPCS Auto-fix
vendor/bin/phpcbf --standard=Magento2 app/code/Vendor/ProductQnA/

# 3. PHPMD Check
vendor/bin/phpmd app/code/Vendor/ProductQnA/ text cleancode,codesize,design,naming,unusedcode

# 4. PHPStan Analysis
vendor/bin/phpstan analyse app/code/Vendor/ProductQnA/ --level=5

# 5. Copy/Paste Detection
vendor/bin/phpcpd app/code/Vendor/ProductQnA/
```

### Docker Commands

```bash
# PHPCS in Docker
docker-compose exec -T fpm vendor/bin/phpcs --standard=Magento2 app/code/Vendor/ProductQnA/

# PHPCBF in Docker
docker-compose exec -T fpm vendor/bin/phpcbf --standard=Magento2 app/code/Vendor/ProductQnA/

# PHPMD in Docker
docker-compose exec -T fpm vendor/bin/phpmd app/code/Vendor/ProductQnA/ text cleancode,codesize,design,naming,unusedcode

# PHPStan in Docker
docker-compose exec -T fpm vendor/bin/phpstan analyse app/code/Vendor/ProductQnA/ --level=5
```

---

## 🎯 What to Focus On

### Priority 1: Critical Issues

1. **Dependency Injection** - No `new` keyword for Magento objects
2. **Type Declarations** - All parameters and returns typed
3. **DocBlocks** - Complete PHPDoc for all public methods
4. **Security** - No SQL injection, XSS vulnerabilities
5. **Error Handling** - Proper exception handling

### Priority 2: Code Quality

1. **Method Length** - Max 50 lines per method
2. **Class Length** - Max 500 lines per class
3. **Cyclomatic Complexity** - Max 10 per method
4. **Parameter Count** - Max 5 parameters per method
5. **Code Duplication** - No duplicate code blocks

### Priority 3: Style & Formatting

1. **Indentation** - 4 spaces, no tabs
2. **Line Length** - Max 120 characters
3. **Naming** - PSR-12 conventions
4. **File Structure** - Proper organization
5. **Imports** - Alphabetical ordering

---

## 🔄 Continuous Integration

### Sample CI/CD Pipeline (GitHub Actions)

Create `.github/workflows/code-quality.yml`:

```yaml
name: Code Quality

on: [push, pull_request]

jobs:
  phpcs:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: PHPCS
        run: vendor/bin/phpcs --standard=Magento2 app/code/Vendor/ProductQnA/

  phpmd:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: PHPMD
        run: vendor/bin/phpmd app/code/Vendor/ProductQnA/ text cleancode,codesize,design,naming

  phpstan:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: PHPStan
        run: vendor/bin/phpstan analyse app/code/Vendor/ProductQnA/ --level=5
```

---

## 📝 Checklist Before Release

- [ ] PHPCS passes with 0 errors
- [ ] PHPMD shows no critical issues
- [ ] PHPStan level 5+ passes
- [ ] No code duplication detected
- [ ] All public methods have DocBlocks
- [ ] No direct object instantiation
- [ ] All dependencies use DI
- [ ] Type declarations present
- [ ] Unit tests pass
- [ ] Integration tests pass
- [ ] Manual testing complete

---

## 🔧 Troubleshooting

### PHPCS Not Finding Magento Standards

```bash
# Re-register standards
vendor/bin/phpcs --config-set installed_paths vendor/magento/magento-coding-standard
vendor/bin/phpcs -i  # Should show Magento2 in list
```

### PHPStan Memory Issues

```bash
# Increase memory limit
vendor/bin/phpstan analyse --memory-limit=4G app/code/Vendor/ProductQnA/
```

### PHPMD Taking Too Long

```bash
# Reduce rule sets
vendor/bin/phpmd app/code/Vendor/ProductQnA/ text codesize,naming,unusedcode
```

---

## 📚 Additional Resources

- [Magento Technical Guidelines](https://developer.adobe.com/commerce/php/coding-standards/)
- [PHP CodeSniffer Documentation](https://github.com/squizlabs/PHP_CodeSniffer/wiki)
- [PHPMD Rules](https://phpmd.org/rules/index.html)
- [PHPStan Documentation](https://phpstan.org/user-guide/getting-started)
- [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)

---

## 💡 Tips

1. **Run checks frequently** during development, not just before release
2. **Auto-fix when possible** using PHPCBF
3. **Use IDE plugins** (PHPStorm has built-in PHPCS/PHPMD support)
4. **Create git hooks** to run checks automatically before commit
5. **Start with lower PHPStan levels** and gradually increase
6. **Focus on new code** first, create baseline for legacy code

---

**Last Updated**: March 2, 2026  
**Module Version**: 1.0.0
