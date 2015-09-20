<?php
namespace Um;

use Bitrix\Main\Type;

abstract class BixMigAbstract {

   protected
        $add_date,
        $change_date,
        $code,
        $id,
        $status;

    abstract public function executeUp();


    abstract public function executeDown();


    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


    public function getId()
    {
        return $this->id;
    }


    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }


    public function getCode()
    {
        return $this->code;
    }


    public function setStatus($status)
    {
        $status = trim($status);
        if ($this->statusAllowed($status)) {
            $this->status = $status;
        }

        return $this;
    }


    public function getStatus()
    {
        return $this->status;
    }


    public function setAddDate($date)
    {
        $this->add_date = $date;

        return $this;
    }


    public function getAddDate()
    {
        return $this->add_date;
    }


    public function setChangeDate($date)
    {
        $this->change_date = $date;

        return $this;
    }


    public function getChangeDate()
    {
        return $this->change_date;
    }


    public function update()
    {
        $res = BixMigTable::update(
            $this->id,
            array(
                'CODE' => $this->code,
                'STATUS' => $this->status,
                'CHANGE_DATE' => new Type\DateTime($this->change_date, 'd.m.Y H:i:s'),
                'ADD_DATE' => new Type\DateTime($this->add_date, 'd.m.Y H:i:s'),
            )
        );

        if (!$res->isSuccess()) {
            // TODO - dunno what to do
            //foreach ($res->getErrors() as $error) {}
        }
    }


    public function add()
    {
        $res = BixMigTable::add(array(
            'CODE' => $this->code,
            'STATUS' => $this->status,
            'CHANGE_DATE' => new Type\DateTime($this->change_date, 'd.m.Y H:i:s'),
            'ADD_DATE' => new Type\DateTime($this->add_date, 'd.m.Y H:i:s'),
        ));

        if (!$res->isSuccess()) {
            // TODO - dunno what to do
            //foreach ($res->getErrors() as $error) {}
        }
    }


    protected function statusAllowed($status)
    {
        return in_array($status, array('UNKNOWN', 'UP', 'DOWN'));
    }
}

