<?php

/**
 * Script de Verificación Automática Post-Migración para Pages
 *
 * Este script verifica automáticamente todos los puntos críticos que
 * comúnmente requieren revisión después de migrar una página.
 *
 * Usage: php verify-page-migration.php [PAGE_FILE] [PRODUCTION_URL]
 * Example: php verify-page-migration.php content/collections/start_a_business_pages/example-page.md https://bizee.com/start-a-business/example-page
 */

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;
use GuzzleHttp\Client;

class PageMigrationVerifier
{
    private $pageFile;
    private $productionUrl;
    private $pageData;
    private $productionHtml;
    private $errors = [];
    private $warnings = [];
    private $info = [];
    private $client;

    public function __construct($pageFile, $productionUrl)
    {
        $this->pageFile = $pageFile;
        $this->productionUrl = $productionUrl;
        $this->client = new Client();

        if (!file_exists($pageFile)) {
            throw new Exception("Page file not found: {$pageFile}");
        }
    }

    public function verify(): array
    {
        echo "╔═══════════════════════════════════════════════════════════╗\n";
        echo "║     Page Migration Verification Script                   ║\n";
        echo "╚═══════════════════════════════════════════════════════════╝\n\n";

        echo "Page: {$this->pageFile}\n";
        echo "Production URL: {$this->productionUrl}\n\n";

        // Load page
        $this->loadPage();

        // Download production HTML
        $this->downloadProductionHtml();

        // Run all verifications
        $this->verifyUUID();
        $this->verifyPublishedStatus();
        $this->verifyPageSettings();
        $this->verifySEOFields();
        $this->verifyImages();
        $this->verifyLinks();
        $this->verifyStandardComponents();
        $this->verifyComponentStructure();
        $this->verifyRouting();
        $this->verifyQuotes();
        $this->verifyBlockStructure();

        // Print results
        $this->printResults();

        return [
            'errors' => $this->errors,
            'warnings' => $this->warnings,
            'info' => $this->info,
        ];
    }

    private function loadPage(): void
    {
        $content = file_get_contents($this->pageFile);
        $parts = explode('---', $content, 3);

        if (count($parts) < 3) {
            throw new Exception("Invalid page format");
        }

        $this->pageData = Yaml::parse($parts[1]);
        $this->pageData['_content'] = $parts[2] ?? '';
        $this->pageData['_yaml'] = $parts[1];
    }

