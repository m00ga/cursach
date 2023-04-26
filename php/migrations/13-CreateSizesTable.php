<?php

class CreateSizesTable extends AbstractMigration{

    function __construct(){
        parent::__construct();
    }

    function up(){
        return "CREATE TABLE IF NOT EXISTS sizes(
                id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                size VARCHAR(64) NOT NULL
            )";
    }

    function down(){
        return "DROP TABLE sizes";
    }
}
