<?php
namespace src\controllers;

use \core\Controller;
use \src\models\ContaHospitalarModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class ContaHospilatarController extends Controller
{

    public function index()
    {
        $dados = new ContaHospitalarModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new ContaHospitalarModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new ContaHospitalarModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new ContaHospitalarModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new ContaHospitalarModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = trim($requestData['cabecalho']['id']);
        $prontuario = $requestData['cabecalho']['prontuario'];
        $paciente = $requestData['cabecalho']['paciente'];
        $nascimento = $requestData['cabecalho']['nascimento'];
        $sexo = $requestData['cabecalho']['sexo'];
        $endereco = $requestData['cabecalho']['endereco'];
        $cep = $requestData['cabecalho']['cep'];
        $numero = $requestData['cabecalho']['numero'];
        $bairro = $requestData['cabecalho']['bairro'];
        $cidade = $requestData['cabecalho']['cidade'];
        $estado = $requestData['cabecalho']['estado'];
        $convenio = $requestData['cabecalho']['convenio'];
        $motivo_alta = $requestData['cabecalho']['motivo_alta'];
        $quarto = $requestData['cabecalho']['quarto'];
        $leito = $requestData['cabecalho']['leito'];
        $medico = $requestData['cabecalho']['medico'];
        $crm = $requestData['cabecalho']['crm'];
        $data_entrada = $requestData['cabecalho']['data_entrada'];
        $hora_entrada = $requestData['cabecalho']['hora_entrada'];
        $data_saida = $requestData['cabecalho']['data_saida'];
        $hora_saida = $requestData['cabecalho']['hora_saida'];
        $permanencia = $requestData['cabecalho']['permanencia'];
        $cid = $requestData['cabecalho']['cid'];
        $data_fechamento = $requestData['cabecalho']['data_fechamento'];

        $itens = $requestData['itens'];

        $data = [
            "id" => $id,
            "prontuario" => $prontuario,
            "paciente" => $paciente,
            "nascimento" => $nascimento,
            "sexo" => $sexo,
            "endereco" => $endereco,
            "cep" => $cep,
            "numero" => $numero,
            "bairro" => $bairro,
            "cidade" => $cidade,
            "estado" => $estado,
            "convenio" => $convenio,
            "motivo_alta" => $motivo_alta,
            "quarto" => $quarto,
            "leito" => $leito,
            "medico" => $medico,
            "crm" => $crm,
            "data_entrada" => $data_entrada,
            "hora_entrada" => $hora_entrada,
            "data_saida" => $data_saida,
            "hora_saida" => $hora_saida,
            "permanencia" => $permanencia,
            "cid" => $cid,
            "data_fechamento" => $data_fechamento,
            "itens" => $itens
        ];

        $dados = new ContaHospitalarModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $prontuario = $requestData['cabecalho']['prontuario'];
        $paciente = $requestData['cabecalho']['paciente'];
        $nascimento = $requestData['cabecalho']['nascimento'];
        $sexo = $requestData['cabecalho']['sexo'];
        $endereco = $requestData['cabecalho']['endereco'];
        $cep = $requestData['cabecalho']['cep'];
        $numero = $requestData['cabecalho']['numero'];
        $bairro = $requestData['cabecalho']['bairro'];
        $cidade = $requestData['cabecalho']['cidade'];
        $estado = $requestData['cabecalho']['estado'];
        $convenio = $requestData['cabecalho']['convenio'];
        $motivo_alta = $requestData['cabecalho']['motivo_alta'];
        $quarto = $requestData['cabecalho']['quarto'];
        $leito = $requestData['cabecalho']['leito'];
        $medico = $requestData['cabecalho']['medico'];
        $crm = $requestData['cabecalho']['crm'];
        $data_entrada = $requestData['cabecalho']['data_entrada'];
        $hora_entrada = $requestData['cabecalho']['hora_entrada'];
        $data_saida = $requestData['cabecalho']['data_saida'];
        $hora_saida = $requestData['cabecalho']['hora_saida'];
        $permanencia = $requestData['cabecalho']['permanencia'];
        $cid = $requestData['cabecalho']['cid'];
        $data_fechamento = $requestData['cabecalho']['data_fechamento'];

        $itens = $requestData['itens'];

        $data = [
            "prontuario" => $prontuario,
            "paciente" => $paciente,
            "nascimento" => $nascimento,
            "sexo" => $sexo,
            "endereco" => $endereco,
            "cep" => $cep,
            "numero" => $numero,
            "bairro" => $bairro,
            "cidade" => $cidade,
            "estado" => $estado,
            "convenio" => $convenio,
            "motivo_alta" => $motivo_alta,
            "quarto" => $quarto,
            "leito" => $leito,
            "medico" => $medico,
            "crm" => $crm,
            "data_entrada" => $data_entrada,
            "hora_entrada" => $hora_entrada,
            "data_saida" => $data_saida,
            "hora_saida" => $hora_saida,
            "permanencia" => $permanencia,
            "cid" => $cid,
            "data_fechamento" => $data_fechamento,
            "itens" => $itens
        ];

        $dados = new ContaHospitalarModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function viewItensProntuario($args)
    {
        $id = $args['id'];

        $dados = new ContaHospitalarModel();
        $data = $dados->viewItensProntuario($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function viewItensGroupProntuario($args)
    {
        $id = $args['id'];

        $dados = new ContaHospitalarModel();
        $data = $dados->viewItensGroupProntuario($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}