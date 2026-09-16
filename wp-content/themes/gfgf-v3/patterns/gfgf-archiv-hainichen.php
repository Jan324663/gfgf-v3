<?php
/**
 * Title: GFGF Archiv – Hainichen
 * Slug: gfgf-v3/gfgf-archiv-hainichen
 * Categories: gfgf-pages
 * Description: Vollständige, redaktionell bearbeitbare Präsentation des GFGF-Archivs in Hainichen.
 * Keywords: archiv, hainichen, sammlung, gfgf
 * Inserter: true
 */

defined('ABSPATH') || exit;

$theme_uri = get_template_directory_uri();
$archive_email = sanitize_email((string) apply_filters('gfgf_v3_archive_email', 'archiv@gfgf.org'));
$visit_url = 'mailto:' . $archive_email . '?subject=' . rawurlencode('Besuch im GFGF-Archiv');
$donation_url = 'mailto:' . $archive_email . '?subject=' . rawurlencode('Unterlagen für das GFGF-Archiv');
$tour_url = 'https://www.pixtours.de/vr-gfgf/';
$directions_url = 'https://www.google.com/maps/dir//Hospitalstra%C3%9Fe%2B1%2B09661%2BHainichen/';
$download_uri = $theme_uri . '/assets/downloads/archive/';
$download_path = get_template_directory() . '/assets/downloads/archive/';

$archive_stats = [
    ['value' => 'ca. 200 m²', 'label' => 'Archivfläche'],
    ['value' => '5.500', 'label' => 'Bücher'],
    ['value' => '3.300+', 'label' => 'Zeitschriftenjahrgänge'],
    ['value' => '1.000+', 'label' => 'Kataloge'],
    ['value' => '40.000+', 'label' => 'Serviceunterlagen'],
];

$archive_collections = [
    [
        'title' => 'Schaltpläne & Serviceunterlagen',
        'summary' => 'Historische technische Unterlagen zu Radios, Fernsehgeräten und weiterer Unterhaltungselektronik.',
        'details' => 'Im Archiv lagern gut 40.000 Serviceunterlagen zu hergestellten Geräten. Sie dokumentieren zahlreiche historische Geräte der Rundfunk- und Unterhaltungselektronik. Die Unterlagen gehören zu den systematisch erschlossenen Beständen und können gezielt recherchiert werden.',
        'download' => 'serviceunterlagen.pdf',
        'download_title' => 'Verzeichnis der Serviceunterlagen',
    ],
    [
        'title' => 'Bücher & Fachliteratur',
        'summary' => 'Fachbücher zur Entwicklung, Technik und Geschichte des Rundfunks und seiner Geräte.',
        'details' => 'Auf etwas mehr als 30 m² befinden sich rund 5.500 Bücher, die für eine schnelle Suche nach den Nachnamen der Autoren sortiert sind. Die Themen reichen von Bauteilen und Geräten bis zu geschichtlichen Zusammenhängen. Etwa zehn Prozent der Bücher sind englischsprachig.',
        'download' => 'buecher.pdf',
        'download_title' => 'Verzeichnis der Bücher',
    ],
    [
        'title' => 'Zeitschriften',
        'summary' => 'Historische Fachzeitschriften und vollständige Zeitschriftenjahrgänge aus vielen Jahrzehnten.',
        'details' => 'Der Bestand umfasst mehr als 3.300 Zeitschriftenjahrgänge und reicht von historischen Ausgaben bis zu jüngeren Jahrgängen. Dazu gehören unter anderem Funkamateur, Radio-Mentor, RFE, Funktechnik und Funkschau. Ergänzt wird die Sammlung durch Firmenzeitschriften und klassische Programmzeitschriften zu Rundfunk und Fernsehen.',
        'download' => 'zeitschriften.pdf',
        'download_title' => 'Verzeichnis der Zeitschriften',
    ],
    [
        'title' => 'Kataloge & Firmenschriften',
        'summary' => 'Herstellerkataloge, Prospekte, Werbematerialien und Dokumentationen bedeutender Firmen.',
        'details' => 'Mehr als 1.000 Kataloge dokumentieren Herstellerwerbung und Firmengeschichte. Hinzu kommen Entwicklungsberichte von Telefunken und WF Berlin sowie Bauunterlagen zu Sendeanlagen. Auch die Blaubücher des DDR-Rundfunks gehören zu diesem Bestand.',
        'download' => 'firmenschriften.pdf',
        'download_title' => 'Verzeichnis der Firmenschriften',
    ],
    [
        'title' => 'Bedienungsanleitungen',
        'summary' => 'Historische Bedienungs- und Gebrauchsanleitungen für Geräte der Heimelektronik.',
        'details' => 'Der Bestand enthält rund 2.000 Bedienungs- und Gebrauchsanleitungen. Sie erschließen zahlreiche Geräte aus der Geschichte der Rundfunk- und Unterhaltungselektronik.',
        'download' => 'bedienungsanleitungen.pdf',
        'download_title' => 'Verzeichnis der Bedienungsanleitungen',
    ],
    [
        'title' => 'Fotos & besondere Sammlungen',
        'summary' => 'Fotografien, Entwicklungsberichte und weitere besondere technik- und firmengeschichtliche Bestände.',
        'details' => 'Zum Archiv gehören tausende historische Fotografien und umfangreiche Mikrofiche-Bestände. Weitere Unterlagen behandeln militärische und kommerzielle Technik. Hinzu kommen spezielle technik- und firmengeschichtliche Sammlungen.',
        'download' => 'fotografien.pdf',
        'download_title' => 'Liste der Fotografien',
    ],
];

