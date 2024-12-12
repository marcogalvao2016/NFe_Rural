<?php

namespace src\models;

use \core\Model;

class ProdutoCadastroModel extends Model
{
    private $conexao; // Variável para armazenar a conexão PDO

    function __construct()
    {
        require_once 'conexao/db_connection_cadastro.php';
        $this->conexao = new \DB_Con();
    }

    function listar()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT p.* FROM produtos p ORDER BY p.id DESC LIMIT 100";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "ean" => $row['ean'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "unidade" => $row['unidade'],
                    "ncm" => $row['ncm'],
                    "cest" => $row['cest'],
                    "preco" => floatval($row['preco']),
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/prodpesquisa/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "prodsPesquisa" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "prodsPesquisa" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT p.* FROM produtos p ORDER BY p.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "ean" => $row['ean'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "unidade" => $row['unidade'],
                    "ncm" => $row['ncm'],
                    "cest" => $row['cest'],
                    "preco" => floatval($row['preco']),
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/prodpesquisa/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "prodsPesquisa" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "prodsPesquisa" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM produtos WHERE descricao like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "ean" => $row['ean'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "unidade" => $row['unidade'],
                    "ncm" => $row['ncm'],
                    "cest" => $row['cest'],
                    "preco" => floatval($row['preco']),
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/prodpesquisa/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "prodsPesquisa" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "prodsPesquisa" => $data,
        ];
    }

    function pesquisarEAN($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM produtos WHERE ean like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "ean" => $row['ean'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "unidade" => $row['unidade'],
                    "ncm" => $row['ncm'],
                    "cest" => $row['cest'],
                    "preco" => floatval($row['preco']),
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/prodpesquisa/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "prodsPesquisa" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "prodsPesquisa" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM produtos WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "ean" => $row['ean'],
                        "descricao" => removeCaracterEspecial($row['descricao']),
                        "unidade" => $row['unidade'],
                        "ncm" => $row['ncm'],
                        "cest" => $row['cest'],
                        "preco" => floatval($row['preco']),
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/prodpesquisa/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "prodPesquisa" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "prodPesquisa" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "prodPesquisa" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM produtos WHERE id = '$id'";
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
                'url' => 'api/produtos',
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

        $id = trim($data['id']);
        $descricao = $data['descricao'];
        $ean = $data['ean'];
        $unidade = $data['unidade'];
        $ncm = $data['ncm'];
        $cest = $data['cest'];
        $preco = $data['preco'];

        try {
            $qry = "UPDATE produtos SET 
                    descricao =:p01,
                    ean =:p02,
                    unidade =:p03,
                    ncm =:p04,
                    cest =:p05,
                    preco =:p06
                WHERE id =:p07";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $ean);
            $stmt->bindValue("p03", $unidade);
            $stmt->bindValue("p04", $ncm);
            $stmt->bindValue("p05", $cest);
            $stmt->bindValue("p06", $preco);
            $stmt->bindValue("p07", $id);
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
                    'url' => 'api/produtos/' . $id
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
        $ean = $data['ean'];
        $unidade = $data['unidade'];
        $ncm = $data['ncm'];
        $cest = $data['cest'];
        $preco = $data['preco'];

        try {
            $qry = "INSERT INTO produtos(
                    descricao,
                    ean,
                    unidade,
                    ncm,
                    cest,
                    preco)VALUES(
                        :p01,
                        :p02,
                        :p03,
                        :p04,
                        :p05,
                        :p06)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $ean);
            $stmt->bindValue("p03", $unidade);
            $stmt->bindValue("p04", $ncm);
            $stmt->bindValue("p05", $cest);
            $stmt->bindValue("p06", $preco);
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
                    'url' => 'api/marcas'
                )
            )
        );

        return $response;
    }
}
