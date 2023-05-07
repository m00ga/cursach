<?php

class CreateShopTable extends AbstractMigration
{

    function __construct()
    {
        parent::__construct();
        $this->dependencies = [
            "10-CreateManufactorsTable",
            "11-CreateTypesTable",
            "12-CreateGendersTable",
            "13-CreateSizesTable"
        ];
    }

    function up()
    {
        return "CREATE TABLE `shop` (
            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `name` varchar(255) NOT NULL,
            `manufactor` int NOT NULL,
            `price` decimal(5,2) NOT NULL,
            `avaliable` tinyint NOT NULL,
            `gender` int NOT NULL,
            `type` int NOT NULL,
            `size` int NOT NULL,
            FOREIGN KEY (`manufactor`) REFERENCES `manufactors` (`id`) ON DELETE CASCADE,
            FOREIGN KEY (`gender`) REFERENCES `genders` (`id`) ON DELETE CASCADE,
            FOREIGN KEY (`type`) REFERENCES `types` (`id`) ON DELETE CASCADE,
            FOREIGN KEY (`size`) REFERENCES `sizes` (`id`) ON DELETE CASCADE
        );";
    }

    function down()
    {
        return "DROP TABLE shop";
    }
}
