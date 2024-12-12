<?php

namespace src\models;

use \core\Model;

class GrupoModel extends Model
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

        try {
            $qry = "SELECT g.id, g.descricao, g.url, g.tipo_fracao, g.mostra_tablet
                FROM grupo g WHERE g.mostra_tablet = 'S' ORDER BY g.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "mostra_tablet" => $row['mostra_tablet'],
                    "tipo_fracao" => $row['tipo_fracao'],
                    "url" => $row['url'],
                    "value" => $row['id'],
                    "label" => $row['descricao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/grupo/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "grupos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "grupos" => $data,
        ];
    }

    function deletar($id)
    {
        $retorno = false;

        try {
            $qry = "DELETE FROM grupo WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            $retorno = false;
        }

        return $retorno;
    }

    function view($id)
    {
        $retorno = true;
        $data = [];

        try {
            $qry = "SELECT 
                g.id, 
                g.descricao,
                g.mostra_tablet,
                g.tipo_fracao,
                g.url
                FROM grupo g 
                WHERE g.id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => intval($row['id']),
                        "descricao" => $row['descricao'],
                        "mostra_tablet" => $row['mostra_tablet'],
                        "tipo_fracao" => $row['tipo_fracao'],
                        "url" => $row['url'],
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
                    "grupo" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "grupo" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "grupo" => $data,
        ];
    }

    function alterar($data)
    {
        $retorno = false;

        $id = $data['id'];
        $descricao = $data['descricao'];
        $mostra_tablet = $data['mostra_tablet'];
        $url = $data['url'];

        try {
            $qry = "UPDATE grupo SET 
                    descricao =:p01,
                    mostra_tablet =:p02,
                    url =:p03
                WHERE id =:p04";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $mostra_tablet);
            $stmt->bindValue("p03", $url);
            $stmt->bindValue("p04", $id);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            $retorno = false;
        }

        return $retorno;
    }

    function inserir($data)
    {
        $retorno = false;
        $descricao = $data['descricao'];
        $mostra_tablet = $data['mostra_tablet'];
        $url = $data['url'];

        try {
            $qry = "INSERT INTO grupo(
                    descricao, mostra_tablet, url)VALUES(:p01, :p02, :p03)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $mostra_tablet);
            $stmt->bindValue("p03", $url);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            $retorno = false;
        }

        return $retorno;
    }

    function search($texto)
    {
        $data = [];

        try {
            $qry = "SELECT g.id, g.descricao, g.url, g.tipo_fracao, g.mostra_tablet, g.url
                FROM grupo g WHERE g.mostra_tablet = 'S' AND 
                g.descricao like '%" . $texto . "%' 
                ORDER BY g.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "mostra_tablet" => $row['mostra_tablet'],
                    "tipo_fracao" => $row['tipo_fracao'],
                    "url" => $row['url'],
                    "value" => $row['id'],
                    "label" => $row['descricao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/grupo/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "grupos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => count($data),
            "grupos" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT g.id, g.descricao, g.url, g.tipo_fracao, g.mostra_tablet, g.url
                FROM grupo g WHERE g.mostra_tablet = 'S' 
                ORDER BY g.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "mostra_tablet" => $row['mostra_tablet'],
                    "tipo_fracao" => $row['tipo_fracao'],
                    "url" => $row['url'],
                    "value" => $row['id'],
                    "label" => $row['descricao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/grupo/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "grupos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => count($data),
            "grupos" => $data,
        ];
    }
}
