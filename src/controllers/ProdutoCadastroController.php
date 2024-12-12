<?php
namespace src\controllers;

use \core\Controller;
use \src\models\ProdutoCadastroModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class ProdutoCadastroController extends Controller
{

    public function index()
    {
        $dados = new ProdutoCadastroModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['texto']);
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new ProdutoCadastroModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarGet($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new ProdutoCadastroModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarEAN($args)
    {
        $vTextoPesqusia = $args['ean'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new ProdutoCadastroModel();
        $data = $dados->pesquisarEAN($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new ProdutoCadastroModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new ProdutoCadastroModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new ProdutoCadastroModel();
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
        $ean = $requestData['ean'];
        $unidade = $requestData['unidade'];
        $ncm = $requestData['ncm'];
        $cest = $requestData['cest'];
        $preco = $requestData['preco'];

        $preco = str_replace(",", ".", $preco);

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "ean" => $ean,
            "unidade" => $unidade,
            "ncm" => $ncm,
            "cest" => $cest,
            "preco" => $preco,
        ];

        $dados = new ProdutoCadastroModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = $requestData['descricao'];
        $ean = $requestData['ean'];
        $unidade = $requestData['unidade'];
        $ncm = $requestData['ncm'];
        $cest = $requestData['cest'];
        $preco = $requestData['preco'];

        $preco = str_replace(",", ".", $preco);

        $data = [
            "descricao" => $descricao,
            "ean" => $ean,
            "unidade" => $unidade,
            "ncm" => $ncm,
            "cest" => $cest,
            "preco" => $preco,
        ];
        $dados = new ProdutoCadastroModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}