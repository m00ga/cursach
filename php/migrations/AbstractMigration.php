<?php

abstract class AbstractMigration
{
    public array $dependencies;

    function __construct(){
        $this->dependencies = array();
    }

    abstract function up();

    abstract function down();
}
