<?php
/**
 * Public search, request form and mail handling.
 */

declare(strict_types=1);

namespace GFGF_Service_Docs;

defined('ABSPATH') || exit;

final class Frontend
{
    private const NONCE_ACTION = 'gfgf_service_doc_request';
    private const PER_PAGE = 20;

    /** @return array<string, string> */
    public static function default_attributes(): array
    {
        return [
            'searchHeading'      => __('Archivbestand durchsuchen', 'gfgf-service-docs'),
            'searchLabel'        => __('Hersteller, Gerät, Typ oder Stichwort', 'gfgf-service-docs'),
            'searchPlaceholder'  => __('Hersteller, Gerät, Typ oder Stichwort', 'gfgf-service-docs'),
            'searchButtonLabel'  => __('Suchen', 'gfgf-service-docs'),
            'detailsLabel'       => __('Details anzeigen', 'gfgf-service-docs'),
            'requestButtonLabel' => __('Unterlage anfragen', 'gfgf-service-docs'),
            'requestHeading'     => __('Unterlage anfragen', 'gfgf-service-docs'),
            'requestIntro'       => __('Bitte geben Sie Ihre Kontaktdaten ein.', 'gfgf-service-docs'),
            'firstNameLabel'     => __('Vorname', 'gfgf-service-docs'),
            'lastNameLabel'      => __('Nachname', 'gfgf-service-docs'),
            'emailLabel'         => __('E-Mail-Adresse', 'gfgf-service-docs'),
            'memberLabel'        => __('Ich bin Mitglied der GFGF e.V.', 'gfgf-service-docs'),
            'messageLabel'       => __('Nachricht / Anmerkung', 'gfgf-service-docs'),
            'submitLabel'        => __('Anfrage absenden', 'gfgf-service-docs'),
            'privacyNotice'      => __('Ihre Angaben werden ausschließlich zur Bearbeitung dieser Anfrage verwendet.', 'gfgf-service-docs'),
            'emptySearchNotice'  => __('Geben Sie bis zu vier Suchbegriffe ein. Alle Begriffe müssen im Datensatz vorkommen.', 'gfgf-service-docs'),
            'noResultsNotice'    => __('Zu Ihrer Suche wurden keine Unterlagen gefunden. Versuchen Sie es mit weniger oder allgemeineren Begriffen.', 'gfgf-service-docs'),
            'successNotice'      => __('Vielen Dank. Ihre Anfrage wurde erfolgreich an den Archiv-Verantwortlichen gesendet.', 'gfgf-service-docs'),
        ];
    }

    /** @param array<string, mixed> $attributes */
    public static function render_search_block(array $attributes, string $content = '', mixed $block = null): string
    {
        $attributes = array_merge(self::default_attributes(), $attributes);
        $query = isset($_GET['docs_q'])
            ? sanitize_text_field(wp_unslash((string) $_GET['docs_q']))
            : '';
        $query = mb_substr($query, 0, 200);
        $idx = isset($_GET['doc_idx'])
            ? sanitize_text_field(wp_unslash((string) $_GET['doc_idx']))
            : '';

        ob_start();
        ?>
        <div class="service-docs-search" id="dokumentensuche">
            <?php
            if ('' !== $idx) {
                self::render_request_view($idx, $query, $attributes);
            } else {
                self::render_search_view($query, $attributes);
            }
            ?>
        </div>
        <?php

        return (string) ob_get_clean();
    }

