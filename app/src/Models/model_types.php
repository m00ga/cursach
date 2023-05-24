<?php

class Model_Type extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            "id" => "int",
            'type' => "str"
        );
        $this->table = "types";
    }
}
