<?php

    namespace App\Models;
    use HenriqueCacerez\MasterPDO\MasterLayer;

    class Category extends MasterLayer {

        function __construct()
        {
            parent::__construct(tableName: "categories", primaryKey: "id", timestamps: true);
        }

        public function findByName(string $name)
        {
            $this->find("name = {$name}")->select();

            return $this;
        }

    }