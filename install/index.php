<?php
class um_bixmigs extends CModule
{
    var $MODULE_ID = "um.bixmigs";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_GROUP_RIGHTS = "Y";

    function um_bixmigs()
    {
        /*$arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path."/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion))
        {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }
        else
        {
            $this->MODULE_VERSION = SEO_VERSION;
            $this->MODULE_VERSION_DATE = SEO_VERSION_DATE;
        }

        $this->MODULE_NAME = GetMessage("SEO_MODULE_NAME");
        $this->MODULE_DESCRIPTION = GetMessage("SEO_MODULE_DESCRIPTION");*/
    }


    function DoInstall()
    {
        $this->InstallFiles();
        $this->InstallDB();

        //RegisterModule("seo");

        //$GLOBALS['APPLICATION']->IncludeAdminFile(GetMessage("SEO_INSTALL_TITLE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/seo/install/step1.php");
    }


    function InstallDB()
    {
        global $DB, $APPLICATION;

        /*$this->errors = false;
        if(!$DB->Query("SELECT 'x' FROM b_seo_search_engine", true))
            $this->errors = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/seo/install/db/".strtolower($DB->type)."/install.sql");

        if($this->errors !== false)
        {
            $APPLICATION->ThrowException(implode("", $this->errors));
            return false;
        }*/
    }


    function InstallFiles()
    {
        if($_ENV["COMPUTERNAME"]!='BX')
        {
            CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/seo/install/admin", $_SERVER["DOCUMENT_ROOT"]."/bitrix/admin", true, true);
            CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/seo/install/tools", $_SERVER["DOCUMENT_ROOT"]."/bitrix/tools", true, true);
            CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/seo/install/panel", $_SERVER["DOCUMENT_ROOT"]."/bitrix/panel", true, true);
            CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/seo/install/js", $_SERVER["DOCUMENT_ROOT"]."/bitrix/js", true, true);
            CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/seo/install/images", $_SERVER["DOCUMENT_ROOT"]."/bitrix/images", true, true);
        }
        return true;
    }


    function DoUninstall()
    {

