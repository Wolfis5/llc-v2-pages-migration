<?php

/**
 * Helper functions for generating Statamic page components
 *
 * These helpers generate the correct structure for components used in
 * start_a_business_pages collection based on the TRAINING-MAPPING-GUIDE.md
 *
 * Usage:
 *   require_once 'component-helpers.php';
 *   $cta = generateCTASection($id, $title, $description, $buttonLabel, $buttonUrl);
 */

use Ramsey\Uuid\Uuid;

/**
 * Generates a unique ID for components
 *
 * @return string Short unique ID (e.g., 'mijammyv')
 */
function generateComponentId(): string
{
    return substr(str_replace('-', '', Uuid::uuid4()->toString()), 0, 8);
}

/**
 * Generates a CTA Section component
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param string $title Main title (H1)
 * @param string $description Description paragraph
 * @param string $buttonLabel Button text
 * @param string|null $buttonUrl Button URL (optional)
 * @param string $version Version (default: 'cta_section_19')
 * @return array CTA section structure
 */
function generateCTASection(
    ?string $id = null,
    string $title,
    string $description,
    string $buttonLabel,
    ?string $buttonUrl = null,
    string $version = 'cta_section_19'
): array {
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'version' => $version,
        'title' => [
            [
                'type' => 'heading',
                'attrs' => [
                    'level' => 1
                ],
                'content' => [
                    [
                        'type' => 'text',
                        'marks' => [
                            ['type' => 'bold']
                        ],
                        'text' => $title
                    ]
                ]
            ]
        ],
        'description' => [
            [
                'type' => 'paragraph',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $description
                    ]
                ]
            ]
        ],
        'images' => [
            'image' => null,
            'mobile_image' => null
        ],
        'button' => [
            'label' => $buttonLabel,
            'mobile_label' => null,
            'link' => $buttonUrl,
            'target_blank' => false,
            'custom_size' => false,
            'custom_alignment' => true,
            'custom_alignment_by_viewport' => false,
            'capitalized' => false,
            'custom_icon' => false,
            'size' => null,
            'button_width' => null,
            'variant' => 'primary',
            'alignment' => 'mx-auto',
            'mobile_alignment' => null,
            'tablet_alignment' => null,
            'desktop_alignment' => null,
            'custom_text_align' => false,
            'text_align' => null,
            'icon' => null,
            'position_icon' => null,
            'wrapper_class' => null
        ],
        'type' => 'cta_section',
        'enabled' => true,
        'select' => [
            'selector_type' => null,
            'state_variant' => null,
            'url' => null,
            'exclude_options' => null,
            'use_arrow_icon' => null,
            'button' => [
                'label' => null,
                'mobile_label' => null,
                'link' => null,
                'target_blank' => null,
                'custom_size' => null,
                'custom_alignment' => null,
                'custom_alignment_by_viewport' => null,
                'capitalized' => null,
                'custom_icon' => null,
                'size' => null,
                'button_width' => null,
                'variant' => null,
                'alignment' => null,
                'mobile_alignment' => null,
                'tablet_alignment' => null,
                'desktop_alignment' => null,
                'custom_text_align' => null,
                'text_align' => null,
                'icon' => null,
                'position_icon' => null,
                'wrapper_class' => null
            ]
        ]
    ];
}

/**
 * Generates Trust Badges component
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @return array Trust badges structure
 */
function generateTrustBadges(?string $id = null): array
{
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'external_page' => [
            [
                'id' => generateComponentId(),
                'review_pages' => 'trustpilot',
                'type' => 'new_set',
                'enabled' => true
            ],
            [
                'id' => generateComponentId(),
                'review_pages' => 'shopper_approved',
                'type' => 'new_set',
                'enabled' => true
            ]
        ],
        'version' => 'trust_badges_1',
        'type' => 'trust_badges',
        'enabled' => true
    ];
}

/**
 * Generates Info Block "Join 1M+ Entrepreneurs" component
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @return array Info block structure
 */
