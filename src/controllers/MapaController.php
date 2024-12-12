<?php
namespace src\controllers;

use \core\Controller;
use \src\models\MapaModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class MapaController extends Controller
{

    public function index()
    {
        $dados = new MapaModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listaGeral()
    {
        $dados = new MapaModel();
        $data = $dados->listaGeral();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new MapaModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new MapaModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new MapaModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new MapaModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        $avatar = "";
        $temAnexo = "N";
        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temAnexo = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/files/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }
        } else {
            //  echo "No file uploaded.";
        }
        
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $requestData = json_decode($_POST['mapa'], true);        

        $id = trim($requestData['id']);
        $nome = $requestData['nome'];
        $latitude = $requestData['latitude'];
        $longitude = $requestData['longitude'];
        $referencia = $requestData['referencia'];
        $link = $requestData['link'];
        $numero = $requestData['numero'];
        $anexo = $avatar;

        $data = [
            "id" => $id,
            "nome" => $nome,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "referencia" => $referencia,
            "link" => $link,
            "numero" => $numero,
            "anexo" => $anexo,
            "temAnexo" => $temAnexo            
        ];

        $dados = new MapaModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        $avatar = "";
        $temAnexo = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temAnexo = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/files/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }
        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['mapa'], true);      

        $nome = $requestData['nome'];
        $latitude = $requestData['latitude'];
        $longitude = $requestData['longitude'];
        $referencia = $requestData['referencia'];
        $link = $requestData['link'];
        $numero = $requestData['numero'];
        $anexo = $avatar;

        $data = [
            "nome" => $nome,
            "latitude" => $latitude,
            "longitude" => $longitude,
            "referencia" => $referencia,
            "link" => $link,
            "numero" => $numero,
            "anexo" => $anexo,
        ];

        $dados = new MapaModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}