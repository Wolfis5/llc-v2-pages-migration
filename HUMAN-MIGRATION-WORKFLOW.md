# Human Migration Workflow - Start a Business Pages

This document describes the **human/manual steps** involved in migrating pages from Payload CMS to Statamic CMS using the AI-assisted migration workflow. This process was developed during the migration of the "Start a Business" collection pages.

## Overview

The migration process combines **automated AI tools** with **human oversight and decision-making**. While the AI agent handles content extraction and YAML generation, human intervention is required for quality assurance, component mapping decisions, and handling edge cases.

---

## Migration Workflow Steps

### Step 1: Content Extraction & Analysis

**Human Action:** Provide the production URL to the AI migration agent.

```
Example: https://bizee.com/start-a-business/women-business
```

**What the AI does:**
- Downloads the HTML content from production
- Analyzes the page structure
- Identifies all H2 headings and content sections
- Extracts SEO metadata (title, description)

**Important Note:** Some pages have issues with content extraction due to HTML reduction in the retrieval process. When this happens:
- The AI may not capture all content correctly
- **Human intervention required:** Pass the content explicitly to the AI agent by copying the visible content from the browser

**When to pass content explicitly:**
- When the AI reports missing sections
- When the extracted content doesn't match what you see in the browser
- When React-rendered content isn't captured properly (the production site uses Next.js)

---

### Step 2: Component Mapping Review

**What the AI does:**
- Generates a table with proposed components for each content section
- Maps production content to Statamic component equivalents

**Human Action:** Review and adjust the component mapping table.

| Production Section | AI Proposed Component | Human Decision |
|-------------------|----------------------|----------------|
| Hero Section | `cta_section_19` | Approve |
| Trust Badges | `trust_badges_1` | Approve |
| Content with icons | `text_with_icon` | Change to `info_28` |
| FAQ Section | `faq_1` | Change to `faq_2` |

**Key considerations:**
- The production pages use similar but not identical components to Statamic
- Match each section to the **closest available Statamic component**
- Some components have multiple versions (e.g., `info_4`, `info_28`, `info_29`)
- Refer to `README-COMPONENTS.md` for available component options

**After review:** Send the final approved table back to the AI agent.

---

### Step 3: YAML File Generation

**What the AI does:**
- Generates the complete YAML frontmatter file
- Creates the markdown file in `/content/collections/start_a_business_pages/[slug].md`
- Includes all content, SEO fields, and component structure

**Human Action:** Review the generated file for inconsistencies.

**What to check:**
- Content accuracy (compare with production page)
- Component structure (correct nesting, required fields)
- SEO fields (title, description match production)
- UUID is unique (not copied from another page)
- Quote formatting in YAML strings

---

### Step 4: Iterative Fixes & Quality Assurance

**Human Action:** Identify issues and provide feedback to the AI agent.

**Common issues to look for:**

| Issue Type | Description | Fix Method |
|-----------|-------------|------------|
| **Broken Links** | Links not matching production URLs | Pass correct URLs to AI agent |
| **Missing Images** | Images not uploaded to S3 | Upload via CMS Tool manually |
| **Wrong Image Paths** | Local paths instead of S3 URLs | Update with correct S3 asset paths |
| **Content Inconsistencies** | Text doesn't match production | Provide correct text to AI |
| **Wrong Component Version** | Using `info_4` instead of `info_28` | Specify correct version |
| **Missing Sections** | Content sections not migrated | Provide missing content explicitly |

**Image Upload Process (Manual):**
1. Download images from production page
2. Rename following convention: `[slug]-[description].webp`
3. Upload to S3 using the CMS Tool (Statamic Control Panel)
4. Copy the S3 asset path
5. Provide path to AI agent for YAML update

**Iteration cycle:**
```
Review Page → Identify Issues → Report to AI → AI Fixes → Review Again
```

