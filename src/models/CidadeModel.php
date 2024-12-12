<?php
namespace src\models;

use \core\Model;

class CidadeModel extends Model
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
            $qry = "SELECT * FROM clientecidade ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "uf" => $row['uf'],
                    "cidade" => $row['cidade'],
                    "idnfe" => $row['idnfe'],
                    "iduf" => $row['iduf'],
                    "value" => $row['id'],
                    "label" => $row['uf'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/cidades/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "cidades" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "cidades" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM clientecidade ORDER BY id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "uf" => $row['uf'],
                    "cidade" => $row['cidade'],
                    "idnfe" => $row['idnfe'],
                    "iduf" => $row['iduf'],
                    "value" => $row['id'],
                    "label" => $row['uf'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/cidades/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "cidades" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "cidades" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM clientecidade WHERE uf like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "uf" => $row['uf'],
                    "cidade" => $row['cidade'],
                    "idnfe" => $row['idnfe'],
                    "iduf" => $row['iduf'],
                    "value" => $row['id'],
                    "label" => $row['uf'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/cidades/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "cidades" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "cidades" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM clientecidade WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "uf" => $row['uf'],
                        "cidade" => $row['cidade'],
                        "idnfe" => $row['idnfe'],
                        "iduf" => $row['iduf'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/cidades/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "cidade" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "cidade" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "cidade" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM clientecidade WHERE id = '$id'";
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
                'url' => 'api/cidade',
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
        $cidade = $data['cidade'];
        $idnfe = $data['idnfe'];
        $uf = $data['uf'];
        $iduf = $data['iduf'];

        try {
            $qry = "UPDATE clientecidade SET 
                    cidade =:p01,
                    idnfe =:p02,
                    uf =:p03,                 
                    iduf =:p04                 
                WHERE id =:p05";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $cidade);
            $stmt->bindValue("p02", $idnfe);
            $stmt->bindValue("p03", $uf);
            $stmt->bindValue("p04", $iduf);
            $stmt->bindValue("p05", $id);
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
                    'url' => 'api/cidade/' . $id
                )
            )
        );

        return $response;
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $cidade = $data['cidade'];
        $idnfe = $data['idnfe'];
        $uf = $data['uf'];
        $iduf = $data['iduf'];

        try {
            $qry = "INSERT INTO clientecidade(
                    cidade, 
                    idnfe,
                    uf,
                    iduf)VALUES(
                        :p01, 
                        :p02,
                        :p03,
                        :p04,
                        :p05)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $cidade);
            $stmt->bindValue("p02", $idnfe);
            $stmt->bindValue("p03", $uf);
            $stmt->bindValue("p04", $iduf);
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
                    'url' => 'api/cidade'
                )
            )
        );

        return $response;
    }
}