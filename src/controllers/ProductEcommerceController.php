<?php
namespace src\controllers;

use \core\Controller;
use \src\models\ProductEcommerceModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class ProductEcommerceController extends Controller
{

    public function index()
    {
        $dados = new ProductEcommerceModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarPorPaginacao($args)
    {
        $pagina = $args['pagina'];
        $limite = $args['limite'];

        $dados = new ProductEcommerceModel();
        $data = $dados->listarPorPaginacao($pagina, $limite);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new ProductEcommerceModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new ProductEcommerceModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new ProductEcommerceModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete()
    {
        $id = filter_input(INPUT_POST, "txtid");

        $dados = new ProductEcommerceModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = trim($requestData['id']);        
        $promocao = trim($requestData['promocao']); 
        $destaque = trim($requestData['destaque']); 
        $produto_novo = trim($requestData['produto_novo']); 
        $visivel = trim($requestData['visivel']); 

        $data = [
            "id" => $id,
            "promocao" => $promocao,    
            "destaque" => $destaque,
            "produto_novo" => $produto_novo,
            "visivel" => $visivel,
        ];

        $dados = new ProductEcommerceModel();
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

        $dados = new ProductEcommerceModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}