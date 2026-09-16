<?php
/**
 * Title: GFGF Archiv – Weitere Archive & Quellen
 * Slug: gfgf-v3/gfgf-archiv-quellen
 * Categories: gfgf-pages
 * Description: Kuratierte, redaktionell bearbeitbare Quellen- und Museumsseite des GFGF-Archivs.
 * Keywords: archiv, quellen, museen, links
 * Inserter: true
 */

defined('ABSPATH') || exit;

$contact_url = gfgf_v3_page_url('kontakt');
$archive_image_url = trailingslashit(get_template_directory_uri()) . 'assets/images/archive/';

$online_sources = [
    [
        'title' => 'Radiomuseum.org',
        'meta' => 'Internationale Datenbank',
        'description' => 'Umfangreiche internationale Datenbank zu historischen Radios, Geräten, Röhren, Schaltplänen, Bildern sowie Hersteller- und Modelldaten.',
        'url' => 'https://www.radiomuseum.org/',
    ],
    [
        'title' => 'GFGF Online-Archiv',
        'meta' => 'Bewahrte historische Webseiten',
        'description' => 'Das Online-Archiv der GFGF macht archivierte Versionen funkhistorischer Webseiten langfristig zugänglich. Die Inhalte bilden jeweils einen historischen Stand ab.',
        'url' => 'https://onlinearchiv.gfgf.org/',
    ],
    [
        'title' => 'Radiomuseum.info',
        'meta' => 'Sammlung Robert Speckmaier',
        'description' => 'Erhaltene Seiten des früheren Radiomuseums ROL mit Beiträgen, Bildern und Materialien des Radiosammlers Robert Speckmaier.',
        'url' => 'https://radiomuseum.info/',
    ],
    [
        'title' => 'Virtuelles Fernsehmuseum',
        'meta' => 'Hans Schuchter · scheida.at',
        'description' => 'Dokumentation zur Entwicklung des Fernsehens – von frühen Verfahren über die Nachkriegszeit und das DDR-Fernsehen bis zum PAL-Farbfernsehen.',
        'url' => 'http://www.scheida.at/scheida/televisionen.htm',
    ],
    [
        'title' => 'Wumpus Welt der Radios',
        'meta' => 'Radiotechnik und Funkgeschichte',
        'description' => 'Deutschsprachiges Nachschlageangebot zu historischen Rundfunkgeräten, Radiotechnik, Detektorempfängern und funkgeschichtlichen Themen.',
        'url' => 'https://www.welt-der-alten-radios.de/',
    ],
];

$museums = [
    [
        'title' => 'Radiomuseum Bocket',
        'meta' => 'Waldfeucht-Bocket',
        'description' => 'Privates Museum mit besonderem Schwerpunkt auf Loewe Opta und historischen Rundfunkgeräten.',
        'url' => 'https://www.radiomuseum-bocket.de/wiki/index.php?title=Hauptseite',
    ],
    [
        'title' => 'Radio-Museum Linsengericht',
        'meta' => 'Linsengericht',
        'description' => 'Museum und Verein zur Geschichte des Radios und Rundfunks mit einer umfangreichen historischen Gerätesammlung.',
        'url' => 'https://radio-museum.de/',
    ],
    [
        'title' => 'Museum Funkerberg',
        'meta' => 'Königs Wusterhausen',
        'description' => 'Historischer Sendestandort und Museum an einem zentralen Ort der deutschen Rundfunkgeschichte.',
        'url' => 'https://funkerberg.de/',
    ],
    [
        'title' => 'Radiomuseum Köln',
        'meta' => 'Köln',
        'description' => 'Von einer Fördergesellschaft getragene Sammlung zur Geschichte der Rundfunk- und Audiotechnik.',
        'url' => 'https://www.radiomuseum-koeln.de/',
    ],
    [
        'title' => 'Radio- und Telefonmuseum Wertingen',
        'meta' => 'Wertingen',
        'description' => 'Museum zur Entwicklung von Radio, Rundfunkempfang und Telefontechnik mit historischen Geräten aus mehreren Epochen.',
        'url' => 'https://www.radiomuseum-wertingen.de/',
    ],
];

