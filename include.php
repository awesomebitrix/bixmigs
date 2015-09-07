<?php
define('UM_MODULE_NAME', 'um.bixmigs');
define('UM_MODULE_MGR_PATH', '/local/modules/' . UM_MODULE_NAME . '/migrations/');
define('UM_MODULE_MGR_FULL_PATH', $_SERVER['DOCUMENT_ROOT'] . '/local/modules/' . UM_MODULE_NAME . '/migrations/');

$autoloaded_classes = array(
    'Um\BixMigAbstract' => 'lib/bxmg_abstract.php',
    'Um\BixMigDispatcher' => 'lib/bxmg_dispatcher.php',
);

if (class_exists('\Bitrix\Main\Loader'))
    \Bitrix\Main\Loader::registerAutoLoadClasses(UM_MODULE_NAME, $autoloaded_classes);
else
    \CModule::AddAutoloadClasses(UM_MODULE_NAME, $autoloaded_classes);
