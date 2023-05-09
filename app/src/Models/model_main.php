<?php

class Model_Main extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            "name" => "str",
            "manufactor" => "int",
            "price" => "float",
            "avaliable" => "bool",
            "gender" => "int",
            "type" => "int",
            "size" => "int"
        );
        $this->table = 'shop';
    }
}
