<?php

namespace src\models;

use \core\Model;

class TabelaPrecoModel extends Model
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
            $qry = "SELECT * FROM tabela_precos ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "validade" => $row['validade'],
                    "validade_final" => $row['validade_final'],
                    "ativado" => $row['ativado'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/tabelaprecos/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "tabelaprecos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "tabelaprecos" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM tabela_precos WHERE id = '$id'";
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
                        "validade" => $row['validade'],
                        "validade_final" => $row['validade_final'],
                        "ativado" => $row['ativado'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/tabelaprecos/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "tabelapreco" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "tabelapreco" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "tabelapreco" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM tabela_precos ORDER BY descricao DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "validade" => $row['validade'],
                    "validade_final" => $row['validade_final'],
                    "ativado" => $row['ativado'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/tabelaprecos/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "tabelaprecos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "tabelaprecos" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM tabela_precos WHERE descricao like '%" . $texto . "%' 
                ORDER BY descricao DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "validade" => $row['validade'],
                    "validade_final" => $row['validade_final'],
                    "ativado" => $row['ativado'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/tabelaprecos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "tabelaprecos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "tabelaprecos" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(200);

        try {
            $qry = "DELETE FROM tabela_precos WHERE id = '$id'";
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
                'url' => 'api/tabelaprecos',
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
        $validade = $data['validade'];
        $validade_final = $data['validade_final'];
        $ativado = $data['ativado'];

        try {
            $qry = "UPDATE tabela_precos SET 
                    descricao =:p01,
                    validade =:p02,       
                    validade_final =:p03,
                    ativado =:p04
                WHERE id =:p05";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $validade);
            $stmt->bindValue("p03", $validade_final);
            $stmt->bindValue("p04", $ativado);
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
                    'url' => 'api/tabelaprecos/' . $id
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
        $validade = $data['validade'];
        $validade_final = $data['validade_final'];
        $ativado = $data['ativado'];

        try {
            $qry = "INSERT INTO tabela_precos(
                    descricao,
                    validade,
                    validade_final,
                    ativado)VALUES(
                        :p01,
                        :p02,
                        :p03,
                        :p04)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $validade);
            $stmt->bindValue("p03", $validade_final);
            $stmt->bindValue("p04", $ativado);
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
                    'url' => 'api/tabelaprecos'
                )
            )
        );

        return $response;
    }
}
