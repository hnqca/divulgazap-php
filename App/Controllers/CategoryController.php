<?php
    
    namespace App\Controllers;
    use App\Core\Controller;
    use App\Models\Category;
    use App\Models\Group;
    use App\View\View;

    class CategoryController extends Controller {

        public function renderPage()
        {
            $categories = (new Category)->select(true, "id, name, icon");

            foreach ($categories as &$item) {
                $item->total_groups = (new Group)->countGroupsInCategory($item->id);
            }
            
            return (new View)->renderPage(filePage: "page-categories.html", variables: ['categories' => $categories]);
        }
        
    }