    /** @param array<string, mixed> $attributes */
    private static function render_search_view(string $query, array $attributes): void
    {
        $page = isset($_GET['docs_page']) ? max(1, absint($_GET['docs_page'])) : 1;
        $results = Repository::search($query, $page, self::PER_PAGE);
        ?>
        <section class="service-docs-search__panel" aria-labelledby="service-docs-search-heading">
            <h2 id="service-docs-search-heading"><?php echo esc_html(self::attribute($attributes, 'searchHeading')); ?></h2>
            <form class="service-docs-search__form" method="get" action="<?php echo esc_url(self::page_url()); ?>" role="search">
                <?php self::render_simple_permalink_field(); ?>
                <label for="service-docs-query"><?php echo esc_html(self::attribute($attributes, 'searchLabel')); ?></label>
                <div class="service-docs-search__controls">
                    <input
                        id="service-docs-query"
                        name="docs_q"
                        type="search"
                        value="<?php echo esc_attr($query); ?>"
                        placeholder="<?php echo esc_attr(self::attribute($attributes, 'searchPlaceholder')); ?>"
                        maxlength="200"
                    >
                    <button type="submit"><?php echo esc_html(self::attribute($attributes, 'searchButtonLabel')); ?></button>
                </div>
            </form>
            <?php if ('' === trim($query)) : ?>
                <p class="service-docs-search__hint"><?php echo esc_html(self::attribute($attributes, 'emptySearchNotice')); ?></p>
            <?php endif; ?>
        </section>

        <?php if ('' !== trim($query)) : ?>
            <section class="service-docs-results" aria-labelledby="service-docs-results-heading">
                <div class="service-docs-results__heading">
                    <h2 id="service-docs-results-heading"><?php esc_html_e('Suchergebnisse', 'gfgf-service-docs'); ?></h2>
                    <p aria-live="polite">
                        <?php
                        $result_summary = sprintf(
                            _n('%s Treffer', '%s Treffer', $results['total'], 'gfgf-service-docs'),
                            number_format_i18n($results['total'])
                        );
                        if ($results['pages'] > 1) {
                            $result_summary .= ' – ' . sprintf(
                                __('Seite %1$s von %2$s', 'gfgf-service-docs'),
                                number_format_i18n($results['page']),
                                number_format_i18n($results['pages'])
                            );
                        }
                        echo esc_html($result_summary);
                        ?>
                    </p>
                </div>

                <?php if (0 === $results['total']) : ?>
                    <div class="service-docs-notice service-docs-notice--neutral">
                        <?php echo esc_html(self::attribute($attributes, 'noResultsNotice')); ?>
                    </div>
                <?php else : ?>
                    <div class="service-docs-results__list">
                        <?php foreach ($results['items'] as $document) : ?>
                            <?php self::render_result_card($document, $query, $results['page'], $attributes); ?>
                        <?php endforeach; ?>
                    </div>
                    <?php self::render_pagination($results, $query); ?>
                <?php endif; ?>
            </section>
        <?php endif;
    }

    /** @param array<string, string> $document
     *  @param array<string, mixed> $attributes
     */
    private static function render_result_card(
        array $document,
        string $query,
        int $results_page,
        array $attributes
    ): void
    {
        $primary_fields = [
            'firma'       => __('Firma / Hersteller', 'gfgf-service-docs'),
            'geraetename' => __('Gerätename', 'gfgf-service-docs'),
            'geraetetyp'  => __('Typ', 'gfgf-service-docs'),
            'typ_zusatz'  => __('Typ-Zusatz', 'gfgf-service-docs'),
            'dokumentart' => __('Dokumentart', 'gfgf-service-docs'),
            'titel'       => __('Titel', 'gfgf-service-docs'),
            'jahr'        => __('Jahr', 'gfgf-service-docs'),
        ];
        $detail_fields = [
            'autor'      => __('Autor', 'gfgf-service-docs'),
            'heft'       => __('Heft', 'gfgf-service-docs'),
            'von_seite'  => __('Von Seite', 'gfgf-service-docs'),
            'bis_seite'  => __('Bis Seite', 'gfgf-service-docs'),
            'drucktitel' => __('Titel der Druckerzeugnisse', 'gfgf-service-docs'),
            'bemerkung'  => __('Bemerkung', 'gfgf-service-docs'),
            'bemerkung1' => __('Bemerkung 1', 'gfgf-service-docs'),
            'bemerkung2' => __('Bemerkung 2', 'gfgf-service-docs'),
            'bemerkung3' => __('Bemerkung 3', 'gfgf-service-docs'),
        ];
        $title = self::document_title($document);
        $request_url = add_query_arg(
            array_filter([
                'doc_idx' => $document['idx_value'],
                'docs_q'  => $query,
                'docs_page' => $results_page > 1 ? $results_page : null,
            ]),
            self::page_url()
        );
        ?>
        <article class="service-doc-card">
            <h3><?php echo esc_html($title); ?></h3>
            <?php self::render_definition_list($document, $primary_fields, 'service-doc-card__primary'); ?>
            <details class="service-doc-card__details">
                <summary><?php echo esc_html(self::attribute($attributes, 'detailsLabel')); ?></summary>
                <?php self::render_definition_list($document, $detail_fields, 'service-doc-card__detail-list'); ?>
            </details>
            <a class="service-doc-card__request" href="<?php echo esc_url($request_url); ?>">
                <?php echo esc_html(self::attribute($attributes, 'requestButtonLabel')); ?>
            </a>
        </article>
        <?php
    }

