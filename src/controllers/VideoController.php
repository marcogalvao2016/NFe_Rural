<?php
namespace src\controllers;

use \core\Controller;
use \src\models\VideoModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class VideoController extends Controller
{

    public function index()
    {
        $dados = new VideoModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new VideoModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new VideoModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new VideoModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = trim($requestData['id']);
        $descricao = trim($requestData['descricao']);
        $link_video = trim($requestData['link_video']);
        $id_categoria = trim($requestData['id_categoria']);

        $descricao = strtoupper($descricao);

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "link_video" => $link_video,
            "id_categoria" => $id_categoria,
        ];

        $dados = new VideoModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = trim($requestData['descricao']);
        $link_video = trim($requestData['link_video']);
        $id_categoria = trim($requestData['id_categoria']);

        $descricao = strtoupper($descricao);

        $data = [
            "descricao" => $descricao,
            "link_video" => $link_video,
            "id_categoria" => $id_categoria,
        ];

        $dados = new VideoModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}