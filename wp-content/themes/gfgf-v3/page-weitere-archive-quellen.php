<?php
/**
 * Template Name: Weitere Archive & Quellen
 * Template Post Type: page
 *
 * Layout shell for the editable Gutenberg archive sources page.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

get_header();
?>
<article class="archive-sources">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <div class="archive-sources__content">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</article>
<?php
get_footer();
