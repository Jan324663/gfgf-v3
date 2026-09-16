<?php
/**
 * One-time migration of the existing empty archive pages to editable blocks.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

function gfgf_v3_render_pattern_content(string $filename): string
{
    $path = get_template_directory() . '/patterns/' . basename($filename);

    if (!is_readable($path)) {
        return '';
    }

    ob_start();
    include $path;

    return trim((string) ob_get_clean());
}

function gfgf_v3_migrate_archive_pages_to_blocks(): void
{
    $migration_version = '2026-09-16-archive-blocks-v2';

    if ($migration_version === get_option('gfgf_v3_archive_content_migration')) {
        return;
    }

    $migration_complete = true;
    $sources_page = get_page_by_path('weitere-archive-quellen', OBJECT, 'page');

    if (!$sources_page instanceof WP_Post) {
        $sources_page_id = wp_insert_post(
            wp_slash([
                'post_type'   => 'page',
                'post_status' => 'publish',
                'post_title'  => 'Weitere Archive & Quellen',
                'post_name'   => 'weitere-archive-quellen',
            ]),
            true
        );

        if (is_wp_error($sources_page_id)) {
            $migration_complete = false;
        } else {
            $sources_page = get_post((int) $sources_page_id);
        }
    }

    $migrations = [
        'das-gfgf-archiv' => 'gfgf-archiv.php',
        'archiv-besuchen' => 'gfgf-archiv-hainichen.php',
    ];

    foreach ($migrations as $slug => $pattern_file) {
        $page = get_page_by_path($slug, OBJECT, 'page');

        if (!$page instanceof WP_Post) {
            $migration_complete = false;
            continue;
        }

        if ('' !== trim((string) $page->post_content)) {
            if ('das-gfgf-archiv' === $slug && $sources_page instanceof WP_Post) {
                $fallback_url = home_url('/weitere-archive-quellen/');
                $permalink = get_permalink($sources_page);

                if (is_string($permalink) && '' !== $permalink && str_contains((string) $page->post_content, $fallback_url)) {
                    $result = wp_update_post(
                        wp_slash([
                            'ID'           => $page->ID,
                            'post_content' => str_replace($fallback_url, $permalink, (string) $page->post_content),
                        ]),
                        true
                    );

                    if (is_wp_error($result)) {
                        $migration_complete = false;
                    }
                }
            }

            continue;
        }

        $content = gfgf_v3_render_pattern_content($pattern_file);

        if ('' === $content) {
            $migration_complete = false;
            continue;
        }

        $result = wp_update_post(
            wp_slash([
                'ID'           => $page->ID,
                'post_content' => $content,
            ]),
            true
        );

        if (is_wp_error($result)) {
            $migration_complete = false;
        }
    }

    if ($migration_complete) {
        update_option('gfgf_v3_archive_content_migration', $migration_version, false);
    }
}
add_action('init', 'gfgf_v3_migrate_archive_pages_to_blocks', 30);

/**
 * Upgrade the untouched first Gutenberg version of the archive landing page.
 *
 * The strict content markers prevent an editor's later changes from being
 * overwritten. The replacement itself remains ordinary, editable core blocks.
 */
function gfgf_v3_upgrade_archive_landing_design(): void
{
    $upgrade_version = '2026-09-16-archive-landing-visual-v1';

    if ($upgrade_version === get_option('gfgf_v3_archive_landing_upgrade')) {
        return;
    }

    $page = get_page_by_path('das-gfgf-archiv', OBJECT, 'page');

    if (!$page instanceof WP_Post) {
        return;
    }

    $content = (string) $page->post_content;

    if (str_contains($content, 'archive-landing__hero-media')) {
        update_option('gfgf_v3_archive_landing_upgrade', $upgrade_version, false);
        return;
    }

    $expected_markers = [
        'Im GFGF-Archiv in Hainichen bewahren wir umfangreiche historische Unterlagen',
        'Sie besitzen alte Schaltpläne, Serviceunterlagen, Bedienungsanleitungen',
        'archive-landing__intro-inner',
    ];

    foreach ($expected_markers as $marker) {
        if (!str_contains($content, $marker)) {
            return;
        }
    }

    if (3 !== substr_count($content, '<article class="wp-block-group archive-teaser">')) {
        return;
    }

    $upgraded_content = gfgf_v3_render_pattern_content('gfgf-archiv.php');

    if ('' === $upgraded_content) {
        return;
    }

    $result = wp_update_post(
        wp_slash([
            'ID'           => $page->ID,
            'post_content' => $upgraded_content,
        ]),
        true
    );

    if (!is_wp_error($result)) {
        update_option('gfgf_v3_archive_landing_upgrade', $upgrade_version, false);
    }
}
add_action('init', 'gfgf_v3_upgrade_archive_landing_design', 31);

/**
 * Fill the existing sources page once with ordinary editable Gutenberg blocks.
 *
 * Existing editorial content is never overwritten. The template only provides
 * the page shell; all visible copy, URLs and images live in post_content.
 */
function gfgf_v3_migrate_archive_sources_page(): void
{
    $migration_version = '2026-09-16-archive-sources-v1';

    if ($migration_version === get_option('gfgf_v3_archive_sources_migration')) {
        return;
    }

    $page = get_page_by_path('weitere-archive-quellen', OBJECT, 'page');

    if (!$page instanceof WP_Post) {
        return;
    }

    update_post_meta($page->ID, '_wp_page_template', 'page-weitere-archive-quellen.php');

    if ('' !== trim((string) $page->post_content)) {
        update_option('gfgf_v3_archive_sources_migration', $migration_version, false);
        return;
    }

    $content = gfgf_v3_render_pattern_content('gfgf-archiv-quellen.php');

    if ('' === $content) {
        return;
    }

    $result = wp_update_post(
        wp_slash([
            'ID'           => $page->ID,
            'post_content' => $content,
        ]),
        true
    );

    if (!is_wp_error($result)) {
        update_option('gfgf_v3_archive_sources_migration', $migration_version, false);
    }
}
add_action('init', 'gfgf_v3_migrate_archive_sources_page', 32);
