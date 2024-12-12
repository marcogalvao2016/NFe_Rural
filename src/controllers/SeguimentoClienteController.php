<?php
namespace src\controllers;

use \core\Controller;
use \src\models\SeguimentoClienteModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class SeguimentoClienteController extends Controller
{

    public function index()
    {
        $dados = new SeguimentoClienteModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new SeguimentoClienteModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new SeguimentoClienteModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new SeguimentoClienteModel();
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
        $classe = trim($requestData['classe']);

        $descricao = strtoupper($descricao);
        $classe = strtolower($classe);

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "classe" => $classe,
        ];

        $dados = new SeguimentoClienteModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = trim($requestData['descricao']);
        $classe = trim($requestData['classe']);

        $descricao = strtoupper($descricao);
        $classe = strtolower($classe);

        $data = [
            "descricao" => $descricao,
            "classe" => $classe,
        ];

        $dados = new SeguimentoClienteModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}