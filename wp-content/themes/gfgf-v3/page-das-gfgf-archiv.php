<?php
/**
 * Template Name: Archiv-Startseite
 * Template Post Type: page
 *
 * Landing page for the GFGF archive.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

/*
 * Keep the destinations in one place so they can be replaced with the final
 * archive subpages without changing the page markup.
 */
$archive_links = apply_filters('gfgf_v3_archive_links', [
    'archive'   => home_url('/archiv-besuchen/'),
    'documents' => home_url('/schaltplanservice/'),
    'sources'   => home_url('/weitere-archive-quellen/'),
    'offer'     => home_url('/kontakt/'),
]);

get_header();
?>
<div class="archive-landing">
    <section class="archive-landing__intro" aria-labelledby="archive-page-title">
        <div class="archive-landing__container archive-landing__intro-inner">
            <h1 id="archive-page-title"><?php esc_html_e('Das GFGF-Archiv', 'gfgf-v3'); ?></h1>
            <p><?php esc_html_e('Im GFGF-Archiv in Hainichen bewahren wir umfangreiche historische Unterlagen zur Rundfunk- und Unterhaltungselektronik – von Schaltplänen und Serviceunterlagen über Bedienungsanleitungen bis zu Prospekten, Katalogen und Fachliteratur.', 'gfgf-v3'); ?></p>
        </div>
    </section>

    <div class="archive-landing__container">
        <section aria-labelledby="archive-teasers-title">
            <h2 id="archive-teasers-title" class="screen-reader-text"><?php esc_html_e('Bereiche des GFGF-Archivs', 'gfgf-v3'); ?></h2>
            <div class="archive-teasers">
                <article class="archive-teaser">
                    <div class="archive-teaser__content">
                        <h2><?php esc_html_e('Das GFGF-Archiv', 'gfgf-v3'); ?></h2>
                        <p><?php esc_html_e('Unser Archiv in Hainichen bewahrt umfangreiche historische Unterlagen zur Rundfunk- und Unterhaltungselektronik. Lernen Sie die Sammlung kennen oder informieren Sie sich über einen Besuch vor Ort.', 'gfgf-v3'); ?></p>
                    </div>
                    <a class="button button--primary archive-teaser__button" href="<?php echo esc_url($archive_links['archive']); ?>">
                        <?php esc_html_e('Archiv entdecken', 'gfgf-v3'); ?>
                    </a>
                </article>

                <article class="archive-teaser">
                    <div class="archive-teaser__content">
                        <h2><?php esc_html_e('Dokumente suchen', 'gfgf-v3'); ?></h2>
                        <p><?php esc_html_e('Durchsuchen Sie unseren Katalog nach Schaltplänen, Serviceunterlagen, Bedienungsanleitungen und weiteren historischen Dokumenten. Gefundene Unterlagen können anschließend über den Schaltplanservice angefragt werden.', 'gfgf-v3'); ?></p>
                    </div>
                    <a class="button button--primary archive-teaser__button" href="<?php echo esc_url($archive_links['documents']); ?>">
                        <?php esc_html_e('Dokument suchen', 'gfgf-v3'); ?>
                    </a>
                </article>

                <article class="archive-teaser">
                    <div class="archive-teaser__content">
                        <h2><?php esc_html_e('Weitere Archive & Quellen', 'gfgf-v3'); ?></h2>
                        <p><?php esc_html_e('Weitere Vereine, Museen, Archive und Online-Angebote rund um historische Rundfunktechnik und technische Dokumentation.', 'gfgf-v3'); ?></p>
                    </div>
                    <a class="button button--primary archive-teaser__button" href="<?php echo esc_url($archive_links['sources']); ?>">
                        <?php esc_html_e('Weitere Quellen', 'gfgf-v3'); ?>
                    </a>
                </article>
            </div>
        </section>

        <aside class="archive-offer" aria-labelledby="archive-offer-title">
            <div class="archive-offer__content">
                <h2 id="archive-offer-title"><?php esc_html_e('Unterlagen für unser Archiv?', 'gfgf-v3'); ?></h2>
                <p><?php esc_html_e('Sie besitzen alte Schaltpläne, Serviceunterlagen, Bedienungsanleitungen, Kataloge, Prospekte oder andere technische Dokumente und möchten diese erhalten wissen? Nehmen Sie gern Kontakt mit uns auf.', 'gfgf-v3'); ?></p>
            </div>
            <a class="button button--primary archive-offer__button" href="<?php echo esc_url($archive_links['offer']); ?>">
                <?php esc_html_e('Unterlagen anbieten', 'gfgf-v3'); ?>
            </a>
        </aside>
    </div>
</div>
<?php
get_footer();