function generateInfoJoin1M(?string $id = null): array
{
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'version' => 'info_4',
        'title' => [
            [
                'type' => 'paragraph',
                'content' => [
                    [
                        'type' => 'text',
                        'text' => 'Join '
                    ],
                    [
                        'type' => 'text',
                        'marks' => [
                            [
                                'type' => 'textColor',
                                'attrs' => [
                                    'color' => '#FF4A00'
                                ]
                            ]
                        ],
                        'text' => '1,000,000+'
                    ],
                    [
                        'type' => 'text',
                        'text' => ' Entrepreneurs like you'
                    ]
                ]
            ]
        ],
        'description' => "Entrepreneurship is booming – and we're happy to be one of America's fastest growing companies.",
        'order_reverse' => false,
        'asset' => 'inc5000_x2.webp',
        'show_index' => true,
        'show_display_options' => false,
        'type' => 'info',
        'enabled' => true,
        'details' => [
            'use_eyebrow' => null,
            'use_only_principal_image' => null
        ]
    ];
}

/**
 * Generates a Text with Icon component
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param string $text Text content (supports markdown-style **bold**)
 * @param string $icon Icon path (e.g., 'checkbox-circle-duocolor.svg')
 * @param string $version Version ('gray' or 'white', default: 'gray')
 * @return array Text with icon structure
 */
function generateTextWithIcon(
    ?string $id = null,
    string $text,
    string $icon,
    string $version = 'gray'
): array {
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'version' => $version,
        'text' => $text,
        'icon' => $icon,
        'type' => 'text_with_icon',
        'enabled' => true
    ];
}

/**
 * Generates a Text Container component
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param string $text Text content (supports markdown and HTML)
 * @param string|null $textContentVersion Version ('disclaimer' or null)
 * @return array Text container structure
 */
function generateTextContainer(
    ?string $id = null,
    string $text,
    ?string $textContentVersion = null
): array {
    $id = $id ?? generateComponentId();

    $result = [
        'id' => $id,
        'text' => $text,
        'type' => 'text_container',
        'enabled' => true
    ];

    if ($textContentVersion !== null) {
        $result['text_content_version'] = $textContentVersion;
    }

    return $result;
}

/**
 * Generates a Video component
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param string $videoUrl Video URL (Wistia or YouTube)
 * @param bool $showVideoObject Show video object (default: false)
 * @return array Video structure
 */
function generateVideo(
    ?string $id = null,
    string $videoUrl,
    bool $showVideoObject = false
): array {
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'version' => 'video_1',
        'video_url' => $videoUrl,
        'show_video_object' => $showVideoObject,
        'type' => 'video',
        'enabled' => true
    ];
}

/**
 * Generates a Simple Button component (for table_of_contents)
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param string $label Button label
 * @param string $link Button URL
 * @param bool $targetBlank Open in new tab (default: false)
 * @param string $variant Button variant (default: 'primary')
 * @return array Simple button structure
 */
function generateSimpleButton(
    ?string $id = null,
    string $label,
    string $link,
    bool $targetBlank = false,
    string $variant = 'primary'
): array {
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'toc_label' => $label,
        'toc_link' => $link,
        'toc_target_blank' => $targetBlank,
        'toc_custom_size' => false,
        'toc_custom_alignment' => false,
        'toc_capitalized' => false,
        'toc_custom_icon' => false,
        'toc_variant' => $variant,
        'toc_custom_text_align' => false,
        'type' => 'simple_button',
        'enabled' => true
    ];
}

/**
 * Generates an Article Button component (for table_of_contents)
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param string $label Button label
 * @param string $url Button URL
 * @param bool $openInNewTab Open in new tab (default: false)
 * @return array Article button structure
 */
function generateArticleButton(
    ?string $id = null,
    string $label,
    string $url,
    bool $openInNewTab = false
): array {
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'toc_version' => 'article_button_1',
        'toc_label' => [
            [
                'type' => 'paragraph',
                'attrs' => [
                    'textAlign' => 'left'
                ],
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $label
                    ]
                ]
            ]
        ],
        'toc_url' => $url,
        'toc_open_in_new_tab' => $openInNewTab,
        'type' => 'article_button',
        'enabled' => true
    ];
}

/**
 * Generates a Card component (for table_of_contents)
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param string $title Card title (bold)
 * @param string $subtitle Card subtitle (small text)
 * @param string $asset Image asset path
 * @param string $label Button label
 * @param string $link Button URL
 * @param string $variant Button variant (default: 'primary')
 * @return array Card structure
 */
