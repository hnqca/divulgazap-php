<?php

namespace App\Http\Controllers;

use App\Http\Request;
use App\Models\Category;
use App\Models\Group;
use App\View\View;

class CategoryController extends Controller
{
    public function renderListingPage(Request $request)
    {
        $categories = (new Category)->find()->select(true);
        $categories = $this->populateCategoriesWithTotalGroups($categories);

        return (new View)->renderHTML('pages/categories', [
            'categories' => $categories,
            'testing'    => $request->getQueryParams()->name ?? null
        ]);
    }

    private function populateCategoriesWithTotalGroups(array $categories = [])
    {
        foreach ($categories as &$category) {
            $category->total_groups = (new Group)->countGroupsInCategory($category->id);
        }

        return $categories;
    }
}
