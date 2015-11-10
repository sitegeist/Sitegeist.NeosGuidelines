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