$communities = [
    [
        'title' => 'Rádiógyűjtők Magyarországi Klubja (RMK)',
        'meta' => 'Ungarn',
        'description' => 'Ungarische Vereinigung für Sammler historischer Radiotechnik. Der Klub wurde in der bisherigen GFGF-Linkliste als Kooperationspartner geführt.',
        'url' => 'https://www.nosztalgiaradio.hu/',
    ],
    [
        'title' => 'Club der Radio- und Grammophon-Sammler (CRGS)',
        'meta' => 'Schweiz',
        'description' => 'Schweizer Sammlergemeinschaft für historische Radios, Grammophone und verwandte Technik.',
        'url' => 'https://www.crgs.ch/',
    ],
    [
        'title' => 'Radio-Bastler-Forum',
        'meta' => 'Online-Gemeinschaft',
        'description' => 'Aktive deutschsprachige Gemeinschaft für Fragen zu historischen Radios, Reparaturen, Restaurierungen und Messtechnik.',
        'url' => 'https://www.radio-bastler.de/forum/',
    ],
];

$special_sources = [
    [
        'title' => 'Jogis Röhrenbude',
        'meta' => 'Röhren und Restaurierung',
        'description' => 'Reparaturhilfen, technische Grundlagen, Bauanleitungen sowie umfangreiches Bild- und Datenmaterial zu Röhrenradios und Röhrenverstärkern.',
        'url' => 'https://www.jogis-roehrenbude.de/',
    ],
    [
        'title' => 'Röhrensammlung Udo Radtke',
        'meta' => 'Elektronenröhren',
        'description' => 'Bild- und Informationssammlung zu Elektronenröhren aus unterschiedlichen Zeiten, Ländern und Anwendungsgebieten.',
        'url' => 'https://www.tubecollection.de/',
    ],
    [
        'title' => 'Skalenscheiben & Rückwände',
        'meta' => 'Radiomuseum Bocket',
        'description' => 'Spezialbereich für Skalenscheiben, Rückwände und weitere Teile, die bei der Restaurierung historischer Geräte benötigt werden.',
        'url' => 'https://www.radiomuseum-bocket.de/wiki/index.php?title=Skalenscheiben_/_R%C3%BCckw%C3%A4nde_-_Dial_Faces_/_Back_Panels',
    ],
    [
        'title' => 'Radiotechnik-web.de',
        'meta' => 'Schaltpläne und Gerätetechnik',
        'description' => 'Fachinformationen und Unterlagen zu Radios, Röhrengeräten, Schaltungen und historischer Technik.',
        'url' => 'https://radiotechnik-web.de/',
    ],
    [
        'title' => 'DDR-Autoradioportal',
        'meta' => 'Historische Fahrzeugtechnik',
        'description' => 'Spezialangebot zu DDR-Autoradios mit Geräteinformationen sowie Hinweisen zu Reparatur und Restaurierung.',
        'url' => 'http://www.ddr-autoradio.de/',
    ],
];

$render_source_card = static function (array $source): void {
    ?>
<!-- wp:group {"className":"archive-source-card"} -->
<div class="wp-block-group archive-source-card"><!-- wp:paragraph {"className":"archive-source-card__meta"} -->
<p class="archive-source-card__meta"><?php echo esc_html($source['meta']); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading"><?php echo esc_html($source['title']); ?></h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p><?php echo esc_html($source['description']); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-source-card__button"} -->
<div class="wp-block-button archive-source-card__button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($source['url']); ?>" target="_blank" rel="noreferrer noopener">Website besuchen <span aria-hidden="true">↗</span><span class="screen-reader-text"> (öffnet in einem neuen Tab)</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
    <?php
};
?>
<!-- wp:group {"tagName":"header","className":"archive-sources__hero"} -->
<header class="wp-block-group archive-sources__hero"><!-- wp:group {"className":"archive-sources__container archive-sources__hero-grid"} -->
<div class="wp-block-group archive-sources__container archive-sources__hero-grid"><!-- wp:group {"className":"archive-sources__hero-copy"} -->
<div class="wp-block-group archive-sources__hero-copy"><!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Weitere Archive &amp; Quellen</h1>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Die Funk- und Technikgeschichte wird von vielen Museen, Archiven, Vereinen und privaten Sammlern dokumentiert. Hier finden Sie ausgewählte weiterführende Angebote für Recherche, Restaurierung und historische Technik.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:image {"sizeSlug":"full","linkDestination":"none","className":"archive-sources__hero-media"} -->
<figure class="wp-block-image size-full archive-sources__hero-media"><img src="<?php echo esc_url($archive_image_url . 'archive-periodicals.jpg'); ?>" alt="Historische Zeitschriftenbestände im GFGF-Archiv" /></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></header>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-sources__section"} -->
<section class="wp-block-group archive-sources__section"><!-- wp:group {"className":"archive-sources__container"} -->
<div class="wp-block-group archive-sources__container"><!-- wp:group {"className":"archive-sources__section-heading"} -->
<div class="wp-block-group archive-sources__section-heading"><!-- wp:heading -->
<h2 class="wp-block-heading">Online recherchieren</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Diese digitalen Angebote helfen bei der Suche nach Geräten, technischen Daten, Schaltplänen und funkhistorischen Zusammenhängen.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-source-grid"} -->
<div class="wp-block-group archive-source-grid"><?php foreach ($online_sources as $source) { $render_source_card($source); } ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-sources__section archive-sources__section--tint"} -->
<section class="wp-block-group archive-sources__section archive-sources__section--tint"><!-- wp:group {"className":"archive-sources__container"} -->
<div class="wp-block-group archive-sources__container"><!-- wp:group {"className":"archive-sources__section-heading"} -->
<div class="wp-block-group archive-sources__section-heading"><!-- wp:heading -->
<h2 class="wp-block-heading">Museen &amp; Sammlungen</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Historische Rundfunk- und Kommunikationstechnik lässt sich an vielen Orten im Original erleben.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-museum-finder"} -->
<div class="wp-block-group archive-museum-finder"><!-- wp:group {"className":"archive-museum-finder__content"} -->
<div class="wp-block-group archive-museum-finder__content"><!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Radiomuseen finden</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Der Museumsfinder von Radiomuseum.org ermöglicht die weltweite Suche nach technischen Museen und Radiomuseen.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-museum-finder__button"} -->
<div class="wp-block-button archive-museum-finder__button"><a class="wp-block-button__link wp-element-button" href="https://www.radiomuseum.org/museum/List_of_museums_science_technical_radio_finder_Museumsliste.html" target="_blank" rel="noreferrer noopener">Zum Museumsfinder <span aria-hidden="true">↗</span><span class="screen-reader-text"> (öffnet in einem neuen Tab)</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-source-grid archive-source-grid--museums"} -->
<div class="wp-block-group archive-source-grid archive-source-grid--museums"><?php foreach ($museums as $source) { $render_source_card($source); } ?></div>
<!-- /wp:group -->