    /** @param array<string, string> $document
     *  @param array<string, string> $fields
     */
    private static function render_definition_list(array $document, array $fields, string $class_name): void
    {
        $available = array_filter(
            $fields,
            static fn (string $_label, string $key): bool => '' !== ($document[$key] ?? ''),
            ARRAY_FILTER_USE_BOTH
        );

        if ([] === $available) {
            return;
        }
        ?>
        <dl class="<?php echo esc_attr($class_name); ?>">
            <?php foreach ($available as $key => $label) : ?>
                <div>
                    <dt><?php echo esc_html($label); ?></dt>
                    <dd><?php echo esc_html($document[$key]); ?></dd>
                </div>
            <?php endforeach; ?>
        </dl>
        <?php
    }

    /** @param array{total: int, pages: int, page: int} $results */
    private static function render_pagination(array $results, string $query): void
    {
        if ($results['pages'] < 2) {
            return;
        }

        $base = add_query_arg(
            [
                'docs_q'    => $query,
                'docs_page' => '%#%',
            ],
            self::page_url()
        );
        $links = paginate_links([
            'base'      => str_replace('%25%23%25', '%#%', esc_url_raw($base)),
            'format'    => '',
            'current'   => $results['page'],
            'total'     => $results['pages'],
            'type'      => 'list',
            'end_size'  => 1,
            'mid_size'  => 1,
            'prev_text' => __('← Zurück', 'gfgf-service-docs'),
            'next_text' => __('Weiter →', 'gfgf-service-docs'),
        ]);

        if (is_string($links)) {
            echo '<nav class="service-docs-pagination" aria-label="' . esc_attr__('Suchergebnisseiten', 'gfgf-service-docs') . '">';
            echo wp_kses_post($links);
            echo '</nav>';
        }
    }

    /** @param array<string, mixed> $attributes */
    private static function render_request_view(string $idx, string $query, array $attributes): void
    {
        $document = Repository::find_by_idx($idx);
        $results_page = isset($_GET['docs_page']) ? max(1, absint($_GET['docs_page'])) : 1;
        $back_url = add_query_arg(
            array_filter([
                'docs_q'    => $query,
                'docs_page' => $results_page > 1 ? $results_page : null,
            ]),
            self::page_url()
        );

        if (null === $document) {
            ?>
            <div class="service-docs-notice service-docs-notice--error" role="alert">
                <?php esc_html_e('Die ausgewählte Unterlage konnte nicht gefunden werden.', 'gfgf-service-docs'); ?>
            </div>
            <a class="service-docs-back" href="<?php echo esc_url($back_url); ?>"><?php esc_html_e('Zurück zur Suche', 'gfgf-service-docs'); ?></a>
            <?php
            return;
        }

        $status = isset($_GET['request_status'])
            ? sanitize_key(wp_unslash((string) $_GET['request_status']))
            : '';
        ?>
        <section class="service-docs-request" id="anfrage" aria-labelledby="service-docs-request-heading">
            <a class="service-docs-back" href="<?php echo esc_url($back_url); ?>">← <?php esc_html_e('Zurück zu den Suchergebnissen', 'gfgf-service-docs'); ?></a>
            <h2 id="service-docs-request-heading"><?php echo esc_html(self::attribute($attributes, 'requestHeading')); ?></h2>
            <p><?php echo esc_html(self::attribute($attributes, 'requestIntro')); ?></p>

            <div class="service-docs-request__document" aria-label="<?php esc_attr_e('Ausgewählte Unterlage', 'gfgf-service-docs'); ?>">
                <p class="service-docs-request__eyebrow"><?php esc_html_e('Ausgewählte Unterlage', 'gfgf-service-docs'); ?></p>
                <h3><?php echo esc_html(self::document_title($document)); ?></h3>
                <?php
                self::render_definition_list(
                    $document,
                    [
                        'firma'       => __('Firma / Hersteller', 'gfgf-service-docs'),
                        'geraetename' => __('Gerätename', 'gfgf-service-docs'),
                        'geraetetyp'  => __('Typ', 'gfgf-service-docs'),
                        'typ_zusatz'  => __('Typ-Zusatz', 'gfgf-service-docs'),
                        'dokumentart' => __('Dokumentart', 'gfgf-service-docs'),
                        'titel'       => __('Titel', 'gfgf-service-docs'),
                        'jahr'        => __('Jahr', 'gfgf-service-docs'),
                    ],
                    'service-docs-request__document-data'
                );
                ?>
            </div>

            <?php self::render_request_status($status, $attributes); ?>
            <?php if ('sent' !== $status) : ?>
                <?php self::render_request_form($document, $query, $results_page, $attributes); ?>
            <?php endif; ?>
        </section>
        <?php
    }

