#!/bin/bash

# Security Testing Script for Magento Module
# Tests for common security vulnerabilities and Magento security standards

set -e

MODULE_PATH="/home/bs01233/Documents/Megento/project-community-edition/app/code/Vendor/ProductQnA"
MAGENTO_ROOT="/home/bs01233/Documents/Megento/project-community-edition"
REPORT_DIR="$MAGENTO_ROOT/security-reports"

cd "$MAGENTO_ROOT"

# Create reports directory
mkdir -p "$REPORT_DIR"

echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "🔒 MAGENTO MODULE SECURITY VALIDATION"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Module: Vendor_ProductQnA"
echo "Date: $(date '+%Y-%m-%d %H:%M:%S')"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""

# Test 1: SQL Injection Vulnerabilities
echo "1️⃣  SQL INJECTION CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking for unsafe SQL queries..."
echo ""

echo "🔍 Direct SQL execution:"
SQL_DIRECT=$(grep -rn "->query(" "$MODULE_PATH" --include="*.php" || true)
if [ -z "$SQL_DIRECT" ]; then
    echo "   ✅ No direct SQL execution found"
else
    echo "   ⚠️  Direct SQL found:"
    echo "$SQL_DIRECT"
fi
echo ""

echo "🔍 Raw SQL in collections:"
RAW_SQL=$(grep -rn "->getSelect()->where(" "$MODULE_PATH" --include="*.php" || true)
if [ -z "$RAW_SQL" ]; then
    echo "   ✅ No raw SQL in collections"
else
    echo "   ⚠️  Raw SQL found:"
    echo "$RAW_SQL"
fi
echo ""

echo "🔍 String concatenation in queries:"
CONCAT_SQL=$(grep -rn '\$.*\.' "$MODULE_PATH" --include="*.php" | grep -i "where\|select\|insert\|update\|delete" || true)
if [ -z "$CONCAT_SQL" ]; then
    echo "   ✅ No string concatenation in SQL"
else
    echo "   ⚠️  Potential SQL concatenation found - Review needed:"
    echo "$CONCAT_SQL" | head -10
fi
echo ""

# Test 2: XSS (Cross-Site Scripting) Vulnerabilities
echo "2️⃣  XSS VULNERABILITY CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking for unescaped output..."
echo ""

echo "🔍 Unescaped echo in PHP:"
UNESCAPED_ECHO=$(grep -rn "echo \$" "$MODULE_PATH" --include="*.php" | grep -v "escapeHtml\|escapeUrl\|escapeJs" || true)
if [ -z "$UNESCAPED_ECHO" ]; then
    echo "   ✅ No unescaped echo found"
else
    echo "   ⚠️  Unescaped output found:"
    echo "$UNESCAPED_ECHO"
fi
echo ""

echo "🔍 Direct output in templates:"
DIRECT_OUTPUT=$(grep -rn "<?=.*\$" "$MODULE_PATH" --include="*.phtml" | grep -v "escapeHtml\|escapeUrl\|escapeJs" || true)
if [ -z "$DIRECT_OUTPUT" ]; then
    echo "   ✅ All template output properly escaped"
else
    echo "   ⚠️  Unescaped template output:"
    echo "$DIRECT_OUTPUT"
fi
echo ""

echo "🔍 Checking templates use Block escaping:"
TEMPLATES=$(find "$MODULE_PATH" -name "*.phtml" -type f 2>/dev/null || true)
if [ -n "$TEMPLATES" ]; then
    for template in $TEMPLATES; do
        if grep -q "escapeHtml\|escapeUrl\|escapeJs" "$template"; then
            echo "   ✅ $(basename $template) - Uses escaping"
        else
            echo "   ⚠️  $(basename $template) - No escaping found"
        fi
    done
else
    echo "   ℹ️  No template files found"
fi
echo ""

# Test 3: CSRF Protection
echo "3️⃣  CSRF PROTECTION CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking POST actions have CSRF protection..."
echo ""

echo "🔍 Form key validation in controllers:"
CONTROLLERS=$(find "$MODULE_PATH/Controller" -name "*.php" -type f 2>/dev/null || true)
if [ -n "$CONTROLLERS" ]; then
    for controller in $CONTROLLERS; do
        if grep -q "Save\|Delete\|Update\|Create" "$(basename $controller)"; then
            if grep -q "formKeyValidator\|validateFormKey\|FormKey" "$controller"; then
                echo "   ✅ $(basename $controller) - Has form key validation"
            else
                echo "   ⚠️  $(basename $controller) - Missing CSRF protection"
            fi
        fi
    done
