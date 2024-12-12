<?php
namespace src\models;

use \core\Model;

class NCMModel extends Model
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
            $qry = "SELECT id, descricao, aliqnacional, 
                aliqinternacional, idncm FROM ncm ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'descricao' => $row['descricao'],
                    'aliqnacional' => floatval($row['aliqnacional']),
                    'aliqinternacional' => floatval($row['aliqinternacional']),
                    'idncm' => $row['idncm'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/ncms/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "ncms" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "ncms" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT id, descricao, aliqnacional, 
                aliqinternacional, idncm FROM ncm ORDER BY id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'descricao' => $row['descricao'],
                    'aliqnacional' => floatval($row['aliqnacional']),
                    'aliqinternacional' => floatval($row['aliqinternacional']),
                    'idncm' => $row['idncm'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/ncms/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "ncms" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "ncms" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT id, descricao, aliqnacional, 
                aliqinternacional, idncm FROM ncm WHERE descricao like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'descricao' => $row['descricao'],
                    'aliqnacional' => floatval($row['aliqnacional']),
                    'aliqinternacional' => floatval($row['aliqinternacional']),
                    'idncm' => $row['idncm'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/ncms/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "ncms" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "ncms" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT id, descricao, aliqnacional, 
                aliqinternacional, idncm FROM ncm WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        'id' => intval($row['id']),
                        'descricao' => $row['descricao'],
                        'aliqnacional' => floatval($row['aliqnacional']),
                        'aliqinternacional' => floatval($row['aliqinternacional']),
                        'idncm' => $row['idncm'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/ncm/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "ncm" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "ncm" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "ncm" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM ncm WHERE id = '$id'";
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
                'url' => 'api/ncm',
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
        $aliqnacional = $data['aliqnacional'];
        $aliqinternacional = $data['aliqinternacional'];
        $idncm = $data['idncm'];

        try {
            $qry = "UPDATE ncm SET
                descricao          =:p01,                     
                aliqnacional       =:p02,
                aliqinternacional  =:p03,
                idncm              =:p04
            WHERE id               =:p05";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $aliqnacional);
            $stmt->bindValue("p03", $aliqinternacional);
            $stmt->bindValue("p04", $idncm);
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
                    'url' => 'api/ncm/' . $id
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
        $aliqnacional = $data['aliqnacional'];
        $aliqinternacional = $data['aliqinternacional'];
        $idncm = $data['idncm'];

        try {
            $qry = "INSERT INTO ncm(
                    descricao, 
                    aliqnacional,
                    aliqinternacional,
                    idncm)VALUES(
                        :p01, 
                        :p02,
                        :p03,
                        :p04)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $aliqnacional);
            $stmt->bindValue("p03", $aliqinternacional);
            $stmt->bindValue("p04", $idncm);
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
                    'url' => 'api/ncm'
                )
            )
        );

        return $response;
    }
}