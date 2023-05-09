<?php

class Model_Cart extends Model
{
    function __construct()
    {
        parent::__construct(false);
        $this->schema = array(
            "id" => "int",
            "prod_id" => "int",
            "amount" => "int"
        );
    }

    function getAll($mode = PDO::FETCH_BOTH)
    {
        return (isset($_SESSION['cart']))? $_SESSION['cart']:false;
    }

    function filterBy(array $params)
    {
        $res = $this->verify($params, $inter);
        if($res !== true) {
            return false;
        }
        $ret = [];
        $cart = $this->getAll();
        if($cart === false) {
            return false;
        }
        $cnt = 0;
        foreach($cart as $elem){
            $cnt = 0;
            foreach($inter as $k=>$v){
                if($elem[$k] == $v) {
                    $cnt++;
                } 
            }
            if($cnt == $res) {
                $ret[] = $elem; 
            }
        }
        if(!empty($ret)) {
            return $ret;
        }else{
            return false;
        }
    }

    function create(array $data)
    {
        $res = $this->verify($data, $inter);
        if($res !== true) {
            return false;
        }
        if(!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }
        $_SESSION['cart'][$inter['id']] = $inter;
        return true;
    }

    function read(int $id)
    {
        $cart = $this->getAll();
        if($cart === false) {
            return false;
        }
        if(isset($cart[$id])) {
            return $cart[$id];
        }else{
            return false;
        }
    }

    function update(int $id, array $data)
    {
        $res = $this->verify($data, $inter);
        if($res === false) {
            return false;
        }
        $cart = $this->getAll();
        if($cart === false) {
            return false;
        }
        if(isset($cart[$id])) {
            $_SESSION['cart'][$id] = array_replace($cart[$id], $inter);
            return true;
        }else{
            return false;
        }
    }

    function delete(int $id)
    {
        $cart = $this->getAll();
        if($cart === false) {
            return false;
        }
        if(isset($cart[$id])) {
            unset($_SESSION['cart'][$id]);
            return true;
        }else{
            return false;
        }
    }
}
