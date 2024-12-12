<?php
namespace src\controllers;

use \core\Controller;
use \src\models\OsSecretariaModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class OsSecretariaController extends Controller
{

    public function index()
    {
        $dados = new OsSecretariaModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new OsSecretariaModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarContrato($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new OsSecretariaModel();
        $data = $dados->pesquisarContrato($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarNumOS($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new OsSecretariaModel();
        $data = $dados->pesquisarNumOS($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new OsSecretariaModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new OsSecretariaModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function finalizaOS($args)
    {
        $id = $args['id'];

        $dados = new OsSecretariaModel();
        $data = $dados->finalizaOS($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function enviadaOS($args)
    {
        $id = $args['id'];
        $status = $args['status'];

        $dados = new OsSecretariaModel();
        $data = $dados->enviadaOS($id, $status);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function viewItensOS($args)
    {
        $id = $args['id'];

        $dados = new OsSecretariaModel();
        $data = $dados->viewItensOSSecretaria($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo           

        $id = $requestData['id'];
        $itens = $requestData['itens'];

        $data = [
            "id" => $id,
            "itens" => $itens,
        ];

        $dados = new OsSecretariaModel();
        $retorno = $dados->deletar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $requestData = json_decode($_POST['ossecretaria'], true);

        $id = trim($requestData['id']);
        $data_lancamento = $requestData['data_lancamento'];
        $numero_pregao = $requestData['numero_pregao'];
        $id_fornecedor = $requestData['id_fornecedor'];
        $solicitante = $requestData['solicitante'];
        $tipo_evento = $requestData['tipo_evento'];
        $descricao = $requestData['descricao'];
        $endereco = $requestData['endereco'];
        $data_inicial = $requestData['data_inicial'];
        $hora_inicial = $requestData['hora_inicial'];
        $data_final = $requestData['data_final'];
        $hora_fim = $requestData['hora_fim'];
        $contato = $requestData['contato'];
        $tel_contato = $requestData['tel_contato'];
        $observacoes = $requestData['observacoes'];
        $situacao = $requestData['situacao'];
        $email_enviado = $requestData['email_enviado'];
        $itens = $_POST['itens'];

        $data = [
            "id" => $id,
            "data_lancamento" => $data_lancamento,
            "numero_pregao" => $numero_pregao,
            "id_fornecedor" => $id_fornecedor,
            "solicitante" => $solicitante,
            "tipo_evento" => $tipo_evento,
            "descricao" => $descricao,
            "endereco" => $endereco,
            "data_inicial" => $data_inicial,
            "hora_inicial" => $hora_inicial,
            "data_final" => $data_final,
            "hora_fim" => $hora_fim,
            "contato" => $contato,
            "tel_contato" => $tel_contato,
            "observacoes" => $observacoes,
            "situacao" => $situacao,
            "email_enviado" => $email_enviado,
            "itens" => $itens,
        ];

        $dados = new OsSecretariaModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $requestData = json_decode($_POST['ossecretaria'], true);

        $data_lancamento = $requestData['data_lancamento'];
        $numero_pregao = $requestData['numero_pregao'];
        $id_fornecedor = $requestData['id_fornecedor'];
        $solicitante = $requestData['solicitante'];
        $tipo_evento = $requestData['tipo_evento'];
        $descricao = $requestData['descricao'];
        $endereco = $requestData['endereco'];
        $data_inicial = $requestData['data_inicial'];
        $hora_inicial = $requestData['hora_inicial'];
        $data_final = $requestData['data_final'];
        $hora_fim = $requestData['hora_fim'];
        $contato = $requestData['contato'];
        $tel_contato = $requestData['tel_contato'];
        $observacoes = $requestData['observacoes'];
        $situacao = $requestData['situacao'];
        $id_usuario = $requestData['id_usuario'];
        $email_enviado = $requestData['email_enviado'];
        $itens = $_POST['itens'];

        $data = [
            "data_lancamento" => $data_lancamento,
            "numero_pregao" => $numero_pregao,
            "id_fornecedor" => $id_fornecedor,
            "solicitante" => $solicitante,
            "tipo_evento" => $tipo_evento,
            "descricao" => $descricao,
            "endereco" => $endereco,
            "data_inicial" => $data_inicial,
            "hora_inicial" => $hora_inicial,
            "data_final" => $data_final,
            "hora_fim" => $hora_fim,
            "contato" => $contato,
            "tel_contato" => $tel_contato,
            "observacoes" => $observacoes,
            "situacao" => $situacao,
            "id_usuario" => $id_usuario,
            "email_enviado" => $email_enviado,
            "itens" => $itens,
        ];

        $dados = new OsSecretariaModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function printOS()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_fornecedor = $requestData['id_fornecedor'];
        $dtInicial = $requestData['data_inicial'];
        $dtFinal = $requestData['data_final'];
        $numero_pregao = $requestData['numero_pregao'];
        $tipo_evento = $requestData['tipo_evento'];
        $solicitante = $requestData['solicitante'];
        $situacao = $requestData['situacao'];

        $data = [
            "id_fornecedor" => $id_fornecedor,
            "dtInicial" => $dtInicial,
            "dtFinal" => $dtFinal,
            "numero_pregao" => $numero_pregao,
            "tipo_evento" => $tipo_evento,
            "solicitante" => $solicitante,
            "situacao" => $situacao,
        ];

        $dados = new OsSecretariaModel();
        $data = $dados->printOS($data);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarPorPaginacao($args)
    {        
        $pagina = $args['pagina'];
        $limite = $args['limite'];

        $dados = new OsSecretariaModel();
        $data = $dados->listarPorPaginacao($pagina, $limite);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }    

}