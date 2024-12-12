<?php
namespace src\controllers;

use \core\Controller;
use \src\models\CategoryEcommerceModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class CategoryEcommerceController extends Controller
{

    public function index()
    {
        $dados = new CategoryEcommerceModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new CategoryEcommerceModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new CategoryEcommerceModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new CategoryEcommerceModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete()
    {
        $id = filter_input(INPUT_POST, "txtid");

        $dados = new CategoryEcommerceModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = trim($requestData['id']);
        $descricao = trim($requestData['descricao']);    
        $slug = trim($requestData['slug']); 
        $visivel = trim($requestData['visivel']); 
        $destaque = trim($requestData['destaque']); 

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "slug" => $slug,
            "visivel" => $visivel,
            "destaque" => $destaque,
        ];

        $dados = new CategoryEcommerceModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = trim($requestData['descricao']);

        $data = [
            "descricao" => $descricao, 
        ];

        $dados = new CategoryEcommerceModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function subCategories()
    {
        $dados = new CategoryEcommerceModel();
        $data = $dados->subCategories();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }    
}