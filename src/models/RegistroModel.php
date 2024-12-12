<?php
namespace src\models;

use \core\Model;

function generateRandomString($length = 15)
{
    // Define os caracteres que podem ser utilizados na string
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    // Gera a string aleatória
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

class RegistroModel extends Model
{
    private $conexao; // Variável para armazenar a conexão PDO

    function __construct()
    {
        require_once 'conexao/db_connection.php';
        $this->conexao = new \DB_Con();
    }

    function listar()
    {
        $data = [];
        $count = 0;
        http_response_code(200);

        try {

        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "bairros" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "bairros" => $data,
        ];
    }

    function registrar($data)
    {
        $response = "";
        http_response_code(200);

        $nome = $data['nome'];
        $nome_completo = $data['nome_completo'];
        $senha = $data['senha'];
        $email_usuario = $data['email_usuario'];

        $dataAtual = date("Y-m-d");
        $vSerialCadastro = generateRandomString(15);

        try {
            $qry = "INSERT INTO usuario (
                nome, 
                senha, 
                confirma, 
                nivel, 
                situacao, 
                cadastro, 
                categoria, 
                id_grupoacesso,
                tel_usuario,
                email_usuario,
                idempresa,
                id_func,
                filiais,
                key_register,
                nome_completo) 
                VALUES (
                    :p01, 
                    :p02, 
                    :p03, 
                    :p04, 
                    :p05, 
                    :p06, 
                    :p07, 
                    :p08, 
                    :p09, 
                    :p10, 
                    :p11, 
                    :p12, 
                    :p13, 
                    :p14, 
                    :p15)";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $nome);
            $stmt->bindValue("p02", $senha);
            $stmt->bindValue("p03", $senha);
            $stmt->bindValue("p03", 1);
            $stmt->bindValue("p03", 'I');
            $stmt->bindValue("p03", $dataAtual);
            $stmt->bindValue("p03", 'USUARIO');
            $stmt->bindValue("p03", 6);
            $stmt->bindValue("p03", '65992565018');
            $stmt->bindValue("p03", $email_usuario);
            $stmt->bindValue("p03", 1);
            $stmt->bindValue("p03", 0);
            $stmt->bindValue("p03", 999);
            $stmt->bindValue("p03", $vSerialCadastro);
            $stmt->bindValue("p03", $nome_completo);
            $stmt->execute();

        } catch (\Exception $e) {
            http_response_code(500);
            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro alterado com sucesso',
            'request' => array(
                'description' => 'Altera um registro',
                'request' => array(
                    'type' => 'PUT',
                    'description' => 'Registro atualizado com sucesso',
                    'url' => 'api/registrar/'
                )
            )
        );

        return $response;
    }

    function ativarRegistro($chave)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "UPDATE usuario SET                   
                situacao = 'A'
                WHERE key_register = '$chave'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro validado com sucesso',
            'request' => array(
                'description' => 'Deleta um registro',
                'url' => 'api/ativarregistro',
                'body' => array(
                    'type' => 'DELETE',
                    'descricao' => 'String',
                )
            )
        );

        return $response;
    }

}