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
    add_theme_support('custom-logo', [
        'height'      => 96,
        'width'       => 82,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    register_nav_menus([
        'primary' => __('Hauptnavigation', 'gfgf-v3'),
        'footer' => __('Footernavigation', 'gfgf-v3'),
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
        ['label' => __('Über uns', 'gfgf-v3'), 'url' => gfgf_v3_page_url('ueber-uns')],
        ['label' => __('Archiv', 'gfgf-v3'), 'url' => gfgf_v3_page_url('das-gfgf-archiv')],
        ['label' => __('Funkgeschichte', 'gfgf-v3'), 'url' => gfgf_v3_page_url('funkgeschichte')],
        ['label' => __('Mitgliedschaft', 'gfgf-v3'), 'url' => gfgf_v3_page_url('mitgliedschaft')],
    ];
}

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
