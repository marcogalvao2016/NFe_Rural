<?php
namespace src\models;

use \core\Model;

class DesignacaoModel extends Model
{
    private $conexao; // Variável para armazenar a conexão PDO

    function __construct()
    {
        require_once 'conexao/db_connection.php';
        $this->conexao = new \DB_Con();
    }

    function listar($vSituacao)
    {
        $data = [];
        http_response_code(200);

        $SQL = "";
        if ($vSituacao != "T"){
            $SQL = "AND ds.situacao = '$vSituacao'";
        }

        try {
            $qry = "SELECT ds.*, d.nome, m.nome as mapa, m.numero, m.link, m.arquivo,
                CASE ds.situacao WHEN '0' then 'DESIGNADO' WHEN '1' then 'ENTREGUE' 
                END AS desc_situacao, u.nome AS nome_usuario,
                DATEDIFF(CURDATE(), ds.data_inicio) AS dias_passados 
                FROM designacao ds
                LEFT JOIN dirigentes d ON (ds.id_dirigente = d.id) 
                LEFT JOIN mapas m ON (ds.id_mapa = m.id) 
                LEFT JOIN usuario u ON (ds.id_usuario = u.idusuario)
                WHERE 1=1 $SQL ORDER BY ds.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $dias_passados = "";
            $count = $stmt->rowCount();
            foreach ($results as $row) {
                if ($row['situacao'] == "1") {
                    $dias_passados = '';
                } else {
                    $dias_passados = " [ ". $row['dias_passados']. " Dia(s)" . " ]";
                }

                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "id_dirigente" => $row['id_dirigente'],
                    "id_mapa" => $row['id_mapa'],
                    "situacao" => $row['situacao'],
                    "data_inicio" => $row['data_inicio'],
                    "hora_inicio" => $row['hora_inicio'],
                    "data_fim" => $row['data_fim'],
                    "hora_fim" => $row['hora_fim'],
                    "observacoes" => $row['observacoes'],
                    "nome" => $row['nome'],
                    "mapa" => $row['mapa'],
                    "arquivo" => $row['arquivo'],
                    "numero" => $row['numero'],
                    "link" => $row['link'],
                    "desc_situacao" => $row['desc_situacao'],
                    "nome_usuario" => $row['nome_usuario'],
                    "dias_passados" => $dias_passados,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/designacoes/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "designacoes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "designacoes" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT ds.*, d.nome, m.nome as mapa, m.numero, m.link, m.arquivo,
                CASE ds.situacao WHEN '0' then 'DESIGNADO' WHEN '1' then 'ENTREGUE' 
                END AS desc_situacao, u.nome AS nome_usuario,
                DATEDIFF(CURDATE(), ds.data_inicio) AS dias_passados 
                FROM designacao ds
                LEFT JOIN dirigentes d ON (ds.id_dirigente = d.id) 
                LEFT JOIN mapas m ON (ds.id_mapa = m.id) 
                LEFT JOIN usuario u ON (ds.id_usuario = u.idusuario)
                ORDER BY ds.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                if ($row['situacao'] == "1") {
                    $dias_passados = '';
                } else {
                    $dias_passados = " [ ". $row['dias_passados']. " Dia(s)" . " ]";
                }

                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "id_dirigente" => $row['id_dirigente'],
                    "id_mapa" => $row['id_mapa'],
                    "situacao" => $row['situacao'],
                    "data_inicio" => $row['data_inicio'],
                    "hora_inicio" => $row['hora_inicio'],
                    "data_fim" => $row['data_fim'],
                    "hora_fim" => $row['hora_fim'],
                    "observacoes" => $row['observacoes'],
                    "nome" => $row['nome'],
                    "mapa" => $row['mapa'],
                    "arquivo" => $row['arquivo'],
                    "numero" => $row['numero'],
                    "link" => $row['link'],
                    "desc_situacao" => $row['desc_situacao'],
                    "nome_usuario" => $row['nome_usuario'],
                    "dias_passados" => $dias_passados,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/bairros/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "designacoes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "designacoes" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT ds.*, d.nome, m.nome as mapa, m.numero, m.link, m.arquivo,
                CASE ds.situacao WHEN '0' then 'DESIGNADO' WHEN '1' then 'ENTREGUE' 
                END AS desc_situacao, u.nome AS nome_usuario,
                DATEDIFF(CURDATE(), ds.data_inicio) AS dias_passados
                FROM designacao ds
                LEFT JOIN dirigentes d ON (ds.id_dirigente = d.id) 
                LEFT JOIN mapas m ON (ds.id_mapa = m.id) 
                LEFT JOIN usuario u ON (ds.id_usuario = u.idusuario)
                WHERE m.nome like '%" . $texto . "%' 
                ORDER BY ds.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                if ($row['situacao'] == "1") {
                    $dias_passados = '';
                } else {
                    $dias_passados = " [ ". $row['dias_passados']. " Dia(s)" . " ]";
                }

                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "id_dirigente" => $row['id_dirigente'],
                    "id_mapa" => $row['id_mapa'],
                    "situacao" => $row['situacao'],
                    "data_inicio" => $row['data_inicio'],
                    "hora_inicio" => $row['hora_inicio'],
                    "data_fim" => $row['data_fim'],
                    "hora_fim" => $row['hora_fim'],
                    "observacoes" => $row['observacoes'],
                    "nome" => $row['nome'],
                    "mapa" => $row['mapa'],
                    "arquivo" => $row['arquivo'],
                    "numero" => $row['numero'],
                    "link" => $row['link'],
                    "desc_situacao" => $row['desc_situacao'],
                    "nome_usuario" => $row['nome_usuario'],
                    "dias_passados" => $dias_passados,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/bairros/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "designacoes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "designacoes" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT ds.*, m.link, m.nome, m.numero FROM designacao ds 
            LEFT JOIN mapas m ON (ds.id_mapa = m.id) 
            WHERE ds.id = '$id'";
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
                        "id_dirigente" => $row['id_dirigente'],
                        "id_mapa" => $row['id_mapa'],
                        "situacao" => $row['situacao'],
                        "data_inicio" => $row['data_inicio'],
                        "hora_inicio" => $row['hora_inicio'],
                        "data_fim" => $row['data_fim'],
                        "hora_fim" => $row['hora_fim'],
                        "observacoes" => $row['observacoes'],
                        "link" => $row['link'],
                        "endereco" => $row['numero'] . " - " . $row['nome'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/bairros/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "designacao" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "designacao" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "designacao" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM designacao WHERE id = '$id'";
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
                'url' => 'api/designacoes',
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
        $id_dirigente = $data['id_dirigente'];
        $id_mapa = $data['id_mapa'];
        $situacao = $data['situacao'];
        $observacoes = $data['observacoes'];
        $id_usuario = $data['id_usuario'];
        $data_inicio = $data['data_inicio'];
        $data_fim = $data['data_fim'];

        if (empty($data_inicio)) {
            $data_inicio = null;
        }

        if (empty($data_fim)) {
            $data_fim = null;
        }

        try {
            $qry = "UPDATE designacao SET 
                    id_dirigente =:p01,
                    id_mapa =:p02,
                    situacao =:p03,
                    data_inicio =:p04,                    
                    data_fim =:p05,                    
                    observacoes =:p06,
                    id_usuario =:p07
                WHERE id =:p08";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $id_dirigente);
            $stmt->bindValue("p02", $id_mapa);
            $stmt->bindValue("p03", $situacao);
            $stmt->bindValue("p04", $data_inicio);
            $stmt->bindValue("p05", $data_fim);
            $stmt->bindValue("p06", $observacoes);
            $stmt->bindValue("p07", $id_usuario);
            $stmt->bindValue("p08", $id);
            $stmt->execute();

            if ($data_fim != null) {
                $qry = "UPDATE mapas SET situacao =:p01  WHERE id =:p02";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", '1');
                $stmt->bindValue("p02", $id_mapa);
                $stmt->execute();
            } else {
                $qry = "UPDATE mapas SET situacao =:p01  WHERE id =:p02";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", '0');
                $stmt->bindValue("p02", $id_mapa);
                $stmt->execute();
            }

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
                    'url' => 'api/designacoes/' . $id
                )
            )
        );

        return $response;
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        $id_dirigente = $data['id_dirigente'];
        $id_mapa = $data['id_mapa'];
        $situacao = $data['situacao'];
        $data_inicio = $data['data_inicio'];
        $data_fim = $data['data_fim'];
        $observacoes = $data['observacoes'];
        $id_usuario = $data['id_usuario'];

        if (empty($data_inicio)) {
            $data_inicio = null;
        }

        if (empty($data_fim)) {
            $data_fim = null;
        }

        try {
            $qry = "INSERT INTO designacao(
                    id_dirigente, 
                    id_mapa,
                    situacao,
                    data_inicio,                    
                    data_fim,                    
                    observacoes,
                    cadastro,
                    hora,
                    id_usuario)VALUES(
                        :p01, 
                        :p02,
                        :p03,
                        :p04,
                        :p05,
                        :p06,
                        :p07,
                        :p08,
                        :p09)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $id_dirigente);
            $stmt->bindValue("p02", $id_mapa);
            $stmt->bindValue("p03", $situacao);
            $stmt->bindValue("p04", $data_inicio);
            $stmt->bindValue("p05", $data_fim);
            $stmt->bindValue("p06", $observacoes);
            $stmt->bindValue("p07", $dataAtual);
            $stmt->bindValue("p08", $hora);
            $stmt->bindValue("p09", $id_usuario);
            $stmt->execute();

            if ($data_fim != null) {
                $qry = "UPDATE mapas SET situacao =:p01  WHERE id =:p08";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", '1');
                $stmt->bindValue("p02", $id_mapa);
                $stmt->execute();
            } else {
                $qry = "UPDATE mapas SET situacao =:p01  WHERE id =:p08";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", '0');
                $stmt->bindValue("p02", $id_mapa);
                $stmt->execute();
            }

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
                    'url' => 'api/designacoes'
                )
            )
        );

        return $response;
    }

    function qtDesignacao($id_dirigente)
    {
        $data = [];

        try {
            $qry = "SELECT id FROM designacao WHERE id_dirigente = '$id_dirigente'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "totalContratosAbertos" => $count <= 0 ? 0 : $count,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/designacoes/totdesignacoes",
                    ]
                );
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "result" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "result" => $data,
        ];
    }
}