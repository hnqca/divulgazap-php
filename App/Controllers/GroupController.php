<?php

    namespace App\Controllers;

    use App\Core\Controller;
    use App\Models\Category;
    use App\Models\Group;
    use App\Services\NotSafeForWorkService;
    use App\Services\RecaptchaService;
    use App\View\View;

    class GroupController extends Controller {

        private ?Group    $groupModel;
        private ?Category $categoryModel;

        function __construct()
        {
            $this->groupModel    = new Group();
            $this->categoryModel = new Category();
        }

        public function renderPageListing()
        {
            $categoryName = $_GET['category'] ?? null;
            
            $groups = $categoryName
                ? $this->groupModel->findAllByCategory($categoryName)
                : $this->groupModel->findAllAvailable();

            $groups = $this->prepareGroupsData($groups);

            return (new View)->renderPage("page-group-listing.html", ['groups' => $groups, 'categoryName' => $categoryName]);
        }

        public function renderPageDetail(array $data)
        {
            $groupId = $data['id'] ?? null;
            $group   = $this->groupModel->findById($groupId);

            if (!isset($this->groupModel->data->id)) {
                parent::redirect('/');
            }

            $group = $this->prepareGroupsData([$group->data])[0];

            return (new View)->renderPage("page-group-detail.html", ['group' => $group]);
        }

        public function renderPageCreate()
        {
            $categories = $this->categoryModel->orderBy(["name" => "ASC"])->select(true, "id, name");

            return (new View)->renderPage("page-group-create.html", ['categories' => $categories]);
        }

        public function findGroupData()
        {
            $dataRequest       = parent::checkDataRequest(requiredFields: ['link']);
            $scrapingGroupData = $this->scrapingGroupData($dataRequest->link);

            return View::renderJson([
                'status' => 'success',
                'group'  => $scrapingGroupData
            ]);
        }

        public function create()
        {
            $dataRequest = parent::checkDataRequest(requiredFields: ['name', 'id_category', 'link', 'description', 'recaptchaResponse']);

            if (mb_strlen($dataRequest->name) < 2) {
                return View::renderError("este nome é muito curto!");
            }

            RecaptchaService::validate($dataRequest->recaptchaResponse);

            $scrapingGroupData = $this->scrapingGroupData($dataRequest->link);
            $image = $scrapingGroupData['image'];

            $nsfw = (new NotSafeForWorkService)->checkImage($image);

            if(!$nsfw->secureToSave) {
                return View::renderJson([
                    'status'  => 'error',
                    'message' => "Não foi possível continuar. Imagem do grupo contém conteúdo inapropriado.",
                    'nsfw'    => $nsfw
                ]);
            }

            $this->groupModel->data->id_category = $dataRequest->id_category ?? 1;
            $this->groupModel->data->name        = $dataRequest->name;
            $this->groupModel->data->image       = urlencode($image);
            $this->groupModel->data->link        = $dataRequest->link;
            $this->groupModel->data->description = $dataRequest->description ?? null;

            $groupId = (int) $this->groupModel->save();

            if (!$groupId) {
                return View::renderError("Não foi possível salvar o grupo");
            }

            return View::renderJson([
                'status' => 'success',
                'id'     => $groupId,
                'nsfw'   => $nsfw
            ]);
        }

        public function read()
        {
            $groups = $this->groupModel->orderBy(["updated_at" => "DESC"])->limit(10)->select(true) ?? [];
            $groups = $this->prepareGroupsData($groups);

            return View::renderJson([
                'status' => 'success',
                'groups' =>  $groups
            ]);
        }

        private function scrapingGroupData(string $link)
        {
            $groupExists = $this->groupModel->findByLink($link);

            if (isset($groupExists->data->id)) {
                return View::renderError("Este grupo já está sendo divulgado.");
            }

            return $this->groupModel->getScrapingGroupData($link);
        }

        private function prepareGroupsData($groups)
        {
            $groups = $groups ?: [];

            foreach ($groups as &$group) {
                $group->image    = urldecode($group->image);
                $group->category = $this->categoryModel->find("id = {$group->id_category}")->select(false, "name");
            }

            return $groups;
        }
    }
