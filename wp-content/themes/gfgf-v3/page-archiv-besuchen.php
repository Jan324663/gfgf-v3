<?php
/**
 * Template Name: GFGF-Archiv in Hainichen
 * Template Post Type: page
 *
 * Layout shell and dialog component for the editable Gutenberg archive page.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

get_header();
?>
<article class="archive-detail">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <div class="archive-detail__content">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <dialog
        id="archive-collection-dialog"
        class="archive-collection-dialog"
        aria-labelledby="archive-dialog-title"
        aria-describedby="archive-dialog-text"
    >
        <div class="archive-collection-dialog__surface">
            <button
                type="button"
                class="archive-collection-dialog__close"
                aria-label="<?php esc_attr_e('Detailfenster schließen', 'gfgf-v3'); ?>"
                autofocus
            ><span aria-hidden="true">&times;</span></button>
            <h2 id="archive-dialog-title"><?php esc_html_e('Archivbestand', 'gfgf-v3'); ?></h2>
            <p id="archive-dialog-text"></p>
            <a class="archive-collection-dialog__download" href="#" target="_blank" rel="noopener" hidden>
                <span class="archive-collection-dialog__download-label"></span>
                <span class="screen-reader-text"><?php esc_html_e(' (PDF öffnet in einem neuen Tab)', 'gfgf-v3'); ?></span>
            </a>
        </div>
    </dialog>
</article>
<?php
get_footer();