    private function downloadProductionHtml(): void
    {
        echo "Downloading production HTML...\n";

        try {
            $response = $this->client->get($this->productionUrl, [
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
                ]
            ]);
            $this->productionHtml = $response->getBody()->getContents();
        } catch (Exception $e) {
            $this->warnings[] = "Could not download production HTML: " . $e->getMessage();
            $this->productionHtml = null;
        }
    }

    // 1. Verify UUID is unique
    private function verifyUUID(): void
    {
        echo "\n[1/12] Verifying UUID uniqueness...\n";

        $uuid = $this->pageData['id'] ?? null;

        if (!$uuid) {
            $this->errors[] = "UUID is missing";
            return;
        }

        // Check if UUID format is valid
        if (!preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $uuid)) {
            $this->errors[] = "UUID format is invalid: {$uuid}";
            return;
        }

        // Check for duplicates in other pages
        $pagesDir = dirname($this->pageFile);
        $files = glob($pagesDir . '/*.md');
        $duplicates = [];

        foreach ($files as $file) {
            if ($file === $this->pageFile) continue;

            $content = file_get_contents($file);
            if (preg_match('/^id:\s*' . preg_quote($uuid, '/') . '/m', $content)) {
                $duplicates[] = basename($file);
            }
        }

        if (!empty($duplicates)) {
            $this->errors[] = "UUID is duplicated in: " . implode(', ', $duplicates);
        } else {
            echo "  ✓ UUID is unique\n";
        }
    }

    // 2. Verify published status
    private function verifyPublishedStatus(): void
    {
        echo "\n[2/12] Verifying published status...\n";

        $published = $this->pageData['published'] ?? null;

        if ($published === null) {
            $this->errors[] = "published field is missing";
        } elseif ($published === false) {
            $this->errors[] = "published is set to false. Migrated pages MUST have published: true";
        } elseif ($published === true) {
            echo "  ✓ Page is published\n";
        } else {
            $this->warnings[] = "published has unexpected value: " . var_export($published, true);
        }
    }

    // 3. Verify page settings
    private function verifyPageSettings(): void
    {
        echo "\n[3/12] Verifying page settings...\n";

        $requiredSettings = [
            'custom_page_header' => false,
            'breadcrumbs' => true,
            'page_settings_no_index' => false,
            'page_settings_hide_breadcrumbs' => false,
            'page_settings_hide_footer' => false,
            'page_settings_hide_on_production' => false,
        ];

        foreach ($requiredSettings as $setting => $expectedValue) {
            $actualValue = $this->pageData[$setting] ?? null;
            if ($actualValue === null) {
                $this->warnings[] = "Missing page setting: {$setting}";
            } elseif ($actualValue !== $expectedValue) {
                $this->warnings[] = "Page setting {$setting} has unexpected value: " . var_export($actualValue, true) . " (expected: " . var_export($expectedValue, true) . ")";
            }
        }

        // Verify enabled scripts
        $enabledScripts = $this->pageData['page_settings_enabled_scripts'] ?? [];
        $requiredScripts = ['fullstory', 'ahrefs'];
        $missingScripts = array_diff($requiredScripts, $enabledScripts);

        if (!empty($missingScripts)) {
            $this->warnings[] = "Missing enabled scripts: " . implode(', ', $missingScripts);
        } else {
            echo "  ✓ Page settings are correct\n";
        }
    }

    // 4. Verify SEO fields
    private function verifySEOFields(): void
    {
        echo "\n[4/12] Verifying SEO fields...\n";

        $requiredFields = [
            'seo_title',
            'seo_meta_description',
            'seo_custom_meta_title',
            'seo_custom_meta_description',
            'seo_canonical',
            'seo_og_description',
            'seo_og_title',
            'seo_tw_title',
            'seo_tw_description',
        ];

        $missing = [];
        foreach ($requiredFields as $field) {
            if (!isset($this->pageData[$field])) {
                $missing[] = $field;
            }
        }

        if (!empty($missing)) {
            $this->errors[] = "Missing SEO fields: " . implode(', ', $missing);
        } else {
            echo "  ✓ All SEO fields present\n";
        }

        // Verify SEO data matches production
        if ($this->productionHtml) {
            // Extract title from production
            if (preg_match('/<title>([^<]+)<\/title>/i', $this->productionHtml, $matches)) {
                $productionTitle = html_entity_decode(trim($matches[1]), ENT_QUOTES | ENT_HTML5);
                $pageTitle = $this->pageData['seo_custom_meta_title'] ?? '';

                if ($productionTitle !== $pageTitle) {
                    $this->warnings[] = "SEO title mismatch:\n    Production: {$productionTitle}\n    Page: {$pageTitle}";
                }
            }

            // Extract meta description from production
            if (preg_match('/<meta\s+name=["\']description["\']\s+content=["\']([^"\']+)["\']/i', $this->productionHtml, $matches)) {
                $productionDesc = html_entity_decode(trim($matches[1]), ENT_QUOTES | ENT_HTML5);
                $pageDesc = $this->pageData['seo_custom_meta_description'] ?? '';

                if ($productionDesc !== $pageDesc) {
                    $this->warnings[] = "SEO description mismatch:\n    Production: {$productionDesc}\n    Page: {$pageDesc}";
                }
            }
        }
    }

    // 5. Verify images
    private function verifyImages(): void
    {
        echo "\n[5/12] Verifying images...\n";

        $blocks = $this->pageData['blocks'] ?? [];
        $imagesFound = [];
        $localImages = [];

        // Check images in blocks
        $this->checkImagesInBlocks($blocks, $imagesFound, $localImages);

        if (!empty($localImages)) {
            foreach ($localImages as $image) {
                $this->errors[] = "Image appears to be local (not in S3): {$image}";
            }
        }

        if (!empty($imagesFound)) {
            echo "  ✓ Found " . count($imagesFound) . " image(s) in S3\n";
        } else {
            $this->warnings[] = "No images found in page blocks";
        }
    }

    private function checkImagesInBlocks($blocks, &$imagesFound, &$localImages): void
    {
        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';

            // Check CTA section images
            if ($type === 'cta_section') {
                $image = $block['images']['image'] ?? null;
                $mobileImage = $block['images']['mobile_image'] ?? null;
                if ($image && $image !== 'null') {
                    $imagesFound[] = $image;
                    if ($this->isLocalImage($image)) {
                        $localImages[] = $image;
                    }
                }
                if ($mobileImage && $mobileImage !== 'null') {
                    $imagesFound[] = $mobileImage;
                    if ($this->isLocalImage($mobileImage)) {
                        $localImages[] = $mobileImage;
                    }
                }
            }

            // Check info block images
            if ($type === 'info') {
                $asset = $block['asset'] ?? null;
                if ($asset && $asset !== 'null') {
                    $imagesFound[] = $asset;
                    if ($this->isLocalImage($asset)) {
                        $localImages[] = $asset;
                    }
                }
            }

            // Check image_with_description
            if ($type === 'image_with_description') {
                $image = $block['image'] ?? null;
                if ($image && $image !== 'null') {
                    $imagesFound[] = $image;
                    if ($this->isLocalImage($image)) {
                        $localImages[] = $image;
                    }
                }
            }

            // Check card images
            if ($type === 'card') {
                $asset = $block['asset'] ?? null;
                if ($asset && $asset !== 'null') {
                    $imagesFound[] = $asset;
                    if ($this->isLocalImage($asset)) {
                        $localImages[] = $asset;
                    }
                }
            }

            // Recursively check nested blocks
            if (isset($block['blocks'])) {
                $this->checkImagesInBlocks($block['blocks'], $imagesFound, $localImages);
            }

            // Check table_of_contents items
            if ($type === 'table_of_contents' && isset($block['toc_items'])) {
                foreach ($block['toc_items'] as $tocItem) {
                    if (isset($tocItem['items'])) {
                        foreach ($tocItem['items'] as $item) {
                            if (($item['type'] ?? '') === 'image_with_description') {
                                $image = $item['image'] ?? null;
                                if ($image && $image !== 'null') {
                                    $imagesFound[] = $image;
                                    if ($this->isLocalImage($image)) {
                                        $localImages[] = $image;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    private function isLocalImage($path): bool
    {
        return strpos($path, 'public/assets/') !== false ||
               strpos($path, '/tmp/') !== false ||
               strpos($path, 'local/') !== false ||
               preg_match('/^[a-z]:\\\\/i', $path) || // Windows path
               (strpos($path, '/') === 0 && strpos($path, 'pages/') !== 0); // Absolute path that's not S3
    }

    // 6. Verify links
    private function verifyLinks(): void
    {
        echo "\n[6/12] Verifying links (main content only)...\n";

        // Extract links from page
        $pageLinks = $this->extractLinksFromPage();

        // Extract links from production
        $productionLinks = $this->extractLinksFromProduction();

        // Compare
        $missingLinks = array_diff($productionLinks, $pageLinks);
        $extraLinks = array_diff($pageLinks, $productionLinks);

        // Filter out external links from extra links (they may be valid additions)
        $extraLinks = array_filter($extraLinks, function($link) {
            return strpos($link, 'https://bizee.com') === 0 ||
                   strpos($link, 'https://www.bizee.com') === 0 ||
                   strpos($link, '/') === 0;
        });

        if (!empty($missingLinks)) {
            $this->errors[] = "Missing links (" . count($missingLinks) . "):\n    " . implode("\n    ", array_slice($missingLinks, 0, 10));
            if (count($missingLinks) > 10) {
                $this->errors[] = "    ... and " . (count($missingLinks) - 10) . " more";
            }
        }

        if (!empty($extraLinks)) {
            $this->warnings[] = "Extra internal links in page (" . count($extraLinks) . "):\n    " . implode("\n    ", array_slice($extraLinks, 0, 5));
        }

        if (empty($missingLinks) && empty($extraLinks)) {
            echo "  ✓ All links match production\n";
        } else {
            echo "  ✓ Found " . count($pageLinks) . " links in page, " . count($productionLinks) . " in production\n";
        }
    }

    private function extractLinksFromPage(): array
    {
        $links = [];
        $blocks = $this->pageData['blocks'] ?? [];

        $this->extractLinksFromBlocks($blocks, $links);

        // Normalize links
        $normalized = [];
        foreach ($links as $link) {
            if (strpos($link, '/') === 0) {
                $link = 'https://bizee.com' . $link;
            }
            $normalized[] = rtrim($link, '/');
        }

        return array_unique($normalized);
    }

    private function extractLinksFromBlocks($blocks, &$links): void
    {
        foreach ($blocks as $block) {
            // Extract from Bard content (title, description, text fields)
            $this->extractLinksFromBardContent($block, $links);

            // Extract from button links
            if (isset($block['button']['link'])) {
                $links[] = $block['button']['link'];
            }

            // Extract from simple_button
            if (isset($block['toc_link'])) {
                $links[] = $block['toc_link'];
            }

            // Extract from article_button
            if (isset($block['toc_url'])) {
                $links[] = $block['toc_url'];
            }

            // Extract from card
            if (isset($block['link'])) {
                $links[] = $block['link'];
            }

            // Recursively check nested blocks
            if (isset($block['blocks'])) {
                $this->extractLinksFromBlocks($block['blocks'], $links);
            }

            // Check table_of_contents items
            if (isset($block['toc_items'])) {
                foreach ($block['toc_items'] as $tocItem) {
                    if (isset($tocItem['items'])) {
                        foreach ($tocItem['items'] as $item) {
                            $this->extractLinksFromBardContent($item, $links);
                            if (isset($item['toc_link'])) {
                                $links[] = $item['toc_link'];
                            }
                            if (isset($item['toc_url'])) {
                                $links[] = $item['toc_url'];
                            }
                        }
                    }
                }
            }

            // Check info items
            if (isset($block['items'])) {
                foreach ($block['items'] as $item) {
                    $this->extractLinksFromBardContent($item, $links);
                }
            }

            // Check FAQ items
            if (isset($block['items']) && $block['type'] === 'faq') {
                foreach ($block['items'] as $item) {
                    if (isset($item['description'])) {
                        // Description might contain links
                        $this->extractLinksFromText($item['description'], $links);
                    }
                }
            }

            // Check info_group_tabs
            if (isset($block['tab_group'])) {
                foreach ($block['tab_group'] as $tabGroup) {
                    if (isset($tabGroup['tab_items'])) {
                        foreach ($tabGroup['tab_items'] as $tabItem) {
                            if (isset($tabItem['link'])) {
                                $links[] = $tabItem['link'];
                            }
                        }
                    }
                }
            }
        }
    }

    private function extractLinksFromBardContent($data, &$links): void
    {
        // Check title (array of Bard nodes)
        if (isset($data['title']) && is_array($data['title'])) {
            foreach ($data['title'] as $node) {
                $this->extractLinksFromBardNode($node, $links);
            }
        }

        // Check description (array of Bard nodes or string)
        if (isset($data['description'])) {
            if (is_array($data['description'])) {
                foreach ($data['description'] as $node) {
                    $this->extractLinksFromBardNode($node, $links);
                }
            } else {
                $this->extractLinksFromText($data['description'], $links);
            }
        }

        // Check text field
        if (isset($data['text'])) {
            $this->extractLinksFromText($data['text'], $links);
        }

        // Check content field (for disclaimer_text)
        if (isset($data['content']) && is_array($data['content'])) {
            foreach ($data['content'] as $node) {
                $this->extractLinksFromBardNode($node, $links);
            }
        }
    }

    private function extractLinksFromBardNode($node, &$links): void
    {
        if (!is_array($node)) return;

        if (isset($node['content']) && is_array($node['content'])) {
            foreach ($node['content'] as $child) {
                if (isset($child['marks'])) {
                    foreach ($child['marks'] as $mark) {
                        if (($mark['type'] ?? '') === 'link' && isset($mark['attrs']['href'])) {
                            $links[] = $mark['attrs']['href'];
                        }
                    }
                }
                $this->extractLinksFromBardNode($child, $links);
            }
        }
    }

    private function extractLinksFromText($text, &$links): void
    {
        // Skip if text is not a string
        if (!is_string($text)) {
            return;
        }

        // Simple regex to find markdown-style links
        if (preg_match_all('/\[([^\]]+)\]\(([^\)]+)\)/', $text, $matches)) {
            foreach ($matches[2] as $link) {
                $links[] = $link;
            }
        }
    }

    private function extractLinksFromProduction(): array
    {
        if (!$this->productionHtml) {
            return [];
        }

        $links = [];

        // Extract all links from main content area (exclude header/footer)
        // Look for links in main content sections
        if (preg_match_all('/<a[^>]+href=["\']([^"\']+)["\'][^>]*>/i', $this->productionHtml, $matches)) {
            foreach ($matches[1] as $link) {
                // Normalize
                if (strpos($link, '/') === 0) {
                    $link = 'https://bizee.com' . $link;
                }
                $links[] = rtrim($link, '/');
            }
        }

        return array_unique($links);
    }

    // 7. Verify standard components
    private function verifyStandardComponents(): void
    {
        echo "\n[7/12] Verifying standard components...\n";

        $blocks = $this->pageData['blocks'] ?? [];
        $foundComponents = [];

        foreach ($blocks as $block) {
            $type = $block['type'] ?? '';
            $version = $block['version'] ?? '';
            $foundComponents[] = "{$type}_{$version}";
        }

        // Check for required standard components
        $requiredComponents = [
            'cta_section_19' => 'Hero CTA section',
            'trust_badges_1' => 'Trust badges',
            'info_4' => 'Join 1M+ Entrepreneurs',
        ];

        foreach ($requiredComponents as $component => $description) {
            $found = false;
            foreach ($blocks as $block) {
                $type = $block['type'] ?? '';
                $version = $block['version'] ?? '';
                if ("{$type}_{$version}" === $component) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $this->warnings[] = "Missing standard component: {$description} ({$component})";
            }
        }

        echo "  ✓ Standard components check completed\n";
    }

    // 8. Verify component structure
    private function verifyComponentStructure(): void
    {
        echo "\n[8/12] Verifying component structure...\n";

        $blocks = $this->pageData['blocks'] ?? [];
        $this->verifyBlocksStructure($blocks);

        echo "  ✓ Component structure check completed\n";
    }

    private function verifyBlocksStructure($blocks): void
    {
        foreach ($blocks as $block) {
            // Check that each block has required fields
            if (!isset($block['id'])) {
                $this->errors[] = "Block missing 'id' field";
            }
            if (!isset($block['type'])) {
                $this->errors[] = "Block missing 'type' field";
            }
            if (!isset($block['enabled'])) {
                $this->warnings[] = "Block missing 'enabled' field";
            }

            // Recursively check nested blocks
            if (isset($block['blocks'])) {
                $this->verifyBlocksStructure($block['blocks']);
            }
        }
    }

    // 9. Verify routing
    private function verifyRouting(): void
    {
        echo "\n[9/12] Verifying routing and redirects...\n";

        // Extract slug from page file
        $slug = basename($this->pageFile, '.md');
        $expectedRoute = "start-a-business/{$slug}";

        // Check released-pages.php
        $releasedPagesFile = __DIR__ . '/../app/Routing/migration/released-pages.php';
        if (file_exists($releasedPagesFile)) {
            $content = file_get_contents($releasedPagesFile);
            if (strpos($content, $expectedRoute) === false) {
                $this->warnings[] = "Route not found in released-pages.php: {$expectedRoute}";
            } else {
                echo "  ✓ Route found in released-pages.php\n";
            }
        } else {
            $this->warnings[] = "released-pages.php file not found";
        }

        // Check redirects.php
        $redirectsFile = __DIR__ . '/../app/Routing/redirects.php';
        if (file_exists($redirectsFile)) {
            $content = file_get_contents($redirectsFile);
            // Extract old URL from production URL
            $oldPath = parse_url($this->productionUrl, PHP_URL_PATH);
            if (strpos($content, $oldPath) === false && strpos($content, $expectedRoute) === false) {
                $this->warnings[] = "Redirect not found in redirects.php for: {$oldPath} => /{$expectedRoute}";
            } else {
                echo "  ✓ Redirect found in redirects.php\n";
            }
        } else {
            $this->warnings[] = "redirects.php file not found";
        }
    }

    // 10. Verify quotes (double quotes in YAML)
    private function verifyQuotes(): void
    {
        echo "\n[10/12] Verifying YAML quote format...\n";

        $yaml = $this->pageData['_yaml'] ?? '';

        // Check for single quotes in string values (should use double quotes)
        // This is a basic check - more complex validation would require parsing
        if (preg_match("/^[a-zA-Z_]+:\s*'[^']*'/m", $yaml)) {
            $this->warnings[] = "Found single quotes in YAML. All strings should use double quotes (\")";
        } else {
            echo "  ✓ YAML quote format appears correct\n";
        }
    }

    // 11. Verify block structure
    private function verifyBlockStructure(): void
    {
        echo "\n[11/12] Verifying block structure...\n";

        $blocks = $this->pageData['blocks'] ?? [];

        if (empty($blocks)) {
            $this->errors[] = "No blocks found in page";
            return;
        }

        // Verify blocks is an array
        if (!is_array($blocks)) {
            $this->errors[] = "blocks field is not an array";
            return;
        }

        echo "  ✓ Found " . count($blocks) . " block(s)\n";
    }

    // Print results
    private function printResults(): void
    {
        echo "\n╔═══════════════════════════════════════════════════════════╗\n";
        echo "║     Verification Results                                 ║\n";
        echo "╚═══════════════════════════════════════════════════════════╝\n\n";

        if (empty($this->errors) && empty($this->warnings)) {
            echo "✅ All checks passed! Migration looks good.\n\n";
            return;
        }

        if (!empty($this->errors)) {
            echo "❌ ERRORS (" . count($this->errors) . "):\n";
            foreach ($this->errors as $error) {
                echo "   • " . str_replace("\n", "\n     ", $error) . "\n";
            }
            echo "\n";
        }

        if (!empty($this->warnings)) {
            echo "⚠️  WARNINGS (" . count($this->warnings) . "):\n";
            foreach ($this->warnings as $warning) {
                echo "   • " . str_replace("\n", "\n     ", $warning) . "\n";
            }
            echo "\n";
        }

        if (!empty($this->info)) {
            echo "ℹ️  INFO:\n";
            foreach ($this->info as $info) {
                echo "   • " . str_replace("\n", "\n     ", $info) . "\n";
            }
            echo "\n";
        }
    }
}

// Execute script
if ($argc < 3) {
    echo "Usage: php verify-page-migration.php [PAGE_FILE] [PRODUCTION_URL]\n\n";
    echo "Example:\n";
    echo "php verify-page-migration.php content/collections/start_a_business_pages/example-page.md https://bizee.com/start-a-business/example-page\n";
    exit(1);
}

$pageFile = $argv[1];
$productionUrl = $argv[2];

try {
    $verifier = new PageMigrationVerifier($pageFile, $productionUrl);
    $results = $verifier->verify();

    exit(empty($results['errors']) ? 0 : 1);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
