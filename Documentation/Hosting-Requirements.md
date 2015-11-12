# Flow/Neos Hosting Requirements

## Datenbank

Es wird eine von doctrine dbal unterstützte Datenbank benötigt. Allen voran wären das:

* MySQL (in der aktuellsten Version)
* PostgreSQL (in der aktuellsten Version)

Einzelheiten zu möglichen Konfigurationen:
 http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html

## Server

Es wird ein UNIX-basiertes Serversystem empfohlen, da Flow Symlinks erstellen muss. Dies auf einem Windows Server zu ermöglichen, bedarf zusätzlicher Konfiguration.

Als HTTP Server wird **Apache** in seiner aktuellsten Version empfohlen. Es muss sichergestellt sein, dass Apache mit aktiviertem **mod_rewrite** läuft.

## PHP

Für die aktuelle Version von TYPO3 Flow wird **PHP** in einer Version **>=5.5.20** benötigt. Es muss sichergestellt sein, dass die selbe PHP Version im Betrieb mit dem HTTP Server (Web), sowie im Command Line Betrieb (CLI) eingesetz wird.

Des Weiteren müssen folgende PHP Extensions installiert sein:

* zlib
* SPL
* json
* mbstring
* Reflection API
* pdo oder mysqli (Abhängig von der gewählten Datenbank, empfohlen wird in jedem Fall pdo)

Die PHP Konfiguration für den Web- und den CLI-Betrieb muss so vorgenommen sein, dass folgende Funktionen aktiviert sind:

* system()
* shell_exec()
* escapeshellcmd()
* escapeshellarg()

## Sonstige Libraries/Services

### Suche

Für Anwendungen mit komplexer Suchfunktion wird **elasticsearch** in seiner aktuellsten Version (**2.0.0**) benötigt. Die Library muss als deamon auf dem Server laufen. Elasticsearch benötigt die **JDK** in einer **Version >=7.0 update u55**.

### TYPO3\\Media (Standard bei Neos)

Für den Einsatz des TYPO3\\Media Paketes wird eine der drei folgenden Image libraries benötigt:

* Imagick
* Gmagick
* GD

Die korrespondierende PHP Extension muss aktiviert sein.

### HTTP-Kommunikation

Für zahlreiche Anwendungen ist es zudem nötig, dass die PHP **curl extension** zur Verfügung steht.

### composer

Die Lauffähigkeit von **composer** muss sichergestellt sein.
