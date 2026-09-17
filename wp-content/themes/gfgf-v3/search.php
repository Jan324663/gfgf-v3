<?php
/**
 * Search results template.
 */

defined('ABSPATH') || exit;

get_header();

global $wp_query;

$search_query = get_search_query(false);
$result_count = (int) $wp_query->found_posts;
$result_label = sprintf(
    _n('%s Treffer', '%s Treffer', $result_count, 'gfgf-v3'),
    number_format_i18n($result_count)
);
?>
<div class="search-page">
    <header class="search-page__header">
        <div class="search-page__container">
            <p class="search-page__eyebrow"><?php esc_html_e('Website-Suche', 'gfgf-v3'); ?></p>
            <h1>
                <?php
                printf(
                    /* translators: %s: Search query. */
                    esc_html__('Suchergebnisse für „%s“', 'gfgf-v3'),
                    esc_html($search_query)
                );
                ?>
            </h1>
            <p class="search-page__summary"><?php echo esc_html($result_label); ?></p>
        </div>
    </header>

    <div class="search-page__container search-page__body">
        <?php if (have_posts()) : ?>
            <div class="search-results__grid">
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $post_type = get_post_type_object(get_post_type());
                    $post_type_label = $post_type instanceof WP_Post_Type
                        ? $post_type->labels->singular_name
                        : __('Inhalt', 'gfgf-v3');
                    $result_title = get_the_title();
                    $result_excerpt = wp_strip_all_tags(get_the_excerpt());

                    if ('' !== $result_title) {
                        $result_excerpt = preg_replace(
                            '/^' . preg_quote($result_title, '/') . '\\s*/iu',
                            '',
                            $result_excerpt,
                            1
                        ) ?? $result_excerpt;
                    }

                    $result_excerpt = wp_trim_words(
                        $result_excerpt,
                        34,
                        ' …'
                    );
                    ?>
                    <article <?php post_class('search-results__item'); ?>>
                        <div class="search-results__content">
                            <p class="search-results__type"><?php echo esc_html($post_type_label); ?></p>
                            <h2 class="search-results__title">
                                <a href="<?php the_permalink(); ?>"><?php echo esc_html($result_title); ?></a>
                            </h2>
                            <?php if ('' !== $result_excerpt) : ?>
                                <p class="search-results__excerpt"><?php echo esc_html($result_excerpt); ?></p>
                            <?php endif; ?>
                        </div>
                        <a class="search-results__link" href="<?php the_permalink(); ?>">
                            <?php esc_html_e('Seite öffnen', 'gfgf-v3'); ?>
                            <span aria-hidden="true">→</span>
                            <span class="screen-reader-text">: <?php echo esc_html($result_title); ?></span>
                        </a>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php if ($wp_query->max_num_pages > 1) : ?>
                <div class="search-pagination">
                    <?php
                    the_posts_pagination([
                        'mid_size'           => 1,
                        'prev_text'          => __('← Zurück', 'gfgf-v3'),
                        'next_text'          => __('Weiter →', 'gfgf-v3'),
                        'screen_reader_text' => __('Seitennavigation der Suchergebnisse', 'gfgf-v3'),
                    ]);
                    ?>
                </div>
            <?php endif; ?>
        <?php else : ?>
            <section class="search-empty" aria-labelledby="search-empty-title">
                <h2 id="search-empty-title"><?php esc_html_e('Keine passenden Inhalte gefunden', 'gfgf-v3'); ?></h2>
                <p><?php esc_html_e('Versuchen Sie es mit einem kürzeren oder allgemeineren Suchbegriff.', 'gfgf-v3'); ?></p>
                <form class="search-empty__form" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                    <label for="search-empty-field"><?php esc_html_e('Neuer Suchbegriff', 'gfgf-v3'); ?></label>
                    <div class="search-empty__controls">
                        <input id="search-empty-field" type="search" name="s" value="<?php echo esc_attr($search_query); ?>">
                        <button type="submit"><?php esc_html_e('Suchen', 'gfgf-v3'); ?></button>
                    </div>
                </form>
            </section>
        <?php endif; ?>
    </div>
</div>
<?php
get_footer();
