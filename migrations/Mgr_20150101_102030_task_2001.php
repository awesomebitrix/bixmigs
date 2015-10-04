<?php
class Mgr_20150101_102030_task_2001 extends Um\BixMigAbstract
{
    const
        IBLOCK_ID = 1;

    function executeUp() {
        CModule::IncludeModule('iblock');
        $obj_ib_element = new CIBlockElement;
        $r = $obj_ib_element->Add(array(
            'NAME' => 'sone new name here',
            'IBLOCK_ID' => self::IBLOCK_ID,
            'ACTIVE' => 'Y',
        ));

        if (!$r) {
            throw new \Exception('Fail do add new element!!!');
        }

        return true;
    }

    function executeDown() {
        CModule::IncludeModule('iblock');
        $obj_ib_element = new CIBlockElement;
        $r = $obj_ib_element->Delete(2);

        if (!$r) {
            throw new \Exception('Fail do remove element with id = 1');
        }

        return true;
    }
}
