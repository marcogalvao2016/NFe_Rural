<?php
namespace src\models;

use \core\Model;

class PlanoContasModel extends Model
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
            $qry = "SELECT * FROM planocontas ORDER BY planocontas DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "planocontas" => $row['planocontas'],
                    "descricao" => $row['descricao'],
                    "categoria" => $row['categoria'],
                    "id_classificacao" => $row['id_classificacao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/planocontas/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário

            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "planocontas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "planocontas" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM planocontas WHERE planocontas = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = false;

                foreach ($results as $row) {
                    $data = array(
                        "planocontas" => $row['planocontas'],
                        "descricao" => $row['descricao'],
                        "categoria" => $row['categoria'],
                        "id_classificacao" => $row['id_classificacao'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/formaspagto/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "planoconta" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "planoconta" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "planoconta" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM planocontas WHERE planocontas = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro removido com sucesso',
            'request' => array(
                'description' => 'Deleta um registro',
                'url' => 'api/planocontas',
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

        $id = intval($data['id']);
        $descricao = $data['descricao'];
        $categoria = $data['categoria'];
        $id_classificacao = $data['id_classificacao'];

        try {
            $qry = "UPDATE planocontas
                SET descricao    =:p01,
                categoria        =:p02,
                id_classificacao =:p03
            WHERE planocontas    =:p04";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $categoria);
            $stmt->bindValue("p03", $id_classificacao);
            $stmt->bindValue("p04", $id);
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
                    'url' => 'api/planocontas/' . $id
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
        $categoria = $data['categoria'];
        $id_classificacao = $data['id_classificacao'];

        try {
            $qry = "INSERT INTO planocontas (
                descricao, 
                categoria, 
                id_classificacao)VALUES(
                    :p01, 
                    :p02,
                    :p03)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $categoria);
            $stmt->bindValue("p03", $id_classificacao);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro alterado com sucesso',
            'request' => array(
                'type' => 'POST',
                'description' => 'Altera um registro',
                'request' => array(
                    'type' => 'PUT',
                    'description' => 'Registro atualizado com sucesso',
                    'url' => 'api/planocontas'
                )
            )
        );

        return $response;
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM planocontas WHERE descricao like '%" . $texto . "%' 
                ORDER BY planocontas DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "planocontas" => $row['planocontas'],
                    "descricao" => $row['descricao'],
                    "categoria" => $row['categoria'],
                    "id_classificacao" => $row['id_classificacao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/planocontas/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "planocontas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "planocontas" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM planocontas ORDER BY planocontas DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "planocontas" => $row['planocontas'],
                    "descricao" => $row['descricao'],
                    "categoria" => $row['categoria'],
                    "id_classificacao" => $row['id_classificacao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/planocontas/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "planocontas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "planocontas" => $data,
        ];
    }

}