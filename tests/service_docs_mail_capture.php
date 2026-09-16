<?php
/**
 * Local test fixture: capture outgoing mail without contacting a mail server.
 * Copy temporarily to wp-content/mu-plugins while running the mail audit.
 */

defined('ABSPATH') || exit;

add_filter(
    'pre_wp_mail',
    static function ($return, array $attributes) {
        update_option('gfgf_test_last_service_docs_mail', $attributes, false);

        return true;
    },
    10,
    2
);
