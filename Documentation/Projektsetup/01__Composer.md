# [Projektsetup](Documentation/Projektsetup.md)/Composer

Auf jedem Neos-Entwicklungssystem ist eine Installation von composer erforderlich. Mit folgendem Befehl kannst Du composer auf Mac und Linux installieren:

```sh
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

Um ein Neos-Projekt in der aktuellsten stabilen Version von Neos zu starten, kannst Du folgenden Befehl verwenden (wobei ``Vendor.Site`` durch den entsprechenden Package Namespace des Projektes zu ersetzen wäre):

```sh
composer create-project typo3/neos-base-distribution Vendor.Site
```

Wenn Dein Projekt third-party depencies benötigt, kannst Du diese ganz einfach über ``composer require`` installieren:

```sh
composer require typo3/swiftmailer
```

Dabei wird die Abhängigkeit automatisch in die ``require``-Sektion der composer.json geschrieben.

Wenn Dein Projekt Abhängigkeiten hat, die nur zur Entwicklung benötigt werden und für das Live-System weggelassen werden können (Wie z.B. ein Unittest-Runner), solltest Du diese per ``composer require-dev`` installieren.

```sh
composer require-dev phpunit/phpunit
```

Dabei wird die Abhängigkeit automatisch in die ``require-dev``-Sektion der composer.json geschrieben.

Um alle dependencies zu updaten, führe folgenden Befehl aus:

```sh
composer update
```

Um nach ``git clone`` oder ``git pull`` die depencies zu aktualisieren, führe folgenden Befehl aus:

```sh
composer install
```

## composer.lock
Das composer.lock file enthält die genauen Versionsinformationen der aktuell installierten Abhängigkeiten. Versionen, die über Versionsconstraints

**Wichtig:** Um zu gewährleisten, dass nach jedem auschecken des Projektes ein konsistenter Stand an Abhängigkeiten installiert wird, muss die composer.lock mitversioniert werden.

## Trusted dependencies

Wir wollen versuchen unseren gemeinsamen technologischen Stack vorhersehbar und stabil zu halten. Aus diesem Grund empfehlen wir eine gewisse Restriktion bei der Auswahl von third-party Packages.

Dependencies von folgenden Github-Organisationen betrachten wir als safe:

* Flownative (https://github.com/flownative)
* Flowpack (https://github.com/flowpack)
* Neos (https://github.com/neos)

## Weitere Pakete

Um herauszufinden, welche weiteren Flow-Pakete existieren, die über composer installiert werden können, lohnt sich ein Blick in die Liste der Flow-Dependents:

https://packagist.org/packages/typo3/flow/dependents

**Achtung:** Wir empfehlen, Rücksprache mit der CoP Neos zu halten, wenn die Einbindung von unbekannten third-party Packages in Deinem Projekt notwendig werden sollte. In diesem Falle sollten wir gemeinsam evaluieren und das gegebene Package darauf überprüfen, ob es unseren qualitativen Standards genügt und welche Alternativen

## Mehr zu composer

Alle wichtigen Infos zu composer findest Du auf der composer-Website https://getcomposer.org/doc/.

Besonders lesenswert sind folgende Artikel:

* Version constraints (https://getcomposer.org/doc/articles/versions.md)
* Troubleshooting (https://getcomposer.org/doc/articles/troubleshooting.md)
