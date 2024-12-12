<?php
namespace src\controllers;

use \core\Controller;
use \src\models\UsuarioModel;
use \src\models\OsSecretariaModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class UsuarioController extends Controller
{

    public function index()
    {
        $dados = new UsuarioModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $texto = $args['texto'];

        $dados = new UsuarioModel();
        $data = $dados->pesquisar($texto);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new UsuarioModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new UsuarioModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        $avatar = "";
        $temIMG = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temIMG = "S";            

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/users/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }

        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['usuario'], true);

        $id = $requestData['id'];
        $nome = $requestData['nome'];
        $categoria = $requestData['categoria'];
        $situacao = $requestData['situacao'];
        $senha = $requestData['senha'];
        $observacoes = $requestData['observacoes'];
        $email_usuario = $requestData['email_usuario'];
        $id_grupoacesso = $requestData['id_grupoacesso'];
        $id_tabela = $requestData['id_tabela'];
        $id_func = $requestData['id_func'];
        $id_cliente = $requestData['id_cliente'];
        $nome_completo = $requestData['nome_completo'];
        $tel_usuario = $requestData['tel_usuario'];

        $data = [
            "id" => $id,
            "nome" => $nome,
            "categoria" => $categoria,
            "situacao" => $situacao,
            "senha" => $senha,
            "observacoes" => $observacoes,
            "email_usuario" => $email_usuario,
            "id_grupoacesso" => $id_grupoacesso,
            "id_tabela" => $id_tabela,
            "id_func" => $id_func,
            "id_cliente" => $id_cliente,
            "nome_completo" => $nome_completo,
            "tel_usuario" => $tel_usuario,
            "avatar" => $avatar,
            "temIMG" => $temIMG,
        ];

        $dados = new UsuarioModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        $avatar = "";
        $temIMG = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temIMG = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/users/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }
        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['usuario'], true);

        $nome = $requestData['nome'];
        $categoria = $requestData['categoria'];
        $situacao = $requestData['situacao'];
        $senha = $requestData['senha'];
        $observacoes = $requestData['observacoes'];
        $email_usuario = $requestData['email_usuario'];
        $id_grupoacesso = $requestData['id_grupoacesso'];
        $id_tabela = $requestData['id_tabela'];
        $id_func = $requestData['id_func'];
        $id_cliente = $requestData['id_cliente'];
        $nome_completo = $requestData['nome_completo'];
        $tel_usuario = $requestData['tel_usuario'];

        $data = [
            "nome" => $nome,
            "categoria" => $categoria,
            "situacao" => $situacao,
            "senha" => $senha,
            "observacoes" => $observacoes,
            "email_usuario" => $email_usuario,
            "id_grupoacesso" => $id_grupoacesso,
            "id_tabela" => $id_tabela,
            "id_func" => $id_func,
            "id_cliente" => $id_cliente,
            "nome_completo" => $nome_completo,
            "tel_usuario" => $tel_usuario,
            "avatar" => $avatar,
            "temIMG" => $temIMG,
        ];

        $dados = new UsuarioModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function login()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $login = trim($requestData['email']);
        $senha = trim($requestData['senha']);

        $data = [
            "login" => strtoupper($login),
            "senha" => $senha
        ];

        $dados = new UsuarioModel();
        $retorno = $dados->login($data);

        //Finaliza OS
        $osOBj = new OsSecretariaModel();
        $osOBj->finalizaOSAuto();

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function loginMail()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $login = trim($requestData['email']);
        $senha = trim($requestData['senha']);

        $data = [
            "login" => strtoupper($login),
            "senha" => $senha
        ];

        $dados = new UsuarioModel();
        $retorno = $dados->loginMail($data);

        $osOBj = new OsSecretariaModel();
        $osOBj->finalizaOSAuto();

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function loginClient()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $login = trim($requestData['email']);
        $senha = trim($requestData['senha']);

        $data = [
            "login" => strtoupper($login),
            "senha" => $senha
        ];

        $dados = new UsuarioModel();
        $retorno = $dados->loginClient($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function updatePasswordUsuario()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo

        $id = $requestData['id'];
        $password_new = $requestData['password_new'];
        $password_old = $requestData['password_old'];

        $data = [
            "id" => $id,
            "password_new" => $password_new,
            "password_old" => $password_old,
        ];

        $dados = new UsuarioModel();
        $retorno = $dados->updatePassword($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function updatePasswordCliente()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo

        $id = $requestData['id'];
        $password_new = $requestData['password_new'];
        $password_old = $requestData['password_old'];

        $data = [
            "id" => $id,
            "password_new" => $password_new,
            "password_old" => $password_old,
        ];

        $dados = new UsuarioModel();
        $retorno = $dados->updatePasswordCliente($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function updateFotoCliente()
    {
        $avatar = "";
        $temIMG = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temIMG = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/users/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }
        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['usuario'], true);

        $id = $requestData;

        $data = [
            "id" => $id,
            "avatar" => $avatar,
            "temIMG" => $temIMG
        ];

        $dados = new UsuarioModel();
        $retorno = $dados->updateFotoCliente($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}