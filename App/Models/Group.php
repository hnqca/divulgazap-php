<?php

    namespace App\Models;
    
    use App\Services\WebScrapingService;
    use HenriqueCacerez\MasterPDO\MasterLayer;

    class Group extends MasterLayer {

        function __construct()
        {
            parent::__construct(tableName: "groups", primaryKey: "id", timestamps: true);
        }

        public function findByLink(string $link)
        {
            $this->find("link = {$link}")->select();

            return $this;
        }

        public function findAllAvailable()
        {
            return $this->find("visible = 1")->orderBy(['updated_at' => "DESC"])->select(true);
        }

        public function findAllByCategory(string $name)
        {
            $category = (new Category)->findByName($name);

            if (!isset($category->data->id)) {
                return [];
            }

            $categories = $this->find("id_category = {$category->data->id}")->orderBy(['created_at' => "DESC"])->select(true);

            return $categories ? $categories : [];
        }

        public function getScrapingGroupData(string $inviteLink)
        {
            // URL de convite do grupo do WhatsApp:
            $inviteLink = "https://chat.whatsapp.com/{$inviteLink}";

            // Expressões regulares para encontrar o nome e a URL da imagem do grupo:
            $patterns = [
                'name'  => '/<h3 class="_9vd5 _9scr" style="color:#5E5E5E;">(.*?)<\/h3>/s',
                'image' => '/<img class="_9vx6" src="(.*?)"/'
            ];

            $scraping = new WebScrapingService(url: $inviteLink);

            $data = [
                'name'  => $scraping->getMatches($patterns['name'])[1][0],
                'image' => $scraping->getMatches($patterns['image'])[1][0]
            ];

            if ($data['name'] === "" OR !isset($data['image'])) {
                throw new \InvalidArgumentException("Link de convite do grupo é inválido.");
            }

            $data['image'] = html_entity_decode($data['image']);;

            return $data;
        }

        public function getCategories()
        {
            foreach ($this->data as &$item) {
                $item->category = (new Category)->find("id = {$item->id_category}")->select(false, "name");
            } 

            return $this;
        }

        public function countGroupsInCategory(int $categoryId)
        {
            return $this->find("id_category = {$categoryId}")->count() ?? 0;
        }
    }