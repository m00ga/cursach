<?php

class CreateManufactorsTable extends AbstractMigration{

    function __construct(){
        parent::__construct();
    }

    function up(){
        return "CREATE TABLE IF NOT EXISTS manufactors(
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                manufactor VARCHAR(64) NOT NULL
            )";
    }

    function down(){
        return "DROP TABLE manufactors";
    }
}
