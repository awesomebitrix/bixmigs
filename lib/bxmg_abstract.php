<?php
namespace \Um;
abstract class BixMigAbstract {

    protected
        $id = 0;

    abstract public function executeUp();

    abstract public function executeDown();

    public function markApplied()
    {
        // do some shit here

    }



}

