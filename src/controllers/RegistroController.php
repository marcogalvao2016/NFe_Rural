<?php
namespace src\controllers;

use \core\Controller;
use \src\models\RegistroModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class RegistroController extends Controller
{

    public function index()
    {
        $dados = new RegistroModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function registrar()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $nome = trim($requestData['nome']);
        $nome_completo = $requestData['nome_completo'];
        $senha = $requestData['senha'];
        $email_usuario = $requestData['email_usuario'];

        $data = [
            "nome" => $nome,
            "nome_completo" => $nome_completo,
            "senha" => $senha,
            "email_usuario" => $email_usuario,
        ];

        $dados = new RegistroModel();
        $data = $dados->registrar($data);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function ativarRegistro($args)
    {
        $chave = $args['chave'];

        $dados = new RegistroModel();
        $retorno = $dados->ativarRegistro($chave);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}