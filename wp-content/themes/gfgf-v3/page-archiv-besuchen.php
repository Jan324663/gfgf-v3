<?php
/**
 * Template Name: GFGF-Archiv in Hainichen
 * Template Post Type: page
 *
 * Presentation of the physical GFGF archive and its collections.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

$theme_uri = get_template_directory_uri();
$archive_email = sanitize_email((string) apply_filters('gfgf_v3_archive_email', 'archiv@gfgf.org'));
$visit_url = 'mailto:' . $archive_email . '?subject=' . rawurlencode('Besuch im GFGF-Archiv');
$donation_url = 'mailto:' . $archive_email . '?subject=' . rawurlencode('Unterlagen für das GFGF-Archiv');
$tour_url = 'https://www.pixtours.de/vr-gfgf/';
$directions_url = 'https://www.google.com/maps/dir//Hospitalstra%C3%9Fe%2B1%2B09661%2BHainichen/';

/* These figures come from the previous GFGF archive page and remain easy to update here. */
$archive_stats = apply_filters('gfgf_v3_archive_stats', [
    ['value' => 'ca. 200 m²', 'label' => __('Archivfläche', 'gfgf-v3')],
    ['value' => '5.500', 'label' => __('Bücher', 'gfgf-v3')],
    ['value' => '3.300+', 'label' => __('Zeitschriftenjahrgänge', 'gfgf-v3')],
    ['value' => '1.000+', 'label' => __('Kataloge', 'gfgf-v3')],
    ['value' => '40.000+', 'label' => __('Serviceunterlagen', 'gfgf-v3')],
]);

$archive_collections = [
    [
        'title'   => __('Schaltpläne & Serviceunterlagen', 'gfgf-v3'),
        'text'    => __('Historische technische Unterlagen zu Radios, Fernsehgeräten und weiterer Unterhaltungselektronik.', 'gfgf-v3'),
        'details' => __('Im Archiv lagern gut 40.000 Serviceunterlagen zu hergestellten Geräten. Sie dokumentieren zahlreiche historische Geräte der Rundfunk- und Unterhaltungselektronik. Die Unterlagen gehören zu den systematisch erschlossenen Beständen und können gezielt recherchiert werden.', 'gfgf-v3'),
    ],
    [
        'title'   => __('Bücher & Fachliteratur', 'gfgf-v3'),
        'text'    => __('Fachbücher zur Entwicklung, Technik und Geschichte des Rundfunks und seiner Geräte.', 'gfgf-v3'),
        'details' => __('Auf etwas mehr als 30 m² befinden sich rund 5.500 Bücher, die für eine schnelle Suche nach den Nachnamen der Autoren sortiert sind. Die Themen reichen von Bauteilen und Geräten bis zu geschichtlichen Zusammenhängen. Etwa zehn Prozent der Bücher sind englischsprachig.', 'gfgf-v3'),
    ],
    [
        'title'   => __('Zeitschriften', 'gfgf-v3'),
        'text'    => __('Historische Fachzeitschriften und vollständige Zeitschriftenjahrgänge aus vielen Jahrzehnten.', 'gfgf-v3'),
        'details' => __('Der Bestand umfasst mehr als 3.300 Zeitschriftenjahrgänge und reicht von historischen Ausgaben bis zu jüngeren Jahrgängen. Dazu gehören unter anderem Funkamateur, Radio-Mentor, RFE, Funktechnik und Funkschau. Ergänzt wird die Sammlung durch Firmenzeitschriften und klassische Programmzeitschriften zu Rundfunk und Fernsehen.', 'gfgf-v3'),
    ],
    [
        'title'   => __('Kataloge & Firmenschriften', 'gfgf-v3'),
        'text'    => __('Herstellerkataloge, Prospekte, Werbematerialien und Dokumentationen bedeutender Firmen.', 'gfgf-v3'),
        'details' => __('Mehr als 1.000 Kataloge dokumentieren Herstellerwerbung und Firmengeschichte. Hinzu kommen Entwicklungsberichte von Telefunken und WF Berlin sowie Bauunterlagen zu Sendeanlagen. Auch die Blaubücher des DDR-Rundfunks gehören zu diesem Bestand.', 'gfgf-v3'),
    ],
    [
        'title'   => __('Bedienungsanleitungen', 'gfgf-v3'),
        'text'    => __('Historische Bedienungs- und Gebrauchsanleitungen für Geräte der Heimelektronik.', 'gfgf-v3'),
        'details' => __('Der Bestand enthält rund 2.000 Bedienungs- und Gebrauchsanleitungen. Sie erschließen zahlreiche Geräte aus der Geschichte der Rundfunk- und Unterhaltungselektronik.', 'gfgf-v3'),
    ],
    [
        'title'   => __('Fotos & besondere Sammlungen', 'gfgf-v3'),
        'text'    => __('Fotografien, Entwicklungsberichte und weitere besondere technik- und firmengeschichtliche Bestände.', 'gfgf-v3'),
        'details' => __('Zum Archiv gehören tausende historische Fotografien und umfangreiche Mikrofiche-Bestände. Weitere Unterlagen behandeln militärische und kommerzielle Technik. Hinzu kommen spezielle technik- und firmengeschichtliche Sammlungen.', 'gfgf-v3'),
    ],
];

