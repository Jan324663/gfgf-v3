</main>
<footer class="site-footer">
    <div class="site-footer__inner">
        <?php
        wp_nav_menu([
            'theme_location' => 'footer',
            'container'      => false,
            'fallback_cb'    => false,
        ]);
        ?>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>

