# Content Repository

## Allgemeine Regeln zur Definition von NodeTypes:

* Jeder NodeType und jedes Mixin ist in einer eigenen yaml-Datei definiert
* Die Datei trägt den Namen `NodeTypes.{Document/Content/Mixin}.{Name des NodeTypes ohne Punkte}.yaml`
* Die Constraints können zentral in die Dateien  `NodeTypes.Constraints.Documents.yaml` bzw.
  `NodeTypes.Constraints.Content.yaml` definiert werden

## Basiseinrichtung der NodeTypes

Wir empfehlen zumindest in größeren Projekten die Neos-NodeTypes durch abgeleitete Typen zu ersetzen und auch die
Constraints vollständig im Projekt zu definieren.

```yaml
##
# declare the base document types as abstract

'TYPO3.Neos:Page':
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
    'TYPO3.Neos:Page': TRUE
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

In Neos wird das erstellen von eigenen Plugins in der regel vermieden. Es werden vielmehr eigene Dokumentenarten
definiert welche das Datemodell des Kunden abbilden. Hierbei ist vor allem die Traversierbarkeit des entstehenden
Dokumentenbaumes zu gewährleisten und die Intuitive bedienung durch Redakteuere sicherzustellen.

Wichtige Muster am Beispiel von News-Items sind:

* Es werden `NewsItem` und `NewsCollection` Dokumente definiert
* Innerhalb eines `NewsItems` können keine weiteren Dokumente angelegt werden
* Innerhalb einer `NewsCollection` können die folgenden Dokumente angelegt werden:
  * `NewsItems`
  * `NewsCollections`: Wenn viele `NewsItems` zu erwarten sind sollten die `NewsCollection` weitere `NewsCollections` enthalten
    können um den Redakteueren die Gliederung der Daten zu ermöglichen.
  * Andere Dokumente: ggf. kann es Sinn ergeben die NewsItems auch außerhalb der Collection zu nutzen

```yaml
'Vendor.Site:ItemCollection':
  superTypes:
    'Vendor.Site:Page': TRUE
  constraints:
    nodeTypes:
      'Vendor.Site:Document' : FALSE
      'Vendor.Site:ItemCollection' : TRUE
      'Vendor.Site:Item' : TRUE

'Vendor.Site:Item':
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
  * Custom NodeTypes
* Ohne Content Repository:
  * Flow Domänenobjekte, ggf. mit BackendMmodulen und ContentPlugins
* Mischformen mit dem ContentRepository:
  * Dokumente mit eigener Implementierung der NodeType-Klasse
  * Dokumente als ContentObjectProxy ... einzelne Properties eines Dokumentes werden transparent an ein hinterlegtes
    Flow-Domänenmodell durchgereicht
  * Indizieren externer Daten mit ElasticSearch und Traversieren als virtuelle CR-Nodes

Abgrenzung verschiedenen Methoden

|                   | Custom NodeTypes | Flow Domänenobjekte | NodeType-Klasse | ContentObjectProxy | ElasticSearch virtuelle Nodes |
| ----------------- | ---------------- | ------------------- | --------------- | ------------------ | ------------------------------|
| ContentDimensions |     JA           | NEIN                | JA              | TEILWEISE          | TEILWEISE                     |
|                   |                  |                     |                 |                    |                               |
|                   |                  |                     |                 |                    |                               |



Offene Diskussion:
  * TypoScript
  * TypoScript vs. Templates
  * Abgrenzung child nodes <-> properties
  * Plugins: Modus Operandi
  * Inspector: Gruppen und Tabs