<?php
namespace src\models;

use \core\Model;

class ProductEcommerceModel extends Model
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
            $qry = "SELECT * FROM product ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "titulo_curto" => $row['titulo_curto'],
                    "produtoid" => $row['produtoid'],
                    "visivel" => $row['visivel'],
                    "destaque" => $row['destaque'],
                    "marca" => $row['marca'],
                    "estoque" => $row['estoque'],
                    "preco_revenda" => $row['preco_revenda'],
                    "link_imagem" => $row['link_imagem'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/product/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário

            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "products" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "products" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM product WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = false;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "titulo_curto" => $row['titulo_curto'],
                        "titulo" => $row['titulo'],
                        "produtoid" => $row['produtoid'],
                        "visivel" => $row['visivel'],
                        "destaque" => $row['destaque'],
                        "marca" => $row['marca'],
                        "estoque" => $row['estoque'],
                        "promocao" => $row['promocao'],
                        "produto_novo" => $row['produto_novo'],
                        "ncm" => $row['ncm'],
                        "preco_normal" => $row['preco_normal'],
                        "preco_revenda" => $row['preco_revenda'],
                        "descricao" => $row['descricao'],
                        "link_imagem" => $row['link_imagem'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/product/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "product" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "product" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "product" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM product WHERE id = '$id'";
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
                'url' => 'api/product',
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
        $promocao = trim($data['promocao']);
        $destaque = trim($data['destaque']);
        $produto_novo = trim($data['produto_novo']);
        $visivel = trim($data['visivel']);

        try {
            $qry = "UPDATE product SET 
                    promocao =:p01,                 
                    destaque   =:p02,
                    produto_novo  =:p03,                                          
                    visivel  =:p04
                WHERE id =:p05";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $promocao);
            $stmt->bindValue("p02", $destaque);
            $stmt->bindValue("p03", $produto_novo);
            $stmt->bindValue("p04", $visivel);
            $stmt->bindValue("p05", $id);
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
                    'url' => 'api/product/' . $id
                )
            )
        );

        return $response;
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $titulo_curto = $data['titulo_curto'];

        try {
            $qry = "INSERT INTO product(
                    titulo_curto)VALUES(:p01)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $titulo_curto);
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
                    'url' => 'api/product'
                )
            )
        );

        return $response;
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM product WHERE titulo_curto like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "titulo_curto" => $row['titulo_curto'],
                    "produtoid" => $row['produtoid'],
                    "visivel" => $row['visivel'],
                    "destaque" => $row['destaque'],
                    "marca" => $row['marca'],
                    "estoque" => $row['estoque'],
                    "preco_revenda" => $row['preco_revenda'],
                    "link_imagem" => $row['link_imagem'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/product/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "products" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "products" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM product ORDER BY id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "titulo_curto" => $row['titulo_curto'],
                    "produtoid" => $row['produtoid'],
                    "visivel" => $row['visivel'],
                    "destaque" => $row['destaque'],
                    "marca" => $row['marca'],
                    "estoque" => $row['estoque'],
                    "preco_revenda" => $row['preco_revenda'],
                    "link_imagem" => $row['link_imagem'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/product/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "products" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "products" => $data,
        ];
    }

    function listarPorPaginacao($pagina, $limte)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        $page = $pagina;
        $limit = $limte;
        $offset = ($page * $limit);

        try {
            // Contar o total de registros
            $countQry = "SELECT COUNT(*) as total FROM product";
            $countStmt = $this->conexao->prepare($countQry);
            $countStmt->execute();
            $totalResults = $countStmt->fetch(\PDO::FETCH_ASSOC)['total'];

            $qry = "SELECT * FROM product ORDER BY id LIMIT $limit OFFSET $offset";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "titulo_curto" => $row['titulo_curto'],
                    "produtoid" => $row['produtoid'],
                    "visivel" => $row['visivel'],
                    "destaque" => $row['destaque'],
                    "marca" => $row['marca'],
                    "estoque" => $row['estoque'],
                    "preco_revenda" => $row['preco_revenda'],
                    "link_imagem" => $row['link_imagem'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/product/listar/paginacao",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "products" => [],
            ];
        }

        return [
            "error" => false,
            "totalQt" => $totalResults,
            "page" => $page,
            "limit" => $limit,
            "products" => $data,
        ];
    }
}