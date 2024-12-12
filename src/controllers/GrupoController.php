<?php
namespace src\controllers;

use \core\Controller;
use \src\models\GrupoModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class GrupoController extends Controller
{

    public function index()
    {
        $grupo = new GrupoModel();
        $data = $grupo->listar();

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function search($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $grupo = new GrupoModel();
        $data = $grupo->search($vTextoPesqusia);

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $grupo = new GrupoModel();
        $data = $grupo->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $grupo = new GrupoModel();
        $data = $grupo->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete()
    {
        $id = filter_input(INPUT_POST, "txtid");

        $grupo = new GrupoModel();
        $retorno = $grupo->deletar($id);

        if ($retorno) {
            $this->redirect("/grupos/listar");
        }
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = trim($requestData['id']);
        $descricao = $requestData['descricao'];
        $mostra_tablet = $requestData['mostra_tablet'];
        $url = $requestData['url'];

        // $largura = "1366";
        // $altura = "850";
        // $vCaminhoNovoNomeFirst = "";
        // $dir = "";
        // if (empty($_FILES['arquivo_first_photo']['name'] == true)) { // Se for vazio
        //     $vCaminhoNovoNomeFirst = $imgFoto;
        // } else {
        //     if (isset($_FILES['arquivo_first_photo'])) {
        //         date_default_timezone_set("Brazil/East"); //Definindo timezone padrão
        //         $nomeEmbaralhado = generateRandomString();

        //         $ext = strtolower(substr($_FILES['arquivo_first_photo']['name'], -4)); //Pegando extensão do arquivo
        //         $new_name1 = $nomeEmbaralhado . "_" . date("Y.m.d-H.i.s") . $ext; //Definindo um novo nome                
        //         $dir = '/dist/img/grupo/'; //Diretório para uploads
        //         $vCaminhoNovoNomeFirst = 'public/dist/img/grupo/' . $new_name1;

        //         $vCaminhoNovoNomeFirst = $dir . $new_name1;

        //         //Redimenciona a imagem
        //         $imagem_temporaria = imagecreatefromjpeg($_FILES['arquivo_first_photo']['tmp_name']);

        //         $largura_original = imagesx($imagem_temporaria);
        //         $altura_original = imagesy($imagem_temporaria);

        //         $nova_largura = $largura ? $largura : floor(($largura_original / $altura_original) * $altura);

        //         $nova_altura = $altura ? $altura : floor(($altura_original / $largura_original) * $largura);

        //         $imagem_redimensionada = imagecreatetruecolor($nova_largura, $nova_altura);
        //         imagecopyresampled($imagem_redimensionada, $imagem_temporaria, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);

        //         imagejpeg($imagem_redimensionada, '../public/dist/img/grupo/' . $new_name1);
        //     }
        // }

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "mostra_tablet" => $mostra_tablet,
            "url" => $url
        ];

        $grupo = new GrupoModel();
        $retorno = $grupo->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = $requestData['descricao'];
        $mostra_tablet = $requestData['mostra_tablet'];
        $url = $requestData['url'];

        // $largura = "1366";
        // $altura = "850";
        // $vCaminhoNovoNomeFirst = "";
        // $dir = "";
        // if (empty($_FILES['arquivo_first_photo']['name'] == true)) { // Se for vazio
        //     $vCaminhoNovoNomeFirst = $imgFoto;
        // } else {
        //     if (isset($_FILES['arquivo_first_photo'])) {
        //         date_default_timezone_set("Brazil/East"); //Definindo timezone padrão
        //         $nomeEmbaralhado = generateRandomString();

        //         $ext = strtolower(substr($_FILES['arquivo_first_photo']['name'], -4)); //Pegando extensão do arquivo
        //         $new_name1 = $nomeEmbaralhado . "_" . date("Y.m.d-H.i.s") . $ext; //Definindo um novo nome
        //         $vNomeFoto = $nomeEmbaralhado . "_" . date("Y.m.d-H.i.s");
        //         $dir = '/dist/img/grupo'; //Diretório para uploads
        //         $vCaminhoNovoNomeFirst = 'public/dist/img/grupo/' . $new_name1;

        //         $vCaminhoNovoNomeFirst = $dir . $new_name1;

        //         //Redimenciona a imagem
        //         $imagem_temporaria = imagecreatefromjpeg($_FILES['arquivo_first_photo']['tmp_name']);

        //         $largura_original = imagesx($imagem_temporaria);
        //         $altura_original = imagesy($imagem_temporaria);

        //         $nova_largura = $largura ? $largura : floor(($largura_original / $altura_original) * $altura);

        //         $nova_altura = $altura ? $altura : floor(($altura_original / $largura_original) * $largura);

        //         $imagem_redimensionada = imagecreatetruecolor($nova_largura, $nova_altura);
        //         imagecopyresampled($imagem_redimensionada, $imagem_temporaria, 0, 0, 0, 0, $nova_largura, $nova_altura, $largura_original, $altura_original);

        //         imagejpeg($imagem_redimensionada, '../public/dist/img/grupo' . $new_name1);
        //     }
        // }

        $data = [
            "descricao" => $descricao,
            "mostra_tablet" => $mostra_tablet,
            "url" => $url,
        ];

        $grupo = new GrupoModel();
        $retorno = $grupo->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}