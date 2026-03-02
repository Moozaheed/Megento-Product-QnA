# Quick Start: Code Quality Testing

## 🚀 Fastest Way to Check Your Code

### Option 1: Run the Automated Script

```bash
cd app/code/Vendor/ProductQnA
./check-code-quality.sh
```

This will:
- ✅ Check if tools are installed
- ✅ Run PHPCS on all PHP files
- ✅ Generate detailed report
- ✅ Create HTML report (open in browser)
- ✅ Show auto-fix command

### Option 2: Manual Commands

```bash
# From Magento root

# Check the module
vendor/bin/phpcs --standard=PSR12 --extensions=php app/code/Vendor/ProductQnA/

# Auto-fix issues
vendor/bin/phpcbf --standard=PSR12 --extensions=php app/code/Vendor/ProductQnA/

# Generate HTML report
vendor/bin/phpcs --standard=PSR12 --extensions=php --report=html --report-file=phpcs-report.html app/code/Vendor/ProductQnA/
```

## 📊 What Gets Checked

### Critical Issues (Must Fix)
- ❌ Missing type declarations
- ❌ Incorrect indentation
- ❌ Long lines (over 120 characters)
- ❌ Missing DocBlocks
- ❌ Incorrect brace placement

### Style Issues (Should Fix)
- ⚠️ Spacing around operators
- ⚠️ Import order
- ⚠️ Method/variable naming
- ⚠️ File structure

## 🔧 Common Fixes

### 1. Add Type Declarations
```php
// ❌ Before
public function save($data) {

// ✅ After  
public function save(array $data): bool {
```

### 2. Add DocBlocks
```php
// ❌ Before
public function getQuestions() {

// ✅ After
/**
 * Get questions for product
 *
 * @return \Vendor\ProductQnA\Model\ResourceModel\Question\Collection
 */
public function getQuestions() {
```

### 3. Fix Line Length
```php
// ❌ Before (too long)
$result = $this->questionRepository->getList($searchCriteria, $sortOrder, $filterGroup, $pageSize);

// ✅ After
$result = $this->questionRepository->getList(
    $searchCriteria,
    $sortOrder,
    $filterGroup,
    $pageSize
);
```

## 📖 Full Documentation

For complete guide, see: [CODE_QUALITY_GUIDE.md](CODE_QUALITY_GUIDE.md)

## ⏱️ Quick Reference

| Command | Purpose |
|---------|---------|
| `./check-code-quality.sh` | Run all checks |
| `vendor/bin/phpcs` | Check code |
| `vendor/bin/phpcbf` | Auto-fix code |
| `vendor/bin/phpcs -i` | List standards |

## 🎯 Before Release Checklist

- [ ] Run `./check-code-quality.sh`
- [ ] Fix all ERRORS (must be 0)
- [ ] Review WARNINGS
- [ ] Auto-fix with `phpcbf`
- [ ] Check HTML report
- [ ] Manual review remaining issues
- [ ] Test the module

## 💡 Tips

1. **Run frequently** - Don't wait until end
2. **Use auto-fix** - Saves time: `phpcbf`
3. **Check HTML** - Easier to review
4. **Focus on errors** first, then warnings
5. **One file at a time** - Easier to manage

---

**Need Help?** See full guide: [CODE_QUALITY_GUIDE.md](CODE_QUALITY_GUIDE.md)
