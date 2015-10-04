<?php
namespace Um;

use Bitrix\Main\Entity;

class BixMigTable extends Entity\DataManager {

    public static function getTableName()
    {
        return UM_BM_TABLE_NAME;
    }


    public static function getMap()
    {
        return array(
            new Entity\IntegerField(
                'ID',
                array(
                    'primary' => true,
                    'autocomplete' => true
                )
            ),
            new Entity\StringField('CODE'),
            new Entity\StringField('STATUS'),
            new Entity\DateTimeField(
                'CHANGE_DATE',
                array('default_value' => null)
            ),
            new Entity\DateTimeField('ADD_DATE'),
        );
    }
}
