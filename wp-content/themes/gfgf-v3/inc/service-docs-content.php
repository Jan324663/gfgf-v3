<?php
/**
 * One-time Gutenberg migration for the document search page.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

function gfgf_v3_migrate_service_docs_page(): void
{
    $migration_version = '2026-09-16-service-docs-page-v1';

    if ($migration_version === get_option('gfgf_v3_service_docs_page_migration')) {
        return;
    }

    $page = get_page_by_path('schaltplanservice', OBJECT, 'page');

    if (!$page instanceof WP_Post) {
        return;
    }

    update_post_meta($page->ID, '_wp_page_template', 'page-schaltplanservice.php');

    $update = [
        'ID'         => $page->ID,
        'post_title' => 'Dokumente suchen',
    ];

    if ('' === trim((string) $page->post_content)) {
        $content = gfgf_v3_render_pattern_content('gfgf-dokumentensuche.php');
        if ('' === $content) {
            return;
        }
        $update['post_content'] = $content;
    }

    $result = wp_update_post(wp_slash($update), true);

    if (!is_wp_error($result)) {
        update_option('gfgf_v3_service_docs_page_migration', $migration_version, false);
    }
}
add_action('init', 'gfgf_v3_migrate_service_docs_page', 34);
