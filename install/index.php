<?php
IncludeModuleLangFile(__FILE__);

if(class_exists('um_bixmigs')) return;

use \Bitrix\Main\ModuleManager;

class um_bixmigs extends CModule
{
    var $MODULE_ID = 'um.bixmigs';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $install_source;

    function um_bixmigs()
    {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . '/version.php');

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = GetMessage('UMBM_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('UMBM_MODULE_DESCRIPTION');

        $this->install_source = $_SERVER['DOCUMENT_ROOT']
            . '/local/modules/um.bixmigs/install/';
    }


    function DoInstall()
    {
        $this->InstallDB();
        $this->InstallFiles();
        ModuleManager::registerModule($this->MODULE_ID);
        $GLOBALS['APPLICATION']->IncludeAdminFile(
            GetMessage("UMBM_INSTALL_TITLE"),
            $this->install_source . 'step.php'
        );
    }


    function InstallDB()
    {
        /*global $DB, $APPLICATION;

        $errors = $DB->RunSQLBatch(
            $this->install_source . '/db/' . strtolower($DB->type) . '/install.sql'
        );

        if ($errors !== false) {
            $APPLICATION->ThrowException(implode("", $errors));
            return false;
        }*/ // TODO
    }


    function InstallFiles()
    {
        CopyDirFiles(
            $this->install_source . '/admin',
            $_SERVER["DOCUMENT_ROOT"] . '/bitrix/admin'
        );
        /*CopyDirFiles(
            $this->install_source . '/images',
            $_SERVER["DOCUMENT_ROOT"] . '/bitrix/images',
            true,
            true
        );*/    // TODO
    }


    function DoUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