$download_groups = [
    [
        'title' => __('Bestandsverzeichnisse', 'gfgf-v3'),
        'items' => [
            ['file' => 'buecher.pdf', 'title' => __('Verzeichnis der Bücher', 'gfgf-v3')],
            ['file' => 'bedienungsanleitungen.pdf', 'title' => __('Bedienungsanleitungen für Geräte der Heimelektronik', 'gfgf-v3')],
            ['file' => 'firmenschriften.pdf', 'title' => __('Verzeichnis der Firmenschriften', 'gfgf-v3')],
            ['file' => 'fotografien.pdf', 'title' => __('Liste der Fotografien', 'gfgf-v3')],
            ['file' => 'kataloge.pdf', 'title' => __('Verzeichnis der Kataloge', 'gfgf-v3')],
            ['file' => 'serviceunterlagen.pdf', 'title' => __('Serviceunterlagen für 40.300 Geräte', 'gfgf-v3')],
            ['file' => 'zeitschriften.pdf', 'title' => __('Verzeichnis der Zeitschriften', 'gfgf-v3')],
        ],
    ],
    [
        'title' => __('Thematische Bestände', 'gfgf-v3'),
        'items' => [
            ['file' => 'chroniken-firmengeschichten.pdf', 'title' => __('Chroniken und Teile zu Firmengeschichten', 'gfgf-v3')],
            ['file' => 'elektrotechnische-fabriken.pdf', 'title' => __('Elektrotechnische Fabriken und Elektrizitätswerke', 'gfgf-v3')],
            ['file' => 'geschichte-des-funkwesens.pdf', 'title' => __('Unterlagen zur Geschichte des Funkwesens', 'gfgf-v3')],
            ['file' => 'militaerische-kommerzielle-geraete.pdf', 'title' => __('Militärische und kommerzielle Geräte', 'gfgf-v3')],
            ['file' => 'messtechnische-geraete.pdf', 'title' => __('Unterlagen zu messtechnischen Geräten', 'gfgf-v3')],
            ['file' => 'navigation-ortung-funkueberwachung.pdf', 'title' => __('Navigation, Ortung und Funküberwachung', 'gfgf-v3')],
            ['file' => 'aeg-telefunken.pdf', 'title' => __('Unterlagen der Firma AEG/Telefunken', 'gfgf-v3')],
            ['file' => 'wf-entwicklungsberichte.pdf', 'title' => __('Entwicklungsberichte des Werks für Fernsehelektronik Berlin', 'gfgf-v3')],
        ],
    ],
    [
        'title' => __('Weitere Informationen', 'gfgf-v3'),
        'items' => [
            ['file' => 'nutzungsordnung.pdf', 'title' => __('Nutzerordnung des GFGF-Archivs', 'gfgf-v3')],
            ['file' => 'funkgeschichte-inhaltsverzeichnis.pdf', 'title' => __('Gesamtinhaltsverzeichnis der Funkgeschichte, Heft 1–225', 'gfgf-v3')],
            ['file' => 'radio-news-inhaltsverzeichnis.pdf', 'title' => __('Inhaltsverzeichnis der Radio News, 1930–1936', 'gfgf-v3')],
        ],
    ],
];

