<?php
namespace src\controllers;

use \core\Controller;
use \src\models\NCMModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class NCMController extends Controller
{

    public function index()
    {
        $dados = new NCMModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new NCMModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new NCMModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new NCMModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new NCMModel();
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
        $aliqnacional = floatval($requestData['aliqnacional']);
        $aliqinternacional = floatval($requestData['aliqinternacional']);
        $idncm = $requestData['idncm'];

        $aliqnacional = str_replace(",", ".", $aliqnacional);
        $aliqinternacional = str_replace(",", ".", $aliqinternacional);

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "aliqnacional" => $aliqnacional,
            "aliqinternacional" => $aliqinternacional,
            "idncm" => $idncm,
        ];

        $dados = new NCMModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = $requestData['descricao'];
        $aliqnacional = floatval($requestData['aliqnacional']);
        $aliqinternacional = floatval($requestData['aliqinternacional']);
        $idncm = $requestData['idncm'];

        $aliqnacional = str_replace(",", ".", $aliqnacional);
        $aliqinternacional = str_replace(",", ".", $aliqinternacional);

        $data = [
            "descricao" => $descricao,
            "aliqnacional" => $aliqnacional,
            "aliqinternacional" => $aliqinternacional,
            "idncm" => $idncm,
        ];
        $dados = new NCMModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}