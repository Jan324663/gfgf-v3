# GFGF Service Docs

Das Plugin stellt die Dokumentensuche und die Einzelanfrage fuer den
GFGF-Schaltplanservice bereit.

## Datenhaltung

Die 64.080 vorhandenen V2-Datensaetze werden mit
`scripts/import_service_docs_v3.py` in die eigene Tabelle
`wp_gfgf_service_docs` uebernommen. Sie werden bewusst nicht erneut als
WordPress-Beitraege und Postmeta angelegt. `idx_value` ist der eindeutige
fachliche Schluessel.

Gespeichert werden Hersteller, Geraetename, Typ, Typ-Zusatz, Titel, Autor,
Heft, Seiten, Dokumentart, Drucktitel, Jahr, vier Bemerkungsfelder sowie die
vorhandenen Ablage-, Ordner- und PC-/Datentraegerangaben. Da die fruehere
V2-Migration das Feld `s_pc` ausgelassen hatte, ergaenzt der Importer dessen
vorhandene Werte direkt aus dem urspruenglichen XML-Export.

## Suche und Anfrage

- Der dynamische Gutenberg-Block `gfgf-service-docs/search` stellt Suche,
  Ergebnislisten und Anfrageformular dar.
- Bis zu vier durch Leerzeichen getrennte Begriffe werden als
  gross-/kleinschreibungsunabhaengige Teiltreffer gesucht. Jeder Begriff muss
  im Datensatz vorkommen.
- Treffer werden als responsive Karten mit optionalen Details ausgegeben.
- Eine Anfrage bezieht sich immer auf genau eine Unterlage.
- Beim Absenden wird ausschliesslich die `idx` aus dem Formular verwendet und
  der vollstaendige Datensatz serverseitig neu aus der Tabelle geladen.
- Nonce, Honeypot, serverseitige Validierung und ein kurzes Rate-Limit schuetzen
  das Formular.
- Die Empfaengeradresse ist unter `Einstellungen > GFGF Schaltplanservice`
  pflegbar. Es werden keine Empfaengeradressen im Frontend ausgegeben.

Die redaktionellen Texte des Blocks sind in dessen Gutenberg-Einstellungen
bearbeitbar. Seitentitel, Einleitung und Suchhinweise bestehen aus normalen
Core-Bloecken im Seiteninhalt.

## Noch nicht Bestandteil des ersten Stands

- Warenkorb oder Mehrfachauswahl
- Benutzerkonten
- Bezahlung oder Dokumentdownload
- redaktionelle Einzelpflege der 64.080 Datensaetze im Backend
