<?php
namespace src\models;

use \core\Model;

class TelasSistemaModel extends Model
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
            $qry = "SELECT t.*, CASE t.tipo WHEN '0' THEN 'NENHUM'
                WHEN '1' THEN 'ADMINISTRATIVO' WHEN '2' THEN 'PDV VAREJO'
                WHEN '3' THEN 'PDV RESTAURANTE' WHEN '4' THEN 'ORDEM DE SERVIÇO'
                WHEN '5' THEN 'CONTROLE DE ÓTICA' WHEN '6' THEN 'ECOMMERCE'
                END AS tipo_descricao FROM telas_sistema t ORDER BY t.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "principal" => $row['principal'] == "N" ? 'NÃO' : 'SIM',
                    "tipo" => $row['tipo'],
                    "foto" => $row['foto'],
                    "observacoes" => $row['observacoes'],
                    "tipo_descricao" => $row['tipo_descricao'],
                    "categorys" => $row['categoria'],
                    "local" => $row['local'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/telassistemas/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "telas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "telas" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT t.*, CASE t.tipo WHEN '0' THEN 'NENHUM'
                WHEN '1' THEN 'ADMINISTRATIVO' WHEN '2' THEN 'PDV VAREJO'
                WHEN '3' THEN 'PDV RESTAURANTE' WHEN '4' THEN 'ORDEM DE SERVIÇO'
                WHEN '5' THEN 'CONTROLE DE ÓTICA' WHEN '6' THEN 'ECOMMERCE'
                END AS tipo_descricao FROM telas_sistema t 
                ORDER BY t.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "principal" => $row['principal'] == "N" ? 'NÃO' : 'SIM',
                    "tipo" => $row['tipo'],
                    "foto" => $row['foto'],
                    "observacoes" => $row['observacoes'],
                    "tipo_descricao" => $row['tipo_descricao'],                    
                    "local" => $row['local'],
                    "categorys" => $row['categoria'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/telassistemas/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "telas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "telas" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT t.*, CASE t.tipo WHEN '0' THEN 'NENHUM'
                WHEN '1' THEN 'ADMINISTRATIVO' WHEN '2' THEN 'PDV VAREJO'
                WHEN '3' THEN 'PDV RESTAURANTE' WHEN '4' THEN 'ORDEM DE SERVIÇO'
                WHEN '5' THEN 'CONTROLE DE ÓTICA' WHEN '6' THEN 'ECOMMERCE'
                END AS tipo_descricao FROM telas_sistema t 
                WHERE t.descricao like '%" . $texto . "%' 
                ORDER BY t.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "principal" => $row['principal'] == "N" ? 'NÃO' : 'SIM',
                    "tipo" => $row['tipo'],
                    "foto" => $row['foto'],
                    "observacoes" => $row['observacoes'],
                    "tipo_descricao" => $row['tipo_descricao'],
                    "local" => $row['local'],
                    "categorys" => $row['categoria'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/telassistemas/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "telas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "telas" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT t.*, CASE t.tipo WHEN '0' THEN 'NENHUM'
                WHEN '1' THEN 'ADMINISTRATIVO' WHEN '2' THEN 'PDV VAREJO'
                WHEN '3' THEN 'PDV RESTAURANTE' WHEN '4' THEN 'ORDEM DE SERVIÇO'
                WHEN '5' THEN 'CONTROLE DE ÓTICA' WHEN '6' THEN 'ECOMMERCE'
                END AS tipo_descricao FROM telas_sistema t WHERE t.id = '$id'";
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
                        "principal" => $row['principal'],
                        "tipo" => $row['tipo'],
                        "foto" => $row['foto'],
                        "observacoes" => $row['observacoes'],
                        "tipo_descricao" => $row['tipo_descricao'],
                        "local" => $row['local'],
                        "categorys" => $row['categoria'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/telassistemas/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "tela" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "tela" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "tela" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM telas_sistema WHERE id = '$id'";
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
                'url' => 'api/telassistemas',
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
        $principal = $data['principal'];
        $tipo = $data['tipo'];
        $observacoes = $data['observacoes'];
        $local = $data['local'];
        $categorys = $data['categorys'];
        $avatar = $data['avatar'];
        $temIMG = $data['temIMG'];

        if ($temIMG == 'N') {
            $SQLUsuario = "SELECT id, foto FROM telas_sistema WHERE id = '$id'";
            $stmt = $this->conexao->prepare($SQLUsuario);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $avatar = $row['foto'];
            }
        }

        try {
            $qry = "UPDATE telas_sistema SET 
                    descricao =:p01,
                    principal =:p02,
                    tipo =:p03,
                    foto =:p04,
                    observacoes =:p05,
                    local =:p06,
                    categoria =:p07          
                WHERE id =:p08";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $principal);
            $stmt->bindValue("p03", $tipo);
            $stmt->bindValue("p04", $avatar);
            $stmt->bindValue("p05", $observacoes);
            $stmt->bindValue("p06", $local);
            $stmt->bindValue("p07", $categorys);
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
                    'url' => 'api/telassistemas/' . $id
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
        $principal = $data['principal'];
        $tipo = $data['tipo'];
        $observacoes = $data['observacoes'];
        $local = $data['local'];
        $categorys = $data['categorys'];
        $avatar = $data['avatar'];

        try {
            $qry = "INSERT INTO telas_sistema(
                    descricao,
                    principal,
                    tipo,
                    observacoes,
                    foto, 
                    local,
                    categoria)VALUES(
                        :p01,
                        :p02,
                        :p03,
                        :p04,
                        :p05,
                        :p06,
                        :p07)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $principal);
            $stmt->bindValue("p03", $tipo);
            $stmt->bindValue("p04", $observacoes);
            $stmt->bindValue("p05", $avatar);
            $stmt->bindValue("p06", $local);
            $stmt->bindValue("p07", $categorys);
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
                    'url' => 'api/telassistemas'
                )
            )
        );

        return $response;
    }
}