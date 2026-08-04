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

function gfgf_v3_primary_nav_items(): array
{
    return [
        ['label' => __('Über uns', 'gfgf-v3'), 'url' => home_url('/ueber-uns/')],
        ['label' => __('Archiv', 'gfgf-v3'), 'url' => home_url('/das-gfgf-archiv/')],
        ['label' => __('Funkgeschichte', 'gfgf-v3'), 'url' => home_url('/funkgeschichte/')],
        ['label' => __('Schaltplanservice', 'gfgf-v3'), 'url' => home_url('/schaltplanservice/')],
        ['label' => __('Mitgliedschaft', 'gfgf-v3'), 'url' => home_url('/mitgliedschaft/')],
    ];
}

function gfgf_v3_footer_nav_items(): array
{
    return [
        ['label' => __('Impressum', 'gfgf-v3'), 'url' => home_url('/impressum/')],
        ['label' => __('Datenschutz', 'gfgf-v3'), 'url' => home_url('/datenschutz/')],
        ['label' => __('Kontakt', 'gfgf-v3'), 'url' => home_url('/kontakt/')],
        ['label' => __('Satzung', 'gfgf-v3'), 'url' => home_url('/satzung/')],
        ['label' => __('Spenden', 'gfgf-v3'), 'url' => home_url('/spenden/')],
    ];
}
