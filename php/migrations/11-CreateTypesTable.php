<?php

class CreateTypesTable extends AbstractMigration{

    function __construct(){
        parent::__construct();
    }

    function up(){
        return "CREATE TABLE IF NOT EXISTS types(
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                type VARCHAR(64) NOT NULL
            )";
    }

    function down(){
        return "DROP TABLE types";
    }
}
