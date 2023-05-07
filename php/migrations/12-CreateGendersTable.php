<?php

class CreateGendersTable extends AbstractMigration
{
    function __construct()
    {
        parent::__construct();
    }

    function up()
    {
        return "CREATE TABLE `genders` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `gender` varchar(64) NOT NULL
            )";
    }

    function down(){
        return "DROP TABLE genders";
    }
} 
