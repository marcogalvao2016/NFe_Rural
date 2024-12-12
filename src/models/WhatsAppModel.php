<?php
namespace src\models;

use \core\Model;

class WhatsAppModel extends Model
{
    private $conexao; // Variável para armazenar a conexão PDO

    function __construct()
    {
        require_once 'conexao/db_connection.php';
        $this->conexao = new \DB_Con();
    }

    function recusa($telefone)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "UPDATE cliente SET recebe_whats = '0' WHERE telefone1 = '$telefone'";
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
                'url' => 'api/whatsapp/recusa',
                'body' => array(
                    'type' => 'DELETE',
                    'descricao' => 'String',
                )
            )
        );

        return $response;
    }

}