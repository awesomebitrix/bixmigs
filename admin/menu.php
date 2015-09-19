<?php
IncludeModuleLangFile(__FILE__);
$aMenu = array(
    "parent_menu" => "global_menu_services",
    "section" => "um.bixmigs",
    "sort" => 1200,
    "text" => GetMessage("UM_BIXMIGS_MENU"),
    "title"=> GetMessage("UM_BIXMIGS_MENU"),
    "url" => "um_bixmigs_admin.php?lang=" . LANG,
    "icon" => "",
    "page_icon" => "",
    "items_id" => "menu_bixmigs",
    "items" => array()
);
return $aMenu;
