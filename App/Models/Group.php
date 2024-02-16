<?php

namespace App\Models;

class Group extends Model
{
    public function __construct()
    {
        parent::__construct(tableName: "groups", primaryKey: "id", timestamps: true);
    }

    public function countGroupsInCategory(int $categoryId)
    {
        return $this->find("visible = 1, id_category = {$categoryId}")->count('id');
    }

    public function findAllByCategoryName(string $name)
    {
        $category = (new Category)->find("name = {$name}")->select(false, 'id');

        if (!isset($category->id)) {
            return [];
        }

        return $this->find("visible = 1, id_category = {$category->id}")->orderBy(['created_at' => "DESC"])->select(true) ?: [];
    }

    public function findAll()
    {
        return $this->find("visible = 1")->orderBy(['updated_at' => "DESC"])->select(true) ?: [];
    }

}