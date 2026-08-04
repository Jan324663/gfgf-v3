<?php
/**
 * Main plugin class.
 */

declare(strict_types=1);

namespace GFGF_Service_Docs;

defined('ABSPATH') || exit;

final class Plugin
{
    public const TABLE = 'gfgf_service_docs';
    public const ROLE = 'gfgf_service_doc_editor';

    public static function init(): void
    {
        add_action('init', [self::class, 'register_post_type']);
    }

    public static function activate(): void
    {
        self::create_tables();
        self::create_roles();
        self::register_post_type();
        flush_rewrite_rules();
    }

    public static function deactivate(): void
    {
        flush_rewrite_rules();
    }

    public static function register_post_type(): void
    {
        register_post_type('gfgf_service_doc', [
            'labels' => [
                'name'          => __('Serviceunterlagen', 'gfgf-service-docs'),
                'singular_name' => __('Serviceunterlage', 'gfgf-service-docs'),
                'add_new_item'  => __('Serviceunterlage hinzufuegen', 'gfgf-service-docs'),
                'edit_item'     => __('Serviceunterlage bearbeiten', 'gfgf-service-docs'),
                'search_items'  => __('Serviceunterlagen suchen', 'gfgf-service-docs'),
                'not_found'     => __('Keine Serviceunterlagen gefunden', 'gfgf-service-docs'),
            ],
            'public'             => false,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'show_in_rest'       => true,
            'menu_icon'          => 'dashicons-archive',
            'supports'           => ['title'],
            'capability_type'    => ['gfgf_service_doc', 'gfgf_service_docs'],
            'map_meta_cap'       => true,
        ]);
    }

    private static function create_tables(): void
    {
        global $wpdb;

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';

        $table = $wpdb->prefix . self::TABLE;
        $charset = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE {$table} (
            id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
            contao_id BIGINT UNSIGNED NULL,
            firma TEXT NULL,
            geraetename TEXT NULL,
            geraetetyp TEXT NULL,
            typ_zusatz TEXT NULL,
            titel TEXT NULL,
            autor TEXT NULL,
            heft TEXT NULL,
            von_seite VARCHAR(64) NULL,
            bis_seite VARCHAR(64) NULL,
            dokumentart TEXT NULL,
            drucktitel TEXT NULL,
            jahr VARCHAR(64) NULL,
            bemerkung TEXT NULL,
            bemerkung1 TEXT NULL,
            bemerkung2 TEXT NULL,
            bemerkung3 TEXT NULL,
            ablage_ordner TEXT NULL,
            ordner_nummer TEXT NULL,
            pc TEXT NULL,
            idx_value VARCHAR(191) NULL,
            search_text LONGTEXT NULL,
            status VARCHAR(32) NOT NULL DEFAULT 'published',
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY contao_id (contao_id),
            KEY idx_value (idx_value),
            KEY jahr (jahr),
            KEY status (status),
            KEY firma (firma(191)),
            KEY geraetename (geraetename(191))
        ) {$charset};";

        dbDelta($sql);
    }

    private static function create_roles(): void
    {
        add_role(self::ROLE, __('Serviceunterlagen-Redaktion', 'gfgf-service-docs'), [
            'read'                         => true,
            'upload_files'                 => true,
            'edit_gfgf_service_docs'       => true,
            'edit_gfgf_service_doc'        => true,
            'edit_others_gfgf_service_docs'=> true,
            'publish_gfgf_service_docs'    => false,
            'delete_gfgf_service_docs'     => false,
        ]);

        $admin = get_role('administrator');
        if ($admin) {
            foreach ([
                'edit_gfgf_service_doc',
                'read_gfgf_service_doc',
                'delete_gfgf_service_doc',
                'edit_gfgf_service_docs',
                'edit_others_gfgf_service_docs',
                'publish_gfgf_service_docs',
                'read_private_gfgf_service_docs',
                'delete_gfgf_service_docs',
            ] as $capability) {
                $admin->add_cap($capability);
            }
        }
    }
}

