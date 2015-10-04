<?php
class Mgr_20150101_102032_task_2003 extends Um\BixMigAbstract
{
    const
        IBLOCK_ID = 1;

    function executeUp() {
        CModule::IncludeModule('iblock');

        $obj_ib_section = new CIBlockSection;
        $r = $obj_ib_section->Add(array(
            'NAME' => 'new section here',
            'CODE' => 'new_section_here',
            'ACTIVE' => 'N',
            'IBLOCK_ID' => self::IBLOCK_ID,
        ));
        if (!$r) {
            throw new \Exception('Err adding section:' . $obj_ib_section->LAST_ERROR);
        }

        return true;
    }

    function executeDown() {
        echo 'doin smth nut down';

        return true;
    }
}
