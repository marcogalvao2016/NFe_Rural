<?php
namespace src\controllers;

use \core\Controller;
use \src\models\PlanoContasModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class PlanoContasController extends Controller
{

    public function index()
    {
        $dados = new PlanoContasModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new PlanoContasModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new PlanoContasModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new PlanoContasModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = intval($requestData['planocontas']);
        $descricao = $requestData['descricao'];
        $categoria = $requestData['categoria'];
        $id_classificacao = $requestData['id_classificacao'];

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "categoria" => $categoria,
            "id_classificacao" => $id_classificacao
        ];

        $dados = new PlanoContasModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = $requestData['descricao'];
        $categoria = $requestData['categoria'];
        $id_classificacao = $requestData['id_classificacao'];

        $data = [
            "descricao" => $descricao,
            "categoria" => $categoria,
            "id_classificacao" => $id_classificacao
        ];

        $dados = new PlanoContasModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}