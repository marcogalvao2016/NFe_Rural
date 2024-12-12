<?php

namespace src\controllers;

use \core\Controller;
use \src\models\TabelaPrecoModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class TabelaPrecoController extends Controller
{

    public function index()
    {
        $dados = new TabelaPrecoModel();
        $data = $dados->listar();

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new TabelaPrecoModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new TabelaPrecoModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new TabelaPrecoModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new TabelaPrecoModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = trim($requestData['id']);
        $descricao = $requestData['descricao'];
        $validade = $requestData['validade'];
        $validade_final = $requestData['validade_final'];
        $ativado = $requestData['ativado'];

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "validade" => $validade,
            "validade_final" => $validade_final,
            "ativado" => $ativado,
        ];

        $dados = new TabelaPrecoModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = $requestData['descricao'];
        $validade = $requestData['validade'];
        $validade_final = $requestData['validade_final'];
        $ativado = $requestData['ativado'];

        $data = [
            "descricao" => $descricao,
            "validade" => $validade,
            "validade_final" => $validade_final,
            "ativado" => $ativado,
        ];

        $dados = new TabelaPrecoModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
