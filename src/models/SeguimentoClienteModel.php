<?php
namespace src\models;

use \core\Model;

class SeguimentoClienteModel extends Model
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
        http_response_code(200);

        try {
            $qry = "SELECT * FROM cliente_seguimento ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "classe" => $row['classe'],                   
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/seguimentos/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "seguimentos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "seguimentos" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM cliente_seguimento ORDER BY id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "classe" => $row['classe'],   
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/seguimentos/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "seguimentos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "seguimentos" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM cliente_seguimento WHERE descricao like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "classe" => $row['classe'],   
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/seguimentos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "seguimentos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "seguimentos" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM cliente_seguimento WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "descricao" => $row['descricao'],
                        "classe" => $row['classe'],   
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/seguimentos/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "seguimento" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "seguimento" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "seguimento" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM cliente_seguimento WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro removido com sucesso',
            'request' => array(
                'description' => 'Deleta um registro',
                'url' => 'api/seguimentos',
                'body' => array(
                    'type' => 'DELETE',
                    'descricao' => 'String',
                )
            )
        );

        return $response;
    }

    function alterar($data)
    {
        $response = "";
        http_response_code(200);

        $id = $data['id'];
        $descricao = $data['descricao'];
        $classe = $data['classe'];        

        try {
            $qry = "UPDATE cliente_seguimento SET 
                    descricao =:p01,
                    classe =:p02
                WHERE id =:p03";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $classe);  
            $stmt->bindValue("p03", $id);
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
                    'url' => 'api/seguimentos/' . $id
                )
            )
        );

        return $response;
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $descricao = $data['descricao'];
        $classe = $data['classe'];    

        try {
            $qry = "INSERT INTO cliente_seguimento(
                    descricao, 
                    classe
                    )VALUES(
                        :p01,
                        :p02)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $classe);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro inserido com sucesso',
            'request' => array(
                'type' => 'POST',
                'description' => 'Inclusão de registro',
                'request' => array(
                    'type' => 'PUT',
                    'description' => 'Registro inserido com sucesso',
                    'url' => 'api/seguimentos'
                )
            )
        );

        return $response;
    }
}