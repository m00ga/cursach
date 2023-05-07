<?php

class Model
{
    protected PDO $conn;
    protected array $schema;
    protected string $table;
    protected array $data;

    function __construct()
    {
        $this->conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
        $this->schema = array();
        $this->data = array();
        $this->table = '';
    }

    protected function verify(array $data, array &$intr = null): bool|int
    {
        $keys = array_keys($data);
        $inter = array_intersect($this->schema, $keys);
        $cnt = count($inter);

        $intr = $inter;

        if ($cnt === count($this->schema)) {
            return true;
        } else {
            return $cnt;
        }
    }

    public function __get($name)
    {
        if(array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }else{
            throw new Exception("Property doesn't exist");
        }
    }

    public function getAll()
    {
        $query = $this->conn->query("SELECT * FROM ".$this->table);
        return $query->fetchAll(PDO::FETCH_BOTH);
    }

    function create(array $data)
    {
        $res = $this->verify($data, $inter);
        if($res !== true) {
            return;
        }
        $values = "";
        $binds = "";
        foreach($this->schema as $k){
            $values .= $k.",";
            $binds .= ":".$k.",";
        }
        $values = rtrim($values, ',');
        $binds = rtrim($binds, ',');
        $sql = 'INSERT INTO '.$this->table." (".$values.") VALUES (".$binds.")";

        $query = $this->conn->prepare($sql);
        foreach($inter as $k){
            $query->bindParam($k, $data[$k]);
        }
        $query->execute();
    }

    function read(int $id)
    {
        $query = $this->conn->prepare("SELECT * FROM ".$this->table." WHERE id = :id");
        $query->execute(array('id' => $id));
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if($res !== false) {
            $this->data = $res;
        }
    }

    function update(int $id, array $data)
    {
        $this->verify($data, $inter);
        $values = "";
        foreach($inter as $k){
            $values .= $k." = ".":".$k.",";
        }
        $values = rtrim($values, ',');
        $sql = "UPDATE ".$this->table." SET ".$values." WHERE id = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam("id", $id);
        foreach($inter as $k){
            $query->bindParam($k, $data[$k]);
        }
        $query->execute();
    }

    function delete(int $id)
    {
        $query = $this->conn->prepare("DELETE FROM ".$this->table." WHERE id = :id");
        $query->execute(array('id' => $id));
    }
}
