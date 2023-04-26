<?php

class CreateGendersTable extends AbstractMigration{

    function __construct(){
        parent::__construct();
    }

    function up(){
        return "CREATE TABLE IF NOT EXISTS genders(
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                gender VARCHAR(64) NOT NULL
            )";
    }

    function down(){
        return "DROP TABLE genders";
    }
}
