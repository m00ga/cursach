<?php

require "migrations/AbstractMigration.php";

function cmp($a, $b)
{
    $args_a = explode('-', basename($a, ".php"));
    $args_b = explode('-', basename($b, ".php"));
    $cmp1 = intval($args_a[0]);
    $cmp2 = intval($args_b[0]);

    return ($cmp1 < $cmp2)? -1:1;
}

function R_cmp($a, $b)
{
    return (cmp($a, $b) === -1)? 1:-1;
}

function printUsage()
{
    echo "USAGE: php migrate.php (up || down) [migration name]";
    exit(1);
}

function applyMigration($name, $conn, $mode, &$depend)
{
    include __DIR__."/migrations/".$name;
    $mig_name = explode('-', basename($name, ".php"))[1];
    $b_name = basename($name, '.php');
    if(array_key_exists($b_name, $depend) === true && intval($depend[$b_name]) === $mode) {
        return;
    }
    $mig_obj = new $mig_name;
    if(count($mig_obj->dependencies) != 0) {
        foreach($mig_obj->dependencies as $dep){
            if(!array_key_exists($dep, $depend) || (array_key_exists($dep, $depend) && $depend[$dep] === false)) {
                applyMigration($dep.".php", $conn, 1, $depend);
            }
        }
    }
    if($mode == 1) {
        $sql = $mig_obj->up();
        $depend[$b_name] = true;
    }else{
        $sql = $mig_obj->down();
        $depend[$b_name] = false;
    }
    $conn->exec($sql);
    print_r($conn->errorInfo(), true);
}

if($argc < 2) {
    printUsage();
}

if($argv[1] !== "up" && $argv[1] !== "down") {
    printUsage();
}

$mode = ($argv[1] == "up")? 1:0;

if($argc === 3) {
    $migration = $argv[2].".php";
    if(!file_exists(__DIR__."/migrations/".$migration)) {
        echo "Migration doesn't exists";
        exit(1);
    }
}else{
    $files = array_diff(scandir(__DIR__."/migrations"), array('..', '.', 'AbstractMigration.php'));
    if(count($files) === 0) {
        exit();
    }
    usort($files, ($mode == 1)? "cmp":"R_cmp");
}

$dependencies = array();

if(file_exists(__DIR__.'/migrations.json')) {
    $dependencies = json_decode(file_get_contents(__DIR__."/migrations.json"), true);
}

try{
    $conn = new PDO("mysql:host=127.0.0.1;dbname=".getenv("MYSQL_DATABASE"), getenv("MYSQL_USER"), getenv("MYSQL_PASSWORD"));
} catch(PDOException $e){
    echo $e->getMessage();
    exit(1);
}

if(isset($migration)) {
    applyMigration($migration, $conn, $mode, $dependencies);
}else{
    foreach($files as $file){
        applyMigration($file, $conn, $mode, $dependencies);
    }
}

$file = fopen(__DIR__."/migrations.json", "w");
$data = json_encode($dependencies);
fwrite($file, $data);
