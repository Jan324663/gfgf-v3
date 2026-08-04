# Infrastruktur

## Lokale Umgebung

Laragon ist installiert unter `C:\laragon`.

V2 liegt lokal unter `C:\laragon\www\gfgf` und nutzt die DB `gfgf`.

Fuer V3 ist als lokale WordPress-Installation vorgesehen:

`C:\laragon\www\gfgf-v3`

Status 2026-08-04:

- lokale DB `gfgf_v3` wurde angelegt
- WordPress wurde installiert
- Site-URL: `http://localhost/gfgf-v3`
- lokaler Admin-User: `admin`
- lokales Admin-Passwort: `gfgf-local-admin`
- Theme `gfgf-v3` ist aktiv
- Plugin `gfgf-service-docs` ist aktiv
- Tabelle `wp_gfgf_service_docs` existiert
- Rolle `gfgf_service_doc_editor` existiert
- WP-CLI lokal: `C:\laragon\bin\wp-cli\wp.bat`
- PHP lokal: `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe`
- MySQL lokal: `C:\laragon\bin\mysql\mysql-8.4.3-winx64\bin\mysql.exe`
- Apache lokal: `C:\laragon\bin\apache\httpd-2.4.66-260223-Win64-VS18\bin\httpd.exe`

Hinweis: `gfgf-v3.test` war am 2026-08-04 nicht aufloesbar. Deshalb ist die lokale Installation vorerst auf den ohne VHost funktionierenden Pfad `http://localhost/gfgf-v3` gesetzt.

Das Repository enthaelt nur projektbezogenen Code unter `wp-content/`, nicht den WordPress-Core.

## Testserver

FTP-Verbindung:

- Host/IP: `45.142.114.254`
- WordPress-Pfad: `/httpdocs/`
- Zugangsdaten-Datei: `C:\Users\info\dev\GFGF.org V3 Codex\ftp.txt`
- FTP-Port 21 ist erreichbar
- FTP-Login funktioniert
- `/httpdocs/` enthaelt eine WordPress-Installation

Datenbank:

- Host/IP: `45.142.114.254`
- Port: `3306`
- DB: siehe `WP Db Zugaenge.txt`
- Tabellenpraefix: `qyBBQ_`
- Site-URL in DB: `https://web-4447.web-interface.eu`
- DB-Login funktioniert
- DB ist frisch: WordPress-Basistabellen vorhanden, kaum Inhalte

Beim Einsatz von `curl.exe` muss `--noproxy "*"` gesetzt werden, weil sonst ein lokaler Proxy `127.0.0.1:9` stoeren kann.

## WP-CLI auf dem Testserver

Port 22 ist erreichbar. Der vorhandene FTP-User konnte am 2026-08-04 nicht nicht-interaktiv per SSH einloggen:

`Permission denied (publickey,password)`

Damit ist serverseitiges WP-CLI noch nicht einsatzbereit. Benoetigt wird einer der folgenden Wege:

- SSH-Zugang mit Passwort, der interaktiv genutzt werden darf
- SSH-Key fuer den Agenten/Arbeitsrechner
- Provider-Konsole mit WP-CLI
- alternativ: administrative Aenderungen per WordPress-Admin oder eigenes Admin-/Deployment-Plugin

## Deployment-Prinzip

Theme und eigene Plugins werden aus dem Repo nach WordPress deployed:

- lokal nach `C:\laragon\www\gfgf-v3\wp-content\...`
- Testserver nach `/httpdocs/wp-content/...`

Uploads, WordPress-Core, `wp-config.php` und produktive Zugangsdaten werden nicht ins Git-Repo aufgenommen.

## V2 als Quelle

Relevante Quellen fuer das erste Modul:

- `C:\Users\info\dev\GFGF Modernisierung\db export\mm_sunterlagen_20260511_182531.xml`
- `C:\Users\info\dev\GFGF Modernisierung\db_dump_20260512_181614.sql`
- `C:\Users\info\dev\GFGF Modernisierung\migration\migrate_service_docs.py`
- `C:\laragon\www\gfgf\wp-content\mu-plugins\gfgf-datatables-ajax.php`
- `C:\laragon\www\gfgf\wp-content\themes\gfgf-theme\page-serviceunterlagen.php`
