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

All `*.fusion`-files are validated by the following rules with the exception of:
 
  - `Root.fusion`
  - `Shame.fusion`
 
Hint: This list can be altered with the Setting `packages.validators.Fusion.options.excludedFileNames`

1. All `*.fusion`-files define exactly one prototype

   This prevents the writing of prototype definitions that are hard to find again.

2. All fusion-prototypes in the `*.fusion`-file have an allowed prefix 

  - `Component` - Presentational component-prototypes that use fusion-properties as only interface to the world.
  - `Content` - Prototypes that define the rendering of `Content`-NodeTypes
  - `Document` - Prototypes that define the rendering of `Document`-NodeTypes
  - `Prototype` - Abstract Prototypes that perform 

Hint: This list can be altered with the Setting `packages.validators.Fusion.options.abstractNodeTypePrefixes`
  
3. The dirname of the `*.fusion`-file represents the beginning of the prototype-name

   To find the definition of a prototype easily all fusion files must be placed in directories 
   that match the start of the prototype-name with `/` replacing the `.`.

4. The filename of the `*.fusion`-file represents the end of the prototype-name or is `index 
 
   To find the definition of a prototype easily all the names of the `*.fusion`-file must represent the 
   end of the fusion-prototype name. In addition `index.fusion` is also considered a valid fusion-filename.   

5. Together dirname and filename represent the whole prototype-name

   We do not make an assumption wether the prototype-name is represented by filename, directory or both,
   we only ensure the prototype-name is fully represented.

Examples for valid filenames for the prototype `Vendor.Site:Blog.Document`:

 -  `Blog.Document.fusion`
 -  `Blog/Document.fusion`
 -  `Blog/Document/index.fusion`
 -  `Blog/Document/Document.fusion`
 -  `Blog/Document/Blog.Document.fusion`