    /** @param array<string, mixed> $attributes */
    private static function render_request_status(string $status, array $attributes): void
    {
        if ('' === $status) {
            return;
        }

        $messages = [
            'sent'       => self::attribute($attributes, 'successNotice'),
            'validation' => __('Bitte prüfen Sie die markierten Pflichtfelder und Ihre E-Mail-Adresse.', 'gfgf-service-docs'),
            'mail-error' => __('Die Anfrage konnte leider nicht versendet werden. Bitte versuchen Sie es später erneut.', 'gfgf-service-docs'),
            'rate-limit' => __('Bitte warten Sie einen Moment, bevor Sie eine weitere Anfrage absenden.', 'gfgf-service-docs'),
            'invalid'    => __('Die Anfrage konnte nicht verarbeitet werden. Bitte öffnen Sie die Unterlage erneut über die Suche.', 'gfgf-service-docs'),
        ];

        if (!isset($messages[$status])) {
            return;
        }

        $class = 'sent' === $status ? 'service-docs-notice--success' : 'service-docs-notice--error';
        $role = 'sent' === $status ? 'status' : 'alert';
        printf(
            '<div class="service-docs-notice %s" role="%s">%s</div>',
            esc_attr($class),
            esc_attr($role),
            esc_html($messages[$status])
        );
    }

    /** @param array<string, string> $document
     *  @param array<string, mixed> $attributes
     */
    private static function render_request_form(
        array $document,
        string $query,
        int $results_page,
        array $attributes
    ): void
    {
        $return_url = add_query_arg(
            array_filter([
                'doc_idx' => $document['idx_value'],
                'docs_q'  => $query,
                'docs_page' => $results_page > 1 ? $results_page : null,
            ]),
            self::page_url()
        ) . '#anfrage';
        $privacy_url = self::privacy_url();
        ?>
        <form class="service-docs-request__form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
            <input type="hidden" name="action" value="gfgf_service_doc_request">
            <input type="hidden" name="document_idx" value="<?php echo esc_attr($document['idx_value']); ?>">
            <input type="hidden" name="return_url" value="<?php echo esc_url($return_url); ?>">
            <?php wp_nonce_field(self::NONCE_ACTION, 'gfgf_service_doc_nonce'); ?>

            <div class="service-docs-honeypot" aria-hidden="true">
                <label for="service-docs-company">Firma</label>
                <input id="service-docs-company" type="text" name="company" value="" tabindex="-1" autocomplete="off">
            </div>

            <div class="service-docs-request__fields">
                <div class="service-docs-field">
                    <label for="service-docs-first-name"><?php echo esc_html(self::attribute($attributes, 'firstNameLabel')); ?> <span aria-hidden="true">*</span></label>
                    <input id="service-docs-first-name" type="text" name="first_name" required maxlength="100" autocomplete="given-name">
                </div>
                <div class="service-docs-field">
                    <label for="service-docs-last-name"><?php echo esc_html(self::attribute($attributes, 'lastNameLabel')); ?> <span aria-hidden="true">*</span></label>
                    <input id="service-docs-last-name" type="text" name="last_name" required maxlength="100" autocomplete="family-name">
                </div>
                <div class="service-docs-field service-docs-field--wide">
                    <label for="service-docs-email"><?php echo esc_html(self::attribute($attributes, 'emailLabel')); ?> <span aria-hidden="true">*</span></label>
                    <input id="service-docs-email" type="email" name="email" required maxlength="254" autocomplete="email">
                </div>
                <div class="service-docs-field service-docs-field--wide">
                    <label class="service-docs-checkbox" for="service-docs-member">
                        <input id="service-docs-member" type="checkbox" name="member" value="1">
                        <span><?php echo esc_html(self::attribute($attributes, 'memberLabel')); ?></span>
                    </label>
                </div>
                <div class="service-docs-field service-docs-field--wide">
                    <label for="service-docs-message"><?php echo esc_html(self::attribute($attributes, 'messageLabel')); ?></label>
                    <textarea id="service-docs-message" name="message" rows="6" maxlength="3000"></textarea>
                </div>
            </div>

            <p class="service-docs-request__privacy">
                <?php echo esc_html(self::attribute($attributes, 'privacyNotice')); ?>
                <a href="<?php echo esc_url($privacy_url); ?>"><?php esc_html_e('Datenschutzerklärung', 'gfgf-service-docs'); ?></a>
            </p>
            <p class="service-docs-request__required"><?php esc_html_e('* Pflichtfeld', 'gfgf-service-docs'); ?></p>
            <button class="service-docs-request__submit" type="submit"><?php echo esc_html(self::attribute($attributes, 'submitLabel')); ?></button>
        </form>
        <?php
    }