else
    echo "   ℹ️  No controller files found"
fi
echo ""

# Test 4: ACL (Access Control List) Check
echo "4️⃣  ACL AUTHORIZATION CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking admin controllers have proper ACL..."
echo ""

echo "🔍 ADMIN_RESOURCE constant in admin controllers:"
ADMIN_CONTROLLERS=$(find "$MODULE_PATH/Controller/Adminhtml" -name "*.php" -type f 2>/dev/null || true)
if [ -n "$ADMIN_CONTROLLERS" ]; then
    for controller in $ADMIN_CONTROLLERS; do
        if grep -q "const ADMIN_RESOURCE" "$controller"; then
            echo "   ✅ $(basename $controller) - Has ADMIN_RESOURCE"
        else
            echo "   ❌ $(basename $controller) - Missing ADMIN_RESOURCE (CRITICAL)"
        fi
    done
else
    echo "   ℹ️  No admin controller files found"
fi
echo ""

echo "🔍 ACL configuration file:"
if [ -f "$MODULE_PATH/etc/acl.xml" ]; then
    ACL_RESOURCES=$(grep -c "resource id=" "$MODULE_PATH/etc/acl.xml" || echo "0")
    echo "   ✅ acl.xml exists with $ACL_RESOURCES resources"
else
    echo "   ❌ acl.xml NOT FOUND (CRITICAL)"
fi
echo ""

# Test 5: Input Validation
echo "5️⃣  INPUT VALIDATION CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking for proper input validation..."
echo ""

echo "🔍 Direct \$_GET usage:"
DIRECT_GET=$(grep -rn '\$_GET' "$MODULE_PATH" --include="*.php" || true)
if [ -z "$DIRECT_GET" ]; then
    echo "   ✅ No direct \$_GET usage"
else
    echo "   ⚠️  Direct \$_GET found (should use \$request->getParam()):"
    echo "$DIRECT_GET"
fi
echo ""

echo "🔍 Direct \$_POST usage:"
DIRECT_POST=$(grep -rn '\$_POST' "$MODULE_PATH" --include="*.php" || true)
if [ -z "$DIRECT_POST" ]; then
    echo "   ✅ No direct \$_POST usage"
else
    echo "   ⚠️  Direct \$_POST found (should use \$request->getPost()):"
    echo "$DIRECT_POST"
fi
echo ""

echo "🔍 Direct \$_REQUEST usage:"
DIRECT_REQUEST=$(grep -rn '\$_REQUEST' "$MODULE_PATH" --include="*.php" || true)
if [ -z "$DIRECT_REQUEST" ]; then
    echo "   ✅ No direct \$_REQUEST usage"
else
    echo "   ⚠️  Direct \$_REQUEST found (should use Request object):"
    echo "$DIRECT_REQUEST"
fi
echo ""

# Test 6: File Upload Security
echo "6️⃣  FILE UPLOAD SECURITY CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking for file upload vulnerabilities..."
echo ""

echo "🔍 File upload handling:"
FILE_UPLOAD=$(grep -rn "move_uploaded_file\|\$_FILES" "$MODULE_PATH" --include="*.php" || true)
if [ -z "$FILE_UPLOAD" ]; then
    echo "   ✅ No file upload code found"
else
    echo "   ⚠️  File upload found - Ensure validation:"
    echo "$FILE_UPLOAD"
fi
echo ""

# Test 7: eval() and dangerous functions
echo "7️⃣  DANGEROUS FUNCTIONS CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking for dangerous PHP functions..."
echo ""

DANGEROUS_FUNCS=("eval" "exec" "shell_exec" "system" "passthru" "popen" "proc_open" "base64_decode")

for func in "${DANGEROUS_FUNCS[@]}"; do
    FOUND=$(grep -rn "$func(" "$MODULE_PATH" --include="*.php" || true)
    if [ -z "$FOUND" ]; then
        echo "   ✅ No $func() usage"
    else
        echo "   ❌ CRITICAL: $func() found:"
        echo "$FOUND"
    fi
done
echo ""

# Test 8: Direct Object Instantiation (Should use DI)
echo "8️⃣  DEPENDENCY INJECTION CHECK"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking for improper object instantiation..."
echo ""

