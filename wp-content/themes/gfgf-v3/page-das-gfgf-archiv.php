<?php
/**
 * Template Name: Archiv-Startseite
 * Template Post Type: page
 *
 * Layout shell for the editable Gutenberg archive landing page.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

get_header();
?>
<article class="archive-landing">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <div class="archive-landing__content">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</article>
<?php
get_footer();
