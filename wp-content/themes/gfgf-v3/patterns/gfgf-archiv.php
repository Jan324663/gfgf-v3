<?php
/**
 * Title: GFGF Archiv – Übersicht
 * Slug: gfgf-v3/gfgf-archiv
 * Categories: gfgf-pages
 * Description: Vollständige, redaktionell bearbeitbare Übersichtsseite des GFGF-Archivs.
 * Keywords: archiv, übersicht, gfgf
 * Inserter: true
 */

defined('ABSPATH') || exit;

$archive_url = gfgf_v3_page_url('archiv-besuchen');
$documents_url = gfgf_v3_page_url('schaltplanservice');
$sources_url = gfgf_v3_page_url('weitere-archive-quellen');
$contact_url = gfgf_v3_page_url('kontakt');
$archive_image_url = trailingslashit(get_template_directory_uri()) . 'assets/images/archive/';
?>
<!-- wp:group {"tagName":"section","className":"archive-landing__intro"} -->
<section class="wp-block-group archive-landing__intro"><!-- wp:group {"className":"archive-landing__container archive-landing__intro-inner archive-landing__hero-grid"} -->
<div class="wp-block-group archive-landing__container archive-landing__intro-inner archive-landing__hero-grid"><!-- wp:group {"className":"archive-landing__intro-copy"} -->
<div class="wp-block-group archive-landing__intro-copy"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Das GFGF-Archiv</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Im GFGF-Archiv in Hainichen bewahren wir historische Unterlagen zur Rundfunk- und Unterhaltungselektronik: Schaltpläne, Serviceunterlagen, Bedienungsanleitungen, Prospekte, Kataloge und Fachliteratur.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"archive-landing__hero-media"} -->
<figure class="wp-block-image size-full archive-landing__hero-media"><img src="<?php echo esc_url($archive_image_url . 'archive-library.jpg'); ?>" alt="Bücherregale in einem Raum des GFGF-Archivs in Hainichen" /></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-landing__container archive-landing__body"} -->
<div class="wp-block-group archive-landing__container archive-landing__body"><!-- wp:group {"tagName":"section","className":"archive-teasers"} -->
<section class="wp-block-group archive-teasers"><!-- wp:group {"tagName":"article","className":"archive-teaser"} -->
<article class="wp-block-group archive-teaser"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"archive-teaser__media"} -->
<figure class="wp-block-image size-full archive-teaser__media"><img src="<?php echo esc_url($archive_image_url . 'archive-building.jpg'); ?>" alt="Außenansicht des GFGF-Archivgebäudes in Hainichen" /></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"archive-teaser__content"} -->
<div class="wp-block-group archive-teaser__content"><!-- wp:heading -->
<h2 class="wp-block-heading">Das GFGF-Archiv</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Unser Archiv in Hainichen bewahrt umfangreiche historische Unterlagen zur Rundfunk- und Unterhaltungselektronik. Lernen Sie die Sammlung kennen oder informieren Sie sich über einen Besuch vor Ort.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-teaser__button"} -->
<div class="wp-block-button archive-teaser__button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($archive_url); ?>">Archiv entdecken</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></article>
<!-- /wp:group -->

<!-- wp:group {"tagName":"article","className":"archive-teaser"} -->
<article class="wp-block-group archive-teaser"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"archive-teaser__media"} -->
<figure class="wp-block-image size-full archive-teaser__media"><img src="<?php echo esc_url($archive_image_url . 'archive-service-files.jpg'); ?>" alt="Regale mit erschlossenen Serviceunterlagen im GFGF-Archiv" /></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"archive-teaser__content"} -->
<div class="wp-block-group archive-teaser__content"><!-- wp:heading -->
<h2 class="wp-block-heading">Dokumente suchen</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Durchsuchen Sie unseren Katalog nach Schaltplänen, Serviceunterlagen, Bedienungsanleitungen und weiteren historischen Dokumenten. Gefundene Unterlagen können anschließend über den Schaltplanservice angefragt werden.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-teaser__button"} -->
<div class="wp-block-button archive-teaser__button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($documents_url); ?>">Dokument suchen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></article>
<!-- /wp:group -->

<!-- wp:group {"tagName":"article","className":"archive-teaser"} -->
<article class="wp-block-group archive-teaser"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"archive-teaser__media"} -->
<figure class="wp-block-image size-full archive-teaser__media"><img src="<?php echo esc_url($archive_image_url . 'archive-periodicals.jpg'); ?>" alt="Historische Zeitschriftenbestände im GFGF-Archiv" /></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"archive-teaser__content"} -->
<div class="wp-block-group archive-teaser__content"><!-- wp:heading -->
<h2 class="wp-block-heading">Weitere Archive &amp; Quellen</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Weitere Vereine, Museen, Archive und Online-Angebote rund um historische Rundfunktechnik und technische Dokumentation.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-teaser__button"} -->
<div class="wp-block-button archive-teaser__button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($sources_url); ?>">Weitere Quellen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></article>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"aside","className":"archive-offer"} -->
<aside class="wp-block-group archive-offer"><!-- wp:group {"className":"archive-offer__content"} -->
<div class="wp-block-group archive-offer__content"><!-- wp:heading -->
<h2 class="wp-block-heading">Unterlagen für unser Archiv?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Helfen Sie mit, Technikgeschichte zu bewahren. Wenn Sie historische Schaltpläne, Serviceunterlagen, Bedienungsanleitungen, Kataloge, Prospekte oder andere technische Dokumente besitzen, nehmen Sie gern Kontakt mit uns auf.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-offer__button"} -->
<div class="wp-block-button archive-offer__button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($contact_url); ?>">Unterlagen anbieten</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></aside>
<!-- /wp:group --></div>
<!-- /wp:group -->
