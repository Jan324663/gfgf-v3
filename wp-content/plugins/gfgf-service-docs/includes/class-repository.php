<?php
/**
 * Database access for service documents.
 */

declare(strict_types=1);

namespace GFGF_Service_Docs;

defined('ABSPATH') || exit;

final class Repository
{
    /** @return array<string, string> */
    public static function document_fields(): array
    {
        return [
            'idx_value'      => __('idx', 'gfgf-service-docs'),
            'contao_id'      => __('Historische Datensatz-ID', 'gfgf-service-docs'),
            'firma'          => __('Firma / Hersteller', 'gfgf-service-docs'),
            'geraetename'    => __('Gerätename', 'gfgf-service-docs'),
            'geraetetyp'     => __('Typ', 'gfgf-service-docs'),
            'typ_zusatz'     => __('Typ-Zusatz', 'gfgf-service-docs'),
            'titel'          => __('Titel', 'gfgf-service-docs'),
            'autor'          => __('Autor', 'gfgf-service-docs'),
            'heft'           => __('Heft', 'gfgf-service-docs'),
            'von_seite'      => __('Von Seite', 'gfgf-service-docs'),
            'bis_seite'      => __('Bis Seite', 'gfgf-service-docs'),
            'dokumentart'    => __('Dokumentart', 'gfgf-service-docs'),
            'drucktitel'     => __('Titel der Druckerzeugnisse', 'gfgf-service-docs'),
            'jahr'           => __('Jahr', 'gfgf-service-docs'),
            'bemerkung'      => __('Bemerkung', 'gfgf-service-docs'),
            'bemerkung1'     => __('Bemerkung 1', 'gfgf-service-docs'),
            'bemerkung2'     => __('Bemerkung 2', 'gfgf-service-docs'),
            'bemerkung3'     => __('Bemerkung 3', 'gfgf-service-docs'),
            'ablage_ordner'  => __('Ablage / Ordner', 'gfgf-service-docs'),
            'ordner_nummer'  => __('Ordnernummer', 'gfgf-service-docs'),
            'pc'             => __('PC / Datenträger', 'gfgf-service-docs'),
        ];
    }

    public static function table_name(): string
    {
        global $wpdb;

        return $wpdb->prefix . Plugin::TABLE;
    }

    /**
     * Match the former search semantics: up to four whitespace-separated
     * terms, all of which must occur as case-insensitive substrings.
     *
     * @return list<string>
     */
    public static function search_terms(string $query): array
    {
        $terms = preg_split('/\s+/u', trim($query)) ?: [];
        $terms = array_values(array_filter(array_map(
            static fn (string $term): string => mb_substr(trim($term), 0, 100),
            $terms
        )));

        return array_slice($terms, 0, 4);
    }

    /**
     * @return array{items: list<array<string, string>>, total: int, pages: int, page: int, terms: list<string>}
     */
    public static function search(string $query, int $page = 1, int $per_page = 25): array
    {
        global $wpdb;

        $terms = self::search_terms($query);
        $page = max(1, $page);
        $per_page = min(50, max(1, $per_page));

        if ([] === $terms) {
            return [
                'items' => [],
                'total' => 0,
                'pages' => 0,
                'page'  => 1,
                'terms' => [],
            ];
        }

        $where = ['status = %s'];
        $where_args = ['published'];

        foreach ($terms as $term) {
            $where[] = 'search_text LIKE %s';
            $where_args[] = '%' . $wpdb->esc_like($term) . '%';
        }

        $where_sql = implode(' AND ', $where);
        $table = self::table_name();
        $count_sql = "SELECT COUNT(*) FROM {$table} WHERE {$where_sql}";
        $total = (int) $wpdb->get_var($wpdb->prepare($count_sql, ...$where_args));
        $pages = (int) ceil($total / $per_page);

        if ($pages > 0) {
            $page = min($page, $pages);
        }

        $offset = ($page - 1) * $per_page;
        $columns = implode(', ', array_keys(self::document_fields()));
        $rows_sql = "SELECT {$columns} FROM {$table} WHERE {$where_sql}
            ORDER BY firma ASC, geraetename ASC, geraetetyp ASC, idx_value ASC
            LIMIT %d OFFSET %d";
        $rows_args = array_merge($where_args, [$per_page, $offset]);
        $rows = $wpdb->get_results($wpdb->prepare($rows_sql, ...$rows_args), ARRAY_A);

        return [
            'items' => array_map([self::class, 'normalize_row'], is_array($rows) ? $rows : []),
            'total' => $total,
            'pages' => $pages,
            'page'  => $page,
            'terms' => $terms,
        ];
    }

    /** @return array<string, string>|null */
    public static function find_by_idx(string $idx): ?array
    {
        global $wpdb;

        $idx = trim($idx);
        if ('' === $idx || strlen($idx) > 191) {
            return null;
        }

        $columns = implode(', ', array_keys(self::document_fields()));
        $table = self::table_name();
        $sql = "SELECT {$columns} FROM {$table} WHERE idx_value = %s AND status = %s LIMIT 1";
        $row = $wpdb->get_row($wpdb->prepare($sql, $idx, 'published'), ARRAY_A);

        return is_array($row) ? self::normalize_row($row) : null;
    }

    /** @param array<string, mixed> $row
     *  @return array<string, string>
     */
    private static function normalize_row(array $row): array
    {
        $normalized = [];
        foreach (self::document_fields() as $key => $_label) {
            $normalized[$key] = isset($row[$key]) ? trim((string) $row[$key]) : '';
        }

        return $normalized;
    }
}
