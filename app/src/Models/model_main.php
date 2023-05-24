<?php

class Model_Main extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            "id" => "int",
            "name" => "str",
            "manufactor" => "int",
            "price" => "float",
            "avaliable" => "bool",
            "gender" => "int",
            "type" => "int",
            "size" => "int"
        );
        $this->constrains = [
            "manufactor" => ["table" => "manufactors", "cond" => "id"],
            "type" => ["table" => "types", "cond" => "id"],
            "size" => ["table" => "sizes", "cond" => "id"]
        ];
        $this->orderby = 'id';
        $this->table = "shop";
    }
}
