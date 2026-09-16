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
    private const DB_VERSION = '0.2.1';
    private const RECIPIENT_OPTION = 'gfgf_service_docs_recipient_email';

    public static function init(): void
    {
        add_action('init', [self::class, 'register_post_type']);
        add_action('init', [self::class, 'register_blocks'], 20);
        add_action('init', [self::class, 'maybe_upgrade_schema'], 5);
        add_action('admin_post_gfgf_service_doc_request', [Frontend::class, 'handle_request']);
        add_action('admin_post_nopriv_gfgf_service_doc_request', [Frontend::class, 'handle_request']);
        add_action('admin_init', [self::class, 'register_settings']);
        add_action('admin_menu', [self::class, 'register_settings_page']);
    }

    public static function activate(): void
    {
        self::create_tables();
        self::create_roles();
        self::register_post_type();
        add_option(self::RECIPIENT_OPTION, 'archiv@gfgf.org', '', false);
        update_option('gfgf_service_docs_db_version', self::DB_VERSION, false);
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

    public static function register_blocks(): void
    {
        register_block_type(
            GFGF_SERVICE_DOCS_PATH . 'blocks/search',
            [
                'render_callback' => [Frontend::class, 'render_search_block'],
            ]
        );
    }

    public static function maybe_upgrade_schema(): void
    {
        if (self::DB_VERSION === get_option('gfgf_service_docs_db_version')) {
            return;
        }

        self::create_tables();
        add_option(self::RECIPIENT_OPTION, 'archiv@gfgf.org', '', false);
        update_option('gfgf_service_docs_db_version', self::DB_VERSION, false);
    }

    public static function recipient_email(): string
    {
        $configured = sanitize_email((string) get_option(self::RECIPIENT_OPTION, ''));
        if ('' === $configured) {
            $configured = sanitize_email((string) get_option('admin_email'));
        }

        $filtered = sanitize_email((string) apply_filters('gfgf_service_docs_recipient_email', $configured));

        return '' !== $filtered ? $filtered : sanitize_email((string) get_option('admin_email'));
    }

    public static function register_settings(): void
    {
        register_setting(
            'gfgf_service_docs_settings',
            self::RECIPIENT_OPTION,
            [
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_email',
                'default'           => 'archiv@gfgf.org',
            ]
        );

        add_settings_section(
            'gfgf_service_docs_mail',
            __('Anfragen', 'gfgf-service-docs'),
            '__return_false',
            'gfgf-service-docs'
        );

        add_settings_field(
            self::RECIPIENT_OPTION,
            __('Empfängeradresse', 'gfgf-service-docs'),
            [self::class, 'render_recipient_field'],
            'gfgf-service-docs',
            'gfgf_service_docs_mail'
        );
    }

    public static function register_settings_page(): void
    {
        add_options_page(
            __('GFGF Schaltplanservice', 'gfgf-service-docs'),
            __('GFGF Schaltplanservice', 'gfgf-service-docs'),
            'manage_options',
            'gfgf-service-docs',
            [self::class, 'render_settings_page']
        );
    }

    public static function render_recipient_field(): void
    {
        printf(
            '<input class="regular-text" type="email" name="%1$s" value="%2$s" required>',
            esc_attr(self::RECIPIENT_OPTION),
            esc_attr(self::recipient_email())
        );
        echo '<p class="description">' . esc_html__('An diese Adresse werden neue Unterlagenanfragen gesendet.', 'gfgf-service-docs') . '</p>';
    }

    public static function render_settings_page(): void
    {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('GFGF Schaltplanservice', 'gfgf-service-docs'); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('gfgf_service_docs_settings');
                do_settings_sections('gfgf-service-docs');
                submit_button();
                ?>
            </form>
        </div>
        <?php
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
            updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY contao_id (contao_id),
            UNIQUE KEY idx_value (idx_value),
            KEY jahr (jahr),
            KEY status (status),
            KEY firma (firma(191)),
            KEY geraetename (geraetename(191))
        ) {$charset};";

        dbDelta($sql);
        self::ensure_unique_idx($table);
    }

    private static function ensure_unique_idx(string $table): void
    {
        global $wpdb;

        $duplicate_count = (int) $wpdb->get_var(
            "SELECT COUNT(*) FROM (
                SELECT idx_value FROM {$table}
                WHERE idx_value IS NOT NULL AND idx_value <> ''
                GROUP BY idx_value HAVING COUNT(*) > 1
            ) duplicate_idx"
        );

        if ($duplicate_count > 0) {
            return;
        }

        $indexes = $wpdb->get_results("SHOW INDEX FROM {$table} WHERE Key_name = 'idx_value'", ARRAY_A);
        $is_unique = is_array($indexes)
            && [] !== $indexes
            && 0 === (int) $indexes[0]['Non_unique'];

        if (!$is_unique) {
            $wpdb->query("ALTER TABLE {$table} DROP INDEX idx_value, ADD UNIQUE KEY idx_value (idx_value)");
        }
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
