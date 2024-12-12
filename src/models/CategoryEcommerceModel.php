<?php
namespace src\models;

use \core\Model;

class CategoryEcommerceModel extends Model
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
            $qry = "SELECT * FROM category ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "category_id" => $row['category_id'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/category/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário

            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "categorys" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "categorys" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM category WHERE id = '$id'";
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
                        "category_id" => $row['category_id'],
                        "slug" => $row['slug'],
                        "visivel" => $row['visivel'],
                        "destaque" => $row['destaque'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/category/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "category" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "category" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "category" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM category WHERE id = '$id'";
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
                'url' => 'api/category',
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
        $visivel = trim($data['visivel']);
        $destaque = trim($data['destaque']);

        try {
            $qry = "UPDATE category SET 
                    descricao =:p01,              
                    visivel   =:p02,
                    destaque  =:p03                                        
                WHERE id =:p04";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $visivel);
            $stmt->bindValue("p03", $destaque);
            $stmt->bindValue("p04", $id);
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
                    'url' => 'api/category/' . $id
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

        try {
            $qry = "INSERT INTO caixa(
                    descricao)VALUES(:p01)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
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
                    'url' => 'api/category'
                )
            )
        );

        return $response;
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM category WHERE descricao like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "category_id" => $row['category_id'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/category/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "categorys" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "categorys" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM category ORDER BY id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "category_id" => $row['category_id'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/category/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "categorys" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "categorys" => $data,
        ];
    }

    function subCategories()
    {
        $DATA = [];
        http_response_code(200);

        try {
            $qry = "SELECT DISTINCT categoria_nome FROM product 
                    WHERE categoria_nome LIKE 'SOM PROFISSIONAL%' OR 
                    categoria_nome LIKE 'INSTRUMENTOS MUSICAIS%' OR 
                    categoria_nome LIKE 'GAMES%' OR     
                    categoria_nome LIKE 'ELETRONICOS%' OR
                    categoria_nome LIKE 'INFORMATICA%' OR 
                    categoria_nome LIKE 'OUTROS%'
                    ORDER BY categoria_nome";
            $stmt = $this->conexao->PREPARE($qry);
            $stmt->EXECUTE();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                // Divide a string de categorias
                $parts = explode(' / ', $row['categoria_nome']);

                // Adiciona cada parte da categoria ao array, evitando duplicação
                foreach ($parts as $part) {
                    if (!in_array($part, $DATA)) {
                        $DATA[] = $part;
                    }
                }
            }
        } catch (\Exception $e) {
            http_response_code(500);

            return [
                "error" => TRUE,
                "length" => 0,
                "categorys" => [],
            ];
        }

        return [
            "error" => FALSE,
            "length" => count($DATA),
            "categorys" => $DATA,
        ];
    }

}