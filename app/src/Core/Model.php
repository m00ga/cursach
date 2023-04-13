<?php

class Model
{
    protected PDO $conn;

    function __construct()
    {
        $this->conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    }

    function create(array $data)
    {

    }

    function read(int $id)
    {

    }

    function update(int $id, array $data)
    {

    }

    function delete(int $id)
    {

    }
}
