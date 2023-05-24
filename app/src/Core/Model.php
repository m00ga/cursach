<?php

function isInteger($val)
{
    return ctype_digit(strval($val));
}

class Model
{
    protected PDO $conn;
    protected array $schema;
    protected string $table;
    protected array $data;
    protected array|null $constrains;
    protected string|null $orderby;

    function __construct(bool $makeConn = true)
    {
        if($makeConn) {
            $this->conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
        }
        $this->schema = array();
        $this->constrains = null;
        $this->data = array();
        $this->orderby = null;
        $this->table = '';
    }

    private function _validate($val, $typ)
    {
        if($typ == "int" || $typ == "float") {
            $res = is_numeric($val);
        }else if ($typ == "str") {
            $res = is_string($val);
        }else{
            $res = ("is_".$typ)($val);
        }
        if($res === false) {
            return false;
        }
        return ($typ."val")($val);
    }

    // Second most complex thing in Model
    // For validating $data by schema
    protected function verify(array $data, array &$intr = null): bool|int
    {
        $keys = array_keys($data);
        $sch_keys = array_keys($this->schema);
        $inter = array_intersect($sch_keys, $keys);
        $ret = [];
        foreach($inter as $k){
            $typ = $this->schema[$k];
            if(is_array($data[$k])) {
                foreach($data[$k] as $subk=>$subv){
                    $res = $this->_validate($subv, $typ);
                    if($res === false) {
                        return false;
                    }else{
                        $ret[$k][$subk] = $res;
                    }
                }
            }else{
                $res = $this->_validate($data[$k], $typ);
                if($res === false) {
                    return false;
                }else{
                    $ret[$k] = $res;
                }
            }
        }

        $intr = $ret;
        $cnt = count(array_keys($ret));
        if($cnt === count($sch_keys)) {
            return true;
        }else{
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

    private function _resolveConstraints()
    {
        $sql = "SELECT ";
        $ret_sql = "";
        $joins = "";
        foreach(array_keys($this->schema) as $key){
            if(in_array($key, array_keys($this->constrains))) {
                $data = $this->constrains[$key];
                $ret_sql .= $data['table'].".".$key.", ";
                $joins .= "INNER JOIN ".$data['table']."\n";
                $joins .= "ON ".$this->table.".".$key." = ".$data['table'].".".$data['cond']."\n";
            }else{
                $ret_sql .= $this->table.".".$key.", ";
            }
        }
        $ret_sql = rtrim($ret_sql, ', ');
        $sql .= $ret_sql." FROM ".$this->table."\n";
        $sql .= $joins;
        return $sql;
    }

    public function getAll($mode = PDO::FETCH_BOTH)
    {
        $sql = '';
        if($this->constrains === null) {
            $sql = "SELECT * FROM ".$this->table;
        }else{
            $sql = $this->_resolveConstraints();
        }
        if($this->orderby !== null) {
            $sql .= " ORDER BY ".$this->orderby;
        }
        $query = $this->conn->query($sql);
        return $query->fetchAll($mode);
    }

    public function count($cond = "id", $args = null)
    {
        $sql = "SELECT COUNT(".$cond.") FROM ".$this->table;
        if($args !== null) {
            $res = $this->verify($args, $inter);
            if($res === false) {
                return false;
            }
            $cond = $this->_buildCond($inter);
            $sql .= " WHERE ".$cond;
        }
        $query = $this->conn->prepare($sql);
        if($args !== null) {
            $this->_prepareQuery($query, $inter);
        }
        $query->execute();
        return $query->fetchColumn();
    }

    private function limit(array $params, string &$cond)
    {
        $keys = array_keys($params);
        $ret = [];
        if(in_array("limit", $keys)) {
            if($this->_validate($params['limit'], 'int') !== false) {
                $cond .= " LIMIT :limit";
                $ret['limit'] = $params['limit'];
            }
        }
        
        if(in_array("offset", $keys)) {
            if($this->_validate($params['offset'], 'int') !== false) {
                if(!isset($ret['limit'])) {
                    $cond .= " LIMIT 9";
                }
                $cond .= " OFFSET :offset";
                $ret['offset'] = $params['offset'];
            }
        }

        return $ret;
    }

    private function _buildCond($conds)
    {
        $cond = "";
        foreach($conds as $key=>$val){
            if(is_array($val)) {
                $cond .= "( ";
                foreach($val as $ind=>$subval){
                    if(str_contains($subval, '%')) {
                        $cond .= $this->table."."."$key"." LIKE ".":".$key.$ind." OR ";
                    }else{
                        $cond .= $this->table.".".$key." = ".":".$key.$ind." OR ";
                    }
                }
                $cond = substr($cond, 0, -3);
                $cond .= ") AND ";
            }else{
                if(str_contains($val, "%")) {
                    $cond .= $this->table.".".$key." LIKE ".":".$key." AND ";
                }else{
                    $cond .= $this->table.".".$key." = ".":".$key." AND ";
                }
            }
        }
        $cond = substr($cond, 0, -4);
        return $cond;
    }

    private function _prepareQuery(&$query, $args)
    {
        foreach($args as $k=>$v){
            if(is_array($v)) {
                foreach($v as $ind => $subk){
                    $query->bindValue($k.$ind, $subk);
                }
            }else{
                $query->bindValue($k, $v);
            }
        }
    }

    // Most complex thing in Model
    // filter by scheme + limit and offset
    // works with array parameters in GET request
    // validate by intersection with scheme
    function filterBy(array $params)
    {
        $res = $this->verify($params, $inter);
        if($res === false) {
            return false;
        }
        $cond = $this->_buildCond($inter);
        if($this->constrains === null) {
            $sql = "SELECT * FROM ".$this->table;
        }else{
            $sql = $this->_resolveConstraints();
        }
        if($cond !== "") {
            $sql .= " WHERE ".$cond;
        }
        if($this->orderby !== null) {
            $sql .= " ORDER BY ".$this->orderby;
        }
        $limit = $this->limit($params, $sql);
        $query = $this->conn->prepare($sql);
        foreach($limit as $k=>$v){
            $query->bindValue($k, abs(intval($v)), PDO::PARAM_INT);
        }
        $this->_prepareQuery($query, $inter);
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
        foreach($inter as $k=>$v){
            $query->bindParam($k, $v);
        }
        $query->execute();
        return true;
    }

    function read(int $id)
    {
        if($this->constrains === null) {
            $sql = "SELECT * FROM ".$this->table;
        }else{
            $sql = $this->_resolveConstraints();
        }
        $query = $this->conn->prepare($sql." WHERE ".$this->table.".id"." = :id");
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
        $res = $this->verify($data, $inter);
        if($res === false) {
            return false;
        }
        $values = "";
        foreach(array_keys($inter) as $k){
            $values .= $k." = ".":".$k.",";
        }
        $values = rtrim($values, ',');
        $sql = "UPDATE ".$this->table." SET ".$values." WHERE id = :id";
        $query = $this->conn->prepare($sql);
        $query->bindParam("id", $id);
        foreach($inter as $k=>$v){
            $query->bindParam($k, $v);
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
