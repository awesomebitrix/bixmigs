<?php
/**
 *
 * @author u_mulder <m264695502@gmail.com>
 */
namespace Um;

use \Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);

class BixMigDispatcher
{

    protected
        $migrations = array(),
        $errors = array(),
        $connectionPool = null;

    public function loadMigrations()
    {
        $result = array(
            'mgrs' => array(),
            'headers' => array(
                array(
                    'id' => 'id',
                    'content' => Loc::getMessage('MIGRATION_ID'),
                    'sort' => 'id',
                    'align' => 'left',
                    'default' => true,
                ),
                array(
                    'id' => 'status',
                    'content' => Loc::getMessage('MIGRATION_STATUS'),
                    'align' => 'right',
                    'default' => true,
                ),
                array(
                    'id' => 'date_c',
                    'content' => Loc::getMessage('MIGRATION_DATE_CHANGED'),
                    'align' => 'right',
                    'default' => true,
                ),
                array(
                    'id' => 'date_a',
                    'content' => Loc::getMessage('MIGRATION_DATE_ADDED'),
                    'align' => 'right',
                    'default' => true,
                ),
            ),
        );

        $db_mgrs = $this->loadDBMigrations();
        //var_dump($db_mgrs); // TODO

        $di = new \DirectoryIterator(\UM_BM_MGR_FULL_PATH);
        while ($di->valid()) {
            if (!$di->isDot() && $this->hasProperFilename($di->getFilename())) {
                $filename = $di->getFilename();
                if (!array_key_exists($filename, $db_mgrs)) {
                    /* migration should be added to db */
                    $mgr = new BixMigBase();
                    $mgr->setCode($filename)
                        ->setStatus('UNKNOWN')
                        ->setAddDate(date('d.m.Y H:i:s'))
                        ->setChangeDate(date('d.m.Y H:i:s'))
                        ->add();

                    $result['mgrs'][] = $mgr;
                } else {
                    $result['mgrs'][] = $db_mgrs[$filename];
                    unset($db_mgrs[$filename]);
                }
            }

            $di->next();
        }

        /*if (!empty($db_mgrs)) {
            $this->deleteOrphans($db_mgrs);
        }*/

        return $result;
    }


    protected function hasProperFilename($filename)
    {
        // TODO pattern can be set as an option
        return preg_match('/^Mgr_([0-9]{8})_([0-9]{6})_([a-z0-9_]+)\.php$/', $filename);
    }


    public function loadDBMigrations()
    {
        $result = array();
        $db_items = BixMigTable::getList();

        while ($row = $db_items->fetch()) {
            $mgr = new BixMigBase();
            $mgr->setId($row['ID'])
                ->setCode($row['CODE'])
                ->setStatus($row['STATUS'])
                ->setAddDate($row['CHANGE_DATE'])
                ->setChangeDate($row['ADD_DATE']);
            $result[$row['CODE']] = $mgr;
        }

        return $result;
    }


    public function deleteOrphans($mgrs)
    {
        $ids = array_reduce(
            $mgrs,
            function($t, $v) {
                $t[] = $v->getId();

                return $t;
            },
            array()
        );

        if (sizeof($ids)) {
            $q = 'DELETE FROM `' . UM_BM_TABLE_NAME
                . '` WHERE `id` IN (' . implode(', ', $ids) . ')';
            $r = $this->connectionPool->execute($q);
        }
    }


    public function __construct(BixMigAbstract $mgr = null)
    {
        if (!is_null($mgr)) {
            $this->migrations[] = $mgr;
        }

        /* Not flexible, no support for previous $DB */
        $this->connectionPool = \Bitrix\Main\Application::getConnection();
    }


    public function addMigration(BixMigAbstract $mgr)
    {
        $this->migrations[] = $mgr;
    }


    public function addBatchMigrations(array $mgrs)
    {
        $mgrs = array_filter(
            $mgrs,
            function ($v) {
                return $v instanceof BixMigAbstract;
            }
        );
        if (sizeof($mgrs)) {
            $this->migrations = array_merge($this->migrations, $mgrs);
        }
    }


    public function executeMigrations($up = true)
    {
        foreach ($this->migrations as $mgr) {
            if ($up) {
                $r = $mgr->executeUp();
            } else {
                $r = $mgr->executeDown();
            }

            if ($r) {
                // как-то распознать оишбки!
            } else {
                //$mgr->setDateChanged()->setStatus()->update();
            }
        }

        return $this->getErrors();
    }


    public function getErrors()
    {
        return $this->errors;
    }
}