    public static function handle_request(): void
    {
        $return_url = isset($_POST['return_url'])
            ? esc_url_raw(wp_unslash((string) $_POST['return_url']))
            : home_url('/');
        $return_url = wp_validate_redirect($return_url, home_url('/'));
        $idx = isset($_POST['document_idx'])
            ? sanitize_text_field(wp_unslash((string) $_POST['document_idx']))
            : '';

        if (
            !isset($_POST['gfgf_service_doc_nonce'])
            || !wp_verify_nonce(
                sanitize_text_field(wp_unslash((string) $_POST['gfgf_service_doc_nonce'])),
                self::NONCE_ACTION
            )
        ) {
            self::redirect_with_status($return_url, 'invalid');
        }

        $document = Repository::find_by_idx($idx);
        if (null === $document) {
            self::redirect_with_status($return_url, 'invalid');
        }

        $honeypot = isset($_POST['company'])
            ? trim((string) wp_unslash($_POST['company']))
            : '';
        if ('' !== $honeypot) {
            self::redirect_with_status($return_url, 'sent');
        }

        $first_name = isset($_POST['first_name'])
            ? sanitize_text_field(wp_unslash((string) $_POST['first_name']))
            : '';
        $last_name = isset($_POST['last_name'])
            ? sanitize_text_field(wp_unslash((string) $_POST['last_name']))
            : '';
        $email = isset($_POST['email'])
            ? sanitize_email(wp_unslash((string) $_POST['email']))
            : '';
        $message = isset($_POST['message'])
            ? sanitize_textarea_field(wp_unslash((string) $_POST['message']))
            : '';
        $member = !empty($_POST['member']);

        $first_name = mb_substr($first_name, 0, 100);
        $last_name = mb_substr($last_name, 0, 100);
        $message = mb_substr($message, 0, 3000);

        if ('' === $first_name || '' === $last_name || !is_email($email)) {
            self::redirect_with_status($return_url, 'validation');
        }

        $remote_address = isset($_SERVER['REMOTE_ADDR'])
            ? sanitize_text_field(wp_unslash((string) $_SERVER['REMOTE_ADDR']))
            : '';
        $rate_key = 'gfgf_doc_request_' . hash('sha256', strtolower($email) . '|' . $remote_address);
        if (false !== get_transient($rate_key)) {
            self::redirect_with_status($return_url, 'rate-limit');
        }

        $recipient = Plugin::recipient_email();
        $subject_parts = array_filter([
            $document['firma'],
            $document['geraetetyp'] ?: $document['geraetename'],
        ]);
        $subject_document = implode(' ', $subject_parts) ?: __('Unterlage', 'gfgf-service-docs');
        $subject = sprintf(
            'GFGF Schaltplanservice – Anfrage zu %s – idx %s',
            $subject_document,
            $document['idx_value']
        );
        $body = self::build_mail_body(
            $first_name,
            $last_name,
            $email,
            $member,
            $message,
            $document
        );
        $sender_email = self::sender_email();
        $headers = [
            'Content-Type: text/plain; charset=UTF-8',
            'From: GFGF Schaltplanservice <' . $sender_email . '>',
            'Reply-To: ' . $email,
        ];
        $set_envelope_sender = static function ($phpmailer) use ($sender_email): void {
            $phpmailer->Sender = $sender_email;
        };
        add_action('phpmailer_init', $set_envelope_sender);
        try {
            $mail_sent = wp_mail($recipient, $subject, $body, $headers);
        } finally {
            remove_action('phpmailer_init', $set_envelope_sender);
        }

        if (!$mail_sent) {
            self::redirect_with_status($return_url, 'mail-error');
        }

        set_transient($rate_key, 1, MINUTE_IN_SECONDS);
        self::redirect_with_status($return_url, 'sent');
    }

