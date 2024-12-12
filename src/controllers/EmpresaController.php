<?php
namespace src\controllers;

use \core\Controller;
use \src\models\EmpresaModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class EmpresaController extends Controller
{

    public function index()
    {
        $dados = new EmpresaModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new EmpresaModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new EmpresaModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }


    public function view($args)
    {
        $id = $args['id'];

        $dados = new EmpresaModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new EmpresaModel();
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
        $endereco = $requestData['endereco'];
        $numero = $requestData['numero'];
        $bairro = $requestData['bairro'];
        $cidade = $requestData['cidade'];
        $uf = $requestData['uf'];
        $cep = $requestData['cep'];
        $telefone1 = $requestData['telefone1'];
        $telefone2 = $requestData['telefone2'];
        $cnpj = $requestData['cnpj'];
        $observacoes = $requestData['observacoes'];
        $insestadual = $requestData['insestadual'];
        $id_regime_tributario = $requestData['id_regime_tributario'];
        $codigo_tributa_municipio = $requestData['codigo_tributacao_municipio'];
        $codigo_servico = $requestData['codigo_servico'];
        $correio = $requestData['correio'];
        $tipo_empresa = $requestData['tipo_empresa'];
        $chave_pix = $requestData['chave_pix'];
        $codcidade = "5103403";
        $insmunicipal = 0;

        $data = [
            "id" => $id,
            "nomefantasia" => $nomefantasia,
            "razaosocial" => $razaosocial,
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
            "insestadual" => $insestadual,
            "codcidade" => $codcidade,
            "insmunicipal" => $insmunicipal,
            "id_regime_tributario" => $id_regime_tributario,
            "codigo_tributa_municipio" => $codigo_tributa_municipio,
            "codigo_servico" => $codigo_servico,
            "correio" => $correio,
            "tipo_empresa" => $tipo_empresa,
            "chave_pix" => $chave_pix
        ];

        $dados = new EmpresaModel();
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
        $endereco = $requestData['endereco'];
        $numero = $requestData['numero'];
        $bairro = $requestData['bairro'];
        $cidade = $requestData['cidade'];
        $uf = $requestData['uf'];
        $cep = $requestData['cep'];
        $telefone1 = $requestData['telefone1'];
        $telefone2 = $requestData['telefone2'];
        $cnpj = $requestData['cnpj'];
        $observacoes = $requestData['observacoes'];
        $insestadual = $requestData['insestadual'];
        $id_regime_tributario = $requestData['id_regime_tributario'];
        $codigo_tributa_municipio = $requestData['codigo_tributa_municipio'];
        $correio = $requestData['correio'];
        $tipo_empresa = $requestData['tipo_empresa'];
        $chave_pix = $requestData['chave_pix'];
        $codcidade = "5103403";
        $insmunicipal = 0;

        $data = [
            "nomefantasia" => $nomefantasia,
            "razaosocial" => $razaosocial,
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
            "insestadual" => $insestadual,
            "codcidade" => $codcidade,
            "insmunicipal" => $insmunicipal,
            "id_regime_tributario" => $id_regime_tributario,
            "codigo_tributa_municipio" => $codigo_tributa_municipio,
            "correio" => $correio,
            "tipo_empresa" => $tipo_empresa,
            "chave_pix" => $chave_pix,
        ];

        $dados = new EmpresaModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}