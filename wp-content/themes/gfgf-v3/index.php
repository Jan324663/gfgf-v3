<?php
/**
 * Default template.
 */

defined('ABSPATH') || exit;

get_header();
?>
<div class="content-area">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class(); ?>>
                <h1><?php the_title(); ?></h1>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <h1><?php esc_html_e('Keine Inhalte gefunden', 'gfgf-v3'); ?></h1>
    <?php endif; ?>
</div>
<?php
get_footer();

