<?php

class Model_Manufactor extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            "id",
            'manufactor'
        );
        $this->table = "manufactors";
    }
}