$timeline = [
    ['date' => 'Späte 1970er Jahre', 'title' => 'Die Ursprünge der Sammlung', 'text' => 'In den frühen Heften der Funkgeschichte veröffentlichte Vereinsgründer Karl Neumann Literaturverzeichnisse und begründete in seinem privaten Umfeld das Funkhistorische Archiv Gruiten.'],
    ['date' => '1991', 'title' => 'Ein vereinseigenes Archiv entsteht', 'text' => 'Mit dem Ankauf einer umfangreichen Sammlung seltener Dokumente nahm die Idee eines gemeinsamen GFGF-Archivs konkrete Gestalt an.'],
    ['date' => '1991–2005', 'title' => 'Aufbau und Erweiterung', 'text' => 'Ein GFGF-Mitglied in Ramsen stellte Räume und Arbeitskraft bereit. Durch Ankäufe und kontinuierliche Erschließung wuchs der Bestand weiter.'],
    ['date' => 'Seit 2005', 'title' => 'Eine neue Heimat in Hainichen', 'text' => 'Für die umfangreichen Bestände entwickelte der Verein ein neues Konzept mit eigenen, technisch ausgestatteten Räumen. Das Archiv fand seinen heutigen Standort im sächsischen Hainichen.'],
];

$download_groups = [
    'Bestandsverzeichnisse' => [
        ['buecher.pdf', 'Verzeichnis der Bücher'],
        ['bedienungsanleitungen.pdf', 'Bedienungsanleitungen für Geräte der Heimelektronik'],
        ['firmenschriften.pdf', 'Verzeichnis der Firmenschriften'],
        ['fotografien.pdf', 'Liste der Fotografien'],
        ['kataloge.pdf', 'Verzeichnis der Kataloge'],
        ['serviceunterlagen.pdf', 'Serviceunterlagen für 40.300 Geräte'],
        ['zeitschriften.pdf', 'Verzeichnis der Zeitschriften'],
    ],
    'Thematische Bestände' => [
        ['chroniken-firmengeschichten.pdf', 'Chroniken und Teile zu Firmengeschichten'],
        ['elektrotechnische-fabriken.pdf', 'Elektrotechnische Fabriken und Elektrizitätswerke'],
        ['geschichte-des-funkwesens.pdf', 'Unterlagen zur Geschichte des Funkwesens'],
        ['militaerische-kommerzielle-geraete.pdf', 'Militärische und kommerzielle Geräte'],
        ['messtechnische-geraete.pdf', 'Unterlagen zu messtechnischen Geräten'],
        ['navigation-ortung-funkueberwachung.pdf', 'Navigation, Ortung und Funküberwachung'],
        ['aeg-telefunken.pdf', 'Unterlagen der Firma AEG/Telefunken'],
        ['wf-entwicklungsberichte.pdf', 'Entwicklungsberichte des Werks für Fernsehelektronik Berlin'],
    ],
    'Weitere Informationen' => [
        ['nutzungsordnung.pdf', 'Nutzerordnung des GFGF-Archivs'],
        ['funkgeschichte-inhaltsverzeichnis.pdf', 'Gesamtinhaltsverzeichnis der Funkgeschichte, Heft 1–225'],
        ['radio-news-inhaltsverzeichnis.pdf', 'Inhaltsverzeichnis der Radio News, 1930–1936'],
    ],
];
?>
<!-- wp:group {"tagName":"header","className":"archive-detail__hero"} -->
<header class="wp-block-group archive-detail__hero"><!-- wp:group {"className":"archive-detail__container archive-detail__hero-grid"} -->
<div class="wp-block-group archive-detail__container archive-detail__hero-grid"><!-- wp:group {"className":"archive-detail__hero-copy"} -->
<div class="wp-block-group archive-detail__hero-copy"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Das GFGF-Archiv in Hainichen</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"archive-detail__tagline"} -->
<p class="archive-detail__tagline">Technikgeschichte bewahren, dokumentieren und zugänglich machen.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Im GFGF-Archiv in Hainichen bewahren wir umfangreiche historische Unterlagen zur Geschichte der Rundfunk- und Unterhaltungselektronik. Dazu gehören Schaltpläne, Serviceunterlagen, Fachbücher, Zeitschriften, Kataloge, Prospekte, Bedienungsanleitungen und zahlreiche weitere Dokumente.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-detail__hero-button"} -->
<div class="wp-block-button archive-detail__hero-button"><a class="wp-block-button__link wp-element-button" href="#archiv-besuch">Archiv besuchen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"archive-detail__hero-media"} -->
<figure class="wp-block-image size-full archive-detail__hero-media"><img src="<?php echo esc_url($theme_uri . '/assets/images/archive/archive-building.jpg'); ?>" alt="Außenansicht des GFGF-Archivgebäudes in der Hospitalstraße in Hainichen"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></header>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-detail__section archive-detail__stats-section"} -->
<section class="wp-block-group archive-detail__section archive-detail__stats-section"><!-- wp:group {"className":"archive-detail__container"} -->
<div class="wp-block-group archive-detail__container"><!-- wp:group {"className":"archive-detail__section-heading archive-detail__section-heading--compact"} -->
<div class="wp-block-group archive-detail__section-heading archive-detail__section-heading--compact"><!-- wp:heading -->
<h2 class="wp-block-heading">Das Archiv in Zahlen</h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-stats"} -->
<div class="wp-block-group archive-stats"><?php foreach ($archive_stats as $stat) : ?><!-- wp:group {"className":"archive-stat"} -->
<div class="wp-block-group archive-stat"><!-- wp:paragraph {"className":"archive-stat__label"} -->
<p class="archive-stat__label"><?php echo esc_html($stat['label']); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"archive-stat__value"} -->
<p class="archive-stat__value"><?php echo esc_html($stat['value']); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><?php endforeach; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-detail__section archive-tour"} -->
<section class="wp-block-group archive-detail__section archive-tour"><!-- wp:group {"className":"archive-detail__container archive-tour__grid"} -->
<div class="wp-block-group archive-detail__container archive-tour__grid"><!-- wp:group {"className":"archive-tour__media"} -->
<div class="wp-block-group archive-tour__media"><!-- wp:image {"sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url($theme_uri . '/assets/images/archive/archive-library.jpg'); ?>" alt="Bücherregale in einem Raum des GFGF-Archivs"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"archive-tour__badge"} -->
<p class="archive-tour__badge">360°-Rundgang</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-tour__content"} -->
<div class="wp-block-group archive-tour__content"><!-- wp:heading -->
<h2 class="wp-block-heading">Virtuell durch unser Archiv</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"archive-detail__lead"} -->
<p class="archive-detail__lead">Werfen Sie schon vor Ihrem Besuch einen Blick in unsere Archivräume.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Der vorhandene Rundgang führt Sie durch die Räume und vermittelt einen ersten Eindruck von der Vielfalt der Bestände.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-tour__button"} -->
<div class="wp-block-button archive-tour__button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($tour_url); ?>" target="_blank" rel="noreferrer noopener">Virtuellen Rundgang starten</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:paragraph {"className":"archive-tour__privacy"} -->
<p class="archive-tour__privacy">Externer Inhalt von Pixtours: Erst mit dem Klick wird eine Verbindung zum Anbieter hergestellt.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-detail__section archive-collections-section"} -->
<section class="wp-block-group archive-detail__section archive-collections-section"><!-- wp:group {"className":"archive-detail__container"} -->
<div class="wp-block-group archive-detail__container"><!-- wp:group {"className":"archive-detail__section-heading"} -->
<div class="wp-block-group archive-detail__section-heading"><!-- wp:heading -->
<h2 class="wp-block-heading">Was Sie bei uns finden</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Unsere Bestände dokumentieren Technik-, Firmen- und Mediengeschichte aus vielen Jahrzehnten.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-collections"} -->
<div class="wp-block-group archive-collections"><!-- wp:group {"className":"archive-collections__row"} -->
<div class="wp-block-group archive-collections__row"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"archive-collections__media"} -->
<figure class="wp-block-image size-full archive-collections__media"><img src="<?php echo esc_url($theme_uri . '/assets/images/archive/archive-service-files.jpg'); ?>" alt="Regale mit geordneten Serviceunterlagen im GFGF-Archiv"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"archive-collections__list"} -->
<div class="wp-block-group archive-collections__list"><?php foreach (array_slice($archive_collections, 0, 3) as $collection) : ?><!-- wp:group {"tagName":"article","className":"archive-collection"} -->
<article class="wp-block-group archive-collection"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html($collection['title']); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html($collection['summary']); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"archive-collection__action"} -->
<div class="wp-block-buttons archive-collection__action"><!-- wp:button {"className":"archive-collection__more"} -->
<div class="wp-block-button archive-collection__more"><a class="wp-block-button__link wp-element-button" href="#archive-collection-dialog">Mehr erfahren</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"className":"archive-collection__dialog-source"} -->
<div class="wp-block-group archive-collection__dialog-source"><!-- wp:paragraph {"className":"archive-collection__dialog-text"} -->
<p class="archive-collection__dialog-text"><?php echo esc_html($collection['details']); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a class="archive-collection__dialog-download" href="<?php echo esc_url($download_uri . $collection['download']); ?>" target="_blank" rel="noreferrer noopener"><?php echo esc_html($collection['download_title']); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></article>
<!-- /wp:group --><?php endforeach; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-collections__row archive-collections__row--reverse"} -->
<div class="wp-block-group archive-collections__row archive-collections__row--reverse"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"archive-collections__media"} -->
<figure class="wp-block-image size-full archive-collections__media"><img src="<?php echo esc_url($theme_uri . '/assets/images/archive/archive-periodicals.jpg'); ?>" alt="Regale mit gebundenen Zeitschriftenjahrgängen im GFGF-Archiv"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"archive-collections__list"} -->
<div class="wp-block-group archive-collections__list"><?php foreach (array_slice($archive_collections, 3) as $collection) : ?><!-- wp:group {"tagName":"article","className":"archive-collection"} -->
<article class="wp-block-group archive-collection"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html($collection['title']); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html($collection['summary']); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":"archive-collection__action"} -->
<div class="wp-block-buttons archive-collection__action"><!-- wp:button {"className":"archive-collection__more"} -->
<div class="wp-block-button archive-collection__more"><a class="wp-block-button__link wp-element-button" href="#archive-collection-dialog">Mehr erfahren</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"className":"archive-collection__dialog-source"} -->
<div class="wp-block-group archive-collection__dialog-source"><!-- wp:paragraph {"className":"archive-collection__dialog-text"} -->
<p class="archive-collection__dialog-text"><?php echo esc_html($collection['details']); ?></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><a class="archive-collection__dialog-download" href="<?php echo esc_url($download_uri . $collection['download']); ?>" target="_blank" rel="noreferrer noopener"><?php echo esc_html($collection['download_title']); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></article>
<!-- /wp:group --><?php endforeach; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","anchor":"archiv-besuch","className":"archive-detail__section archive-visit"} -->
<section id="archiv-besuch" class="wp-block-group archive-detail__section archive-visit"><!-- wp:group {"className":"archive-detail__container archive-visit__panel"} -->
<div class="wp-block-group archive-detail__container archive-visit__panel"><!-- wp:group {"className":"archive-visit__content"} -->
<div class="wp-block-group archive-visit__content"><!-- wp:heading -->
<h2 class="wp-block-heading">Besuchen Sie unser Archiv</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"archive-detail__lead"} -->
<p class="archive-detail__lead">Das GFGF-Archiv steht funkhistorisch Interessierten nach vorheriger Absprache offen.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>Wenn Sie selbst recherchieren, historische Unterlagen einsehen oder unser Archiv kennenlernen möchten, nehmen Sie gerne Kontakt mit uns auf.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-visit__button"} -->
<div class="wp-block-button archive-visit__button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($visit_url); ?>">Besuch anfragen</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-visit__address"} -->
<div class="wp-block-group archive-visit__address"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Archiv der GFGF in Hainichen</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"archive-visit__address-lines"} -->
<p class="archive-visit__address-lines">GFGF<br>Hospitalstraße 1<br>09661 Hainichen</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"archive-visit__map"} -->
<p class="archive-visit__map"><a href="<?php echo esc_url($directions_url); ?>" target="_blank" rel="noreferrer noopener">Anfahrt in Google Maps</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-detail__section archive-history"} -->
<section class="wp-block-group archive-detail__section archive-history"><!-- wp:group {"className":"archive-detail__container"} -->
<div class="wp-block-group archive-detail__container"><!-- wp:group {"className":"archive-detail__section-heading"} -->
<div class="wp-block-group archive-detail__section-heading"><!-- wp:heading -->
<h2 class="wp-block-heading">Wie alles begann</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Aus privaten Sammlungen und gemeinsamer Vereinsarbeit entstand ein Archiv, das heute in Hainichen zugänglich ist.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-history__grid"} -->
<div class="wp-block-group archive-history__grid"><!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"archive-history__portrait"} -->
<figure class="wp-block-image size-full archive-history__portrait"><img src="<?php echo esc_url($theme_uri . '/assets/images/archive/karl-neumann.jpg'); ?>" alt="Karl Neumann, Gründer des Funkhistorischen Archivs Gruiten"/><figcaption class="wp-element-caption">Karl Neumann</figcaption></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"archive-timeline"} -->
<div class="wp-block-group archive-timeline"><?php foreach ($timeline as $item) : ?><!-- wp:group {"className":"archive-timeline__item"} -->
<div class="wp-block-group archive-timeline__item"><!-- wp:paragraph {"className":"archive-timeline__date"} -->
<p class="archive-timeline__date"><?php echo esc_html($item['date']); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html($item['title']); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html($item['text']); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --><?php endforeach; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-detail__section archive-detail__section--compact"} -->
<section class="wp-block-group archive-detail__section archive-detail__section--compact"><!-- wp:group {"className":"archive-detail__container"} -->
<div class="wp-block-group archive-detail__container"><!-- wp:group {"tagName":"aside","className":"archive-offer archive-detail__donation"} -->
<aside class="wp-block-group archive-offer archive-detail__donation"><!-- wp:group {"className":"archive-offer__content"} -->
<div class="wp-block-group archive-offer__content"><!-- wp:heading -->
<h2 class="wp-block-heading">Unterlagen für unser Archiv?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Sie besitzen historische Schaltpläne, Serviceunterlagen, Bedienungsanleitungen, Kataloge, Prospekte oder andere technische Dokumente? Nehmen Sie gerne Kontakt mit uns auf.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-offer__button"} -->
<div class="wp-block-button archive-offer__button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($donation_url); ?>">Unterlagen anbieten</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></aside>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-detail__section archive-downloads"} -->
<section class="wp-block-group archive-detail__section archive-downloads"><!-- wp:group {"className":"archive-detail__container"} -->
<div class="wp-block-group archive-detail__container"><!-- wp:group {"className":"archive-detail__section-heading"} -->
<div class="wp-block-group archive-detail__section-heading"><!-- wp:heading -->
<h2 class="wp-block-heading">Bestandsverzeichnisse und weitere Informationen</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Die vorhandenen Verzeichnisse geben einen detaillierten Überblick über Bestände und Themenbereiche des Archivs.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-downloads__grid"} -->
<div class="wp-block-group archive-downloads__grid"><?php foreach ($download_groups as $group_title => $downloads) : ?><!-- wp:group {"className":"archive-download-group"} -->
<div class="wp-block-group archive-download-group"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html($group_title); ?></h3>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><?php foreach ($downloads as $download) : ?><?php
    $file_size = file_exists($download_path . $download[0]) ? size_format((int) filesize($download_path . $download[0]), 1) : '';
?><!-- wp:list-item -->
<li><a href="<?php echo esc_url($download_uri . $download[0]); ?>" target="_blank" rel="noreferrer noopener"><?php echo esc_html($download[1]); ?><br><small>PDF<?php echo $file_size ? ' · ' . esc_html($file_size) : ''; ?></small></a></li>
<!-- /wp:list-item --><?php endforeach; ?></ul>
<!-- /wp:list --></div>
<!-- /wp:group --><?php endforeach; ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
