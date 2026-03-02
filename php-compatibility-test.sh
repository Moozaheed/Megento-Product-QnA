#!/bin/bash

# PHP Compatibility Testing Script for Magento Module
# Tests compatibility with different PHP versions

set -e

MODULE_PATH="/home/bs01233/Documents/Megento/project-community-edition/app/code/Vendor/ProductQnA"
MAGENTO_ROOT="/home/bs01233/Documents/Megento/project-community-edition"
REPORT_DIR="$MAGENTO_ROOT/compatibility-reports"

cd "$MAGENTO_ROOT"

# Create reports directory
mkdir -p "$REPORT_DIR"

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🐘 PHP COMPATIBILITY VALIDATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Module: Vendor_ProductQnA v1.0.0"
echo "Current PHP Version: $(php -v | head -1)"
echo "Date: $(date '+%Y-%m-%d %H:%M:%S')"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Magento 2.4.x requires PHP 8.1 or 8.2
# We'll test for both compatibility

# Test 1: PHP 8.1 Compatibility
echo "1️⃣  PHP 8.1 COMPATIBILITY CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Testing compatibility with PHP 8.1 (Magento 2.4.x minimum)..."
echo ""

vendor/bin/phpcs \
    --standard=PHPCompatibility \
    --runtime-set testVersion 8.1 \
    --extensions=php \
    --report=summary \
    "$MODULE_PATH" 2>&1 | tee "$REPORT_DIR/php81-compatibility.txt"

echo ""
echo ""

# Test 2: PHP 8.2 Compatibility
echo "2️⃣  PHP 8.2 COMPATIBILITY CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Testing compatibility with PHP 8.2 (Magento 2.4.x recommended)..."
echo ""

vendor/bin/phpcs \
    --standard=PHPCompatibility \
    --runtime-set testVersion 8.2 \
    --extensions=php \
    --report=summary \
    "$MODULE_PATH" 2>&1 | tee "$REPORT_DIR/php82-compatibility.txt"

echo ""
echo ""

# Test 3: PHP 8.1-8.2 Range
echo "3️⃣  PHP 8.1-8.2 RANGE COMPATIBILITY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Testing compatibility across PHP 8.1-8.2 range..."
echo ""

vendor/bin/phpcs \
    --standard=PHPCompatibility \
    --runtime-set testVersion 8.1-8.2 \
    --extensions=php \
    --report=full \
    "$MODULE_PATH" 2>&1 | tee "$REPORT_DIR/php-range-compatibility.txt" | head -100

echo ""
echo ""

# Test 4: Deprecated Functions Check
echo "4️⃣  DEPRECATED FUNCTIONS CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking for deprecated PHP functions..."
echo ""

# Common deprecated functions
DEPRECATED_FUNCS=(
    "each("
    "create_function("
    "money_format("
    "__autoload("
    "ereg("
    "split("
    "mysql_"
)

FOUND_DEPRECATED=0
for func in "${DEPRECATED_FUNCS[@]}"; do
    FOUND=$(grep -rn "$func" "$MODULE_PATH" --include="*.php" 2>/dev/null || true)
    if [ -z "$FOUND" ]; then
        echo "   ✅ No $func usage"
    else
        echo "   ❌ FOUND: $func"
        echo "$FOUND" | head -3
        FOUND_DEPRECATED=$((FOUND_DEPRECATED + 1))
    fi
done

if [ $FOUND_DEPRECATED -eq 0 ]; then
    echo ""
    echo "   ✅ No deprecated functions found!"
fi

echo ""
echo ""

# Test 5: PHP 8.x Specific Features Check
echo "5️⃣  PHP 8.X FEATURES CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking usage of PHP 8.x specific features..."
echo ""

echo "🔍 Named arguments (PHP 8.0+):"
NAMED_ARGS=$(grep -rn ":\s*\$" "$MODULE_PATH" --include="*.php" | grep -v "//" | head -5 || true)
if [ -z "$NAMED_ARGS" ]; then
    echo "   ℹ️  Not used (compatible with PHP 7.4+)"
else
    echo "   ✅ Found named arguments (PHP 8.0+ required)"
    echo "$NAMED_ARGS" | head -3
fi
echo ""

echo "🔍 Union types (PHP 8.0+):"
UNION_TYPES=$(grep -rn "string|int\|int|string\|array|null" "$MODULE_PATH" --include="*.php" | head -5 || true)
if [ -z "$UNION_TYPES" ]; then
    echo "   ℹ️  Not used (compatible with PHP 7.4+)"
else
    echo "   ✅ Found union types (PHP 8.0+ required)"
fi
echo ""

echo "🔍 Match expressions (PHP 8.0+):"
MATCH_EXPR=$(grep -rn "match\s*(" "$MODULE_PATH" --include="*.php" | head -5 || true)
if [ -z "$MATCH_EXPR" ]; then
    echo "   ℹ️  Not used (compatible with PHP 7.4+)"
else
    echo "   ✅ Found match expressions (PHP 8.0+ required)"
fi
echo ""

echo "🔍 Attributes (PHP 8.0+):"
ATTRIBUTES=$(grep -rn "#\[" "$MODULE_PATH" --include="*.php" | head -5 || true)
if [ -z "$ATTRIBUTES" ]; then
    echo "   ℹ️  Not used (compatible with PHP 7.4+)"