get_header();
?>
<article class="archive-detail">
    <header class="archive-detail__hero">
        <div class="archive-detail__container archive-detail__hero-grid">
            <div class="archive-detail__hero-copy">
                <h1><?php esc_html_e('Das GFGF-Archiv in Hainichen', 'gfgf-v3'); ?></h1>
                <p class="archive-detail__tagline"><?php esc_html_e('Technikgeschichte bewahren, dokumentieren und zugänglich machen.', 'gfgf-v3'); ?></p>
                <p><?php esc_html_e('Im GFGF-Archiv in Hainichen bewahren wir umfangreiche historische Unterlagen zur Geschichte der Rundfunk- und Unterhaltungselektronik. Dazu gehören Schaltpläne, Serviceunterlagen, Fachbücher, Zeitschriften, Kataloge, Prospekte, Bedienungsanleitungen und zahlreiche weitere Dokumente.', 'gfgf-v3'); ?></p>
                <a class="button button--primary" href="#archiv-besuch"><?php esc_html_e('Archiv besuchen', 'gfgf-v3'); ?></a>
            </div>
            <figure class="archive-detail__hero-media">
                <img
                    src="<?php echo esc_url($theme_uri . '/assets/images/archive/archive-building.jpg'); ?>"
                    width="1634"
                    height="1556"
                    alt="<?php esc_attr_e('Außenansicht des GFGF-Archivgebäudes in der Hospitalstraße in Hainichen', 'gfgf-v3'); ?>"
                    fetchpriority="high"
                >
            </figure>
        </div>
    </header>

    <section class="archive-detail__section archive-detail__stats-section" aria-labelledby="archive-stats-title">
        <div class="archive-detail__container">
            <div class="archive-detail__section-heading archive-detail__section-heading--compact">
                <h2 id="archive-stats-title"><?php esc_html_e('Das Archiv in Zahlen', 'gfgf-v3'); ?></h2>
            </div>
            <dl class="archive-stats">
                <?php foreach ($archive_stats as $stat) : ?>
                    <div class="archive-stat">
                        <dt><?php echo esc_html($stat['label']); ?></dt>
                        <dd><?php echo esc_html($stat['value']); ?></dd>
                    </div>
                <?php endforeach; ?>
            </dl>
        </div>
    </section>

    <section class="archive-detail__section archive-tour" aria-labelledby="archive-tour-title">
        <div class="archive-detail__container archive-tour__grid">
            <figure class="archive-tour__media">
                <img
                    src="<?php echo esc_url($theme_uri . '/assets/images/archive/archive-library.jpg'); ?>"
                    width="2282"
                    height="1656"
                    alt="<?php esc_attr_e('Bücherregale in einem Raum des GFGF-Archivs', 'gfgf-v3'); ?>"
                    loading="lazy"
                >
                <figcaption class="archive-tour__badge"><?php esc_html_e('360°-Rundgang', 'gfgf-v3'); ?></figcaption>
            </figure>
            <div class="archive-tour__content">
                <h2 id="archive-tour-title"><?php esc_html_e('Virtuell durch unser Archiv', 'gfgf-v3'); ?></h2>
                <p class="archive-detail__lead"><?php esc_html_e('Werfen Sie schon vor Ihrem Besuch einen Blick in unsere Archivräume.', 'gfgf-v3'); ?></p>
                <p><?php esc_html_e('Der vorhandene Rundgang führt Sie durch die Räume und vermittelt einen ersten Eindruck von der Vielfalt der Bestände.', 'gfgf-v3'); ?></p>
                <a class="button button--primary" href="<?php echo esc_url($tour_url); ?>" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e('Virtuellen Rundgang starten', 'gfgf-v3'); ?>
                    <span class="screen-reader-text"><?php esc_html_e(' (öffnet auf einer externen Website)', 'gfgf-v3'); ?></span>
                </a>
                <p class="archive-tour__privacy"><?php esc_html_e('Externer Inhalt von Pixtours: Erst mit dem Klick wird eine Verbindung zum Anbieter hergestellt.', 'gfgf-v3'); ?></p>
            </div>
        </div>
    </section>

    <section class="archive-detail__section" aria-labelledby="archive-collections-title">
        <div class="archive-detail__container">
            <div class="archive-detail__section-heading">
                <h2 id="archive-collections-title"><?php esc_html_e('Was Sie bei uns finden', 'gfgf-v3'); ?></h2>
                <p><?php esc_html_e('Unsere Bestände dokumentieren Technik-, Firmen- und Mediengeschichte aus vielen Jahrzehnten.', 'gfgf-v3'); ?></p>
            </div>
            <div class="archive-collections">
                <div class="archive-collections__row">
                    <figure class="archive-collections__media">
                        <img
                            src="<?php echo esc_url($theme_uri . '/assets/images/archive/archive-service-files.jpg'); ?>"
                            width="2338"
                            height="1644"
                            alt="<?php esc_attr_e('Regale mit geordneten Serviceunterlagen im GFGF-Archiv', 'gfgf-v3'); ?>"
                            loading="lazy"
                        >
                    </figure>
                    <div class="archive-collections__list">
                        <?php foreach (array_slice($archive_collections, 0, 3) as $collection) : ?>
                            <article class="archive-collection">
                                <h3><?php echo esc_html($collection['title']); ?></h3>
                                <p><?php echo esc_html($collection['text']); ?></p>
                                <details class="archive-collection__details">
                                    <summary>
                                        <span class="archive-collection__toggle archive-collection__toggle--open"><?php esc_html_e('Mehr erfahren', 'gfgf-v3'); ?></span>
                                        <span class="archive-collection__toggle archive-collection__toggle--close"><?php esc_html_e('Weniger anzeigen', 'gfgf-v3'); ?></span>
                                    </summary>
                                    <p><?php echo esc_html($collection['details']); ?></p>
                                </details>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="archive-collections__row archive-collections__row--reverse">
                    <figure class="archive-collections__media">
                        <img
                            src="<?php echo esc_url($theme_uri . '/assets/images/archive/archive-periodicals.jpg'); ?>"
                            width="2334"
                            height="1660"
                            alt="<?php esc_attr_e('Regale mit gebundenen Zeitschriftenjahrgängen im GFGF-Archiv', 'gfgf-v3'); ?>"
                            loading="lazy"
                        >
                    </figure>
                    <div class="archive-collections__list">
                        <?php foreach (array_slice($archive_collections, 3) as $collection) : ?>
                            <article class="archive-collection">
                                <h3><?php echo esc_html($collection['title']); ?></h3>
                                <p><?php echo esc_html($collection['text']); ?></p>
                                <details class="archive-collection__details">
                                    <summary>
                                        <span class="archive-collection__toggle archive-collection__toggle--open"><?php esc_html_e('Mehr erfahren', 'gfgf-v3'); ?></span>
                                        <span class="archive-collection__toggle archive-collection__toggle--close"><?php esc_html_e('Weniger anzeigen', 'gfgf-v3'); ?></span>
                                    </summary>
                                    <p><?php echo esc_html($collection['details']); ?></p>
                                </details>
                            </article>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="archiv-besuch" class="archive-detail__section archive-visit" aria-labelledby="archive-visit-title">
        <div class="archive-detail__container archive-visit__panel">
            <div class="archive-visit__content">
                <h2 id="archive-visit-title"><?php esc_html_e('Besuchen Sie unser Archiv', 'gfgf-v3'); ?></h2>
                <p class="archive-detail__lead"><?php esc_html_e('Das GFGF-Archiv steht funkhistorisch Interessierten nach vorheriger Absprache offen.', 'gfgf-v3'); ?></p>
                <p><?php esc_html_e('Wenn Sie selbst recherchieren, historische Unterlagen einsehen oder unser Archiv kennenlernen möchten, nehmen Sie gerne Kontakt mit uns auf.', 'gfgf-v3'); ?></p>
                <a class="button button--primary" href="<?php echo esc_url($visit_url); ?>"><?php esc_html_e('Besuch anfragen', 'gfgf-v3'); ?></a>
            </div>
            <div class="archive-visit__address">
                <h3><?php esc_html_e('Archiv der GFGF in Hainichen', 'gfgf-v3'); ?></h3>
                <address>
                    GFGF<br>
                    Hospitalstraße 1<br>
                    09661 Hainichen
                </address>
                <a href="<?php echo esc_url($directions_url); ?>" target="_blank" rel="noopener noreferrer">
                    <?php esc_html_e('Anfahrt in Google Maps', 'gfgf-v3'); ?>
                    <span class="screen-reader-text"><?php esc_html_e(' (öffnet auf einer externen Website)', 'gfgf-v3'); ?></span>
                </a>
            </div>
        </div>
    </section>

    <section class="archive-detail__section archive-history" aria-labelledby="archive-history-title">
        <div class="archive-detail__container">
            <div class="archive-detail__section-heading">
                <h2 id="archive-history-title"><?php esc_html_e('Wie alles begann', 'gfgf-v3'); ?></h2>
                <p><?php esc_html_e('Aus privaten Sammlungen und gemeinsamer Vereinsarbeit entstand ein Archiv, das heute in Hainichen zugänglich ist.', 'gfgf-v3'); ?></p>
            </div>
            <div class="archive-history__grid">
                <figure class="archive-history__portrait">
                    <img
                        src="<?php echo esc_url($theme_uri . '/assets/images/archive/karl-neumann.jpg'); ?>"
                        width="783"
                        height="781"
                        alt="<?php esc_attr_e('Karl Neumann, Gründer des Funkhistorischen Archivs Gruiten', 'gfgf-v3'); ?>"
                        loading="lazy"
                    >
                    <figcaption><?php esc_html_e('Karl Neumann', 'gfgf-v3'); ?></figcaption>
                </figure>
                <ol class="archive-timeline">
                    <li>
                        <span><?php esc_html_e('Späte 1970er Jahre', 'gfgf-v3'); ?></span>
                        <h3><?php esc_html_e('Die Ursprünge der Sammlung', 'gfgf-v3'); ?></h3>
                        <p><?php esc_html_e('In den frühen Heften der Funkgeschichte veröffentlichte Vereinsgründer Karl Neumann Literaturverzeichnisse und begründete in seinem privaten Umfeld das Funkhistorische Archiv Gruiten.', 'gfgf-v3'); ?></p>
                    </li>
                    <li>
                        <span><?php esc_html_e('1991', 'gfgf-v3'); ?></span>
                        <h3><?php esc_html_e('Ein vereinseigenes Archiv entsteht', 'gfgf-v3'); ?></h3>
                        <p><?php esc_html_e('Mit dem Ankauf einer umfangreichen Sammlung seltener Dokumente nahm die Idee eines gemeinsamen GFGF-Archivs konkrete Gestalt an.', 'gfgf-v3'); ?></p>
                    </li>
                    <li>
                        <span><?php esc_html_e('1991–2005', 'gfgf-v3'); ?></span>
                        <h3><?php esc_html_e('Aufbau und Erweiterung', 'gfgf-v3'); ?></h3>
                        <p><?php esc_html_e('Ein GFGF-Mitglied in Ramsen stellte Räume und Arbeitskraft bereit. Durch Ankäufe und kontinuierliche Erschließung wuchs der Bestand weiter.', 'gfgf-v3'); ?></p>
                    </li>
                    <li>
                        <span><?php esc_html_e('Seit 2005', 'gfgf-v3'); ?></span>
                        <h3><?php esc_html_e('Eine neue Heimat in Hainichen', 'gfgf-v3'); ?></h3>
                        <p><?php esc_html_e('Für die umfangreichen Bestände entwickelte der Verein ein neues Konzept mit eigenen, technisch ausgestatteten Räumen. Das Archiv fand seinen heutigen Standort im sächsischen Hainichen.', 'gfgf-v3'); ?></p>
                    </li>
                </ol>
            </div>
        </div>
    </section>

    <section class="archive-detail__section archive-detail__section--compact" aria-labelledby="archive-donation-title">
        <div class="archive-detail__container">
            <aside class="archive-offer archive-detail__donation">
                <div class="archive-offer__content">
                    <h2 id="archive-donation-title"><?php esc_html_e('Unterlagen für unser Archiv?', 'gfgf-v3'); ?></h2>
                    <p><?php esc_html_e('Sie besitzen historische Schaltpläne, Serviceunterlagen, Bedienungsanleitungen, Kataloge, Prospekte oder andere technische Dokumente? Nehmen Sie gerne Kontakt mit uns auf.', 'gfgf-v3'); ?></p>
                </div>
                <a class="button button--primary archive-offer__button" href="<?php echo esc_url($donation_url); ?>"><?php esc_html_e('Unterlagen anbieten', 'gfgf-v3'); ?></a>
            </aside>
        </div>
    </section>

    <section class="archive-detail__section archive-downloads" aria-labelledby="archive-downloads-title">
        <div class="archive-detail__container">
            <div class="archive-detail__section-heading">
                <h2 id="archive-downloads-title"><?php esc_html_e('Bestandsverzeichnisse und weitere Informationen', 'gfgf-v3'); ?></h2>
                <p><?php esc_html_e('Die vorhandenen Verzeichnisse geben einen detaillierten Überblick über Bestände und Themenbereiche des Archivs.', 'gfgf-v3'); ?></p>
            </div>
            <div class="archive-downloads__grid">
                <?php foreach ($download_groups as $group) : ?>
                    <section class="archive-download-group" aria-labelledby="<?php echo esc_attr('download-group-' . sanitize_title($group['title'])); ?>">
                        <h3 id="<?php echo esc_attr('download-group-' . sanitize_title($group['title'])); ?>"><?php echo esc_html($group['title']); ?></h3>
                        <ul>
                            <?php foreach ($group['items'] as $download) : ?>
                                <?php
                                $relative_path = '/assets/downloads/archive/' . $download['file'];
                                $local_path = get_template_directory() . $relative_path;
                                $file_size = file_exists($local_path) ? size_format((int) filesize($local_path), 1) : '';
                                ?>
                                <li>
                                    <a href="<?php echo esc_url($theme_uri . $relative_path); ?>" target="_blank" rel="noopener">
                                        <span><?php echo esc_html($download['title']); ?></span>
                                        <small>PDF<?php echo $file_size ? ' · ' . esc_html($file_size) : ''; ?></small>
                                        <span class="screen-reader-text"><?php esc_html_e(' (öffnet in einem neuen Tab)', 'gfgf-v3'); ?></span>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </section>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
</article>
<?php
get_footer();
