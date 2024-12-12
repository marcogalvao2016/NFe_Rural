<?php
namespace src\models;

use \core\Model;

class VideoModel extends Model
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
            $qry = "SELECT v.*, vc.descricao as categoria FROM videos v 
                LEFT JOIN video_categoria vc ON (v.id_categoria = vc.id)
                ORDER BY v.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "descricao" => $row['descricao_video'],
                    "link_video" => $row['link_video'],
                    "id_categoria" => $row['id_categoria'],
                    "categoria" => $row['categoria'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/videos/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "videos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "videos" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT v.*, vc.descricao as categoria FROM videos v 
                LEFT JOIN video_categoria vc ON (v.id_categoria = vc.id) 
                ORDER BY v.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "descricao" => $row['descricao_video'],
                    "link_video" => $row['link_video'],
                    "id_categoria" => $row['id_categoria'],
                    "categoria" => $row['categoria'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/videos/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "videos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "videos" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT v.*, vc.descricao as categoria FROM videos v 
                LEFT JOIN video_categoria vc ON (v.id_categoria = vc.id) 
                WHERE v.descricao_video like '%" . $texto . "%' 
                ORDER BY v.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "descricao" => $row['descricao_video'],
                    "link_video" => $row['link_video'],
                    "id_categoria" => $row['id_categoria'],
                    "categoria" => $row['categoria'],
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
                "videos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "videos" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT v.*, vc.descricao as categoria FROM videos v 
                LEFT JOIN video_categoria vc ON (v.id_categoria = vc.id) 
                WHERE v.id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "cadastro" => $row['cadastro'],
                        "hora" => $row['hora'],
                        "descricao" => $row['descricao_video'],
                        "link_video" => $row['link_video'],
                        "id_categoria" => $row['id_categoria'],
                        "categoria" => $row['categoria'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/videos/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "video" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "video" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "video" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM videos WHERE id = '$id'";
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
                'url' => 'api/videos',
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
        $link_video = $data['link_video'];
        $id_categoria = $data['id_categoria'];

        try {
            $qry = "UPDATE videos SET 
                    descricao_video =:p01,
                    link_video =:p02,
                    id_categoria =:p03          
                WHERE id =:p04";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $link_video);
            $stmt->bindValue("p03", $id_categoria);
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
                    'url' => 'api/videos/' . $id
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
        $link_video = $data['link_video'];
        $id_categoria = $data['id_categoria'];

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        try {
            $qry = "INSERT INTO videos(
                    descricao_video,
                    link_video,
                    id_categoria,
                    cadastro,
                    hora
                    )VALUES(
                        :p01,
                        :p02,
                        :p03,
                        :p04,
                        :p05)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $link_video);
            $stmt->bindValue("p03", $id_categoria);
            $stmt->bindValue("p04", $dataAtual);
            $stmt->bindValue("p05", $hora);
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
                    'url' => 'api/videos'
                )
            )
        );

        return $response;
    }
}