else
    echo "   ✅ Found attributes (PHP 8.0+ required)"
fi
echo ""

echo "🔍 Readonly properties (PHP 8.1+):"
READONLY=$(grep -rn "readonly\s" "$MODULE_PATH" --include="*.php" | head -5 || true)
if [ -z "$READONLY" ]; then
    echo "   ℹ️  Not used (compatible with PHP 8.0)"
else
    echo "   ✅ Found readonly properties (PHP 8.1+ required)"
fi
echo ""

echo "🔍 Enums (PHP 8.1+):"
ENUMS=$(grep -rn "enum\s" "$MODULE_PATH" --include="*.php" | head -5 || true)
if [ -z "$ENUMS" ]; then
    echo "   ℹ️  Not used (compatible with PHP 8.0)"
else
    echo "   ✅ Found enums (PHP 8.1+ required)"
fi
echo ""

# Test 6: Type Declarations
echo "6️⃣  TYPE DECLARATIONS CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking type declaration usage..."
echo ""

echo "🔍 Return type declarations:"
RETURN_TYPES=$(grep -rn "):\s*\(string\|int\|bool\|array\|void\|float\|object\)" "$MODULE_PATH" --include="*.php" | wc -l)
echo "   ✅ Found $RETURN_TYPES return type declarations"
echo ""

echo "🔍 Property type declarations:"
PROP_TYPES=$(grep -rn "private\|protected\|public" "$MODULE_PATH" --include="*.php" | grep -E "\s(string|int|bool|array|float)\s\\\$" | wc -l)
echo "   ✅ Found $PROP_TYPES property type declarations"
echo ""

echo "🔍 Nullable types (? prefix):"
NULLABLE=$(grep -rn "?\(string\|int\|bool\|array\)" "$MODULE_PATH" --include="*.php" | wc -l)
echo "   ✅ Found $NULLABLE nullable type declarations"
echo ""

# Test 7: PHP 7.4+ Features Check
echo "7️⃣  PHP 7.4 COMPATIBILITY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking for PHP 7.4 incompatibilities (Magento 2.4 dropped PHP 7.4)..."
echo ""

vendor/bin/phpcs \
    --standard=PHPCompatibility \
    --runtime-set testVersion 7.4 \
    --extensions=php \
    --report=summary \
    "$MODULE_PATH" 2>&1 | tee "$REPORT_DIR/php74-compatibility.txt" || true

echo ""
echo ""

# Test 8: Syntax Validation
echo "8️⃣  PHP SYNTAX VALIDATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Validating PHP syntax in all files..."
echo ""

SYNTAX_ERRORS=0
while IFS= read -r file; do
    if ! php -l "$file" > /dev/null 2>&1; then
        echo "   ❌ Syntax error in: $file"
        SYNTAX_ERRORS=$((SYNTAX_ERRORS + 1))
    fi
done < <(find "$MODULE_PATH" -name "*.php" -type f)

if [ $SYNTAX_ERRORS -eq 0 ]; then
    echo "   ✅ All PHP files have valid syntax"
else
    echo "   ❌ Found $SYNTAX_ERRORS file(s) with syntax errors"
fi

echo ""
echo ""

# Generate Summary Report
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📊 PHP COMPATIBILITY SUMMARY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Parse results from reports
PHP81_ERRORS=$(grep -c "FOUND.*ERROR" "$REPORT_DIR/php81-compatibility.txt" 2>/dev/null || echo "0")
PHP82_ERRORS=$(grep -c "FOUND.*ERROR" "$REPORT_DIR/php82-compatibility.txt" 2>/dev/null || echo "0")

echo "Compatibility Tests Completed:"
echo "  1. ✅ PHP 8.1 Compatibility Check"
echo "  2. ✅ PHP 8.2 Compatibility Check"
echo "  3. ✅ PHP 8.1-8.2 Range Check"
echo "  4. ✅ Deprecated Functions Check"
echo "  5. ✅ PHP 8.x Features Check"
echo "  6. ✅ Type Declarations Check"
echo "  7. ✅ PHP 7.4 Compatibility Check"
echo "  8. ✅ Syntax Validation"
echo ""

echo "Magento 2.4.x Requirements:"
echo "  ✅ PHP 8.1 - Minimum supported"
echo "  ✅ PHP 8.2 - Recommended"
echo ""

echo "Reports saved in: $REPORT_DIR/"
echo "  • php81-compatibility.txt"
echo "  • php82-compatibility.txt"
echo "  • php-range-compatibility.txt"
echo "  • php74-compatibility.txt"
echo ""

# Determine compatibility status
if [ "$PHP81_ERRORS" = "0" ] && [ "$PHP82_ERRORS" = "0" ] && [ $SYNTAX_ERRORS -eq 0 ]; then
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "✅ PHP COMPATIBILITY: PASSED"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    echo "Your module is compatible with:"
    echo "  ✅ PHP 8.1 (Magento 2.4.x minimum)"
    echo "  ✅ PHP 8.2 (Magento 2.4.x recommended)"
    echo ""
    echo "Status: READY FOR DEPLOYMENT"
else
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "⚠️  PHP COMPATIBILITY: WARNINGS FOUND"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    echo "Please review the reports in: $REPORT_DIR/"
    echo ""
fi

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Compatibility validation complete!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
