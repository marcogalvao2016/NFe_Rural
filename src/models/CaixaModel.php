<?php
namespace src\models;

use \core\Model;

class CaixaModel extends Model
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
            $qry = "SELECT * FROM caixa ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "situacao" => $row['situacao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/caixa/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário

            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "caixas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "caixas" => $data,
        ];
    }

    function caixaAberto()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT mc.id AS id_movimento, mc.idcaixa AS id_caixa,
                cx.descricao as caixa, cx.caixa_web FROM movimentocaixa mc
                LEFT JOIN caixa cx ON (mc.idcaixa = cx.id) 
                WHERE mc.status = 'A' AND cx.caixa_web = 'S' 
                LIMIT 1";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();

            if ($count <= 0) {
                $data = array(
                    "id_movimento" => 2,
                    "caixa" => 'GERAL',
                    "id_caixa" => 2,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os caixas abertos",
                        "url" => "api/caixaaberto/",
                    ]
                );
            } else {
                foreach ($results as $row) {
                    $data = array(
                        "id_movimento" => $row['id_movimento'],
                        "caixa" => $row['caixa'],
                        "id_caixa" => $row['id_caixa'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna todos os caixas abertos",
                            "url" => "api/caixaaberto/",
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário

            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "caixasAberto" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "caixasAberto" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM caixa WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = false;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "descricao" => $row['descricao'],
                        "situacao" => $row['situacao'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/caixa/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "caixa" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "caixa" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "caixa" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM caixa WHERE id = '$id'";
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
                'url' => 'api/caixa',
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
        $situacao = $data['situacao'];

        try {
            $qry = "UPDATE caixa SET 
                    descricao =:p01,
                    situacao =:p02                    
                WHERE id =:p03";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $situacao);
            $stmt->bindValue("p03", $id);
            $stmt->execute();

            $retorno = true;
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
                    'url' => 'api/caixa/' . $id
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
        $situacao = $data['situacao'];

        try {
            $qry = "INSERT INTO caixa(
                    descricao, situacao)VALUES(:p01, :p02)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $situacao);
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
                    'url' => 'api/caixa'
                )
            )
        );

        return $response;
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM caixa WHERE descricao like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "situacao" => $row['situacao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/caixa/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "caixas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "caixas" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM caixa ORDER BY id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "situacao" => $row['situacao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/caixa/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "caixas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "caixas" => $data,
        ];
    }

}