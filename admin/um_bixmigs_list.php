<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Миграции [BixMigs]');

//CModule:;RegisterModule('um.bixmigs');  // move to
CModule::IncludeModule('um.bixmigs');
$cur_page = $APPLICATION->GetCurPage();

/* Основные объекты страницы */
$sTableID = 'tbl_bixmigs_table';
$oSort = new CAdminSorting($sTableID, 'id', 'desc');
$lAdmin = new CAdminList($sTableID, $oSort);


define('UM_MODULE_MGR_PATH', '/local/modules/um.bixmigs/migrations/');  // TODO remove
$mgr_dsp = new \Um\BixMigDispatcher();
$mgrs_data = $mgr_dsp->loadMigrations();
$db_data = new CAdminResult($mgrs_data['mgrs'], $sTableID);
$db_data->NavStart();
$lAdmin->NavText($db_data->GetNavPrint('Показано'));
$lAdmin->AddHeaders($mgrs_data['headers']);

$img_pattern = '<img hspace="4" title="%1$s" alt="%1$s" src="/bitrix/images/sale/%2$s">';
while ($item = $db_data->NavNext()) {
    $row =& $lAdmin->AddRow($item['id'], $item);
    $row->AddViewField('id', $item['m']);
    $img_pic = sprintf(
        $img_pattern,
        $item['s'] == 'N'? 'Migration not applied or no data available' : 'Migration applied',
        $item['s'] == 'N'? 'red.gif' : 'green.gif'
    );
    $row->AddViewField('status', $img_pic);
    $row->AddViewField('date', $item['d']);
}

// альтернативный вывод
$lAdmin->CheckListMode();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$lAdmin->DisplayList();

/*if (isset($_SESSION[$errors_key])) {
    if (sizeof($_SESSION[$errors_key])) {
        echo CAdminMessage::ShowMessage(array(
            'TYPE' => 'ERROR',
            'MESSAGE' => 'Ошибки изменения статуса товаров:',
            'DETAILS' => implode('<br />', $_SESSION[$errors_key]),
            'HTML' => true
        ));
    } else {
        echo CAdminMessage::ShowNote('Статус товаров успешно изменен');
    }
    unset($_SESSION[$errors_key]);
}*/

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");

