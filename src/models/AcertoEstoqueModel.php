<?php

namespace src\models;

use \core\Model;

class AcertoEstoqueModel extends Model
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
            $qry = "SELECT ae.*, p.descricao, g.descricao AS nomegrupo, p.ean, u.nome
                FROM acertoestoque ae LEFT JOIN Produto p ON (ae.idproduto = p.id)
                LEFT JOIN Grupo g ON (p.IdGrupo = g.Id)
                LEFT JOIN usuario u ON (ae.id_usuario = u.idusuario) 
                ORDER BY ae.Id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "tipo" => $row['tipo'],
                    "motivo" => $row['motivo'],
                    "descricao" => $row['descricao'],
                    "ean" => $row['ean'],
                    "nome" => $row['nome'],
                    "nomegrupo" => $row['nomegrupo'],
                    "saldoatual" => $row['saldoatual'],
                    "qtdacerto" => $row['qtdacerto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/acertoestoque/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "acertosestoque" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "acertosestoque" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT ae.*, p.descricao, g.descricao AS nomegrupo, p.ean, u.nome
                FROM acertoestoque ae LEFT JOIN Produto p ON (ae.idproduto = p.id)
                LEFT JOIN Grupo g ON (p.IdGrupo = g.Id)
                LEFT JOIN usuario u ON (ae.id_usuario = u.idusuario) 
                WHERE p.descricao like '%" . $texto . "%' 
                ORDER BY ae.Id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "tipo" => $row['tipo'],
                    "motivo" => $row['motivo'],
                    "descricao" => $row['descricao'],
                    "ean" => $row['ean'],
                    "nome" => $row['nome'],
                    "nomegrupo" => $row['nomegrupo'],
                    "saldoatual" => $row['saldoatual'],
                    "qtdacerto" => $row['qtdacerto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/acertoestoque/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "acertosestoque" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "acertosestoque" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM acertoestoque WHERE id = '$id'";
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
                'url' => 'api/acertoestoque',
                'body' => array(
                    'type' => 'DELETE',
                    'descricao' => 'String',
                )
            )
        );

        return $response;
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $cadastro = $data['cadastro'];
        $id_produto = $data['id_produto'];
        $saldoatual = $data['saldoatual'];
        $qtdeacerto = $data['qtdeacerto'];
        $tipo = $data['tipo'];
        $motivo = $data['motivo'];
        $id_usuario = $data['id_usuario'];
        $valor_custo_acerto = $data['valor_custo_acerto'];
        $valor_venda_acerto = $data['valor_venda_acerto'];

        try {
            $qry = "INSERT INTO acertoestoque(
                    cadastro, 
                    idproduto,
                    saldoatual,
                    qtdacerto,
                    tipo,
                    motivo,
                    id_usuario,
                    valor_custo_acerto,
                    valor_venda_acerto
                    )VALUES(
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
            $stmt->bindValue("p01", $cadastro);
            $stmt->bindValue("p02", $id_produto);
            $stmt->bindValue("p03", $saldoatual);
            $stmt->bindValue("p04", $qtdeacerto);
            $stmt->bindValue("p05", $tipo);
            $stmt->bindValue("p06", $motivo);
            $stmt->bindValue("p07", $id_usuario);
            $stmt->bindValue("p08", $valor_custo_acerto);
            $stmt->bindValue("p09", $valor_venda_acerto);
            $stmt->execute();

            $stmt = $this->conexao->prepare("SELECT id, estoqueatual 
                FROM produto WHERE id = '$id_produto'");
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $vIdItem = $row['id'];
                $vQtItem = floatval($row['estoqueatual']);

                if ($tipo == "SAIDA") {
                    $vSaldoFinal = floatval($vQtItem) - floatval($qtdeacerto);
                } else {
                    $vSaldoFinal = floatval($vQtItem) + floatval($qtdeacerto);
                }

                $tabela = $this->conexao->prepare("UPDATE produto SET estoqueatual = '$vSaldoFinal' 
                    WHERE id = '$vIdItem'");
                $tabela->execute();
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
                    'url' => 'api/bairro'
                )
            )
        );

        return $response;
    }
}
