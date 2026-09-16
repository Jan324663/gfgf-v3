<?php
/**
 * Theme bootstrap for GFGF V3.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

add_action('after_setup_theme', function (): void {
    load_theme_textdomain('gfgf-v3', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor.css');
    add_theme_support('custom-logo', [
        'height'      => 84,
        'width'       => 72,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Hauptnavigation', 'gfgf-v3'),
        'footer' => __('Footernavigation', 'gfgf-v3'),
    ]);
});

add_action('init', function (): void {
    register_block_pattern_category('gfgf-pages', [
        'label' => __('GFGF Seiten', 'gfgf-v3'),
    ]);
});

add_action('wp_enqueue_scripts', function (): void {
    wp_enqueue_style(
        'gfgf-v3-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'gfgf-v3-style',
        get_stylesheet_uri(),
        ['gfgf-v3-fonts'],
        wp_get_theme()->get('Version')
    );

    wp_enqueue_script(
        'gfgf-v3-header',
        get_template_directory_uri() . '/assets/js/header.js',
        [],
        wp_get_theme()->get('Version'),
        true
    );

    if (is_page_template('page-archiv-besuchen.php')) {
        wp_enqueue_script(
            'gfgf-v3-archive-details',
            get_template_directory_uri() . '/assets/js/archive-details.js',
            [],
            wp_get_theme()->get('Version'),
            true
        );
    }
});

function gfgf_v3_page_url(string $slug): string
{
    $slug = trim($slug, '/');
    $page = get_page_by_path($slug);

    if ($page instanceof WP_Post) {
        $permalink = get_permalink($page);
        if (is_string($permalink) && '' !== $permalink) {
            return $permalink;
        }
    }

    return home_url('/' . $slug . '/');
}

function gfgf_v3_primary_nav_items(): array
{
    return [
        ['label' => __('Über uns', 'gfgf-v3'), 'slug' => 'ueber-uns', 'url' => gfgf_v3_page_url('ueber-uns')],
        ['label' => __('Archiv', 'gfgf-v3'), 'slug' => 'das-gfgf-archiv', 'url' => gfgf_v3_page_url('das-gfgf-archiv')],
        ['label' => __('Funkgeschichte', 'gfgf-v3'), 'slug' => 'funkgeschichte', 'url' => gfgf_v3_page_url('funkgeschichte')],
        ['label' => __('Mitgliedschaft', 'gfgf-v3'), 'slug' => 'mitgliedschaft', 'url' => gfgf_v3_page_url('mitgliedschaft')],
    ];
}

/**
 * Return the current top-level section represented in the primary navigation.
 *
 * WordPress page ancestry is the primary source of truth. The template map is
 * deliberately small and only covers existing pages that predate that page
 * hierarchy. It can be extended without adding URL checks to the header.
 *
 * @return array{slug:string,root_id:int,is_root:bool}|null
 */
function gfgf_v3_active_primary_section(): ?array
{
    static $resolved = false;
    static $section = null;

    if ($resolved) {
        return $section;
    }

    $resolved = true;

    if (!is_page()) {
        return null;
    }

    $current_id = (int) get_queried_object_id();
    if ($current_id < 1) {
        return null;
    }

    $lineage = array_merge([$current_id], array_map('intval', get_post_ancestors($current_id)));
    $roots = [];

    foreach (gfgf_v3_primary_nav_items() as $item) {
        $root_page = get_page_by_path($item['slug'], OBJECT, 'page');
        if (!$root_page instanceof WP_Post) {
            continue;
        }

        $root_id = (int) $root_page->ID;
        $roots[$item['slug']] = $root_id;

        if (in_array($root_id, $lineage, true)) {
            $section = [
                'slug'    => $item['slug'],
                'root_id' => $root_id,
                'is_root' => $current_id === $root_id,
            ];

            return $section;
        }
    }

    /**
     * Map legacy standalone page templates to a primary section.
     *
     * Pages arranged below a main page in the WordPress page hierarchy do not
     * need an entry here. Themes or child themes can extend this map using the
     * `gfgf_v3_primary_section_template_map` filter.
     *
     * @var array<string,array<int,string>> $template_map
     */
    $template_map = apply_filters('gfgf_v3_primary_section_template_map', [
        'das-gfgf-archiv' => [
            'page-das-gfgf-archiv.php',
            'page-archiv-besuchen.php',
            'page-schaltplanservice.php',
            'page-weitere-archive-quellen.php',
        ],
    ]);

    $current_template = get_page_template_slug($current_id);

    foreach ($template_map as $root_slug => $templates) {
        if (!isset($roots[$root_slug]) || !in_array($current_template, $templates, true)) {
            continue;
        }

        $section = [
            'slug'    => $root_slug,
            'root_id' => $roots[$root_slug],
            'is_root' => $current_id === $roots[$root_slug],
        ];

        return $section;
    }

    return null;
}

/**
 * Check whether a WordPress menu item represents the active primary section.
 */
function gfgf_v3_is_active_primary_menu_item(WP_Post $menu_item): bool
{
    $section = gfgf_v3_active_primary_section();
    if (null === $section) {
        return false;
    }

    if ('page' === $menu_item->object && (int) $menu_item->object_id === $section['root_id']) {
        return true;
    }

    foreach (gfgf_v3_primary_nav_items() as $item) {
        if ($item['slug'] !== $section['slug']) {
            continue;
        }

        return untrailingslashit((string) $menu_item->url) === untrailingslashit($item['url']);
    }

    return false;
}

add_filter('nav_menu_css_class', function (array $classes, WP_Post $menu_item, stdClass $args, int $depth): array {
    if ('primary' !== ($args->theme_location ?? '') || 0 !== $depth || !gfgf_v3_is_active_primary_menu_item($menu_item)) {
        return $classes;
    }

    $section = gfgf_v3_active_primary_section();
    $classes[] = 'is-active-section';
    $classes[] = $section && $section['is_root'] ? 'current-menu-item' : 'current-menu-ancestor';

    return array_values(array_unique($classes));
}, 10, 4);

add_filter('nav_menu_link_attributes', function (array $attributes, WP_Post $menu_item, stdClass $args, int $depth): array {
    if ('primary' !== ($args->theme_location ?? '') || 0 !== $depth || !gfgf_v3_is_active_primary_menu_item($menu_item)) {
        return $attributes;
    }

    $section = gfgf_v3_active_primary_section();
    $attributes['aria-current'] = $section && $section['is_root'] ? 'page' : 'location';

    return $attributes;
}, 10, 4);

function gfgf_v3_footer_nav_items(): array
{
    return [
        ['label' => __('Impressum', 'gfgf-v3'), 'url' => gfgf_v3_page_url('impressum')],
        ['label' => __('Datenschutz', 'gfgf-v3'), 'url' => gfgf_v3_page_url('datenschutz')],
        ['label' => __('Kontakt', 'gfgf-v3'), 'url' => gfgf_v3_page_url('kontakt')],
        ['label' => __('Satzung', 'gfgf-v3'), 'url' => gfgf_v3_page_url('satzung')],
        ['label' => __('Spenden', 'gfgf-v3'), 'url' => gfgf_v3_page_url('spenden')],
    ];
}

require_once get_template_directory() . '/inc/archive-content.php';
require_once get_template_directory() . '/inc/service-docs-content.php';
