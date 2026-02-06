# Branching Strategy

This repository follows a structured branching strategy for better version management and development workflow.

## Branch Structure

### Main Branches

- **`main`** - Production-ready code, always stable
  - Contains the latest stable release
  - Protected branch (no direct commits)
  - Merged from release branches after testing

- **`develop`** - Integration branch for ongoing development
  - Contains the latest development changes
  - Base branch for new features
  - Merged into release branches for new versions

### Release Branches

Release branches maintain stable versions and allow for version-specific bug fixes.

- **`release/1.0.0`** - First stable release
  - Basic Q&A functionality
  - Admin management panel
  - Email notifications

- **`release/2.0.0`** - AI-powered release (current)
  - AI Assistant integration
  - Customer choice system
  - Rule-based and AI model modes
  - All v1.0.0 features plus AI capabilities

### Future Branches

- **`release/X.Y.Z`** - Future version releases
- **`feature/*`** - Feature development branches
- **`hotfix/*`** - Emergency fixes for production issues
- **`bugfix/*`** - Bug fixes for develop branch

## Version Tags

Each release has a corresponding Git tag:

- `v1.0.0` - Points to release/1.0.0
- `v2.0.0` - Points to release/2.0.0
- `vX.Y.Z` - Future releases

## Installation by Version

### Install Latest Stable (main)
```bash
git clone https://github.com/Moozaheed/Megento-Product-QnA.git app/code/Vendor/ProductQnA
```

### Install Specific Version

**v2.0.0 (AI-powered)**
```bash
git clone -b release/2.0.0 https://github.com/Moozaheed/Megento-Product-QnA.git app/code/Vendor/ProductQnA
```

**v1.0.0 (Basic Q&A)**
```bash
git clone -b release/1.0.0 https://github.com/Moozaheed/Megento-Product-QnA.git app/code/Vendor/ProductQnA
```

**Using Git Tag**
```bash
git clone https://github.com/Moozaheed/Megento-Product-QnA.git app/code/Vendor/ProductQnA
cd app/code/Vendor/ProductQnA
git checkout v2.0.0
```

**Development Version (develop)**
```bash
git clone -b develop https://github.com/Moozaheed/Megento-Product-QnA.git app/code/Vendor/ProductQnA
# Warning: May contain unstable features
```

## Workflow

### For Users

1. **Production Use**: Clone from `main` or specific `release/X.Y.Z` branch
2. **Testing**: Use latest `release/X.Y.Z` branch
3. **Bleeding Edge**: Use `develop` (not recommended for production)

### For Contributors

1. Fork the repository
2. Create feature branch from `develop`:
   ```bash
   git checkout develop
   git checkout -b feature/my-new-feature
   ```
3. Make changes and commit
4. Push and create Pull Request to `develop`
5. After review, merge to `develop`

### For Maintainers

#### Creating a New Release

1. **Start Release Branch**:
   ```bash
   git checkout develop
   git checkout -b release/2.1.0
   ```

2. **Update Version Numbers**:
   - `composer.json` → version: "2.1.0"
   - `etc/module.xml` → setup_version: "2.1.0"
   - Update `CHANGELOG.md`
   - Update `README.md` badges

3. **Test Release**:
   - Run all tests
   - Test installation on clean Magento
   - Test upgrade from previous version

4. **Finalize Release**:
   ```bash
   git add -A
   git commit -m "Release v2.1.0"
   
   # Merge to main
   git checkout main
   git merge release/2.1.0
   
   # Create tag
   git tag -a v2.1.0 -m "Version 2.1.0"
   
   # Push
   git push origin main
   git push origin release/2.1.0
   git push origin v2.1.0
   
   # Merge back to develop
   git checkout develop
   git merge release/2.1.0
   git push origin develop
   ```

#### Hotfix Process

For urgent production fixes:

```bash
# Create hotfix from main
git checkout main
git checkout -b hotfix/2.0.1

# Make fixes and update version to 2.0.1
# Update CHANGELOG.md

# Commit
git commit -m "Hotfix v2.0.1: Critical bug fix"

# Merge to main
git checkout main
git merge hotfix/2.0.1
git tag -a v2.0.1 -m "Hotfix 2.0.1"

# Update release branch
git checkout release/2.0.0
git merge hotfix/2.0.1
git checkout -b release/2.0.1
git push origin release/2.0.1

# Merge to develop
git checkout develop
git merge hotfix/2.0.1

# Push all
git push origin main
git push origin develop
git push origin v2.0.1

# Delete hotfix branch
git branch -d hotfix/2.0.1
```

## Branch Protection Rules

### Recommended Settings

**`main` branch**:
- Require pull request reviews before merging
- Require status checks to pass
- Require branches to be up to date before merging
- Include administrators in restrictions

**`develop` branch**:
- Require pull request reviews before merging
- Require status checks to pass

**`release/*` branches**:
- Require pull request reviews before merging
- Lock branch after release (read-only)

## Quick Reference

| Branch | Purpose | Base | Merge To |
|--------|---------|------|----------|
| main | Production code | - | - |
| develop | Active development | main | release/* |
| release/X.Y.Z | Version releases | develop | main, develop |
| feature/* | New features | develop | develop |
| bugfix/* | Bug fixes | develop | develop |
| hotfix/* | Urgent fixes | main | main, develop, release/* |

## Version Comparison

```bash
# Compare v1.0.0 with v2.0.0
git diff v1.0.0..v2.0.0

# Compare release branches
git diff release/1.0.0..release/2.0.0

# List all tags
git tag -l

# Show tag details
git show v2.0.0
```

## FAQs

**Q: Which branch should I clone for production?**  
A: Clone `main` or specific `release/X.Y.Z` branch for production use.

**Q: How do I update to the latest version?**  
A: `git fetch origin && git checkout main && git pull`

**Q: Can I contribute to a specific version?**  
A: Yes, create a branch from the appropriate `release/X.Y.Z` branch and submit a PR.

**Q: What's the difference between tags and release branches?**  
A: Tags are immutable pointers to specific commits. Release branches allow for version-specific maintenance and bug fixes.

**Q: Which version has AI features?**  
A: AI features are available in v2.0.0 and later (`release/2.0.0` branch or `v2.0.0` tag).

---

For more information, see [README.md](README.md) and [CHANGELOG.md](CHANGELOG.md).
