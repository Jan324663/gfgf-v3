</main>
<footer class="site-footer">
    <div class="site-footer__inner">
        <div class="site-footer__brand">
            <strong>GFGF e.V.</strong>
            <span>© <?php echo esc_html(gmdate('Y')); ?> Gesellschaft der Freunde der Geschichte des Funkwesens e.V. (GFGF)</span>
        </div>
        <nav class="site-footer__nav" aria-label="<?php esc_attr_e('Footernavigation', 'gfgf-v3'); ?>">
            <?php
            $footer_menu = wp_nav_menu([
                'theme_location' => 'footer',
                'container'      => false,
                'fallback_cb'    => false,
                'echo'           => false,
                'menu_class'     => 'site-footer__list',
            ]);

            if ($footer_menu) {
                echo $footer_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            } else {
                echo '<ul class="site-footer__list">';
                foreach (gfgf_v3_footer_nav_items() as $item) {
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
        <div class="site-footer__actions">
            <a class="icon-button icon-button--footer" href="<?php echo esc_url(home_url('/kontakt/')); ?>" aria-label="<?php esc_attr_e('Kontakt', 'gfgf-v3'); ?>">
                <span aria-hidden="true">@</span>
            </a>
            <a class="icon-button icon-button--footer" href="<?php echo esc_url(home_url('/spenden/')); ?>" aria-label="<?php esc_attr_e('Teilen', 'gfgf-v3'); ?>">
                <span aria-hidden="true">↗</span>
            </a>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
