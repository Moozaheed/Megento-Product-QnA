#!/bin/bash

# Code Quality Check Script for Product Q&A Module
# This script runs various code quality checks on the module

set -e

MODULE_PATH="app/code/Vendor/ProductQnA"
MAGENTO_ROOT="/home/bs01233/Documents/Megento/project-community-edition"

cd "$MAGENTO_ROOT"

echo "=========================================="
echo "Product Q&A Module - Code Quality Check"
echo "=========================================="
echo ""

# Check if tools are installed
echo "Checking if required tools are installed..."
if [ ! -f "vendor/bin/phpcs" ]; then
    echo "❌ PHP CodeSniffer not found. Installing..."
    composer require --dev squizlabs/php_codesniffer
fi

if [ ! -f "vendor/magento/magento-coding-standard/Magento2/ruleset.xml" ]; then
    echo "❌ Magento Coding Standard not found. Installing..."
    composer require --dev magento/magento-coding-standard
    vendor/bin/phpcs --config-set installed_paths vendor/magento/magento-coding-standard
fi

echo "✅ All tools installed"
echo ""

# 1. PHPCS Check
echo "=========================================="
echo "1. Running PHP CodeSniffer (PHPCS)"
echo "=========================================="
echo "Checking: $MODULE_PATH"
echo ""

vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --ignore=*/Test/*,*/tests/*,*.phtml,*.xml,*.md \
    --report=summary \
    "$MODULE_PATH" || true

echo ""

# 2. PHPCS Full Report
echo "=========================================="
echo "2. Detailed PHPCS Report"
echo "=========================================="
echo ""

vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --ignore=*/Test/*,*/tests/*,*.phtml,*.xml,*.md \
    --report=full \
    "$MODULE_PATH" || true

echo ""

# 3. Check specific important files
echo "=========================================="
echo "3. Checking Key Files"
echo "=========================================="
echo ""

KEY_FILES=(
    "Controller/Question/Save.php"
    "Controller/Adminhtml/Question/Save.php"
    "Block/Product/View/Questions.php"
    "Model/Question.php"
    "Model/Answer.php"
)

for file in "${KEY_FILES[@]}"; do
    if [ -f "$MODULE_PATH/$file" ]; then
        echo "Checking: $file"
        vendor/bin/phpcs \
            --standard=PSR12 \
            --extensions=php \
            "$MODULE_PATH/$file" || true
        echo ""
    fi
done

# 4. Auto-fix suggestions
echo "=========================================="
echo "4. Auto-Fix Available Issues"
echo "=========================================="
echo ""
echo "To automatically fix coding standard issues, run:"
echo "  vendor/bin/phpcbf --standard=PSR12 $MODULE_PATH"
echo ""

# 5. Generate HTML report
echo "=========================================="
echo "5. Generating HTML Report"
echo "=========================================="
echo ""

vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --ignore=*/Test/*,*/tests/*,*.phtml,*.xml,*.md \
    --report=html \
    --report-file=phpcs-report.html \
    "$MODULE_PATH" || true

if [ -f "phpcs-report.html" ]; then
    echo "✅ HTML report generated: phpcs-report.html"
    echo "   Open it in browser: file://$MAGENTO_ROOT/phpcs-report.html"
else
    echo "⚠️  No issues found or report generation failed"
fi

echo ""
echo "=========================================="
echo "Summary"
echo "=========================================="
echo ""
echo "✅ Code quality check complete!"
echo ""
echo "Next steps:"
echo "1. Review the issues above"
echo "2. Run auto-fix: vendor/bin/phpcbf --standard=PSR12 $MODULE_PATH"
echo "3. Check HTML report: phpcs-report.html"
echo "4. Fix remaining issues manually"
echo ""
