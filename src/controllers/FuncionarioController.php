<?php
namespace src\controllers;

use \core\Controller;
use \src\models\FuncionarioModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class FuncionarioController extends Controller
{

    public function index()
    {
        $dados = new FuncionarioModel();
        $data = $dados->listar();

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);                
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new FuncionarioModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new FuncionarioModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }


    public function view($args)
    {
        $id = $args['id'];

        $dados = new FuncionarioModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new FuncionarioModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = trim($requestData['id']);
        $nome = $requestData['nome'];
        $idcargo = $requestData['idcargo'];
        $endereco = $requestData['endereco'];
        $numero = $requestData['numero'];
        $bairro = "";
        $cidade = "";
        $uf = $requestData['uf'];
        $cep = $requestData['cep'];
        $telefone1 = $requestData['telefone1'];
        $telefone2 = $requestData['telefone2'];
        $cfp = $requestData['cfp'];
        $observacoes = $requestData['observacoes'];
        $id_cidade = $requestData['id_cidade'];
        $id_bairro = $requestData['id_bairro'];
        $correio = $requestData['correio'];
        $apelido = $requestData['apelido'];

        $data = [
            "id" => $id,
            "nome" => $nome,
            "idcargo" => $idcargo,
            "endereco" => $endereco,
            "numero" => $numero,
            "bairro" => $bairro,
            "cidade" => $cidade,
            "uf" => $uf,
            "cep" => $cep,
            "telefone1" => $telefone1,
            "telefone2" => $telefone2,
            "cfp" => $cfp,
            "observacoes" => $observacoes,
            "id_cidade" => $id_cidade,
            "id_bairro" => $id_bairro,
            "correio" => $correio,   
            "apelido" => $apelido            
        ];

        $dados = new FuncionarioModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $nome = $requestData['nome'];
        $idcargo = $requestData['idcargo'];
        $endereco = $requestData['endereco'];
        $numero = $requestData['numero'];
        $bairro = "";
        $cidade = "";
        $uf = $requestData['uf'];
        $cep = $requestData['cep'];
        $telefone1 = $requestData['telefone1'];
        $telefone2 = $requestData['telefone2'];
        $cfp = $requestData['cfp'];
        $observacoes = $requestData['observacoes'];
        $id_cidade = $requestData['id_cidade'];
        $id_bairro = $requestData['id_bairro'];
        $correio = $requestData['correio'];
        $apelido = $requestData['apelido'];

        $data = [            
            "nome" => $nome,
            "idcargo" => $idcargo,
            "endereco" => $endereco,
            "numero" => $numero,
            "bairro" => $bairro,
            "cidade" => $cidade,
            "uf" => $uf,
            "cep" => $cep,
            "telefone1" => $telefone1,
            "telefone2" => $telefone2,
            "cfp" => $cfp,
            "observacoes" => $observacoes,
            "id_cidade" => $id_cidade,
            "id_bairro" => $id_bairro,
            "correio" => $correio,   
            "apelido" => $apelido            
        ];

        $dados = new FuncionarioModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}