**Note:** Some pages required **3 or more iterations** or manual YAML edits to achieve the correct final content.

---

### Step 5: Final Verification & Deployment

**Human Action:** Perform final checks before considering the migration complete.

**Checklist:**
- [ ] All content matches production page
- [ ] All images are uploaded to S3 and paths are correct
- [ ] All links are verified and working
- [ ] SEO fields are correctly set
- [ ] Component structure is valid YAML
- [ ] Quote script has been executed (`replace_quotes_final.py`)
- [ ] Page route is added to `released-pages.php`

**Verify route registration:**
```php
// Check app/Routing/migration/released-pages.php
return [
    // ... existing routes
    '/start-a-business/[your-slug]',  // Must be added
];
```

**Final commands to run:**
```bash
# Run quote replacement script
python3 llc-v2-pages-migration/replace_quotes_final.py content/collections/start_a_business_pages/[slug].md

# Run verification script
php llc-v2-pages-migration/verify-page-migration.php \
  content/collections/start_a_business_pages/[slug].md \
  https://bizee.com/start-a-business/[slug]
```

---

## Common Edge Cases & Solutions

### Edge Case 1: React-Rendered Content Not Captured

**Problem:** Production pages use Next.js/React, so static HTML extraction misses dynamic content.

**Solution:**
- Open the page in a browser
- Copy the visible text content
- Pass it explicitly to the AI agent

### Edge Case 2: Complex Nested Components

**Problem:** Some sections have deeply nested structures (tabs within cards, etc.)

**Solution:**
- Break down the structure manually
- Provide clear hierarchy to AI agent
- May require manual YAML editing

### Edge Case 3: Videos Not Migrating

**Problem:** Wistia video embeds need special handling

**Solution:**
- Extract Wistia video ID from production
- Use `video` component with correct version
- Provide video ID explicitly to AI

### Edge Case 4: FAQ Formatting Issues

**Problem:** FAQ items losing formatting or structure

**Solution:**
- Use `faq_2` for traditional Q&A format
- Use `faq_1` for step-by-step lists
- Verify Bard content structure

---

## Tools Reference

| Tool | Purpose | When to Use |
|------|---------|-------------|
| `migrate-page.php` | Initial page structure creation | Start of migration |
| `component-helpers.php` | Generate component structures | During YAML creation |
| `verify-page-migration.php` | Automated verification | After migration |
| `replace_quotes_final.py` | Quote normalization | Final step |
| **CMS Tool (Manual)** | Image upload to S3 | When images need updating |
| **Browser (Manual)** | Content verification | Throughout process |

---

## Time Expectations

Based on migration experience:

| Page Complexity | Iterations Needed | Human Effort |
|----------------|-------------------|--------------|
| Simple (few sections) | 1-2 iterations | Low |
| Medium (multiple sections, some images) | 2-3 iterations | Medium |
| Complex (many images, nested content, videos) | 3+ iterations | High |

---

## Summary

The migration process is a **collaborative workflow** between the AI agent and human operator:

1. **AI handles:** Content extraction, YAML generation, automated fixes
2. **Human handles:** Quality assurance, component mapping decisions, image uploads, edge cases

The key to successful migration is **iterative review and feedback** - don't expect perfect results on the first pass. Plan for multiple review cycles, especially for complex pages.

---

**Related Documentation:**
- [`QUICK-START.md`](./QUICK-START.md) - Fast reference
- [`CRITICAL-CHECKLIST.md`](./CRITICAL-CHECKLIST.md) - Must-check items
- [`CURSOR-AGENT-INSTRUCTIONS.md`](./CURSOR-AGENT-INSTRUCTIONS.md) - AI agent setup
- [`README-COMPONENTS.md`](./README-COMPONENTS.md) - Component reference
- [`MIGRATION-ERRORS-LEARNED.md`](./MIGRATION-ERRORS-LEARNED.md) - Common mistakes

---

**Last Updated:** January 2026
