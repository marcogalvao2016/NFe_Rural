<?php
namespace src\controllers;

use \core\Controller;
use \src\models\TelasSistemaModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class TelasSistemaController extends Controller
{

    public function index()
    {
        $dados = new TelasSistemaModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new TelasSistemaModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new TelasSistemaModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new TelasSistemaModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        $avatar = "";
        $temIMG = "N";

        $surceFiles = generateSourceString();

        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temIMG = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/sistema/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }

        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['tela'], true);

        $id = trim($requestData['id']);
        $descricao = trim($requestData['descricao']);
        $principal = trim($requestData['principal']);
        $tipo = trim($requestData['tipo']);
        $observacoes = trim($requestData['observacoes']);
        $local = trim($requestData['local']);
        $categorys = $requestData['categorys'];

        $descricao = strtoupper($descricao);

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "principal" => $principal,
            "tipo" => $tipo,
            "observacoes" => $observacoes,
            "local" => $local,
            "categorys" => $categorys,
            "avatar" => $avatar,
            "temIMG" => $temIMG,
        ];

        $dados = new TelasSistemaModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        $avatar = "";
        $temIMG = "N";

        $surceFiles = generateSourceString();

        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temIMG = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/sistema/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }

        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['tela'], true);

        $descricao = trim($requestData['descricao']);
        $principal = trim($requestData['principal']);
        $tipo = trim($requestData['tipo']);
        $observacoes = trim($requestData['observacoes']);
        $local = trim($requestData['local']);

        $descricao = strtoupper($descricao);

        $data = [
            "descricao" => $descricao,
            "principal" => $principal,
            "tipo" => $tipo,
            "observacoes" => $observacoes,
            "local" => $local,
            "avatar" => $avatar,
            "temIMG" => $temIMG,
        ];

        $dados = new TelasSistemaModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}