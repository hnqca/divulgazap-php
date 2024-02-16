<?php

namespace App\Http\Controllers;

use App\Helpers\Env;
use App\Helpers\Sanitize;
use App\Http\Request;
use App\Http\Response;
use App\Models\Category;
use App\Models\Group;
use App\Services\RecaptchaService;
use App\Services\WebScrapingService;
use App\View\View;

class GroupController extends Controller
{
    /**
     * Responsável por exibir a página contendo a lista de grupos.
     */
    public function renderListingPage(Request $request)
    {
        $categoryName = $request->getQueryParams()->categoria ?? null;

        $groups = $categoryName
            ? (new Group)->findAllByCategoryName($categoryName)
            : (new Group)->findAll();

        $groups = $this->populateGroupsWithCategoryNames($groups);

        return (new View)->renderHTML(fileName: 'pages/home', variables: [
            'groups'       => $groups,
            'categoryName' => $categoryName
        ]);
    }


    /**
     * Responsável por exibir a página de detalhes do grupo
     */
    public function renderDetailsPage(Request $request)
    {
        $id = $request->getParams()->id;
        $id = Sanitize::integer($id);

        $data = (new Group)->find("id = {$id}")->select();

        return (new View)->renderHTML('pages/group-details', [
            'group' => $data
        ]);
    }

    /**
     * Responsável por exibir a página para divulgar um novo grupo.
     */
    public function renderCreationPage(Request $request)
    {
        $categories = (new Category)->select(true);

        return (new View)->renderHTML('pages/group-create', [
            'categories'           => $categories,
            'reCAPTCHA_public_key' => Env::get('reCAPTCHA_public_key')
        ]);
    }

    /**
     * Responsável por verificar se o link do grupo é válido ou não.
     */
    public function validateLink(Request $request)
    {
        $link = $request->getParams()->link;
       
        $this->checkGroupAlreadyCreated($link);

        $data = $this->getScrapingGroupData($link);

        return (new Response)->sendResponse(200, [
            'status' => 'success',
            'group'  => $data
        ]);
    }

    /**
     * Responsável por verificar se o grupo já está sendo divulgado.
     */
    private function checkGroupAlreadyCreated(string $link)
    {
        $group = (new Group)->find("link = {$link}")->select();

        if (isset($group->id)) {
            return (new Response)->json(400, [
                'status'  => 'error',
                'message' => "Este grupo já está sendo divulgado"
            ]);
        }
    }

    /**
     * Responsável por cadastrar um novo grupo no banco de dados.
     */
    public function create(Request $request)
    {
        $body = $request->checkDataRequest(requiredFields: ['name', 'id_category', 'link', 'description', 'recaptchaResponse']);

        // Validação do Google reCAPTCHA.
        RecaptchaService::validate($body->recaptchaResponse);

        $this->checkGroupAlreadyCreated($body->link);

        $groupData = $this->getScrapingGroupData($body->link);
        $image     = self::saveGroupImage($groupData['image']);

        $group = (new Group)->setData([
            'id_category' => $body->id_category,
            'name'        => $body->name,
            'image'       => $image,
            'link'        => $body->link,
            'description' => $body->description
        ]);

        $groupId = $group->save();

        if (!$groupId) {
            return (new Response)->json(500, [
                'status'  => "error",
                'message' => "Não foi possível salvar o grupo no banco de dados"
            ]);
        }

        return (new Response)->json(201, ['status' => "success"]);
    }


    /**
     * Responsável por salvar a imagem do grupo no servidor
     */
    private function saveGroupImage(string $imageUrl)
    {
        // Diretório onde a imagem será salva
        $directory = __DIR__ . '/../../../public/assets/images/groups/';

        // Obtendo o conteúdo da imagem
        $imageContent = file_get_contents($imageUrl);

        // Gerando um nome de arquivo aleatório
        $randomFilename = uniqid() . ".jpg"; // ou md5(uniqid()) . ".jpg";

        // Caminho completo do arquivo
        $filename = $directory . $randomFilename;

        // Salvando o conteúdo da imagem em um arquivo local
        file_put_contents($filename, $imageContent);

        return $randomFilename;
    }

    /**
     * Responsável por capturar informações do grupo de WhatsApp com base no link de convite fornecido pelo usuário.
     */
    private function getScrapingGroupData(string $inviteLink)
    {
        // URL de convite do grupo do WhatsApp:
        $inviteLink = "https://chat.whatsapp.com/{$inviteLink}";

        // Expressões regulares para encontrar o nome e a URL da imagem do grupo:
        $patterns = [
            'name'  => '/<h3 class="_9vd5 _9scr" style="color:#5E5E5E;">(.*?)<\/h3>/s',
            'image' => '/<img class="_9vx6" src="(.*?)"/'
        ];

        $scraping = new WebScrapingService($inviteLink);

        $data = [
            'name'  => $scraping->getMatches($patterns['name'])[1][0],
            'image' => $scraping->getMatches($patterns['image'])[1][0]
        ];

        if ($data['name'] === "" OR !isset($data['image'])) {
            return (new Response)->json(400, [
                'status'  => "error",
                'message' => "Link de convite do grupo é inválido."
            ]);
        }

        $data['image'] = html_entity_decode($data['image']);

        return $data;
    }

    private function populateGroupsWithCategoryNames($groups = [])
    {
        foreach ($groups as &$group) {
            $group->category = (new Category)->find("id = {$group->id_category}")->select(false, "name");
        }

        return $groups;
    }
}