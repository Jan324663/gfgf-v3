# Scripts

PowerShell-Skripte fuer lokale Einrichtung und Deployment.

- `sync-local.ps1` kopiert Theme und eigene Plugins in eine lokale Laragon-WordPress-Installation.
- `deploy-test-ftp.ps1` laedt Theme und eigene Plugins per FTP in `/httpdocs/wp-content/` hoch.

Die Skripte lesen Zugangsdaten aus Dateien ausserhalb des Repos und schreiben keine Secrets ins Git.

