<?php
class Mgr_20154545_332231_task_2001 extends Um\BixMigAbstract
{
    function executeUp() {
        $db_users = CUser::GetList(
            $by = 'id',
            $order = 'asc',
            array(),
            array('FIELDS' => array('ID', 'LOGIN'))
        );
        while ($row = $db_users->Fetch()) {
            echo'<pre>$row ',print_r($row),'</pre>';    // TODO
        }

        return true;
    }

    function executeDown() {
        echo 'doin smth nut down';

        return true;
    }
}
