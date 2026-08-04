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
        'gfgf-v3-style',
        get_stylesheet_uri(),
        [],
        wp_get_theme()->get('Version')
    );
});

