#!/bin/bash

# Comprehensive Code Quality Check Script
# Uses best practices for PHP CodeSniffer

set -e

MODULE_PATH="app/code/Vendor/ProductQnA"
MAGENTO_ROOT="/home/bs01233/Documents/Megento/project-community-edition"
REPORT_DIR="$MAGENTO_ROOT/code-quality-reports"

cd "$MAGENTO_ROOT"

# Create reports directory
mkdir -p "$REPORT_DIR"

echo "=========================================="
echo "Comprehensive Code Quality Analysis"
echo "Module: Product Q&A"
echo "=========================================="
echo ""

# 1. Summary Report
echo "1️⃣  SUMMARY REPORT"
echo "=========================================="
vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --colors \
    --report=summary \
    "$MODULE_PATH"

echo ""
echo ""

# 2. Source Analysis (Which rules are triggered most)
echo "2️⃣  VIOLATION SOURCE ANALYSIS"
echo "=========================================="
vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --report=source \
    --report-width=120 \
    "$MODULE_PATH"

echo ""
echo ""

# 3. Code Statistics
echo "3️⃣  CODE STATISTICS"
echo "=========================================="
vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --report=info \
    "$MODULE_PATH"

echo ""
echo ""

# 4. File-by-File Breakdown
echo "4️⃣  FILE-BY-FILE ANALYSIS"
echo "=========================================="
vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --report=full \
    --report-width=120 \
    "$MODULE_PATH" > "$REPORT_DIR/full-report.txt" 2>&1 || true

echo "✅ Detailed report saved: $REPORT_DIR/full-report.txt"
echo "   View with: cat $REPORT_DIR/full-report.txt | less"
echo ""

# 5. JSON Report for CI/CD
echo "5️⃣  GENERATING JSON REPORT FOR CI/CD"
echo "=========================================="
vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --report=json \
    --report-file="$REPORT_DIR/phpcs-report.json" \
    "$MODULE_PATH" || true

if [ -f "$REPORT_DIR/phpcs-report.json" ]; then
    echo "✅ JSON report generated: $REPORT_DIR/phpcs-report.json"
    
    # Parse JSON and show summary
    if command -v jq &> /dev/null; then
        TOTAL_ERRORS=$(jq '.totals.errors' "$REPORT_DIR/phpcs-report.json")
        TOTAL_WARNINGS=$(jq '.totals.warnings' "$REPORT_DIR/phpcs-report.json")
        TOTAL_FIXABLE=$(jq '.totals.fixable' "$REPORT_DIR/phpcs-report.json")
        
        echo ""
        echo "   Total Errors:   $TOTAL_ERRORS"
        echo "   Total Warnings: $TOTAL_WARNINGS"
        echo "   Auto-fixable:   $TOTAL_FIXABLE"
    fi
fi
echo ""

# 6. XML Report
echo "6️⃣  GENERATING XML REPORT"
echo "=========================================="
vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --report=xml \
    --report-file="$REPORT_DIR/phpcs-report.xml" \
    "$MODULE_PATH" || true

if [ -f "$REPORT_DIR/phpcs-report.xml" ]; then
    echo "✅ XML report generated: $REPORT_DIR/phpcs-report.xml"
fi
echo ""

# 7. CSV Report (Easy to import into spreadsheet)
echo "7️⃣  GENERATING CSV REPORT"
echo "=========================================="
vendor/bin/phpcs \
    --standard=PSR12 \
    --extensions=php \
    --report=csv \
    --report-file="$REPORT_DIR/phpcs-report.csv" \
    "$MODULE_PATH" || true

if [ -f "$REPORT_DIR/phpcs-report.csv" ]; then
    echo "✅ CSV report generated: $REPORT_DIR/phpcs-report.csv"
    echo "   Can be opened in Excel/Google Sheets"
fi
echo ""

# 8. Check specific critical files
echo "8️⃣  CRITICAL FILES DEEP CHECK"
echo "=========================================="

CRITICAL_FILES=(
    "Controller/Question/Save.php"
    "Controller/Adminhtml/Question/SaveAnswer.php"
    "Model/Question.php"
    "Model/Answer.php"
    "Block/Product/View/Questions.php"
)

for file in "${CRITICAL_FILES[@]}"; do
    if [ -f "$MODULE_PATH/$file" ]; then
        echo ""
        echo "📄 Checking: $file"
        echo "---"
        vendor/bin/phpcs \
            --standard=PSR12 \
            --extensions=php \
            --report=summary \
            "$MODULE_PATH/$file" || true
    fi
done

echo ""
echo ""

# 9. Check for potential security issues
echo "9️⃣  SECURITY & BEST PRACTICES CHECK"
echo "=========================================="
echo "Checking for common issues..."
echo ""

# Check for direct object instantiation (new keyword)
echo "🔍 Direct Object Instantiation (new keyword):"
grep -rn "new \\\\" "$MODULE_PATH" --include="*.php" | grep -v "Factory" | grep -v "Exception" | grep -v "DataObject" || echo "   ✅ None found (Good!)"
echo ""

# Check for eval() usage (security risk)
echo "🔍 eval() usage (Security Risk):"
grep -rn "eval(" "$MODULE_PATH" --include="*.php" || echo "   ✅ None found (Good!)"
echo ""

# Check for SQL injection risks (raw queries)
echo "🔍 Direct SQL queries:"
grep -rn "\$this->connection" "$MODULE_PATH" --include="*.php" | head -5 || echo "   ✅ None found (Good - using ORM)"
echo ""

# Check for XSS risks (echo without escaping)
echo "🔍 Potential XSS (echo without escaping):"
grep -rn "echo \$" "$MODULE_PATH" --include="*.php" || echo "   ✅ None found (Good!)"
echo ""

# 10. Code Complexity Analysis
echo "🔟 CODE COMPLEXITY METRICS"
echo "=========================================="

if command -v phploc &> /dev/null; then
    phploc "$MODULE_PATH" --exclude=Test
else
    echo "⚠️  phploc not installed. Install with: composer require --dev phploc/phploc"
    echo ""
    echo "Manual complexity check:"
    find "$MODULE_PATH" -name "*.php" -type f | while read file; do
        lines=$(wc -l < "$file")
        if [ $lines -gt 500 ]; then
            echo "   ⚠️  Large file ($lines lines): $(basename $file)"
        fi
    done
fi

echo ""
echo ""

# Summary
echo "=========================================="
echo "📊 SUMMARY"
echo "=========================================="
echo ""
echo "Reports generated in: $REPORT_DIR/"
echo ""
echo "Available reports:"
echo "  • full-report.txt  - Detailed line-by-line report"
echo "  • phpcs-report.json - Machine-readable for CI/CD"
echo "  • phpcs-report.xml  - XML format"
echo "  • phpcs-report.csv  - Spreadsheet format"
echo ""
echo "View detailed report:"
echo "  cat $REPORT_DIR/full-report.txt | less"
echo ""
echo "Next steps:"
echo "  1. Review full-report.txt for all issues"
echo "  2. Fix constants visibility (add 'public')"
echo "  3. Ignore Magento-specific patterns (_construct)"
echo "  4. Run auto-fix: vendor/bin/phpcbf --standard=PSR12 $MODULE_PATH"
echo ""
echo "✅ Code quality analysis complete!"
echo ""
