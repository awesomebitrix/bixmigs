# Bixmigs

1C-Bitrix (Bitrix) DB migration tool.

Tested on MySQL database only.

Not tested for Oracle, MSSQL.

## Contents

[Installation](#installation)

[Migration basics](#migration-basics)

[Migration files](#migration-files)

[Errors handling](#errors-handling)

## Installation

By default module should be placed in `/local/modules/`. Folder name module should be `um.bixmigs`.

If you want to install module to `/bitrix/modules` - you can try, but I **don't ensure** proper functionality.

After installation, module admin page is available in `Services` section.

Module also has a couple of settings.

## Migration basics

On module admin page you can see list of existing migration files.

With action menu for each record you can apply migration up or down.

## Migration-files

Migration files should be stored in a folder defined by a module setting.

Every migration file should store **one** class which extends `Um\BixMigAbstract` class and defines two methods:

 - `executeUp` - adds some data via migration, takes no arguments
 - `executeDown` - removes previously added with `executeUp` data, takes no arguments

File name should be the same as class name with `.php` extension.
So, if your migration class is `My_super_migration_2211`, file name should be `My_super_migration_2211.php`

## Errors handling

By default `executeUp` and `executeDown` function should return `true` in case of success.

In case of failure - you can throw standard `\Exception` with your message, which will be caught and showed to a user.

