<?php
namespace src\models;

use \core\Model;

class SubGrupoModel extends Model
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
            $qry = "SELECT sg.id, sg.descricao, g.descricao as grupo, 
                sg.id_grupo FROM sub_grupo sg LEFT JOIN grupo g ON (sg.id_grupo = g.id) 
                ORDER BY sg.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'descricao' => $row['descricao'],
                    'id_grupo' => $row['id_grupo'],
                    'grupo' => $row['grupo'],
                    "value" => $row['id'],
                    "label" => $row['descricao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/subgrupos/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "subgrupos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "subgrupos" => $data,
        ];
    }

    function listarPorGrupo($idgrupo)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT sg.id, sg.descricao, g.descricao as grupo, 
                sg.id_grupo FROM sub_grupo sg 
                LEFT JOIN grupo g ON (sg.id_grupo = g.id) 
                WHERE sg.id_grupo = '$idgrupo'
                ORDER BY sg.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'descricao' => $row['descricao'],
                    'id_grupo' => $row['id_grupo'],
                    'grupo' => $row['grupo'],
                    'value' => $row['descricao'],
                    'label' => $row['descricao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/subgrupos/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "subgrupos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "subgrupos" => $data,
        ];
    }
    
    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT sg.id, sg.descricao, g.descricao as grupo, 
                sg.id_grupo FROM sub_grupo sg LEFT JOIN grupo g ON (sg.id_grupo = g.id) 
                ORDER BY sg.id DESC ORDER BY sg.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'descricao' => $row['descricao'],
                    'id_grupo' => $row['id_grupo'],
                    'grupo' => $row['grupo'],
                    "value" => $row['id'],
                    "label" => $row['descricao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/subgrupos/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "subgrupos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "subgrupos" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT sg.id, sg.descricao, g.descricao as grupo, 
                sg.id_grupo FROM sub_grupo sg LEFT JOIN grupo g ON (sg.id_grupo = g.id) 
                ORDER BY sg.id DESC WHERE sg.descricao like '%" . $texto . "%' 
                ORDER BY sg.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'descricao' => $row['descricao'],
                    'id_grupo' => $row['id_grupo'],
                    'grupo' => $row['grupo'],
                    "value" => $row['id'],
                    "label" => $row['descricao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/subgrupos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "subgrupos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "subgrupos" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT sg.id, sg.descricao, g.descricao as grupo, 
                sg.id_grupo FROM sub_grupo sg LEFT JOIN grupo g ON (sg.id_grupo = g.id) 
                WHERE sg.id = '$id'";
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
                        'id_grupo' => $row['id_grupo'],
                        'grupo' => $row['grupo'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/subgrupos/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "subgrupo" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "subgrupo" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "subgrupo" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM sub_grupo WHERE id = '$id'";
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
                'url' => 'api/subgrupo',
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
        $id_grupo = $data['id_grupo'];

        try {
            $qry = "UPDATE sub_grupo SET 
                    descricao =:p01,
                    id_grupo =:p02
                WHERE id =:p03";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $id_grupo);
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
                    'url' => 'api/subgrupo/' . $id
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
        $id_grupo = $data['id_grupo'];

        try {
            $qry = "INSERT INTO sub_grupo(
                    descricao, 
                    id_grupo)VALUES(
                        :p01, 
                        :p02)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $id_grupo);
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
                    'url' => 'api/subgrupo'
                )
            )
        );

        return $response;
    }
}