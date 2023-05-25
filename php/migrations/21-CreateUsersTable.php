<?php

class CreateUsersTable extends AbstractMigration
{

    function __construct()
    {
        parent::__construct();
    }

    function up()
    {
        return "CREATE TABLE `users` (
            id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            login varchar(128) NOT NULL UNIQUE,
            pass_hash varchar(255) NOT NULL,
            role TINYINT NOT NULL
            )";
    }

    function down()
    {
        return "DROP TABLE `users`";
    }
}
