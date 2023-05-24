<?php

class Model_Gender extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            "id" => "int",
            "gender" => "str"
        );
        $this->table = "genders";
    }
}
