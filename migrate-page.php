<?php

/**
 * Page migration script
 *
 * This script helps migrate pages from production to Statamic CMS (start_a_business_pages collection).
 *
 * Usage: php migrate-page.php [PAGE_URL] [SLUG]
 *
 * Example:
 * php migrate-page.php https://bizee.com/start-a-business/travel-agency travel-agency
 *
 * ⚠️ IMPORTANT: This script creates a basic structure. You must:
 * - Complete the content conversion manually
 * - Verify all links are included
 * - Ensure all images are uploaded to S3
 * - Configure SEO fields from production
 * - Use correct components (cta_section, table_of_contents, info, etc.)
 */

require __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

class PageMigrator
{
    private $pageUrl;
    private $pageSlug;
    private $pagePath;
    private $productionHtml;

    public function __construct($pageUrl, $pageSlug)
    {
        $this->pageUrl = $pageUrl;
        $this->pageSlug = $pageSlug;
        $this->pagePath = __DIR__ . '/../content/collections/start_a_business_pages/' . $pageSlug . '.md';
    }

    /**
     * Generates a UUID v4
     */
    private function generateUUID(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    /**
     * Downloads production HTML
     */
    private function downloadProductionHtml(): void
    {
        echo "Downloading production HTML...\n";

        $ch = curl_init($this->pageUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');

        $html = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200 || !$html) {
            throw new Exception("Failed to download production HTML. HTTP Code: $httpCode");
        }

        $this->productionHtml = $html;
        echo "✓ HTML downloaded successfully\n\n";
    }

    /**
     * Extracts SEO fields from production HTML
     */
    private function extractSEOFields(): array
    {
        $seo = [
            'seo_custom_meta_title' => '',
            'seo_custom_meta_description' => '',
        ];

        // Extract title
        if (preg_match('/<title>(.*?)<\/title>/is', $this->productionHtml, $matches)) {
            $seo['seo_custom_meta_title'] = trim(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
        }

        // Extract meta description
        if (preg_match('/<meta\s+name=["\']description["\']\s+content=["\'](.*?)["\']/is', $this->productionHtml, $matches)) {
            $seo['seo_custom_meta_description'] = trim(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
        }

        return $seo;
    }

    /**
     * Extracts page title from production HTML
     */
    private function extractPageTitle(): string
    {
        // Try to extract from <h1> first
        if (preg_match('/<h1[^>]*>(.*?)<\/h1>/is', $this->productionHtml, $matches)) {
            $title = strip_tags($matches[1]);
            $title = html_entity_decode($title, ENT_QUOTES, 'UTF-8');
            return trim($title);
        }

        // Fallback to <title>
        if (preg_match('/<title>(.*?)<\/title>/is', $this->productionHtml, $matches)) {
            $title = trim(html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8'));
            return $title;
        }

        return 'Page Title';
    }

    /**
     * Creates the basic page structure
     */
    public function migrate(): void
    {
        echo "╔═══════════════════════════════════════════════════════════╗\n";
        echo "║     Migración de Página                                   ║\n";
        echo "╚═══════════════════════════════════════════════════════════╝\n\n";

        echo "Página: {$this->pageSlug}\n";
        echo "URL: {$this->pageUrl}\n";
        echo "Archivo: {$this->pagePath}\n\n";

        // Step 1: Download production HTML
        echo "═══════════════════════════════════════════════════════════\n";
        echo "STEP 1: Downloading production HTML...\n";
        echo "═══════════════════════════════════════════════════════════\n\n";

        $this->downloadProductionHtml();

        // Step 2: Extract basic information
        echo "═══════════════════════════════════════════════════════════\n";
        echo "STEP 2: Extracting page information...\n";
        echo "═══════════════════════════════════════════════════════════\n\n";

        $pageTitle = $this->extractPageTitle();
        $seoFields = $this->extractSEOFields();

        echo "Title: $pageTitle\n";
        echo "SEO Title: {$seoFields['seo_custom_meta_title']}\n";
        echo "SEO Description: " . substr($seoFields['seo_custom_meta_description'], 0, 80) . "...\n\n";

        // Step 3: Create page file
        echo "═══════════════════════════════════════════════════════════\n";
        echo "STEP 3: Creating page file...\n";
        echo "═══════════════════════════════════════════════════════════\n\n";

        $frontmatter = [
            'id' => $this->generateUUID(),
            'blueprint' => 'start_a_business_page',
            'title' => $pageTitle,
            'custom_page_header' => false,
            'breadcrumbs' => true,
            'breadcrumb_version' => 'breadcrumbs_v1',
            'page_settings_no_index' => false,
            'page_settings_hide_breadcrumbs' => false,
            'page_settings_hide_footer' => false,
            'page_settings_hide_on_production' => false,
            'page_settings_enabled_scripts' => [
                'fullstory',
                'ahrefs',
            ],
            'seo_title' => 'title',
            'seo_meta_description' => 'custom',
            'seo_custom_meta_title' => $seoFields['seo_custom_meta_title'] ?: $pageTitle,
            'seo_custom_meta_description' => $seoFields['seo_custom_meta_description'] ?: '',
            'seo_canonical' => 'none',
            'seo_og_description' => 'general',
            'seo_og_title' => 'title',
            'seo_tw_title' => 'title',
            'seo_tw_description' => 'general',
            'blocks' => [],
        ];

        // Create YAML frontmatter
        $yaml = Yaml::dump($frontmatter, 10, 2);

        // Write file
        $content = "---\n" . $yaml . "---\n";

        // Ensure directory exists
        $dir = dirname($this->pagePath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($this->pagePath, $content);

        echo "✓ Page file created: {$this->pagePath}\n\n";

        // Final summary
        echo "═══════════════════════════════════════════════════════════\n";
        echo "MIGRATION SUMMARY\n";
        echo "═══════════════════════════════════════════════════════════\n\n";

        echo "✓ Page file created\n";
        echo "✓ Basic frontmatter added\n";
        echo "✓ SEO fields extracted\n";
        echo "\n";
        echo "⚠️  NEXT STEPS:\n";
        echo "1. Open the file and convert HTML content to appropriate blocks\n";
        echo "2. Use correct components (cta_section, table_of_contents, info, faq, etc.)\n";
        echo "3. Upload all images to S3\n";
        echo "4. Verify all links are included in Bard format\n";
        echo "5. Run verify-page-migration.php to check everything\n";
        echo "\n";
        echo "✓ Basic migration completed!\n";
    }
}

// Execute script
if ($argc < 3) {
    echo "Usage: php migrate-page.php [PAGE_URL] [SLUG]\n\n";
    echo "Example:\n";
    echo "php migrate-page.php \\\n";
    echo "  https://bizee.com/start-a-business/travel-agency \\\n";
    echo "  travel-agency\n";
    exit(1);
}

$pageUrl = $argv[1];
$pageSlug = $argv[2];

try {
    $migrator = new PageMigrator($pageUrl, $pageSlug);
    $migrator->migrate();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
