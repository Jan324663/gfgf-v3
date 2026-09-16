<?php
/**
 * Plugin Name: GFGF Service Docs
 * Description: Infrastruktur fuer das Modul Schaltplaene / Unterlagen.
 * Version: 0.2.1
 * Author: GFGF
 * Text Domain: gfgf-service-docs
 * Requires PHP: 8.1
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

define('GFGF_SERVICE_DOCS_VERSION', '0.2.1');
define('GFGF_SERVICE_DOCS_FILE', __FILE__);
define('GFGF_SERVICE_DOCS_PATH', plugin_dir_path(__FILE__));

require_once GFGF_SERVICE_DOCS_PATH . 'includes/class-plugin.php';
require_once GFGF_SERVICE_DOCS_PATH . 'includes/class-repository.php';
require_once GFGF_SERVICE_DOCS_PATH . 'includes/class-frontend.php';

register_activation_hook(__FILE__, ['GFGF_Service_Docs\\Plugin', 'activate']);
register_deactivation_hook(__FILE__, ['GFGF_Service_Docs\\Plugin', 'deactivate']);

add_action('plugins_loaded', static function (): void {
    GFGF_Service_Docs\Plugin::init();
});
