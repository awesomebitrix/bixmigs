<?php
/**
 *
 * @author u_mulder <m264695502@gmail.com>
 */
namespace Um;
class BixMigDispatcher
{

    const
        TABLE_NAME = 'um_bixmigs_migrations';

    public function loadMigrations()
    {
        $result = array(
            'mgrs' => array(),
            'headers' => array(
                array(
                    'id' => 'id',
                    'content' => 'Migration',
                    'sort' => 'id',
                    'align' => 'left',
                    'default' => true,
                ),
                array(
                    'id' => 'status',
                    'content' => 'Status',
                    'align' => 'right',
                    'default' => true,
                ),
                array(
                    'id' => 'date',
                    'content' => 'Date applied',
                    'align' => 'right',
                    'default' => true,
                ),
            ),
        );

        $db_mgrs = $this->loadDBMigrations();
        $di = new \DirectoryIterator(\UM_MODULE_MGR_FULL_PATH);
        while ($di->valid()) {
            if (!$di->isDot() && $this->hasProperFilename($di->getFilename())) {
                $filename = $di->getFilename();
                if (!array_key_exists($filename, $db_mgrs)) {
                    $result['mgrs'][] = array(
                        'id' => 201,
                        'm' => $filename,
                        's' => 'N',
                        'd' => null,
                    );
                } else {
                    // skip or what?
                    // unset $db_mgrs
                    /*$result[] = array(
                        'm' => ,
                        's' => ,
                        'd' => null,
                    );*/
                }
            }

            $di->next();
        }

        if (!empty($db_mgrs)) {
            //self::deleteOrphans(array_keys($db_mgrs));
        }

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

        $q = 'SELECT * FROM `' . self::TABLE_NAME . '` ORDER BY `id` DESC';
        $db_items = $this->connectionPool->query($q);
        while ($row = $db_items->fetch()) {
            echo'<pre>$row ',print_r($row),'</pre>';    // TODO
        }

        return $result;
    }


    public static function deleteOrphans($ids)
    {
        /*$q = 'DELETE FROM `' . self::TABLE_NAME . '` WHERE ? IN ()';
        $r = ->execute($q);*/
    }


    protected
        $migrations = array(),
        $errors = array(),
        $connectionPool = null;


    public function __construct(BixMigAbstract $mgr = null)
    {
        if (!is_null($mgr)) {
            $this->migrations[] = $mgr;
        }

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
