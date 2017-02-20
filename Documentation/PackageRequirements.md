# Neos -  Package Requirements

`./flow guidelines:validatePackages` - validates your neos/flow packages against the sitegist-guidelines

Hint: The list of packages that is validated is configured with the Setting `packages.packageKeys`

## Packages - Mandatory files

1. composer.json

## Packages - Editorconfig

For all files with a suffix from the list `packages.validators.Editorconfig.options.suffixes` 
the indent style is validated against the setting in the main .editorconfig``the list 
`packages.validators.Editorconfig.options.exclude` can be used to exclude items from the list.
 
## Packages - NodeType Rules

1. No Empty NodeTypes.*.yaml

   All Configuration files that match NodeTypes.*.yaml actually contain nodeType configurations.

2. Only one NodeTyp is defined per NodeTypes.*.yaml

   The files NodeTypes.*.yaml define exactly one nodeTyoe. This prevents situations where it is hard to find 
   the defining configuration for nodeTypes because in a single file multiple NodeTypes were declared.

3. All NodeTypes.*.yaml-files start with an allowed prefix.

   The NodeTypes.*.yaml files start with a prefix section that defines the general role for this nodeType. 
   Allowed prefixes and their intended usese are: 
   
   - `Content.*` - NodeTypes that inherit from Neos.Neos:Content and are usually created by Editors on Documents.  
   - `Document.*` - NodeTypes that inherit from Neos.Neos:Document.
   - `Mixin.*` - Abstract NodeTypes that defines a set of properties that can be assigned to other NodeTypes.
   - `Constraint.*` - NodeTypes that inherit from Neos.Neos:Document 
   - `Collections.*` - NodeTypes a node that can only have specific children. Often used together with `Constraint`-Types.
   - `Override.*` - NodeTypes-Definitions that override nodeTypes from other packages. 

   Hint: This list can be altered with the Setting `packages.validators.NodeTypes.options.allowedNodeTypePrefixes`

4. NodeTypes.Override.*.yaml overrides nodeTyos configuration of other packages

   The follwing rules 1-3 skipped for those files.

5. NodeTypes with a prefix `Mixin`- and `Constraint` are declared abstract

   Hint: This list can be altered with the Setting `packages.validators.NodeTypes.options.abstractNodeTypePrefixes`

6. NodeTypes are named PackageKey:Name

   The Package Key should always be used as namspace part of the nodeTypes.

## Packages - Fusion Rules

1. All *.Fusion files except Root.fusion define exactly one prototype

   This prevents the writing of prototype definitions that are hard to find again.

2. All *.Fusion files have an allowed Prefix ()

  - `Component` - Presentational component-prototypes that use fusion-properties as only interface to the world.
  - `Content` - Prototypes that define the rendering of `Content`-NodeTypes
  - `Document` - Prototypes that define the rendering of `Document`-NodeTypes
  - `Prototype` - Abstract Prototypes that perform 

Hint: This list can be altered with the Setting `Sitegeist.NeosGuidelines.packages.validators.Fusion.options.abstractNodeTypePrefixes`
  
3. All *.Fusion files define a prototype that matches the fileName and path

   To find the definition of a prototype easoily all prototype-names habe to match the filenames. 
   We do not make an assumption wether the namespace parts are represented by pathes or dots in the 
   filename and we also ignore an inde.fusion a Namespace part.
   
   Examples:
   
   -  `Component/Molecule/Link.fusion` -> `prototype(Vendor.Site:Component.Molecule.Link)`
   -  `Component/Molecule/Link/index.fusion` -> `prototype(Vendor.Site:Component.Molecule.Link)`
   -  `Component/Molecule/Link.fusion` -> `prototype(Vendor.Site:Component.Molecule.Link)`
   -  `Component/Teaser/index.fusion` -> `prototype(Vendor.Site:Component.Teaser)`
   -  `Component/Teaser/Product.fusion` -> `prototype(Vendor.Site:Component.Teaser.Product)`
   -  `Component/Teaser.Product.fusion` -> `prototype(Vendor.Site:Component.Teaser.Product)`
   -  `Component/Teaser/Product/index.fusion` -> `prototype(Vendor.Site:Component.Teaser.Product)`