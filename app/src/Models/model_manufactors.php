<?php

class Model_Manufactor extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            "id" => "int",
            'manufactor' => "str"
        );
        $this->table = "manufactors";
    }
}
