<?php
/**
 *
 * @author u_mulder <m264695502@gmail.com>
 */
namespace Um;

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc as Loc;
Loc::loadMessages(__FILE__);

class BixMigDispatcher
{

    const
        DEFAULT_FILENAME_PATTERN = '/^Mgr_([0-9]{8})_([0-9]{6})_([a-z0-9_]+)\.php$/';

    protected
        $filename_pattern = '',
        $migrations = array(),
        $errors = array();

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
        $mgr_path = Option::get(
            UM_BM_MODULE_NAME,
            'migration_folder',
            UM_BM_MGR_PATH
        );
        $di = new \DirectoryIterator($_SERVER['DOCUMENT_ROOT'] . $mgr_path);
        while ($di->valid()) {
            if (!$di->isDot() && $this->hasProperFilename($di->getFilename())) {
                $filename = $di->getFilename();
                if (!array_key_exists($filename, $db_mgrs)) {
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

        if (!empty($db_mgrs)) {
            $this->deleteOrphans($db_mgrs);
        }

        return $result;
    }


    protected function hasProperFilename($filename)
    {
        if (!$this->filename_pattern) {
            $this->filename_pattern = Option::get(
                UM_BM_MODULE_NAME,
                'migration_filename_regexp',
                self::DEFAULT_FILENAME_PATTERN
            );
        }

        return preg_match($this->filename_pattern, $filename);
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
        foreach ($mgrs as $mgr) {
            $r = BixMigTable::delete($mgr->getId());
            // TODO - dunno what to do
            //if (!$r->isSuccess()) {}
        }
    }


    public function __construct(BixMigAbstract $mgr = null)
    {
        if (!is_null($mgr)) {
            $this->migrations[] = $mgr;
        }
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


    public function createMigration($id)
    {
        $result = false;

        $mgr_data = $this->getMigrationById($id);
        if ($mgr_data['CODE'] && $this->hasProperFilename($mgr_data['CODE'])) {
            $mgr_file = \UM_BM_MGR_FULL_PATH . $mgr_data['CODE'];
            if (file_exists($mgr_file)) {
                $class_name = $this->extractClassName($mgr_data['CODE']);
                if ($class_name) {
                    require_once $mgr_file;
                    $result = new $class_name;
                    $result->setId($id)
                        ->setCode($mgr_data['CODE']);
                }
            }
        }

        return $result;
    }


    protected function extractClassName($str)
    {
        return strstr($str, '.php', true);
    }


    public function executeMigrations($up = true)
    {
        foreach ($this->migrations as $mgr) {
            if ($up) {
                $r = $mgr->executeUp();
            } else {
                $r = $mgr->executeDown();
            }

            if (!$r) {
                // как-то распознать ошибки!
            } else {
                $mgr->setChangeDate(date('d.m.Y H:i:s'))
                    ->setStatus($up? 'UP' : 'DOWN')
                    ->update();
            }
        }

        //return $this->getErrors();
        return true;
    }


    public function getMigrationById($id)
    {
        $result = array();

        if (0 < (int)$id) {
            $result = BixMigTable::getById($id)->Fetch();
        }

        return $result;
    }


    public function getErrors()
    {
        return $this->errors;
    }
}
