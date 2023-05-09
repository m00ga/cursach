<?php

class Model_Size extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            "id",
            "size"
        );
        $this->table = "sizes";
    }
}
