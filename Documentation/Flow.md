* Flow
  * Translations #
    * BEM für trans-unit-ids
    * Pro Modul ein XLIFF file
  * Eel & FlowQuery
  * Domänenmodelle #
  * Transferobjekte #
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
