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

    public function getAll($mode = PDO::FETCH_BOTH)
    {
        $query = $this->conn->query("SELECT * FROM ".$this->table);
        return $query->fetchAll($mode);
    }

    function filterBy(array $params)
    {
        $this->verify($params, $inter);
        $cond = "";
        foreach($inter as $k){
            if(is_array($params[$k])) {
                $cond .= "( ";
                foreach($params[$k] as $ind => $subk){
                    $cond .= $k." = ".":".$k.$ind." OR "; 
                }
                $cond = substr($cond, 0, -3);
                $cond .= ") AND ";
            }else{
                $cond .= $k." = ".":".$k." AND ";
            }
        }
        $cond = substr($cond, 0, -4);
        $query = $this->conn->prepare("SELECT * FROM ".$this->table." WHERE ".$cond);
        foreach($inter as $k){
            if(is_array($params[$k])) {
                foreach($params[$k] as $ind => $subk){
                    $query->bindValue($k.$ind, $subk);
                }
            }else{
                $query->bindValue($k, $params[$k]);
            }
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    function create(array $data)
    {
        $res = $this->verify($data, $inter);
        if($res !== true) {
            return false;
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
        return true;
    }

    function read(int $id)
    {
        $query = $this->conn->prepare("SELECT * FROM ".$this->table." WHERE id = :id");
        $query->execute(array('id' => $id));
        $res = $query->fetch(PDO::FETCH_ASSOC);
        if($res !== false) {
            $this->data = $res;
            return $res;
        }
        return false;
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
        if($query->rowCount() > 0) {
            return true;
        }else{
            return false;
        }
    }

    function delete(int $id)
    {
        $query = $this->conn->prepare("DELETE FROM ".$this->table." WHERE id = :id");
        $query->execute(array('id' => $id));
        if($query->rowCount() > 0) {
            return true;
        }else{
            return false;
        }
    }
}