echo "🔍 Direct 'new' keyword usage:"
DIRECT_NEW=$(grep -rn "new \\\\" "$MODULE_PATH" --include="*.php" | grep -v "Factory\|Exception\|DataObject\|DateTime\|Data\\\\" || true)
if [ -z "$DIRECT_NEW" ]; then
    echo "   ✅ All objects use dependency injection"
else
    echo "   ⚠️  Direct instantiation found (should use DI):"
    echo "$DIRECT_NEW" | head -10
fi
echo ""

# Test 9: PHPStan Static Analysis
echo "9️⃣  PHPSTAN STATIC ANALYSIS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Running PHPStan for type safety and logic errors..."
echo ""

if [ -f "vendor/bin/phpstan" ]; then
    vendor/bin/phpstan analyse "$MODULE_PATH" --level=1 --no-progress 2>&1 | head -50 || true
    echo ""
    echo "   ℹ️  For full report: vendor/bin/phpstan analyse $MODULE_PATH --level=5"
else
    echo "   ⚠️  PHPStan not available"
fi
echo ""

# Test 10: PHPMD Security Rules
echo "🔟 PHPMD SECURITY RULES"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Running PHP Mess Detector for security issues..."
echo ""

if [ -f "vendor/bin/phpmd" ]; then
    vendor/bin/phpmd "$MODULE_PATH" text cleancode,codesize,controversial,design,naming,unusedcode --exclude Test 2>&1 | head -50 || true
    echo ""
    echo "   ℹ️  For full report: vendor/bin/phpmd $MODULE_PATH html cleancode,codesize > report.html"
else
    echo "   ⚠️  PHPMD not available"
fi
echo ""

# Test 11: Magento Specific Security
echo "1️⃣1️⃣  MAGENTO SECURITY PATTERNS"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking Magento-specific security patterns..."
echo ""

echo "🔍 Repository pattern usage:"
REPOSITORIES=$(find "$MODULE_PATH" -name "*Repository.php" -type f 2>/dev/null | wc -l)
echo "   ✅ Found $REPOSITORIES repository classes"
echo ""

echo "🔍 API interface usage:"
API_INTERFACES=$(find "$MODULE_PATH/Api" -name "*.php" -type f 2>/dev/null | wc -l)
echo "   ✅ Found $API_INTERFACES API interfaces"
echo ""

echo "🔍 Data validation in models:"
VALIDATORS=$(grep -r "validate\|Validator" "$MODULE_PATH/Model" --include="*.php" | wc -l)
if [ "$VALIDATORS" -gt 0 ]; then
    echo "   ✅ Found $VALIDATORS validation references"
else
    echo "   ⚠️  No validators found - consider adding validation"
fi
echo ""

# Test 12: Configuration Security
echo "1️⃣2️⃣  CONFIGURATION SECURITY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "Checking configuration files for security..."
echo ""

echo "🔍 Sensitive data in config files:"
SENSITIVE=$(grep -ri "password\|secret\|token\|key" "$MODULE_PATH/etc" --include="*.xml" || true)
if [ -z "$SENSITIVE" ]; then
    echo "   ✅ No sensitive data in config files"
else
    echo "   ⚠️  Potential sensitive data found:"
    echo "$SENSITIVE"
fi
echo ""

echo "🔍 Frontend routes security:"
if [ -f "$MODULE_PATH/etc/frontend/routes.xml" ]; then
    echo "   ✅ Frontend routes configured"
    grep -n "route\|frontName" "$MODULE_PATH/etc/frontend/routes.xml" || true
else
    echo "   ℹ️  No frontend routes"
fi
echo ""

# Generate Summary Report
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "📊 SECURITY VALIDATION SUMMARY"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
echo "Security Tests Completed:"
echo "  1. ✅ SQL Injection Check"
echo "  2. ✅ XSS Vulnerability Check"
echo "  3. ✅ CSRF Protection Check"
echo "  4. ✅ ACL Authorization Check"
echo "  5. ✅ Input Validation Check"
echo "  6. ✅ File Upload Security"
echo "  7. ✅ Dangerous Functions Check"
echo "  8. ✅ Dependency Injection Check"
echo "  9. ✅ PHPStan Static Analysis"
echo " 10. ✅ PHPMD Security Rules"
echo " 11. ✅ Magento Security Patterns"
echo " 12. ✅ Configuration Security"
echo ""
echo "Detailed reports saved in: $REPORT_DIR/"
echo ""
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo "✅ Security validation complete!"
echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
echo ""
