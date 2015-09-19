<?php

error_reporting(E_ALL);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
Bitrix\Main\Localization\Loc::loadMessages(__FILE__);
$APPLICATION->SetTitle(Bitrix\Main\Localization\Loc::getMessage('UM_BM_LIST_TITLE'));
\Bitrix\Main\Loader::includeModule('um.bixmigs');
$cur_page = $APPLICATION->GetCurPage();

$sTableID = 'tbl_bixmigs_table';
$oSort = new CAdminSorting($sTableID, 'id', 'desc');
$lAdmin = new CAdminList($sTableID, $oSort);

if ($lAdmin->EditAction()) {
    /* var $FIELDS */
    /* $lAdmin->AddGroupError(GetMessage("rub_save_error")." ".$cData->LAST_ERROR, $ID); */
}

if ($arID = $lAdmin->GroupAction()) {
    //$_REQUEST['action_target']=='selected' // для всех
    // foreach($arID as $ID)
    // switch($_REQUEST['action'])
}

$mgr_dsp = new \Um\BixMigDispatcher();
$mgrs_data = $mgr_dsp->loadMigrations();
$db_data = new CAdminResult($mgrs_data['mgrs'], $sTableID);
$db_data->NavStart();
$lAdmin->NavText($db_data->GetNavPrint(Bitrix\Main\Localization\Loc::getMessage(UM_BM_LIST_SHOWED)));
$lAdmin->AddHeaders($mgrs_data['headers']);

$img_pattern = '<img hspace="4" title="%1$s" alt="%1$s" src="/bitrix/images/' . UM_BM_MODULE_NAME . '/%2$s">';
while ($item = $db_data->NavNext()) {
    $row =& $lAdmin->AddRow($item->getId());
    $row->AddViewField('id', $item->getCode());

    $pic = $item->getStatus() == 'UP'?
        'green.gif' : ($item->getStatus() == 'DOWN'? 'red.gif' : 'grey.gif');
    $img_pic = sprintf(
        $img_pattern,
        Bitrix\Main\Localization\Loc::getMessage(
            'UM_BM_STATUS_' . $item->getStatus() . '_CAPTION'
        ),
        $pic
    );
    $row->AddViewField('status', $img_pic);
    $row->AddViewField('date_c', $item->getChangeDate());
    $row->AddViewField('date_a', $item->getAddDate());

    $actions = array(
        array(
            "ICON" => "edit",
            "DEFAULT" => true,
            "TEXT" => Bitrix\Main\Localization\Loc::getMessage(
                $item->getStatus() != 'UP'? 'UM_BM_MIG_UP' : 'UM_BM_MIG_DOWN'
            ),
            "ACTION" => $lAdmin->ActionRedirect("?id=" . $item->getId())
        )
    );
    $row->AddActions($actions);
}

// альтернативный вывод
$lAdmin->CheckListMode();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");

