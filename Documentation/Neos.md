# Content Repository

## Allgemeine Regeln zur Definition von NodeTypes:

* Jeder NodeType und jedes Mixin ist in einer eigenen yaml-Datei definiert
* Die Datei trägt den Namen `NodeTypes.{Document|Content|Mixin}.{Name des NodeTypes ohne Punkte}.yaml`
* Die Constraints können zentral in die Dateien  `NodeTypes.Constraints.Documents.yaml` bzw.
  `NodeTypes.Constraints.Content.yaml` definiert werden

## Basiseinrichtung der NodeTypes

Wir empfehlen zumindest in größeren Projekten die Neos-NodeTypes durch abgeleitete Typen zu ersetzen und auch die
Constraints vollständig im Projekt zu definieren.

```yaml
##
# declare the base document types as abstract

'TYPO3.Neos.NodeTypes:Page':
  abstract: TRUE

'TYPO3.Neos:Shortcut':
  abstract: TRUE

##
# create a custom document type hierarchy

'Vendor.Site:Document':
  constraints:
    nodeTypes:
      'TYPO3.Neos:Document': FALSE
      'Vendor.Site:Document': TRUE

'Vendor.Site:Page':
  superTypes:
    'TYPO3.Neos.NodeTypes:Page': TRUE
    'Vendor.Site:Document' : TRUE
  childNodes:
    main:
      type: 'Vendor.Site:ContentCollection'

'Vendor.Site:Shortcut':
  superTypes:
    'TYPO3.Neos:Shortcut': TRUE
    'Vendor.Site:Document' : TRUE

##
# create custom content collections

'Vendor.Site:ContentCollection':
  superTypes:
    'TYPO3.Neos:ContentCollection': TRUE
  constraints:
    nodeTypes:
      '*': FALSE
      'Vendor.Site:Content': TRUE

'Vendor.Site:MainStageContentCollection':
  superTypes:
    'Vendor.Site:ContentCollection': TRUE
  constraints:
    nodeTypes:
      'Vendor.Site:Content': FALSE
      'Vendor.Site:MainStageContent': TRUE

##
# create custom content base types

'Vendor.Site:Content':
  abstract: TRUE

'Vendor.Site:MainStageContent':
  abstract: TRUE

##
# configure where the default neos contents can be used

'TYPO3.Neos.NodeTypes:Text':
  superTypes:
    'Vendor.Site:Content': TRUE

'TYPO3.Neos.NodeTypes:Image':
  superTypes:
    'Vendor.Site:Content': TRUE
    'Vendor.Site:MainStageContent': TRUE
```

## Eigene Dokumente (NodeTypes)

In Neos wird das erstellen von eigenen Plugins in der Regel vermieden, es werden vielmehr eigene Dokumentenarten
definiert welche das Datemodell des Kunden abbilden. Hierbei ist vor allem die Traversierbarkeit des entstehenden
Dokumentenbaumes zu gewährleisten und die Intuitive Bedienung durch Redakteuere sicherzustellen.

Wichtige Muster am Beispiel von News-Items sind:

* Es werden `NewsItem` und `NewsCollection` als Dokumente definiert
* Innerhalb eines `NewsItems` können keine weiteren Dokumente angelegt werden
* Innerhalb einer `NewsCollection` können die folgenden Dokumente angelegt werden:
  * `NewsItems`
  * `NewsCollections`: Wenn viele `NewsItems` zu erwarten sind sollten die `NewsCollection` weitere `NewsCollections` enthalten
    können um den Redakteueren die Gliederung der Daten zu ermöglichen.
* ggf. kann es Sinn ergeben die NewsItems auch außerhalb der Collection zu nutzen

```yaml
'Vendor.Site:NewsCollection':
  superTypes:
    'Vendor.Site:Page': TRUE
  constraints:
    nodeTypes:
      'Vendor.Site:Document' : FALSE
      'Vendor.Site:NewsCollection' : TRUE
      'Vendor.Site:NewsItem' : TRUE

'Vendor.Site:NewsItem':
  superTypes:
    'Vendor.Site:Page': TRUE
  constraints:
    nodeTypes:
      'Vendor.Site:Document': FALSE
```

