<?php

class Model_User extends Model
{
    function __construct()
    {
        parent::__construct();
        $this->schema = array(
            'id' => 'int',
            'login' => 'str',
            'pass_hash' => 'str',
            'role' => 'int'
        );
        $this->table = 'users';
        $this->readby = 'login';
    }
}
