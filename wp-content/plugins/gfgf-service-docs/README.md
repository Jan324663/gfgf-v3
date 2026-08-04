# GFGF Service Docs

Plugin fuer das erste V3-Modul `Schaltplaene / Unterlagen`.

## Ziel

Die V2-Loesung speicherte 64.080 Serviceunterlagen als WordPress-CPT plus sehr viele `postmeta`-Zeilen. Fuer V3 startet das Plugin mit einer eigenen Tabelle `wp_gfgf_service_docs`, damit Suche, Import und Redaktion performanter umgesetzt werden koennen.

## Status

Aktuell angelegt:

- Plugin-Bootstrap
- eigene Datenbanktabelle bei Aktivierung
- interne CPT-Verwaltungsbasis
- Rolle `gfgf_service_doc_editor`

Noch offen:

- Importer aus V2/XML/CSV
- Such-Frontend
- Admin-UI fuer Upload/Aenderung
- Review-/Freigabeprozess

