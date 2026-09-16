<?php
/**
 * Template Name: Dokumente suchen
 * Template Post Type: page
 *
 * Layout shell for the editable Gutenberg document search page.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

get_header();
$request_open = isset($_GET['doc_idx'])
    && '' !== trim(sanitize_text_field(wp_unslash((string) $_GET['doc_idx'])));
?>
<article class="service-docs-page<?php echo $request_open ? ' service-docs-page--request-open' : ''; ?>">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <div class="service-docs-page__content">
                <?php the_content(); ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</article>
<?php
get_footer();