## Alternative Wege Kundendaten in Neos abzubilden

NodeTypes sind oft, aber nicht immer die beste Art Daten aus der Kundendomäne in Neos abzubilden, die folgenden
Alternativen können erwogen werden.

* Mit dem Content Repository:
  * Custom NodeTypes (siehe oben)
* Ohne Content Repository:
  * Flow Domänenobjekte, ggf. mit BackendModulen und ContentPlugins
* Mischformen mit dem ContentRepository:
  * Dokumente mit eigener Implementierung der NodeType-Klasse
  * Dokumente als ContentObjectProxy ... einzelne Properties eines Dokumentes werden transparent an ein hinterlegtes
    Flow-Domänenmodell durchgereicht
  * Indizieren externer Daten mit ElasticSearch und Traversieren als virtuelle CR-Nodes

Abgrenzung der verschiedenen Methoden nach möglichen Features

|                     | Custom NodeTypes | Flow Domänenobjekte | NodeType-Klasse | ContentObjectProxy | ElasticSearch virtuelle Nodes |
| ------------------- | ---------------- | ------------------- | --------------- | ------------------ | ------------------------------|
| ContentDimensions   | JA               | NEIN                | JA              | TEILWEISE          | TEILWEISE                     |
| Domänenübergreifend | NEIN             | JA                  | NEIN            | TEILWEISE          | NEIN                          |
|                     |                  |                     |                 |                    |                               |

# TypoScript

## Allgemeine Regeln für TypoScript

* Jeder Prototyp ist in einer eigenen .ts2 Datei.
* In aller Regel wird die Definition von Prototypen TypoScript-Pfaden bevorzugt! 
* Alle Typprüfungen nutzen `instanceof` um auch bei abgeleiteten Typen zu funktioniern.
* Prototypen für Nodes liegen in den Ordnern NodeTypes oder DocumentTypes und tragen den Namen des NodeTypes
* Thematisch zusammenhängende Definitionen können in Bundles zusammengfasst werden

### Dateistruktur in Resources/Private/TypoScript:

Die Dateistruktur der TypoScripte gliedert sich primär danach ob Content- oder 
Document-Nodes der Gegenstand des jeweiligen Scriptes sind. Alles andere wandert 
in den Ordner `TypoScriptObjects`. Thematisch zusammenhängende Definitionen können 
dabei in Bundles zusammengefasst werden welche intern analog zum TypoScript-Ordner 
aufgebaut sind.

- `Root.ts` : Haupteinstiegspunkt für das Rendering. Das Script definiert `/page` und bindet die anderen TypoScripts über Includes ein.
- `NodeTypes/*.ts2` : Prototypen für Content Nodes aus dem Content Repository
- `DocumentTypes/*.ts2` : Prototypen für Document Nodes aus dem Content Repository sowie eine root.renderingCondition
- `TypoScriptObjects/*.ts2` : nodeunabhängige Prototypen für Menüs etc.
- `Bundles/(__Name__)/(NodeTypes|DocumentTypes|TypoScriptObjects)` : optionaler Ordner zur thematischen Gruppierung von TypoScript

```
prototype(Vendor.Site:NewsItem) < prototype(Vendor.Site:Page) {
    # ...
}

root.isVendorSiteNews {
  condition = ${q(node).is('[instanceof Vendor.Site:NewsItem]')}
  type = Vendor.Site:NewsItem
  @position = 'before layout'
}
```

# Templates

## Dateistruktur in Resources/Private/Templates:

Die Dateistruktur der Templates spiegelt den Aufbau der TypoScript-Struktur. Wann 
immer möglich sollte ein Template denselben Pfad wie das TypoScript des Prototypen haben. 

- `NodeTypes` : Templates für Content-Nodes
- `DocumentTypes` : Templates für Document-Nodes
- `TypoScriptObjects` : Templates für nodeunabhängige Module
- `Bundles/(__Name__)/(NodeTypes|DocumentTypes|TypoScriptObjects)` : thematische Gruppe von Templates

Offene Diskussion:
  * TypoScript
  * TypoScript vs. Templates
  * Abgrenzung child nodes <-> properties
  * Plugins: Modus Operandi
  * Inspector: Gruppen und Tabs