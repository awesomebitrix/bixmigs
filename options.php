<?php
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

CModule::IncludeModule('um.bixmigs');
$cur_page = $APPLICATION->GetCurPage() . '?mid='
    . htmlspecialcharsbx(UM_BM_MODULE_NAME) . '&lang=' . LANGUAGE_ID;
$session_errs_key = 'um_bm_save_errors';
Loc::loadMessages(__FILE__);

if (isset($_POST['Update'])) {
    $errors = array();
    if (isset($_POST['migration_folder'])) {
        $folder = trim($_POST['migration_folder']);
        if (!empty($folder)) {
            $realpath = realpath($_SERVER['DOCUMENT_ROOT'] . $folder);
            if (is_dir($realpath)) {
                Option::set(UM_BM_MODULE_NAME, 'migration_folder', $folder);
            } else {
                $errors[] = Loc::GetMessage(
                    'UM_BM_FOLDER_NA',
                    array('#FOLDER#' => $folder)
                );
            }
        } else {
            $errors[] = Loc::GetMessage('UM_BM_NO_FOLDER');
        }
    }

    if (isset($_POST['migration_filename_regexp'])) {
        $regexp = trim($_POST['migration_filename_regexp']);
        if (!empty($regexp)) {
            Option::set(UM_BM_MODULE_NAME, 'migration_filename_regexp', $regexp);

        } else {
            $errors[] = Loc::GetMessage('UM_BM_NO_REGEXP');
        }
    }

    if ($errors) {
        $_SESSION[$session_errs_key] = $errors;
    }
    LocalRedirect($cur_page);
}

if ($_SESSION[$session_errs_key]) {
    CAdminMessage::ShowMessage(
        Loc::GetMessage(
            'UM_BM_SAVE_ERRORS',
            array('#ERRORS#' => implode('<br />', $_SESSION[$session_errs_key]))
        )
    );
    unset($_SESSION[$session_errs_key]);
}

$aTabs = array(
    array("DIV" => "edit1", "TAB" => Loc::GetMessage('UM_BM_TAB_NAME'), "ICON" => "", "TITLE" => Loc::GetMessage('UM_BM_TITLE')),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();?>
<form method="POST" action="<?=$cur_page?>?mid=<?=htmlspecialcharsbx(UM_BM_MODULE_NAME)?>&lang=<?=LANGUAGE_ID?>">
<?=bitrix_sessid_post();
$tabControl->BeginNextTab();?>
    <tr>
        <td width="40%"><?=Loc::GetMessage('UM_BM_MIGRATION_FOLDER')?>:</td>
        <td width="60%">
<?php
$val = Option::get(UM_BM_MODULE_NAME, 'migration_folder', UM_BM_MGR_PATH)?>
            <input type="text" size="35" maxlength="255" value="<?=htmlspecialcharsbx($val)?>" name="migration_folder" />
        </td>
    </tr>
    <tr>
        <td width="40%"><?=Loc::GetMessage('UM_BM_MIGRATION_FILENAME_REGEXP')?>:</td>
        <td width="60%">
<?php
$val = Option::get(UM_BM_MODULE_NAME, 'migration_filename_regexp', Um\BixMigDispatcher::DEFAULT_FILENAME_PATTERN)?>
            <input type="text" size="35" maxlength="128" value="<?=htmlspecialcharsbx($val)?>" name="migration_filename_regexp" />
        </td>
    </tr>
<?php
$tabControl->Buttons()?>
    <input type="submit" class="adm-btn-green" name="Update" value="<?=Loc::GetMessage('UM_BM_SAVE')?>" />
    <input type="hidden" name="Update" value="Y" />
<?php
$tabControl->End()?>
</form>
