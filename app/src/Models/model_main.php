<?php

class Model_Main extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            "id" => "int",
            "name" => "str",
            "img" => "str",
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
        $this->groupby = 'name';
        $this->togroup = ['id', 'avaliable', 'size'];
        $this->table = "shop";
    }
}
