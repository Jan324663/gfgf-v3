<?php
/**
 * Header template.
 */

defined('ABSPATH') || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link" href="#content"><?php esc_html_e('Zum Inhalt springen', 'gfgf-v3'); ?></a>
<header class="site-header">
    <div class="site-header__inner">
        <div class="site-brand">
            <?php if (has_custom_logo()) : ?>
                <?php echo get_custom_logo(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            <?php else : ?>
                <a class="site-logo" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php esc_attr_e('GFGF Startseite', 'gfgf-v3'); ?>">
                    <img
                        class="site-logo__image"
                        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gfgf-logo.png'); ?>"
                        width="123"
                        height="122"
                        alt="<?php esc_attr_e('GFGF e.V.', 'gfgf-v3'); ?>"
                    >
                </a>
            <?php endif; ?>
        </div>
        <nav id="primary-navigation" class="site-navigation" aria-label="<?php esc_attr_e('Hauptnavigation', 'gfgf-v3'); ?>">
            <?php
            $primary_menu = wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => false,
                'echo'           => false,
                'menu_class'     => 'site-navigation__list',
            ]);

            if ($primary_menu) {
                echo $primary_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } else {
                echo '<ul class="site-navigation__list">';
                foreach (gfgf_v3_primary_nav_items() as $item) {
                    printf(
                        '<li><a href="%s">%s</a></li>',
                        esc_url($item['url']),
                        esc_html($item['label'])
                    );
                }
                echo '</ul>';
            }
            ?>
        </nav>
        <div class="site-header__actions">
            <form class="site-search" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                <label class="screen-reader-text" for="site-search-field"><?php esc_html_e('Website durchsuchen', 'gfgf-v3'); ?></label>
                <input id="site-search-field" class="site-search__field" type="search" name="s" placeholder="<?php esc_attr_e('Suche', 'gfgf-v3'); ?>" value="<?php echo esc_attr(get_search_query()); ?>">
                <button class="site-search__button" type="submit"><?php esc_html_e('Suchen', 'gfgf-v3'); ?></button>
            </form>
            <a class="button button--primary site-header__join" href="<?php echo esc_url(gfgf_v3_page_url('mitgliedschaft')); ?>">
                <?php esc_html_e('Mitglied werden', 'gfgf-v3'); ?>
            </a>
            <button
                class="icon-button site-header__menu"
                type="button"
                aria-label="<?php esc_attr_e('Menü öffnen', 'gfgf-v3'); ?>"
                aria-controls="primary-navigation"
                aria-expanded="false"
                data-open-label="<?php esc_attr_e('Menü öffnen', 'gfgf-v3'); ?>"
                data-close-label="<?php esc_attr_e('Menü schließen', 'gfgf-v3'); ?>"
            >
                <span class="site-header__menu-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</header>
<main id="content" class="site-main">