<!-- wp:paragraph {"className":"archive-sources__visit-note"} -->
<p class="archive-sources__visit-note">Bitte informieren Sie sich vor einem Besuch direkt beim jeweiligen Museum über aktuelle Öffnungszeiten und Besuchsmöglichkeiten.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-sources__section"} -->
<section class="wp-block-group archive-sources__section"><!-- wp:group {"className":"archive-sources__container"} -->
<div class="wp-block-group archive-sources__container"><!-- wp:group {"className":"archive-sources__section-heading"} -->
<div class="wp-block-group archive-sources__section-heading"><!-- wp:heading -->
<h2 class="wp-block-heading">Vereine &amp; Sammlergemeinschaften</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Der Austausch zwischen Sammlerinnen, Sammlern und Fachleuten trägt wesentlich dazu bei, Wissen und historische Technik zu bewahren.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-source-grid"} -->
<div class="wp-block-group archive-source-grid"><?php foreach ($communities as $source) { $render_source_card($source); } ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","className":"archive-sources__section archive-sources__section--tint"} -->
<section class="wp-block-group archive-sources__section archive-sources__section--tint"><!-- wp:group {"className":"archive-sources__container"} -->
<div class="wp-block-group archive-sources__container"><!-- wp:group {"className":"archive-sources__section-heading"} -->
<div class="wp-block-group archive-sources__section-heading"><!-- wp:heading -->
<h2 class="wp-block-heading">Spezialquellen</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Ausgewählte Fachseiten unterstützen bei der Bestimmung, Dokumentation und Restaurierung historischer Geräte.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"archive-source-grid"} -->
<div class="wp-block-group archive-source-grid"><?php foreach ($special_sources as $source) { $render_source_card($source); } ?></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"aside","className":"archive-sources__external-note"} -->
<aside class="wp-block-group archive-sources__external-note"><!-- wp:group {"className":"archive-sources__container archive-sources__external-note-inner"} -->
<div class="wp-block-group archive-sources__container archive-sources__external-note-inner"><!-- wp:group {"className":"archive-sources__external-note-content"} -->
<div class="wp-block-group archive-sources__external-note-content"><!-- wp:heading -->
<h2 class="wp-block-heading">Externe Angebote</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Die GFGF ist für Inhalte und Aktualität externer Webseiten nicht verantwortlich. Sollte ein Link nicht mehr funktionieren oder eine wichtige funkhistorische Quelle fehlen, freuen wir uns über einen Hinweis.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"archive-sources__report-button"} -->
<div class="wp-block-button archive-sources__report-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url($contact_url); ?>">Link melden</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></aside>
<!-- /wp:group -->
