# GFGF WordPress – Projektregeln

## Redaktionelle Inhalte

Alle neuen Seiten und Module müssen so umgesetzt werden, dass redaktionelle Inhalte möglichst über das WordPress-Backend bearbeitbar sind.

Nicht direkt in PHP-Templates hart codieren:

- Überschriften
- Fließtexte
- Bilder
- Kennzahlen
- Button-Texte
- Links
- Termine
- Adressen
- Download-Links
- sonstige regelmäßig änderbare Inhalte

Bevorzugt native Gutenberg-Blöcke und Block-Patterns verwenden.

## Theme

Im Theme bzw. Code bleiben:

- Header und Footer
- globale Layouts
- Farben und Typografie
- Responsive CSS
- JavaScript-Funktionalität
- wiederverwendbare Design-Komponenten
- technische Logik

Seitentemplates sollen nach Möglichkeit den normalen WordPress-Inhalt über `the_content()` ausgeben und nicht komplette Seiteninhalte hart codieren.

## Ziel

Ein Vorstandsmitglied ohne Programmierkenntnisse soll typische Inhaltsänderungen im WordPress-Backend durchführen können, ohne PHP, HTML oder CSS bearbeiten zu müssen.

Das aktuelle Design darf durch die Editierbarkeit nicht unnötig vereinfacht werden.

## Ausnahme

Wenn eine Funktion technisch sinnvollerweise im Theme implementiert werden muss, darf die Funktionalität dort liegen. Die dazugehörigen redaktionellen Texte und Daten sollen dennoch möglichst über WordPress pflegbar bleiben.
