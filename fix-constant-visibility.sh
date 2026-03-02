#!/bin/bash

# Fix Constant Visibility Issues
# Adds 'public' keyword to all constants without visibility declaration

set -e

MODULE_PATH="/home/bs01233/Documents/Megento/project-community-edition/app/code/Vendor/ProductQnA"

echo "========================================"
echo "Fixing Constant Visibility Issues"
echo "========================================"
echo ""

# Backup files first
echo "📦 Creating backups..."
cp "$MODULE_PATH/Api/Data/QuestionInterface.php" "$MODULE_PATH/Api/Data/QuestionInterface.php.bak"
cp "$MODULE_PATH/Api/Data/AnswerInterface.php" "$MODULE_PATH/Api/Data/AnswerInterface.php.bak"

# Find all controller files that need fixing
CONTROLLER_FILES=$(find "$MODULE_PATH/Controller/Adminhtml" -name "*.php" -type f)

for file in $CONTROLLER_FILES; do
    cp "$file" "$file.bak"
done

echo "✅ Backups created (.bak files)"
echo ""

# Fix API interfaces
echo "🔧 Fixing Api/Data/QuestionInterface.php..."
sed -i 's/^    const /    public const /g' "$MODULE_PATH/Api/Data/QuestionInterface.php"

echo "🔧 Fixing Api/Data/AnswerInterface.php..."
sed -i 's/^    const /    public const /g' "$MODULE_PATH/Api/Data/AnswerInterface.php"

# Fix controller files
echo "🔧 Fixing Controller files..."
for file in $CONTROLLER_FILES; do
    sed -i 's/^    const ADMIN_RESOURCE/    public const ADMIN_RESOURCE/g' "$file"
    echo "   ✓ $(basename $file)"
done

echo ""
echo "========================================"
echo "✅ All constant visibility issues fixed!"
echo "========================================"
echo ""
echo "Files modified:"
echo "  • Api/Data/QuestionInterface.php (18 constants)"
echo "  • Api/Data/AnswerInterface.php (9 constants)"
echo "  • Controller/Adminhtml/*.php (10 ADMIN_RESOURCE constants)"
echo ""
echo "Backups saved with .bak extension"
echo ""
echo "Next step: Run verification"
echo "  vendor/bin/phpcs --standard=PSR12 --extensions=php app/code/Vendor/ProductQnA/"
echo ""