    /** @param array<string, string> $document */
    private static function build_mail_body(
        string $first_name,
        string $last_name,
        string $email,
        bool $member,
        string $message,
        array $document
    ): string {
        $lines = [
            'Neue Anfrage über den GFGF Schaltplanservice',
            '',
            'Anfragender',
            '-----------',
            'Vorname: ' . $first_name,
            'Nachname: ' . $last_name,
            'E-Mail: ' . $email,
            'GFGF-Mitglied: ' . ($member ? 'Ja' : 'Nein'),
        ];

        if ('' !== $message) {
            $lines[] = 'Nachricht:';
            $lines[] = $message;
        }

        $lines[] = '';
        $lines[] = 'Gewünschtes Dokument';
        $lines[] = '---------------------';

        foreach (Repository::document_fields() as $key => $label) {
            if ('' !== ($document[$key] ?? '')) {
                $lines[] = $label . ': ' . $document[$key];
            }
        }

        $lines[] = '';
        $lines[] = 'Die Dokumentdaten wurden beim Absenden serverseitig anhand der idx aus dem Archivbestand geladen.';

        return implode("\n", $lines);
    }

    private static function redirect_with_status(string $return_url, string $status): never
    {
        $return_url = remove_query_arg('request_status', $return_url);
        $return_url = add_query_arg('request_status', $status, $return_url);
        wp_safe_redirect($return_url);
        exit;
    }

    /** @param array<string, string> $document */
    private static function document_title(array $document): string
    {
        $parts = array_filter([
            $document['firma'] ?? '',
            $document['geraetename'] ?? '',
            $document['geraetetyp'] ?? '',
        ]);

        if ([] !== $parts) {
            return implode(' · ', $parts);
        }

        if ('' !== ($document['titel'] ?? '')) {
            return $document['titel'];
        }

        return __('Unterlage', 'gfgf-service-docs');
    }

    /** @param array<string, mixed> $attributes */
    private static function attribute(array $attributes, string $key): string
    {
        return isset($attributes[$key]) ? trim((string) $attributes[$key]) : '';
    }

    private static function page_url(): string
    {
        $permalink = get_permalink(get_queried_object_id());

        return is_string($permalink) && '' !== $permalink ? $permalink : home_url('/');
    }

    private static function privacy_url(): string
    {
        $page = get_page_by_path('datenschutz');
        if ($page instanceof \WP_Post) {
            $permalink = get_permalink($page);
            if (is_string($permalink) && '' !== $permalink) {
                return $permalink;
            }
        }

        return home_url('/datenschutz/');
    }

    private static function sender_email(): string
    {
        $default = sanitize_email((string) get_option('admin_email'));
        $filtered = sanitize_email((string) apply_filters('gfgf_service_docs_sender_email', $default));

        return '' !== $filtered ? $filtered : $default;
    }

    private static function render_simple_permalink_field(): void
    {
        if ('' === (string) get_option('permalink_structure')) {
            printf(
                '<input type="hidden" name="page_id" value="%d">',
                absint(get_queried_object_id())
            );
        }
    }
}
