<?php
define('UM_BM_MODULE_NAME', 'um.bixmigs');
define('UM_BM_MGR_PATH', '/local/modules/' . UM_BM_MODULE_NAME . '/migrations/');
define(
    'UM_BM_MGR_FULL_PATH',
    $_SERVER['DOCUMENT_ROOT'] . '/local/modules/'
        . UM_BM_MODULE_NAME . '/migrations/'
);
define('UM_BM_TABLE_NAME', 'um_bixmigs_migrations');

$autoloaded_classes = array(
    '\Um\BixMigAbstract' => '/lib/bxmg_abstract.php',
    '\Um\BixMigBase' => '/lib/bxmg_base.php',
    '\Um\BixMigDispatcher' => '/lib/bxmg_dispatcher.php',
    '\Um\BixMigTable' => '/lib/bxmg_mig_entity.php',
);

if (class_exists('\Bitrix\Main\Loader'))
    \Bitrix\Main\Loader::registerAutoLoadClasses(UM_BM_MODULE_NAME, $autoloaded_classes);
else
    \CModule::AddAutoloadClasses(UM_BM_MODULE_NAME, $autoloaded_classes);
