<?php
namespace src\controllers;

use \core\Controller;
use \src\models\FornecedorModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class FornecedorController extends Controller
{

    public function index()
    {
        $dados = new FornecedorModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);        
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new FornecedorModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new FornecedorModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new FornecedorModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new FornecedorModel();
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
        $cnpj = $requestData['cnpj'];
        $observacoes = $requestData['observacoes'];
        $id_cidade = $requestData['id_cidade'];
        $id_bairro = $requestData['id_bairro'];
        $correio = $requestData['correio'];
        $insestadual = $requestData['insestadual'];
        $lista_emails = $requestData['lista_emails'];
        $codcidade = "5103403";
        $insmunicipal = 0;

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
            "cnpj" => $cnpj,
            "observacoes" => $observacoes,
            "id_cidade" => $id_cidade,
            "id_bairro" => $id_bairro,
            "correio" => $correio,
            "insestadual" => $insestadual,
            "codcidade" => $codcidade,
            "insmunicipal" => $insmunicipal,
            "lista_emails" => $lista_emails,
        ];

        $dados = new FornecedorModel();
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
        $cnpj = $requestData['cnpj'];
        $observacoes = $requestData['observacoes'];
        $id_cidade = $requestData['id_cidade'];
        $id_bairro = $requestData['id_bairro'];
        $correio = $requestData['correio'];
        $insestadual = $requestData['insestadual'];
        $lista_emails = $requestData['lista_emails'];
        $codcidade = "5103403";

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
            "lista_emails" => $lista_emails,
        ];

        $dados = new FornecedorModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function printFornecedor()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_fornecedor = $requestData['id_fornecedor'];
        $dtInicial = $requestData['data_inicial'];
        $dtFinal = $requestData['data_final'];        

        $data = [
            "id_fornecedor" => $id_fornecedor,
            "dtInicial" => $dtInicial,
            "dtFinal" => $dtFinal,         
        ];

        $dados = new FornecedorModel();
        $data = $dados->printFornecedor($data);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }    

}