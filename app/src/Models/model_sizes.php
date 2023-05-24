<?php

class Model_Size extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            "id" => "int",
            "size" => "str"
        );
        $this->table = "sizes";
    }
}
