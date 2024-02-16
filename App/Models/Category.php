<?php

namespace App\Models;

class Category extends Model
{
    public function __construct()
    {
        parent::__construct(tableName: "categories", primaryKey: "id", timestamps: true);
    }
}