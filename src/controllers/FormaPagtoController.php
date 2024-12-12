<?php
namespace src\controllers;

use \core\Controller;
use \src\models\FormaPagtoModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class FormaPagtoController extends Controller
{

    public function index()
    {
        $dados = new FormaPagtoModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new FormaPagtoModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new FormaPagtoModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new FormaPagtoModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = intval($requestData['id']);
        $descricao = $requestData['descricao'];
        $idpagamento = $requestData['idpagamento'];
        $tipo = $requestData['tipo'];
        $id_plano_contas = $requestData['id_plano_contas'];
        $id_caixa = $requestData['id_caixa'];

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "idpagamento" => $idpagamento,
            "tipo" => $tipo,
            "id_plano_contas" => $id_plano_contas,
            "id_caixa" => $id_caixa,
        ];

        $dados = new FormaPagtoModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = $requestData['descricao'];
        $idpagamento = $requestData['idpagamento'];
        $tipo = $requestData['tipo'];
        $id_plano_contas = $requestData['id_plano_contas'];
        $id_caixa = $requestData['id_caixa'];

        $data = [
            "descricao" => $descricao,
            "idpagamento" => $idpagamento,
            "tipo" => $tipo,
            "id_plano_contas" => $id_plano_contas,
            "id_caixa" => $id_caixa,
        ];

        $dados = new FormaPagtoModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}