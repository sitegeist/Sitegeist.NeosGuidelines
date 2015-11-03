* Projektsetup
  * composer
    * Trusted dependencies:
      * Flownative (Organisation)
      * Flowpack (Organisation)
      * Neos (Organisation)
  * Aufbau Repositories
    * Namespaces
      * Vendornamespace ist der Name des Kunden
      * Packagenamespace ist hostname + tld in PascalCase
      * eventuelle Ausnahmen müssen vorher abgesprochen werden
    * Name des Repos ist Name des vollen Package namespace
  * Wann Packages splitten?
    * Module können in Packages ausgelagert werden
    * ggf. als SubPackage des Site Packages
    * Module sollten ausgelagert werden, wenn
      * 5+ NodeTypes
      * Mehrere Controller
      * Eigene Domänenmodelle
    * Auslagerung sollte frühestmöglich diskutiert werden, da eine spätere Änderung unverhältnismäßig teuer wird
  * Wann Packages separat versionieren?
    * Nur, wenn projektübergreifend
    * -> Namespace?
      * Sitegeist, wenn kundenübergreifend
      * sonst Kundennamespace
  * Deployment
    * Branches
    * Surf
    * CI
* Flow
  * Translations
    * BEM für trans-unit-ids
    * Pro Modul ein XLIFF file
    * Singular/Plural Geschichte in die Neos Doku zurückspielen
  * Eel & FlowQuery
  * Domänenmodelle
  * API Anbindungen
  * Application Kontexte
    * Möglichst viel in die globale Settings.yaml // Settings werden in Development verfeinert
    * Globale Settings.yaml wird versioniert
    * Im Zweifel wird nur der Test-Kontext versioniert
  * Routes / URLs
    * Globale Routes.yaml wird versioniert
    * Konkrete Routen liegen im Package
    * Globale Routes.yaml bindet die Package-Routes als SubRoutes ein
    * URLs immer per ViewHelper transportieren
* Neos
  * NodeTypes
    * Patterns
      * Collections
    * Document vs. Content vs. Flow Entity
    * Document nodes & Content nodes
    * Mixins
    * Neos NodeTypes
      * Custom node types für Dokumente, Content und Collections
    * Constraints
      * keine child node constraints!
      * Steuerung über Mixins
    * Abgrenzung child nodes <-> properties
    * Frontend editing vs. Inspector
    * Inspector: Gruppen und Tabs
    * Structure Tree reliability
    * Translations
      * Dev Command um labels automatisch zu ersetzen -> Sitegeist.Translation
  * TypoScript
  * TypoScript vs. Templates
  * Plugins: Modus Operandi
* Generalisierungsprozess
  * Lösungen werden zunächst im Kundennamespace gehalten
  * Wenn eine Generalisierung für sinnvoll erachtet wird, wird die entsprechende Lösung in den Sitegeist Namespace
    überführt
  * Danach wird die ggf. contributet
  * Generalisierung / Standards etc. über CoP Neos kommunizieren
