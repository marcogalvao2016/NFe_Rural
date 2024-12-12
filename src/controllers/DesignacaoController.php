<?php
namespace src\controllers;

use \core\Controller;
use \src\models\DesignacaoModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class DesignacaoController extends Controller
{

    public function index($args)
    {
        $vSituacao = $args['situacao'];

        $dados = new DesignacaoModel();
        $data = $dados->listar($vSituacao);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {        
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new DesignacaoModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new DesignacaoModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new DesignacaoModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new DesignacaoModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = trim($requestData['id']);
        $id_dirigente = $requestData['id_dirigente'];        
        $id_mapa = $requestData['id_mapa'];
        $situacao = $requestData['situacao'];
        $data_inicio = $requestData['data_inicio'];
        $data_fim = $requestData['data_fim'];        
        $observacoes = $requestData['observacoes'];
        $id_usuario = $requestData['id_usuario'];

        $data = [
            "id" => $id,
            "id_dirigente" => $id_dirigente,
            "id_mapa" => $id_mapa,
            "situacao" => $situacao,
            "data_inicio" => $data_inicio,     
            "data_fim" => $data_fim,            
            "observacoes" => $observacoes,
            "id_usuario" => $id_usuario,
        ];

        $dados = new DesignacaoModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_dirigente = $requestData['id_dirigente'];        
        $id_mapa = $requestData['id_mapa'];
        $situacao = $requestData['situacao'];
        $data_inicio = $requestData['data_inicio'];
        $data_fim = $requestData['data_fim'];        
        $observacoes = $requestData['observacoes'];
        $id_usuario = $requestData['id_usuario'];

        $data = [    
            "id_dirigente" => $id_dirigente,
            "id_mapa" => $id_mapa,
            "situacao" => $situacao,
            "data_inicio" => $data_inicio,
            "data_fim" => $data_fim,            
            "observacoes" => $observacoes,
            "id_usuario" => $id_usuario,
        ];

        $dados = new DesignacaoModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function qtDesignacao($args)
    {
        $id_dirigente = $args['id'];

        $dados = new DesignacaoModel();
        $data = $dados->qtDesignacao($id_dirigente);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}