function generateCard(
    ?string $id = null,
    string $title,
    string $subtitle,
    string $asset,
    string $label,
    string $link,
    string $variant = 'primary'
): array {
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'text' => [
            [
                'type' => 'paragraph',
                'attrs' => [
                    'textAlign' => 'left'
                ],
                'content' => [
                    [
                        'type' => 'text',
                        'marks' => [
                            ['type' => 'bold']
                        ],
                        'text' => $title
                    ]
                ]
            ],
            [
                'type' => 'paragraph',
                'attrs' => [
                    'textAlign' => 'left'
                ],
                'content' => [
                    [
                        'type' => 'text',
                        'marks' => [
                            ['type' => 'small']
                        ],
                        'text' => $subtitle
                    ]
                ]
            ]
        ],
        'bard_alignment' => false,
        'secondary_font' => false,
        'custom_font_size' => false,
        'asset' => $asset,
        'label' => $label,
        'link' => $link,
        'target_blank' => false,
        'custom_size' => false,
        'custom_alignment' => false,
        'capitalized' => false,
        'custom_icon' => false,
        'variant' => $variant,
        'custom_text_align' => false,
        'type' => 'card',
        'enabled' => true
    ];
}

/**
 * Generates a Disclaimer Text component
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param string|array $content Content (string or array of Bard nodes)
 * @return array Disclaimer text structure
 */
function generateDisclaimerText(
    ?string $id = null,
    $content
): array {
    $id = $id ?? generateComponentId();

    // If content is a string, convert to paragraph
    if (is_string($content)) {
        $content = [
            [
                'type' => 'paragraph',
                'attrs' => [
                    'textAlign' => 'left'
                ],
                'content' => [
                    [
                        'type' => 'text',
                        'text' => $content
                    ]
                ]
            ]
        ];
    }

    return [
        'id' => $id,
        'version' => 'disclaimer_text_1',
        'content' => $content,
        'type' => 'disclaimer_text',
        'enabled' => true
    ];
}

/**
 * Generates an Info Block component (with items)
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param string|array $title Title (string or Bard nodes)
 * @param string|null $description Description (optional)
 * @param array $items Array of items (each with 'title' and 'description')
 * @param string $version Version ('info_28', 'info_29', 'info_11', default: 'info_28')
 * @param string|null $background Background color (optional)
 * @return array Info block structure
 */
function generateInfoBlock(
    ?string $id = null,
    $title,
    ?string $description = null,
    array $items = [],
    string $version = 'info_28',
    ?string $background = null
): array {
    $id = $id ?? generateComponentId();

    // Convert title to Bard format if string
    if (is_string($title)) {
        $title = [
            [
                'type' => 'heading',
                'attrs' => [
                    'level' => 2
                ],
                'content' => [
                    [
                        'type' => 'text',
                        'marks' => [
                            ['type' => 'bold']
                        ],
                        'text' => $title
                    ]
                ]
            ]
        ];
    }

    $result = [
        'id' => $id,
        'version' => $version,
        'title' => $title,
        'order_reverse' => false,
        'show_index' => true,
        'show_display_options' => false,
        'type' => 'info',
        'enabled' => true,
        'details' => [
            'use_eyebrow' => null,
            'use_only_principal_image' => null
        ]
    ];

    if ($description !== null) {
        $result['description'] = $description;
    }

    if (!empty($items)) {
        $result['items'] = array_map(function($item) {
            return [
                'id' => generateComponentId(),
                'title' => [
                    [
                        'type' => 'paragraph',
                        'content' => [
                            [
                                'type' => 'text',
                                'marks' => [
                                    ['type' => 'bold']
                                ],
                                'text' => $item['title'] ?? ''
                            ]
                        ]
                    ]
                ],
                'description' => $item['description'] ?? '',
                'asset' => $item['asset'] ?? null,
                'select_an_icon' => false,
                'type' => 'new_item',
                'enabled' => true
            ];
        }, $items);
    }

    if ($background !== null) {
        $result['background'] = $background;
    }

    return $result;
}

/**
 * Generates a Blocks container component
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param array $blocks Array of block components
 * @return array Blocks container structure
 */
function generateBlocks(
    ?string $id = null,
    array $blocks
): array {
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'blocks' => $blocks,
        'type' => 'blocks',
        'enabled' => true
    ];
}

/**
 * Generates a Components container component
 *
 * @param string|null $id Component ID (auto-generated if null)
 * @param array $components Array of component structures
 * @return array Components container structure
 */
function generateComponents(
    ?string $id = null,
    array $components
): array {
    $id = $id ?? generateComponentId();

    return [
        'id' => $id,
        'component' => $components,
        'type' => 'components',
        'enabled' => true
    ];
}
