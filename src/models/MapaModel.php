<?php
namespace src\models;

use \core\Model;

class MapaModel extends Model
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
            $qry = "SELECT * FROM mapas WHERE situacao = '0' ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "nome" => $row['nome'],
                    "latitude" => $row['latitude'],
                    "longitude" => $row['longitude'],
                    "referencia" => $row['referencia'],
                    "link" => $row['link'],
                    "numero" => $row['numero'],
                    "descricao_mapa" => $row['numero'] . " - " . $row['nome'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/mapas/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "mapas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "mapas" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM mapas WHERE situacao = '0' 
                ORDER BY id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "nome" => $row['nome'],
                    "latitude" => $row['latitude'],
                    "longitude" => $row['longitude'],
                    "referencia" => $row['referencia'],
                    "numero" => $row['numero'],
                    "link" => $row['link'],
                    "descricao_mapa" => $row['numero'] . " - " . $row['nome'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/mapas/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "mapas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "mapas" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM mapas WHERE situacao = '0' 
                AND descricao like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "nome" => $row['nome'],
                    "latitude" => $row['latitude'],
                    "longitude" => $row['longitude'],
                    "referencia" => $row['referencia'],
                    "link" => $row['link'],
                    "numero" => $row['numero'],
                    "descricao_mapa" => $row['numero'] . " - " . $row['nome'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/mapas/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "mapas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "mapas" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM mapas WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "nome" => $row['nome'],
                        "latitude" => $row['latitude'],
                        "longitude" => $row['longitude'],
                        "referencia" => $row['referencia'],
                        "link" => $row['link'],
                        "numero" => $row['numero'],
                        "arquivo" => $row['arquivo'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/mapas/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "mapa" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "mapa" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "mapa" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM mapas WHERE id = '$id'";
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
                'url' => 'api/mapas',
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
        $nome = $data['nome'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $referencia = $data['referencia'];
        $link = $data['link'];
        $numero = $data['numero'];
        $anexo = $data['anexo'];
        $temAnexo = $data['temAnexo'];

        if ($temAnexo == 'N') {
            $SQLProduto = "SELECT id, arquivo FROM mapas WHERE id = '$id'";
            $stmt = $this->conexao->prepare($SQLProduto);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $anexo = $row['arquivo'];
            }
        }

        try {
            $qry = "UPDATE mapas SET 
                    nome =:p01,
                    latitude =:p02,
                    longitude =:p03,
                    referencia =:p04,
                    link =:p05,
                    numero =:p06,
                    arquivo  =:p07
                  WHERE id =:p08";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $nome);
            $stmt->bindValue("p02", $latitude);
            $stmt->bindValue("p03", $longitude);
            $stmt->bindValue("p04", $referencia);
            $stmt->bindValue("p05", $link);
            $stmt->bindValue("p06", $numero);
            $stmt->bindValue("p07", $anexo);
            $stmt->bindValue("p08", $id);
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
                    'url' => 'api/mapas/' . $id
                )
            )
        );

        return $response;
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $nome = $data['nome'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $referencia = $data['referencia'];
        $link = $data['link'];
        $numero = $data['numero'];
        $anexo = $data['anexo'];

        try {
            $qry = "INSERT INTO mapas(
                    nome,
                    latitude,
                    longitude,
                    referencia,
                    link,
                    numero,
                    arquivo)VALUES(
                        :p01,
                        :p02,
                        :p03,
                        :p04,
                        :p05,
                        :p06,
                        :p07)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $nome);
            $stmt->bindValue("p02", $latitude);
            $stmt->bindValue("p03", $longitude);
            $stmt->bindValue("p04", $referencia);
            $stmt->bindValue("p05", $link);
            $stmt->bindValue("p06", $numero);
            $stmt->bindValue("p07", $anexo);
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
                    'url' => 'api/mapas'
                )
            )
        );

        return $response;
    }

    function listaGeral()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT m.*, DATEDIFF(CURDATE(), m.ultima_designacao) AS dias_passados
              FROM mapas m ORDER BY m.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $descricao_mapa = "";
            $count = $stmt->rowCount();
            foreach ($results as $row) {
                if ($row['situacao'] == "0") {
                    $descricao_mapa = $row['numero'] . " - " . $row['nome'];
                } else {
                    $descricao_mapa = $row['numero'] . " - " . $row['nome'] . " - [ " . $row['dias_passados'] . " dia(s) ]";
                }

                $data[] = [
                    "id" => $row['id'],
                    "nome" => $row['nome'],
                    "latitude" => $row['latitude'],
                    "longitude" => $row['longitude'],
                    "referencia" => $row['referencia'],
                    "link" => $row['link'],
                    "numero" => $row['numero'],
                    "situacao" => $row['situacao'],
                    "descricao_mapa" => $descricao_mapa,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/mapas/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "mapas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "mapas" => $data,
        ];
    }
}