<?php
namespace src\controllers;

use \core\Controller;
use \src\models\ClienteModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class ClienteController extends Controller
{

    public function index()
    {
        $dados = new ClienteModel();
        $data = $dados->listar();

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarVendedor($args)
    {
        $idvendedor = $args['idvendedor'];

        $dados = new ClienteModel();
        $data = $dados->listarVendedor($idvendedor);

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new ClienteModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarComVendedor($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $idVendedor = $args['idvendedor'];

        $dados = new ClienteModel();
        $data = $dados->pesquisarComVendedor($vTextoPesqusia, $idVendedor);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new ClienteModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new ClienteModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new ClienteModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = trim($requestData['id']);
        $nomefantasia = $requestData['nomefantasia'];
        $razaosocial = $requestData['razaosocial'];
        $idcategoria = $requestData['idcategoria'];
        $endereco = $requestData['endereco'];
        $numero = $requestData['numero'];
        $bairro = "";
        $cidade = "";
        $uf = $requestData['uf'];
        $cep = $requestData['cep'];
        $telefone1 = $requestData['telefone1'];
        $telefone2 = $requestData['telefone2'];
        $cfpcnpj = $requestData['cfpcnpj'];
        $observacoes = $requestData['observacoes'];
        $id_cidade = $requestData['id_cidade'];
        $id_bairro = $requestData['id_bairro'];
        $correio = $requestData['correio'];
        $codcidade = "5103403";
        $rginsestadual = $requestData['rginsestadual'];
        $insmunicipal = $requestData['insmunicipal'];
        $senha_acesso = $requestData['senha_acesso'];

        $data = [
            "id" => $id,
            "nomefantasia" => $nomefantasia,
            "razaosocial" => $razaosocial,
            "idcategoria" => $idcategoria,
            "endereco" => $endereco,
            "numero" => $numero,
            "bairro" => $bairro,
            "cidade" => $cidade,
            "uf" => $uf,
            "cep" => $cep,
            "telefone1" => $telefone1,
            "telefone2" => $telefone2,
            "cfpcnpj" => $cfpcnpj,
            "observacoes" => $observacoes,
            "id_cidade" => $id_cidade,
            "id_bairro" => $id_bairro,
            "correio" => $correio,
            "codcidade" => $codcidade,
            "rginsestadual" => $rginsestadual,
            "insmunicipal" => $insmunicipal,
            "senha_acesso" => $senha_acesso,
        ];

        $dados = new ClienteModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $nomefantasia = $requestData['nomefantasia'];
        $razaosocial = $requestData['razaosocial'];
        $idcategoria = $requestData['idcategoria'];
        $endereco = $requestData['endereco'];
        $numero = $requestData['numero'];
        $bairro = "";
        $cidade = "";
        $uf = $requestData['uf'];
        $cep = $requestData['cep'];
        $telefone1 = $requestData['telefone1'];
        $telefone2 = $requestData['telefone2'];
        $cnpj = $requestData['cfpcnpj'];
        $observacoes = $requestData['observacoes'];
        $id_cidade = $requestData['id_cidade'];
        $id_bairro = $requestData['id_bairro'];
        $correio = $requestData['correio'];
        $insestadual = $requestData['rginsestadual'];
        $codcidade = "5103403";
        $insmunicipal = $requestData['insmunicipal'];
        $id_vendedor = $requestData['id_vendedor'];
        $senha_acesso = $requestData['senha_acesso'];

        $data = [
            "nomefantasia" => $nomefantasia,
            "razaosocial" => $razaosocial,
            "idcategoria" => $idcategoria,
            "endereco" => $endereco,
            "numero" => $numero,
            "bairro" => $bairro,
            "cidade" => $cidade,
            "uf" => $uf,
            "cep" => $cep,
            "telefone1" => $telefone1,
            "telefone2" => $telefone2,
            "cnpj" => $cnpj,
            "observacoes" => $observacoes,
            "id_cidade" => $id_cidade,
            "id_bairro" => $id_bairro,
            "correio" => $correio,
            "insestadual" => $insestadual,
            "codcidade" => $codcidade,
            "insmunicipal" => $insmunicipal,
            "id_vendedor" => $id_vendedor,
            "senha_acesso" => $senha_acesso,
        ];

        $dados = new ClienteModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function contaTotaClientes()
    {
        $dados = new ClienteModel();
        $data = $dados->contaTotaClientes();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarMobile()
    {
        $dados = new ClienteModel();
        $data = $dados->listarMobile();

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }


}