<?php

namespace src\models;

use \core\Model;

function getPrimeiroDiaMes()
{
    // Obtém o ano e mês atuais
    $anoAtual = date("Y");
    $mesAtual = date("m");

    // Define a data para o primeiro dia do mês atual
    $primeiroDiaDoMes = "{$anoAtual}-{$mesAtual}-01";

    return $primeiroDiaDoMes;
}

function generateRandomString($length = 15)
{
    // Define os caracteres que podem ser utilizados na string
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';

    // Gera a string aleatória
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }

    return $randomString;
}

class VendaModel extends Model
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
            $qry = "SELECT * FROM unidade ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => $row['descricao'],
                    "sigla" => $row['sigla'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/unidades/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "unidades" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "unidades" => $data,
        ];
    }

    public function processaEstoque($idProduto, $vQuantidade, $vTipo)
    {
        $stmt = $this->conexao->prepare("SELECT id, estoqueatual 
            FROM produto WHERE id = '$idProduto'");
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $vIdItem = $row['id'];
            $vQtItem = floatval($row['estoqueatual']);

            if ($vTipo == "S") {
                $vSaldoFinal = floatval($vQtItem) - floatval($vQuantidade);
            } else {
                $vSaldoFinal = floatval($vQtItem) + floatval($vQuantidade);
            }

            $tabela = $this->conexao->prepare("UPDATE produto SET estoqueatual = '$vSaldoFinal' 
                WHERE id = '$vIdItem'");
            $tabela->execute();
        }
    }

    function deletar($id, $tipo)
    {
        $response = "";
        http_response_code(202);

        try {
            if ($tipo == "O") {
                $qry = "DELETE FROM venda WHERE id = '$id'";
                $stmt = $this->conexao->prepare($qry);
                $stmt->execute();

                $qry = "DELETE FROM vendaitens WHERE idvenda = '$id'";
                $stmt = $this->conexao->prepare($qry);
                $stmt->execute();
            } else {
                $qry = "DELETE FROM venda WHERE id = '$id'";
                $stmt = $this->conexao->prepare($qry);
                $stmt->execute();

                $qry = "DELETE FROM vendaitens WHERE idvenda = '$id'";
                $stmt = $this->conexao->prepare($qry);
                $stmt->execute();

                $qry = "DELETE FROM id_origem WHERE idvenda = '$id'";
                $stmt = $this->conexao->prepare($qry);
                $stmt->execute();
            }
        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro removido com sucesso',
            'request' => array(
                'description' => 'Deleta um registro',
                'url' => 'api/vendas',
                'body' => array(
                    'type' => 'DELETE',
                    'descricao' => 'String',
                )
            )
        );

        return $response;
    }

    function pesquisaVendasTipo($texto, $tipo)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.idcliente, v.data, v.hora, 
                v.id_filial, c.nomefantasia, v.total, v.subtotal, v.desconto, v.tipo, v.analise,
                CASE v.idcliente WHEN '0' THEN 'CONSUMIDOR' WHEN '' THEN 'CONSUMIDOR'
                ELSE c.nomefantasia END AS nome_cliente,
                CASE v.nota WHEN '0' THEN 'NÃO EMITIDA' WHEN '1' THEN 'EMITIDA'
                ELSE 'CANCELADA' END AS status_nfe, 
                CASE v.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA'
                ELSE 'CANCELADA' END AS situacao FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                WHERE c.nomefantasia LIKE '%$texto%' 
                AND v.tipo = '$tipo' 
                ORDER BY v.id DESC LIMIT 30";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "id_cliente" => $row['idcliente'],
                    "data" => $row['data'],
                    "nome_cliente" => $row['nome_cliente'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "id_filial" => $row['id_filial'],
                    "status_nfe" => $row['status_nfe'],
                    "situacao" => $row['situacao'],
                    "tipo" => $row['tipo'],
                    "analise" => $row['analise'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function pesquisaVendasVendedorTipo($texto, $idvendedor, $tipo)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.idcliente, v.data, v.hora, 
                v.id_filial, c.nomefantasia, v.total, v.subtotal, v.desconto, v.tipo, v.analise,
                CASE v.idcliente WHEN '0' THEN 'CONSUMIDOR' WHEN '' THEN 'CONSUMIDOR'
                ELSE c.nomefantasia END AS nome_cliente,
                CASE v.nota WHEN '0' THEN 'NÃO EMITIDA' WHEN '1' THEN 'EMITIDA'
                ELSE 'CANCELADA' END AS status_nfe, 
                CASE v.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA'
                ELSE 'CANCELADA' END AS situacao FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                WHERE c.nomefantasia LIKE '%$texto%' 
                AND v.idatendente = '$idvendedor' AND v.tipo = '$tipo' 
                ORDER BY v.id DESC LIMIT 30";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "id_cliente" => $row['idcliente'],
                    "data" => $row['data'],
                    "nome_cliente" => $row['nome_cliente'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "id_filial" => $row['id_filial'],
                    "status_nfe" => $row['status_nfe'],
                    "situacao" => $row['situacao'],
                    "tipo" => $row['tipo'],
                    "analise" => $row['analise'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function pesquisaVendasClienteTipo($texto, $idcliente, $tipo)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.idcliente, v.data, v.hora, 
                v.id_filial, c.nomefantasia, v.total, v.subtotal, v.desconto, v.tipo, v.analise,
                CASE v.idcliente WHEN '0' THEN 'CONSUMIDOR' WHEN '' THEN 'CONSUMIDOR'
                ELSE c.nomefantasia END AS nome_cliente,
                CASE v.nota WHEN '0' THEN 'NÃO EMITIDA' WHEN '1' THEN 'EMITIDA'
                ELSE 'CANCELADA' END AS status_nfe, 
                CASE v.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA'
                ELSE 'CANCELADA' END AS situacao FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                WHERE c.nomefantasia LIKE '%$texto%' 
                AND v.idcliente = '$idcliente' AND v.tipo = '$tipo' 
                ORDER BY v.id DESC LIMIT 30";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "id_cliente" => $row['idcliente'],
                    "data" => $row['data'],
                    "nome_cliente" => $row['nome_cliente'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "id_filial" => $row['id_filial'],
                    "status_nfe" => $row['status_nfe'],
                    "situacao" => $row['situacao'],
                    "tipo" => $row['tipo'],
                    "analise" => $row['analise'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function vendaProdutos($idvenda)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT vi.id, vi.idproduto, vi.quantidade, p.ean,
                vi.vlunitario AS preco, vi.desconto, vi.total, p.descricao AS descricao_item,
                pc.cfop, p.idncm, pic.csosn_cst, p.cst, un.sigla
                FROM vendaitens vi LEFT JOIN produto p ON (vi.idproduto = p.id)
                LEFT JOIN produto_cfop pc ON (vi.idproduto = pc.idproduto)
                LEFT JOIN produto_icms pic ON (vi.idproduto = pic.idproduto)
                LEFT JOIN unidade un ON (p.unidsaida = un.id)
                WHERE vi.idvenda = '$idvenda'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "id_produto" => $row['idproduto'],
                    "ean" => $row['ean'],
                    "quantidade" => floatval($row['quantidade']),
                    "preco" => $row['preco'],
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "descricao_item" => removeCaracterEspecial($row['descricao_item']),
                    "cfop" => $row['cfop'],
                    "idncm" => $row['idncm'],
                    "csosn_cst" => $row['csosn_cst'],
                    "cst" => $row['cst'],
                    "sigla" => $row['sigla'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/produtos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "produtos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "produtos" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.idcliente, v.data, v.hora, 
                v.id_filial, c.razaosocial, v.total, v.observacoes, v.subtotal, v.desconto, 
                v.tipo, v.analise, v.resumo_pagto,
                CASE v.idcliente WHEN '0' THEN 'CONSUMIDOR' WHEN '' THEN 'CONSUMIDOR'
                ELSE c.razaosocial END AS nome_cliente,
                CASE v.nota WHEN '0' THEN 'NÃO EMITIDA' WHEN '1' THEN 'EMITIDA'
                ELSE 'CANCELADA' END AS status_nfe,
                CASE v.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA'
                ELSE 'CANCELADA' END AS situacao, f.nome as vendedor FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                LEFT JOIN funcionario f ON (v.idatendente = f.id)
                WHERE v.id = '$id' ORDER BY v.id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "id_cliente" => $row['idcliente'],
                        "data" => $row['data'],
                        "nome_cliente" => $row['nome_cliente'],
                        "subtotal" => floatval($row['subtotal']),
                        "desconto" => floatval($row['desconto']),
                        "total" => floatval($row['total']),
                        "id_filial" => $row['id_filial'],
                        "status_nfe" => $row['status_nfe'],
                        "situacao" => $row['situacao'],
                        "tipo" => $row['tipo'],
                        "analise" => $row['analise'],
                        "usuario" => $row['vendedor'],
                        "resumo_pagto" => $row['resumo_pagto'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/vendas/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "venda" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "venda" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "venda" => $data,
        ];
    }

    function vendasClienteTipo($idcliente, $tipo)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.idcliente, v.data, v.hora, 
                v.id_filial, c.nomefantasia, v.total, v.subtotal, v.desconto, v.tipo, v.analise,
                CASE v.idcliente WHEN '0' THEN 'CONSUMIDOR' WHEN '' THEN 'CONSUMIDOR'
                ELSE c.nomefantasia END AS nome_cliente,
                CASE v.nota WHEN '0' THEN 'NÃO EMITIDA' WHEN '1' THEN 'EMITIDA'
                ELSE 'CANCELADA' END AS status_nfe, 
                CASE v.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA'
                ELSE 'CANCELADA' END AS situacao FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                WHERE v.idcliente = '$idcliente' AND v.tipo = '$tipo' 
                ORDER BY v.id DESC LIMIT 30";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "id_cliente" => $row['idcliente'],
                    "data" => $row['data'],
                    "nome_cliente" => $row['nome_cliente'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "id_filial" => $row['id_filial'],
                    "status_nfe" => $row['status_nfe'],
                    "situacao" => $row['situacao'],
                    "tipo" => $row['tipo'],
                    "analise" => $row['analise'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function vendasVendedorTipo($idvendedor, $tipo)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.idcliente, v.data, v.hora, 
                v.id_filial, c.nomefantasia, v.total, v.subtotal, v.desconto, v.tipo, v.analise,
                CASE v.idcliente WHEN '0' THEN 'CONSUMIDOR' WHEN '' THEN 'CONSUMIDOR'
                ELSE c.nomefantasia END AS nome_cliente,
                CASE v.nota WHEN '0' THEN 'NÃO EMITIDA' WHEN '1' THEN 'EMITIDA'
                ELSE 'CANCELADA' END AS status_nfe, 
                CASE v.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA'
                ELSE 'CANCELADA' END AS situacao FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                WHERE v.idatendente = '$idvendedor' AND v.tipo = '$tipo' 
                ORDER BY v.id DESC LIMIT 30";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "id_cliente" => $row['idcliente'],
                    "data" => $row['data'],
                    "nome_cliente" => $row['nome_cliente'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "id_filial" => $row['id_filial'],
                    "status_nfe" => $row['status_nfe'],
                    "situacao" => $row['situacao'],
                    "tipo" => $row['tipo'],
                    "analise" => $row['analise'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function vendasTipo($tipo)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.idcliente, v.data, v.hora, v.id_usuario, u.nome AS usuario,
                v.id_filial, c.nomefantasia, v.total, v.subtotal, v.desconto, v.tipo, v.analise,
                CASE v.idcliente WHEN '0' THEN 'CONSUMIDOR' WHEN '' THEN 'CONSUMIDOR'
                ELSE c.nomefantasia END AS nome_cliente,
                CASE v.nota WHEN '0' THEN 'NÃO EMITIDA' WHEN '1' THEN 'EMITIDA'
                ELSE 'CANCELADA' END AS status_nfe, 
                CASE v.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA'
                ELSE 'CANCELADA' END AS situacao FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                LEFT JOIN usuario u ON (v.id_usuario = u.idusuario) 
                WHERE v.tipo = '$tipo' 
                ORDER BY v.id DESC LIMIT 30";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "id_cliente" => $row['idcliente'],
                    "data" => $row['data'],
                    "nome_cliente" => $row['nome_cliente'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "id_filial" => $row['id_filial'],
                    "status_nfe" => $row['status_nfe'],
                    "situacao" => $row['situacao'],
                    "tipo" => $row['tipo'],
                    "analise" => $row['analise'],
                    "data_venda" => date('d/m/Y', strtotime($row['data'])),
                    "usuario" => $row['usuario'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function vendasDelivery($id_vendedor)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.idcliente, v.data, v.hora, v.id_usuario, u.nome AS usuario,
                v.id_filial, c.nomefantasia, v.total, v.subtotal, v.desconto, v.tipo, v.analise,
                CASE v.idcliente WHEN '0' THEN 'CONSUMIDOR' WHEN '' THEN 'CONSUMIDOR'
                ELSE c.nomefantasia END AS nome_cliente,
                CASE v.nota WHEN '0' THEN 'NÃO EMITIDA' WHEN '1' THEN 'EMITIDA'
                ELSE 'CANCELADA' END AS status_nfe, v.entrega_realizada,
                CASE v.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA'
                ELSE 'CANCELADA' END AS situacao, v.observacoes FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                LEFT JOIN usuario u ON (v.id_usuario = u.idusuario) 
                WHERE v.tipo = 'E' AND v.idatendente = '$id_vendedor' 
                AND v.situacao = 'A' ORDER BY v.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "id_cliente" => $row['idcliente'],
                    "data" => $row['data'],
                    "nome_cliente" => $row['nome_cliente'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "id_filial" => $row['id_filial'],
                    "status_nfe" => $row['status_nfe'],
                    "situacao" => $row['situacao'],
                    "tipo" => $row['tipo'],
                    "analise" => $row['analise'],
                    "data_venda" => date('d/m/Y', strtotime($row['data'])),
                    "usuario" => $row['usuario'],
                    "observacoes" => $row['observacoes'],
                    "entrega_realizada" => $row['entrega_realizada'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function vendasMes()
    {
        $data = [];
        http_response_code(200);

        $vPrimeiroDiaMes = getPrimeiroDiaMes();
        $dataAtual = date("Y-m-d");

        try {
            $qry = "SELECT Round(sum(v.total), 2) as totvenda FROM venda v
                WHERE v.data >= '$vPrimeiroDiaMes'
                AND v.data <= '$dataAtual' GROUP by Month(v.data) LIMIT 1";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "total" => $row['totvenda'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/mes",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function totalVendas()
    {
        $data = [];
        http_response_code(200);

        $vPrimeiroDiaMes = getPrimeiroDiaMes();
        $dataAtual = date("Y-m-d");

        try {
            $qry = "SELECT COUNT(*) AS vendas FROM venda 
                WHERE data >= '$vPrimeiroDiaMes'
                AND data <= '$dataAtual";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "total" => $row['vendas'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        $id_cliente = $data['id_cliente'];
        $data_venda = $data['data_venda'];
        $subtotal = $data['total'];
        $desconto = $data['desconto'];
        $total = $data['total'];
        $troco = $data['troco'];
        $vlpagto = $data['vlpagto'];
        $nota = $data['nota'];
        $nome_cliente = $data['nome_cliente'];
        $id_usuario = $data['id_usuario'];
        $listaItens = $data['listaItens'];
        $listaFormasPagtos = $data['listaFormasPagtos'];
        $observacoes = $data['observacoes'];
        $tipo = $data['tipo'];
        $situacao = $data['situacao'];
        $idatendente = $data['idatendente'];
        $id_caixa = $data['id_caixa'];
        $id_movimento = $data['id_movimento'];
        $qtde_produtos = $data['qtde_produtos'];

        $nome_cliente = "MARCEL";
        $vUltimoId = 0;
        $idEmpresa = 1;
        $vAnalise = 'N';

        if ($tipo == "A") {
            $tipo = "O";
            $vAnalise = "S";
            $situacao = "A";
        }

        if ($tipo == "T") {
            $subtotal = 0;
            $total = 0;
            $vlpagto = 0;
            $troco = 0;
        }

        $numeroAleatorio = generateRandomString(15);

        try {
            $qry = "INSERT INTO venda (
                idcliente, 
                data, 
                hora, 
                subtotal, 
                desconto, 
                total,                                                                        
                tipo, 
                situacao, 
                idatendente, 
                id_filial, 
                id_usuario,
                idcaixa, 
                id_movimento, 
                observacoes, 
                origem, 
                pedido_web,
                vlpagto, 
                nota, 
                resumo_pagto, 
                data_vencimento, 
                total_custo,
                total_lucro, 
                hora_fechamento, 
                aliqicms, 
                vlbcicms, 
                valoricms,
                aliqipi, 
                vlipi, 
                vlfrete, 
                vlseguro, 
                vloutras, 
                desctotitens,
                troco, 
                aliqicmsst, 
                vlbcicmsst, 
                vlicmsst, 
                idempresa, 
                hash, 
                analise,
                pagtoparcial) 
                VALUES (
                    :p01, 
                    :p02, 
                    :p03, 
                    :p04, 
                    :p05, 
                    :p06, 
                    :p07, 
                    :p08, 
                    :p09, 
                    :p10, 
                    :p11, 
                    :p12, 
                    :p13, 
                    :p14, 
                    :p15, 
                    :p16, 
                    :p17, 
                    :p18, 
                    :p19, 
                    :p20, 
                    :p21, 
                    :p22, 
                    :p23, 
                    :p24, 
                    :p25, 
                    :p26, 
                    :p27, 
                    :p28, 
                    :p29, 
                    :p30, 
                    :p31, 
                    :p32, 
                    :p33, 
                    :p34, 
                    :p35, 
                    :p36, 
                    :p37, 
                    :p38, 
                    :p39,
                    :p40)";
            $stmt = $this->conexao->prepare($qry);

            $stmt->bindValue("p01", $id_cliente);
            $stmt->bindValue("p02", $dataAtual);
            $stmt->bindValue("p03", $hora);
            $stmt->bindValue("p04", $subtotal);
            $stmt->bindValue("p05", $desconto);
            $stmt->bindValue("p06", $total);
            $stmt->bindValue("p07", $tipo);
            $stmt->bindValue("p08", $situacao);
            $stmt->bindValue("p09", $idatendente);
            $stmt->bindValue("p10", '999');
            $stmt->bindValue("p11", $id_usuario);
            $stmt->bindValue("p12", $id_caixa);
            $stmt->bindValue("p13", $id_movimento);
            $stmt->bindValue("p14", $observacoes);
            $stmt->bindValue("p15", 'W');
            $stmt->bindValue("p16", 'S');
            $stmt->bindValue("p17", $vlpagto);
            $stmt->bindValue("p18", $nota);
            $stmt->bindValue("p19", 'WEB');
            $stmt->bindValue("p20", $dataAtual);
            $stmt->bindValue("p21", 0);
            $stmt->bindValue("p22", 0);
            $stmt->bindValue("p23", $hora);
            $stmt->bindValue("p24", 0);
            $stmt->bindValue("p25", 0);
            $stmt->bindValue("p26", 0);
            $stmt->bindValue("p27", 0);
            $stmt->bindValue("p28", 0);
            $stmt->bindValue("p29", 0);
            $stmt->bindValue("p30", 0);
            $stmt->bindValue("p31", 0);
            $stmt->bindValue("p32", 0);
            $stmt->bindValue("p33", $troco);
            $stmt->bindValue("p34", 0);
            $stmt->bindValue("p35", 0);
            $stmt->bindValue("p36", 0);
            $stmt->bindValue("p37", $idEmpresa);
            $stmt->bindValue("p38", $numeroAleatorio);
            $stmt->bindValue("p39", $vAnalise);
            $stmt->bindValue("p40", 0);
            $stmt->execute();

            $vUltimoId = "0";
            $ultimoIDSQL = "SELECT id FROM venda ORDER BY id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($ultimoIDSQL);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $vUltimoId = $row['id'];
            }

            foreach ($listaItens as $chave => $valor) {
                //disponível variáveis $chave e $valor
                $pCusto = 0;
                $vTotalCusto = 0;

                $vCodigoItem = $valor['codigo'];
                $vQtdeItem = $valor['quantidade'];

                //Verifica o Custo ok
                $queryProduto = "SELECT id, precovenda, precocusto 
                    FROM produto WHERE id = '$vCodigoItem' LIMIT 1";
                $stmt = $this->conexao->prepare($queryProduto);
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($results as $row) {
                    $pCusto = $row['precocusto'];
                }
                $vTotalCusto = floatval($vQtdeItem) * floatval($pCusto);
                //Verifica o Custo    

                if ($tipo == "T") {
                    $valor['preco'] = 0;
                    $valor['total'] = 0;
                }

                $qry = "INSERT INTO vendaitens (
                    idvenda, 
                    idproduto, 
                    desconto, 
                    vlunitario, 
                    total, 
                    quantidade, 
                    preco_custo,
                    id_atendente, 
                    custo_medio, 
                    observacoes, 
                    descricao_item, 
                    uid, 
                    aliqicms, 
                    vlbcicms, 
                    vlicms, 
                    aliqipi, 
                    vlipi, 
                    vlfrete, 
                    vlseguro, 
                    vloutras, 
                    aliqicmsst, 
                    vlbcicmsst, 
                    vlicmsst, 
                    descvaloritens, 
                    largura, 
                    altura, 
                    producao, 
                    precovenda_cadastro,                 
                    xped)VALUES (
                        :p01, 
                        :p02, 
                        :p03, 
                        :p04, 
                        :p05, 
                        :p06, 
                        :p07, 
                        :p08, 
                        :p09, 
                        :p10, 
                        :p11, 
                        :p12, 
                        :p13, 
                        :p14, 
                        :p15, 
                        :p16, 
                        :p17, 
                        :p18, 
                        :p19, 
                        :p20, 
                        :p21, 
                        :p22, 
                        :p23, 
                        :p24, 
                        :p25, 
                        :p26, 
                        :p27, 
                        :p28, 
                        :p29)";
                $stmt = $this->conexao->prepare($qry);

                $stmt->bindValue("p01", $vUltimoId);
                $stmt->bindValue("p02", $vCodigoItem);
                $stmt->bindValue("p03", 0);
                $stmt->bindValue("p04", $valor['preco']);
                $stmt->bindValue("p05", $valor['total']);
                $stmt->bindValue("p06", $valor['quantidade']);
                $stmt->bindValue("p07", $vTotalCusto);
                $stmt->bindValue("p08", $idatendente);
                $stmt->bindValue("p09", $pCusto);
                $stmt->bindValue("p10", 'WEB');
                $stmt->bindValue("p11", $valor['descricao']);
                $stmt->bindValue("p12", $numeroAleatorio);
                $stmt->bindValue("p13", 0);
                $stmt->bindValue("p14", 0);
                $stmt->bindValue("p15", 0);
                $stmt->bindValue("p16", 0);
                $stmt->bindValue("p17", 0);
                $stmt->bindValue("p18", 0);
                $stmt->bindValue("p19", 0);
                $stmt->bindValue("p20", 0);
                $stmt->bindValue("p21", 0);
                $stmt->bindValue("p22", 0);
                $stmt->bindValue("p23", 0);
                $stmt->bindValue("p24", 0);
                $stmt->bindValue("p25", 0);
                $stmt->bindValue("p26", 0);
                $stmt->bindValue("p27", 'N');
                $stmt->bindValue("p28", $valor['preco']);
                $stmt->bindValue("p29", '');
                $stmt->execute();

                if ($tipo == "V") {
                    $this->processaEstoque($valor['codigo'], $valor['quantidade'], 'S');
                }
            }

            if ($tipo == "V") {
                foreach ($listaFormasPagtos as $chave => $valor) {
                    $vNovoVencimento = $valor['dataVencimento'];
                    $vNovoVencimento = explode("/", $vNovoVencimento);
                    $vNovoVencimento = $vNovoVencimento[2] . "-" . $vNovoVencimento[1] . "-" . $vNovoVencimento[0];

                    //Verifica o parcela
                    $vSituacao = "N";
                    $FormaPagto = $valor['formaPagamento'];
                    if ($FormaPagto === "DINHEIRO") {
                        $vSituacao = "S";
                    }

                    //Verifica o cliente
                    $nome_cliente = "CONSUMIDOR";
                    $id_cond_pagto = 0;
                    $desc_cond_pagto = "";
                    $queryCliente = "SELECT c.id, c.razaosocial, c.nomefantasia, 
                        c.id_condicaopagto, cp.descricao FROM cliente c
                        LEFT JOIN formas_pagamento cp ON (c.id_condicaopagto = cp.id) 
                        WHERE c.id = '$id_cliente' LIMIT 1";
                    $stmt = $this->conexao->prepare($queryCliente);
                    $stmt->execute();
                    $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        $nome_cliente = $row['nomefantasia'];
                        $id_cond_pagto = $row['id_condicaopagto'];
                        $desc_cond_pagto = $row['descricao'];
                    }
                    //Verifica o cliente     

                    //Verifica o forma de pagamento
                    $id_pagto = "0";
                    $queryForma = "SELECT id, descricao FROM tipo_pagamento 
                        WHERE descricao = '$FormaPagto' LIMIT 1";
                    $stmt = $this->conexao->prepare($queryForma);
                    $stmt->execute();
                    $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        $id_pagto = $row['id'];
                    }
                    //Verifica o forma de pagamento

                    //Verifica a competência
                    $anoAtual = date("Y");
                    $mesAtual = date("m");

                    $competencia = $mesAtual . '/' . $anoAtual;
                    //Verifica a competência

                    $qry = "INSERT INTO lancamentoscaixa (
                        idusuario, 
                        idcaixa, 
                        idcentrocusto,
                        dataemissao, 
                        tipomovimento, 
                        ndoc, 
                        valor, 
                        cadastro, 
                        observacoes,
                        parcela, 
                        id_origem, 
                        sigla_origem, 
                        hora, 
                        descricao_recebimento,
                        id_plano_contas, 
                        situacao, 
                        id_movimento, 
                        vencimento, 
                        valor_deduzido,
                        lancamento_futuro, 
                        id_pagto, 
                        desc_tipo, 
                        valor_real, 
                        titulo, 
                        id_parcelamento,
                        situacao_os, 
                        id_aluno, 
                        nome_cliente, 
                        data_pagamento, 
                        valor_pago, 
                        tipo_documento,
                        competencia,
                        id_cond_pagto,
                        desc_cond_pagto)
                    VALUES (
                        :p01, 
                        :p02, 
                        :p03, 
                        :p04, 
                        :p05, 
                        :p06, 
                        :p07, 
                        :p08, 
                        :p09, 
                        :p10, 
                        :p11, 
                        :p12, 
                        :p13, 
                        :p14, 
                        :p15, 
                        :p16, 
                        :p17, 
                        :p18, 
                        :p19, 
                        :p20, 
                        :p21, 
                        :p22, 
                        :p23, 
                        :p24, 
                        :p25, 
                        :p26, 
                        :p27, 
                        :p28, 
                        :p29, 
                        :p30, 
                        :p31, 
                        :p32,
                        :p33,
                        :p34)";
                    $stmt = $this->conexao->prepare($qry);

                    $stmt->bindValue("p01", 2);
                    $stmt->bindValue("p02", 2);
                    $stmt->bindValue("p03", 1);
                    $stmt->bindValue("p04", $dataAtual);
                    $stmt->bindValue("p05", 'E');
                    $stmt->bindValue("p06", $vUltimoId);
                    $stmt->bindValue("p07", $valor['vlrParcela']);
                    $stmt->bindValue("p08", $dataAtual);
                    $stmt->bindValue("p09", 'Movimento de venda (' . $FormaPagto . ')');
                    $stmt->bindValue("p10", 1);
                    $stmt->bindValue("p11", $vUltimoId);
                    $stmt->bindValue("p12", 'V');
                    $stmt->bindValue("p13", $hora);
                    $stmt->bindValue("p14", 'Movimento de venda (' . $FormaPagto . ')');
                    $stmt->bindValue("p15", 1);
                    $stmt->bindValue("p16", $vSituacao);
                    $stmt->bindValue("p17", 2);
                    $stmt->bindValue("p18", $vNovoVencimento);
                    $stmt->bindValue("p19", $valor['vlrParcela']);
                    $stmt->bindValue("p20", 'N');
                    $stmt->bindValue("p21", $id_pagto);
                    $stmt->bindValue("p22", $FormaPagto);
                    $stmt->bindValue("p23", $valor['vlrParcela']);
                    $stmt->bindValue("p24", $vUltimoId);
                    $stmt->bindValue("p25", 1);
                    $stmt->bindValue("p26", 'N');
                    $stmt->bindValue("p27", $id_cliente);
                    $stmt->bindValue("p28", $nome_cliente);
                    $stmt->bindValue("p29", $dataAtual);
                    $stmt->bindValue("p30", $valor['vlrParcela']);
                    $stmt->bindValue("p31", 'DUPLICATA');
                    $stmt->bindValue("p32", $competencia);
                    $stmt->bindValue("p33", $id_cond_pagto);
                    $stmt->bindValue("p34", $desc_cond_pagto);
                    $stmt->execute();
                }
            }
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
                    'url' => 'api/venda'
                )
            )
        );

        return $response;
    }

    function inserirPedidoCliente($data)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        try {
            $id_cliente = $data['id_cliente'];
            $data_venda = $data['data_venda'];
            $subtotal = $data['total'];
            $desconto = 0;
            $total = $data['total'];
            $troco = 0;
            $vlpagto = $data['total'];
            $nota = $data['nota'];
            $nome_cliente = $data['nome_cliente'];
            $id_usuario = $data['id_usuario'];
            $listaItens = $data['produtos'];
            $observacoes = $data['observacoes'];
            $tipo = $data['tipo'];
            $situacao = $data['situacao'];
            $idatendente = $data['idatendente'];
            $id_caixa = $data['id_caixa'];
            $id_movimento = $data['id_movimento'];
            $condicoes_pagtos = $data['condicoes_pagtos'];
            $qtde_vendida = $data['qtde_vendida'];

            $observacoes = ($observacoes . " \nCondição de pagto: " . $condicoes_pagtos);
            $resumo_pagto = "Condição de pagto: " . $condicoes_pagtos;

            $vUltimoId = 0;
            $idEmpresa = 1;

            $numeroAleatorio = generateRandomString(15);

            $qry = "INSERT INTO venda (
                idcliente, 
                data, 
                hora, 
                subtotal, 
                desconto, 
                total,                                                                        
                tipo, 
                situacao, 
                idatendente, 
                id_filial, 
                id_usuario,
                idcaixa, 
                id_movimento, 
                observacoes, 
                origem, 
                pedido_web,
                vlpagto, 
                nota, 
                resumo_pagto, 
                data_vencimento, 
                total_custo,
                total_lucro, 
                hora_fechamento, 
                aliqicms, 
                vlbcicms, 
                valoricms,
                aliqipi, 
                vlipi, 
                vlfrete, 
                vlseguro, 
                vloutras, 
                desctotitens,
                troco, 
                aliqicmsst, 
                vlbcicmsst, 
                vlicmsst, 
                idempresa, 
                hash, 
                analise,
                qtde_vendida,
                pagtoparcial) 
                VALUES (
                    :p01, 
                    :p02, 
                    :p03, 
                    :p04, 
                    :p05, 
                    :p06, 
                    :p07, 
                    :p08, 
                    :p09, 
                    :p10, 
                    :p11, 
                    :p12, 
                    :p13, 
                    :p14, 
                    :p15, 
                    :p16, 
                    :p17, 
                    :p18, 
                    :p19, 
                    :p20, 
                    :p21, 
                    :p22, 
                    :p23, 
                    :p24, 
                    :p25, 
                    :p26, 
                    :p27, 
                    :p28, 
                    :p29, 
                    :p30, 
                    :p31, 
                    :p32, 
                    :p33, 
                    :p34, 
                    :p35, 
                    :p36, 
                    :p37, 
                    :p38, 
                    :p39,
                    :p40,
                    :p41)";
            $stmt = $this->conexao->prepare($qry);

            $stmt->bindValue("p01", $id_cliente);
            $stmt->bindValue("p02", $dataAtual);
            $stmt->bindValue("p03", $hora);
            $stmt->bindValue("p04", $subtotal);
            $stmt->bindValue("p05", $desconto);
            $stmt->bindValue("p06", $total);
            $stmt->bindValue("p07", $tipo);
            $stmt->bindValue("p08", $situacao);
            $stmt->bindValue("p09", $idatendente);
            $stmt->bindValue("p10", '999');
            $stmt->bindValue("p11", $id_usuario);
            $stmt->bindValue("p12", $id_caixa);
            $stmt->bindValue("p13", $id_movimento);
            $stmt->bindValue("p14", $observacoes);
            $stmt->bindValue("p15", 'W');
            $stmt->bindValue("p16", 'S');
            $stmt->bindValue("p17", $vlpagto);
            $stmt->bindValue("p18", $nota);
            $stmt->bindValue("p19", $resumo_pagto);
            $stmt->bindValue("p20", $dataAtual);
            $stmt->bindValue("p21", 0);
            $stmt->bindValue("p22", 0);
            $stmt->bindValue("p23", $hora);
            $stmt->bindValue("p24", 0);
            $stmt->bindValue("p25", 0);
            $stmt->bindValue("p26", 0);
            $stmt->bindValue("p27", 0);
            $stmt->bindValue("p28", 0);
            $stmt->bindValue("p29", 0);
            $stmt->bindValue("p30", 0);
            $stmt->bindValue("p31", 0);
            $stmt->bindValue("p32", 0);
            $stmt->bindValue("p33", $troco);
            $stmt->bindValue("p34", 0);
            $stmt->bindValue("p35", 0);
            $stmt->bindValue("p36", 0);
            $stmt->bindValue("p37", $idEmpresa);
            $stmt->bindValue("p38", $numeroAleatorio);
            $stmt->bindValue("p39", 'N');
            $stmt->bindValue("p40", $qtde_vendida);
            $stmt->bindValue("p41", 0);
            $stmt->execute();

            $vUltimoId = "0";
            $ultimoIDSQL = "SELECT id FROM venda ORDER BY id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($ultimoIDSQL);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $vUltimoId = $row['id'];
            }

            foreach ($listaItens as $chave => $valor) {
                //disponível variáveis $chave e $valor
                $pCusto = 0;
                $vTotalCusto = 0;
                $vTotalItem = floatval($valor['estoqueatual'] * $valor['precovenda']);

                $vCodigoItem = $valor['id'];
                $vQtdeItem = $valor['estoqueatual'];

                //Verifica o Custo ok
                $queryProduto = "SELECT id, precovenda, precocusto 
                    FROM produto WHERE id = '$vCodigoItem' LIMIT 1";
                $stmt = $this->conexao->prepare($queryProduto);
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($results as $row) {
                    $pCusto = $row['precocusto'];
                }
                $vTotalCusto = floatval($vQtdeItem) * floatval($pCusto);
                //Verifica o Custo             

                $qry = "INSERT INTO vendaitens (
                    idvenda, 
                    idproduto, 
                    desconto, 
                    vlunitario, 
                    total, 
                    quantidade, 
                    preco_custo,
                    id_atendente, 
                    custo_medio, 
                    observacoes, 
                    descricao_item, 
                    uid, 
                    aliqicms, 
                    vlbcicms, 
                    vlicms, 
                    aliqipi, 
                    vlipi, 
                    vlfrete, 
                    vlseguro, 
                    vloutras, 
                    aliqicmsst, 
                    vlbcicmsst, 
                    vlicmsst, 
                    descvaloritens, 
                    largura, 
                    altura, 
                    producao, 
                    precovenda_cadastro,                 
                    xped)VALUES (
                        :p01, 
                        :p02, 
                        :p03, 
                        :p04, 
                        :p05, 
                        :p06, 
                        :p07, 
                        :p08, 
                        :p09, 
                        :p10, 
                        :p11, 
                        :p12, 
                        :p13, 
                        :p14, 
                        :p15, 
                        :p16, 
                        :p17, 
                        :p18, 
                        :p19, 
                        :p20, 
                        :p21, 
                        :p22, 
                        :p23, 
                        :p24, 
                        :p25, 
                        :p26, 
                        :p27, 
                        :p28, 
                        :p29)";
                $stmt = $this->conexao->prepare($qry);

                $stmt->bindValue("p01", $vUltimoId);
                $stmt->bindValue("p02", $vCodigoItem);
                $stmt->bindValue("p03", 0);
                $stmt->bindValue("p04", $valor['precovenda']);
                $stmt->bindValue("p05", $vTotalItem);
                $stmt->bindValue("p06", $valor['estoqueatual']);
                $stmt->bindValue("p07", $vTotalCusto);
                $stmt->bindValue("p08", $idatendente);
                $stmt->bindValue("p09", $pCusto);
                $stmt->bindValue("p10", 'WEB');
                $stmt->bindValue("p11", $valor['descricao']);
                $stmt->bindValue("p12", $numeroAleatorio);
                $stmt->bindValue("p13", 0);
                $stmt->bindValue("p14", 0);
                $stmt->bindValue("p15", 0);
                $stmt->bindValue("p16", 0);
                $stmt->bindValue("p17", 0);
                $stmt->bindValue("p18", 0);
                $stmt->bindValue("p19", 0);
                $stmt->bindValue("p20", 0);
                $stmt->bindValue("p21", 0);
                $stmt->bindValue("p22", 0);
                $stmt->bindValue("p23", 0);
                $stmt->bindValue("p24", 0);
                $stmt->bindValue("p25", 0);
                $stmt->bindValue("p26", 0);
                $stmt->bindValue("p27", 'N');
                $stmt->bindValue("p28", $valor['precovenda']);
                $stmt->bindValue("p29", '');

                $stmt->execute();

                // if ($tipo == "V") {
                //     $this->processaEstoque($valor['codigo'], $valor['quantidade'], 'S');
                // }
            }
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
                    'url' => 'api/usuario'
                )
            )
        );

        return $response;
    }

    function listarComandasAbertas()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.data, v.subtotal, v.desconto, 
                v.total, v.situacao, v.numeromesa, v.tipo, v.fecha_conta, v.idcaixa, 
                v.id_movimento FROM venda v WHERE v.situacao = 'A' 
                AND v.tipo IN ('M','C','S') AND v.agrupada = 'N'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "data" => $row['data'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "situacao" => $row['situacao'],
                    "numeromesa" => $row['numeromesa'],
                    "fecha_conta" => $row['fecha_conta'],
                    "tipo" => $row['tipo'],
                    "idcaixa" => $row['idcaixa'],
                    "id_movimento" => $row['id_movimento'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/listar/comandas",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "comandas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => count($data),
            "comandas" => $data,
        ];
    }

    function listarRetiradasAbertas()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.data, v.hora, v.subtotal, v.desconto, 
                v.total, v.situacao, v.numeromesa, v.tipo, v.fecha_conta, 
                nome_cliente_sem_cadastro, v.idcaixa, v.id_movimento 
                FROM venda v WHERE v.situacao = 'A' 
                AND balcao_espera = 'S' AND tipo = 'B'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "data" => $row['data'],
                    "hora" => $row['hora'],
                    "nome_cliente" => $row['nome_cliente_sem_cadastro'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "situacao" => $row['situacao'],
                    "numeromesa" => $row['numeromesa'],
                    "fecha_conta" => $row['fecha_conta'],
                    "tipo" => $row['tipo'],
                    "idcaixa" => $row['idcaixa'],
                    "id_movimento" => $row['id_movimento'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/listar/retiradas",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "comandas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => count($data),
            "comandas" => $data,
        ];
    }

    function listarPedidosOnlineAbertos()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.data, v.hora, v.subtotal, v.desconto, 
                v.ponto_referencia, v.total, v.situacao, v.numeromesa, v.tipo, v.fecha_conta,
                v.hash, v.nome_cliente_sem_cadastro, v.resumo_pagto, v.entrega_taxa, v.transferido,
                v.entrega_troco,  v.endereco, v.numero, v.bairro, v.id_bairro, v.referencia 
                FROM venda v WHERE v.transferido = 'N' AND v.tipo <> 'M'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "data" => $row['data'],
                    "hora" => $row['hora'],
                    "nome_cliente" => $row['nome_cliente_sem_cadastro'],
                    "subtotal" => floatval($row['subtotal']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "situacao" => $row['situacao'],
                    "numeromesa" => $row['numeromesa'],
                    "fecha_conta" => $row['fecha_conta'],
                    "tipo" => $row['tipo'],
                    "referencia" => $row['referencia'],
                    "hash" => $row['hash'],
                    "resumo_pagto" => $row['resumo_pagto'],
                    "entrega_taxa" => $row['entrega_taxa'],
                    "entrega_troco" => $row['entrega_troco'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "id_bairro" => $row['id_bairro'],
                    "transferido" => $row['transferido'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/online/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "pedidosonline" => [],
            ];
        }

        return [
            "error" => false,
            "length" => count($data),
            "pedidosonline" => $data,
        ];
    }

    function listarPedidosOnlineItens($idVenda)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT vi.id, vi.idproduto, vi.quantidade, 
                vi.vlunitario, vi.desconto, vi.total, vi.impresso, vi.descricao_item, vi.hash, 
                vi.observacoes, vi.complementos FROM vendaitens vi WHERE vi.hash = '$idVenda'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "idproduto" => $row['idproduto'],
                    "quantidade" => floatval($row['quantidade']),
                    "vlunitario" => floatval($row['vlunitario']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "impresso" => $row['impresso'],
                    "descricao_item" => removeCaracterEspecial($row['descricao_item']),
                    "hash" => $row['hash'],
                    "observacoes" => $row['observacoes'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/online/itens/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "pedidoItem" => [],
            ];
        }

        return [
            "error" => false,
            "length" => count($data),
            "pedidoItem" => $data,
        ];
    }

    function listarComandasItens($idVenda)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT vi.id, vi.idproduto, vi.quantidade, 
                vi.vlunitario, vi.desconto, vi.total, vi.impresso, vi.descricao_item 
                FROM vendaitens vi WHERE vi.idvenda = '$idVenda'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "idproduto" => $row['idproduto'],
                    "quantidade" => floatval($row['quantidade']),
                    "vlunitario" => floatval($row['vlunitario']),
                    "desconto" => floatval($row['desconto']),
                    "total" => floatval($row['total']),
                    "impresso" => $row['impresso'],
                    "descricao_item" => removeCaracterEspecial($row['descricao_item']),
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/comanda/itens/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "comandaItem" => [],
            ];
        }

        return [
            "error" => false,
            "length" => count($data),
            "comandaItem" => $data,
        ];
    }

    function totComandasAbertas()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT v.id, v.numeromesa, v.situacao, tipo FROM venda v 
                WHERE v.situacao = 'A' AND v.tipo IN ('M','C','S') AND v.agrupada = 'N'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "total" => $count,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/totcomandas",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function totPedidoEspera()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT id, numeromesa, situacao, tipo FROM venda 
                WHERE tipo = 'B' AND balcao_espera = 'S' AND situacao = 'A'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "total" => $count,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/vendas/totcomandas",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "vendas" => $data,
        ];
    }

    function inserirApp($data)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        try {
            $idcliente = $data['idcliente'];
            $data = $data['data'];
            $hora = $data['hora'];
            $subtotal = $data['subtotal'];
            $desconto = $data['desconto'];
            $total = $data['total'];
            $troco = $data['troco'];
            $vlpagto = $data['vlpagto'];
            $nota = $data['nota'];
            $nome_cliente = $data['nome_cliente'];
            $emissao = $data['emissao'];
            $id_usuario = $data['id_usuario'];
            $nome_usuario = $data['nome_usuario'];
            $listaItens = $data['listaItens'];
            $listaFormasPagtos = $data['listaFormasPagtos'];
            $observacoes = $data['observacoes'];

            $id_usuario = 1;
            $nome_usuario = "MARCEL";
            $vUltimoId = 0;

            $numeroAleatorio = generateRandomString(15);
            $vHoraAtual = $hora;

            $query = "INSERT INTO venda (
                idcliente, 
                data, 
                hora, 
                subtotal, 
                desconto, 
                total, 
                idatendente, 
                idempresa, 
                vlpagto, 
                nota, 
                idcaixa, 
                nome_cliente_sem_cadastro, 
                resumo_pagto, 
                observacoes, 
                id_movimento, 
                id_usuario, 
                tipo, 
                situacao, 
                troco, 
                transferido, 
                hash,
                pagtoparcial) 
                VALUES (
                    :p01, 
                    :p02, 
                    :p03, 
                    :p04, 
                    :p05, 
                    :p06, 
                    :p07, 
                    :p08, 
                    :p09, 
                    :p10, 
                    :p11, 
                    :p12, 
                    :p13, 
                    :p14, 
                    :p15, 
                    :p16, 
                    :p17, 
                    :p18, 
                    :p19, 
                    :p20, 
                    :p21,
                    :p22)";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue("p01", $idcliente);
            $stmt->bindValue("p02", $dataAtual);
            $stmt->bindValue("p03", $vHoraAtual);
            $stmt->bindValue("p04", $subtotal);
            $stmt->bindValue("p05", $desconto);
            $stmt->bindValue("p06", $total);
            $stmt->bindValue("p07", '1');
            $stmt->bindValue("p08", '1');
            $stmt->bindValue("p09", $vlpagto);
            $stmt->bindValue("p10", $nota);
            $stmt->bindValue("p11", '2');
            $stmt->bindValue("p12", $nome_cliente);
            $stmt->bindValue("p13", 'DINHEIRO');
            $stmt->bindValue("p14", $observacoes);
            $stmt->bindValue("p15", '2');
            $stmt->bindValue("p16", $id_usuario);
            $stmt->bindValue("p17", 'V');
            $stmt->bindValue("p18", 'A');
            $stmt->bindValue("p19", $troco);
            $stmt->bindValue("p20", 'N');
            $stmt->bindValue("p21", $numeroAleatorio);
            $stmt->bindValue("p22", 0);
            $stmt->execute();

            //Último registro
            $vUltimoId = "0";
            $queryUltimoRegistro = "SELECT id FROM venda ORDER BY id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($queryUltimoRegistro);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $vUltimoId = $row['id'];
            }
            //Último registro

            $vContador = 0;
            $vTotalVenda = 0;
            foreach ($listaItens as $item) {
                $vContador = floatval($vContador + 1);
                $vTotalVenda = ($vTotalVenda + $item['total']);

                $query = "INSERT INTO vendaitens (
                        idvenda, 
                        idproduto, 
                        desconto, 
                        vlunitario, 
                        total, 
                        quantidade, 
                        preco_custo,
                        id_atendente, 
                        custo_medio, 
                        observacoes, 
                        descricao_item, 
                        hash)
                        VALUES (
                            :p01, 
                            :p02, 
                            :p03, 
                            :p04, 
                            :p05, 
                            :p06, 
                            :p07, 
                            :p08, 
                            :p09, 
                            :p10, 
                            :p11, 
                            :p12)";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue("p01", $vUltimoId);
                $stmt->bindValue("p02", $item['id_produto']);
                $stmt->bindValue("p03", $item['desconto']);
                $stmt->bindValue("p04", $item['unitario']);
                $stmt->bindValue("p05", $item['total']);
                $stmt->bindValue("p06", $item['quantidade']);
                $stmt->bindValue("p07", $item['unitario']);
                $stmt->bindValue("p08", $id_usuario);
                $stmt->bindValue("p09", $item['unitario']);
                $stmt->bindValue("p10", '');
                $stmt->bindValue("p11", $item['descricao_produto']);
                $stmt->bindValue("p12", $numeroAleatorio);
                $stmt->execute();
            }

            foreach ($listaFormasPagtos as $itemPagto) {
                $vNovoVencimento = $itemPagto['dataVencimento'];
                $vNovoVencimento = explode("/", $vNovoVencimento);
                $vNovoVencimento = $vNovoVencimento[2] . "-" . $vNovoVencimento[1] . "-" . $vNovoVencimento[0];

                $query = "INSERT INTO lancamentoscaixa (idusuario, idcaixa, idcentrocusto,
                        dataemissao, tipomovimento, ndoc, valor, cadastro, observacoes,
                        parcela, id_origem, sigla_origem, hora, descricao_recebimento,
                        id_plano_contas, situacao, id_movimento, vencimento, valor_deduzido,
                        lancamento_futuro, id_pagto, desc_tipo, valor_real, titulo, id_parcelamento,
                        situacao_os, id_aluno, nome_cliente, data_pagamento, valor_pago)
                        VALUES (:p01, :p02, :p03, :p04, :p05, :p06, :p07, :p08, :p09, :p10, 
                        :p11, :p12, :p13, :p14, :p15, :p16, :p17, :p18, :p19, :p20, :p21, 
                            :p22, :p23, :p24, :p25, :p26, :p27, :p28, :p29, :p30)";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue("p01", $id_usuario);
                $stmt->bindValue("p02", 2);
                $stmt->bindValue("p03", 1);
                $stmt->bindValue("p04", $dataAtual);
                $stmt->bindValue("p05", "E");
                $stmt->bindValue("p06", $vUltimoId);
                $stmt->bindValue("p07", $item['valor']);
                $stmt->bindValue("p08", $dataAtual);
                $stmt->bindValue("p09", 'MOV. DE VENDA ( ' . $itemPagto['descricao'] . ' )');
                $stmt->bindValue("p10", 1);
                $stmt->bindValue("p11", $vUltimoId);
                $stmt->bindValue("p12", 'V');
                $stmt->bindValue("p13", $hora);
                $stmt->bindValue("p14", 'MOV. DE VENDA ( ' . $itemPagto['descricao'] . ' )');
                $stmt->bindValue("p15", 1);
                $stmt->bindValue("p16", 'N');
                $stmt->bindValue("p17", 2);
                $stmt->bindValue("p18", $vNovoVencimento);
                $stmt->bindValue("p19", $itemPagto['valor']);
                $stmt->bindValue("p20", 'N');
                $stmt->bindValue("p21", $itemPagto['id']);
                $stmt->bindValue("p22", $itemPagto['descricao']);
                $stmt->bindValue("p23", $itemPagto['valor']);
                $stmt->bindValue("p24", $vUltimoId);
                $stmt->bindValue("p25", 1);
                $stmt->bindValue("p26", 'N');
                $stmt->bindValue("p27", $idcliente);
                $stmt->bindValue("p28", $nome_cliente);
                $stmt->bindValue("p29", $dataAtual);
                $stmt->bindValue("p30", $itemPagto['valor']);
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
                    'url' => 'api/usuario'
                )
            )
        );

        return $response;
    }

    function inserirComanda($data)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        try {
            $idcliente = $data['id_cliente'];
            $data_venda = $data['data_venda'];
            $subtotal = $data['subtotal'];
            $desconto = $data['desconto'];
            $total = $data['total'];
            $troco = $data['troco'];
            $vlpagto = $data['vlpagto'];
            $nota = $data['nota'];
            $nome_cliente = $data['nome_cliente'];
            $id_usuario = $data['id_usuario'];
            $listaItens = $data['produtos'];
            $observacoes = $data['observacoes'];
            $comanda = $data['comanda'];
            $tipo = $data['tipo'];
            $listaFormasPagtos = $data['listaFormasPagtos'];
            $tipo_lancamento = $data['tipo_lancamento'];

            $balcao_espera = "N";

            if ($id_usuario == "") {
                $id_usuario = 1;
            }
            if ($idcliente === '') {
                $idcliente = '0';
            }

            $vUltimoId = 0;
            $vContador = 0;
            $vCupom = 0;

            // //Verifica o caixa
            $caixaId = 2;
            $movimentoId = 2;
            $queryCaixa = "SELECT mc.id, mc.idcaixa, cx.descricao as caixa, cx.caixa_web 
                            FROM movimentocaixa mc LEFT JOIN caixa cx ON (mc.idcaixa = cx.id) 
                            WHERE mc.status = 'A' LIMIT 1";

            $stmt = $this->conexao->prepare($queryCaixa);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $caixaId = $row['idcaixa'];
                $queryCaixa = $row['id'];
            }
            // //Verifica o caixa

            $numeroAleatorio = generateRandomString(15);

            $vPerServico = 0;
            $queryParametro = "SELECT per_servico FROM parametro LIMIT 1";
            $stmt = $this->conexao->prepare($queryParametro);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $vPerServico = $row['per_servico'];
            }

            $vServico = ($total * $vPerServico / 100);

            if ($tipo == "B") {
                $vServico = 0;
                $balcao_espera = "S";
                $nome_cliente = $comanda;
            }

            $queryVenda = "SELECT id, numeromesa, total FROM venda 
                WHERE numeromesa = '$comanda' AND situacao = 'A' AND tipo = 'M' LIMIT 1";
            $stmt = $this->conexao->prepare($queryVenda);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();

            if ($count > 0) {
                foreach ($results as $rowVenda) {
                    $vUltimaVenda = $rowVenda['id'];
                    $vTotalVenda = $rowVenda['total'];

                    foreach ($listaItens as $item) {
                        $vContador = floatval($vContador + 1);
                        $vServicoItem = ($item['total'] * $vPerServico / 100);

                        $vTotalVenda = ($vTotalVenda + $item['total']);

                        $query = "INSERT INTO vendaitens (
                            idvenda, 
                            idproduto, 
                            desconto, 
                            vlunitario, 
                            total, 
                            quantidade, 
                            preco_custo,
                            id_atendente, 
                            custo_medio, 
                            observacoes, 
                            descricao_item,
                            servico, 
                            origem, 
                            contador, 
                            fracionada, 
                            id_referencia)
                             VALUES (
                                :p01, 
                                :p02, 
                                :p03,
                                :p04, 
                                :p05, 
                                :p06, 
                                :p07, 
                                :p08, 
                                :p09, 
                                :p10, 
                                :p11, 
                                :p12, 
                                :p13, 
                                :p14, 
                                :p15, 
                                :p16)";
                        $stmt = $this->conexao->prepare($query);
                        $stmt->bindValue("p01", $vUltimaVenda);
                        $stmt->bindValue("p02", $item['id_produto']);
                        $stmt->bindValue("p03", $item['desconto']);
                        $stmt->bindValue("p04", $item['unitario']);
                        $stmt->bindValue("p05", $item['total']);
                        $stmt->bindValue("p06", $item['quantidade']);
                        $stmt->bindValue("p07", $item['unitario']);
                        $stmt->bindValue("p08", $id_usuario);
                        $stmt->bindValue("p09", $item['unitario']);
                        $stmt->bindValue("p10", $item['observacoes']);
                        $stmt->bindValue("p11", $item['descricao_produto']);
                        $stmt->bindValue("p12", $vServicoItem);
                        $stmt->bindValue("p13", 'T');
                        $stmt->bindValue("p14", $vContador);
                        $stmt->bindValue("p15", $item['fracionada']);
                        $stmt->bindValue("p16", $numeroAleatorio);
                        $stmt->execute();

                        //Complementos
                        $id_complemento = "0";
                        $vDescricao = "";
                        if (is_array($item['complementos'])) {
                            foreach ($item['listaComplementos'] as $itemComplemento) {
                                $queryCompl = "SELECT * FROM complemento WHERE id = '$itemComplemento'";
                                $stmt = $this->conexao->prepare($queryCompl);
                                $stmt->execute();

                                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                                foreach ($results as $rowComplemento) {
                                    $id_complemento = $rowComplemento['id'];
                                    $vDescricao = $rowComplemento['descricao'];
                                }

                                $query = "INSERT INTO venda_complemento (
                                    id_venda, 
                                    id_complemento, 
                                    situacao, 
                                    id_produto, 
                                    quantidade, 
                                    descricao, 
                                    contador, 
                                    uid)
                                    VALUES (
                                        :p01, 
                                        :p02, 
                                        :p03, 
                                        :p04, 
                                        :p05, 
                                        :p06, 
                                        :p07, 
                                        :p08)";
                                $stmt = $this->conexao->prepare($query);
                                $stmt->bindValue("p01", $vUltimaVenda);
                                $stmt->bindValue("p02", $id_complemento);
                                $stmt->bindValue("p03", 'N');
                                $stmt->bindValue("p04", $item['id_produto']);
                                $stmt->bindValue("p05", 1);
                                $stmt->bindValue("p06", $vDescricao);
                                $stmt->bindValue("p07", $vContador);
                                $stmt->bindValue("p08", $numeroAleatorio);
                                $stmt->execute();
                            }
                        }

                        //Borda      
                        $id_borda = "0";
                        $vDescricao = "";
                        if (is_array($item['bordas'])) {
                            foreach ($item['bordas'] as $itemBordas) {
                                $queryBorda = "SELECT * FROM bordas WHERE id = '$itemBordas'";
                                $stmt = $this->conexao->prepare($queryBorda);
                                $stmt->execute();
                                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                                foreach ($results as $rowBorda) {
                                    $id_borda = $rowBorda['id'];
                                    $vDescricao = $rowBorda['descricao'];
                                }

                                $query = "INSERT INTO venda_itens_borda (
                                    id_venda, 
                                    id_borda, 
                                    quantidade, 
                                    id_produto, 
                                    impresso, 
                                    id_referencia)
                                VALUES (
                                    :p01, 
                                    :p02, 
                                    :p03, 
                                    :p04, 
                                    :p05, 
                                    :p06)";
                                $stmt = $this->conexao->prepare($query);
                                $stmt->bindValue("p01", $vUltimaVenda);
                                $stmt->bindValue("p02", $id_borda);
                                $stmt->bindValue("p03", 1);
                                $stmt->bindValue("p04", $item['id_produto']);
                                $stmt->bindValue("p05", 'N');
                                $stmt->bindValue("p06", $numeroAleatorio);
                                $stmt->execute();
                            }
                        }

                        //Fracao
                        $id_fracao = "0";
                        $vDescricao = "";
                        $vValor = 0;
                        $vIdPizza = 0;
                        if (is_array($item['fracao'])) {
                            foreach ($item['fracao'] as $itemFracao) {
                                $queryFracao = "SELECT id, descricao, precovenda FROM produto WHERE id = '$itemFracao'";
                                $stmt = $this->conexao->prepare($queryFracao);
                                $stmt->execute();
                                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                                foreach ($results as $rowFracao) {
                                    $id_fracao = $rowFracao['id'];
                                    $vDescricao = $rowFracao['descricao'];
                                }

                                $query = "INSERT INTO venda_itens_fracao (
                                    id_venda, 
                                    valor, 
                                    quantidade, 
                                    id_produto, 
                                    id_produto_principal, 
                                    impresso, 
                                    id_referencia, 
                                    uid)
                                    VALUES (
                                        :p01, 
                                        :p02, 
                                        :p03, 
                                        :p04, 
                                        :p05, 
                                        :p06, 
                                        :p07, 
                                        :p08)";
                                $stmt = $this->conexao->prepare($query);
                                $stmt->bindValue("p01", $vUltimaVenda);
                                $stmt->bindValue("p02", $vValor);
                                $stmt->bindValue("p03", 1);
                                $stmt->bindValue("p04", $vIdPizza);
                                $stmt->bindValue("p05", $item['id_produto']);
                                $stmt->bindValue("p06", 'N');
                                $stmt->bindValue("p07", $numeroAleatorio);
                                $stmt->bindValue("p08", '');
                                $stmt->execute();
                            }
                        }
                    }

                    $vServico = ($vTotalVenda * $vPerServico / 100);

                    $query = "UPDATE venda SET 
                        subtotal = :p01, 
                        desconto = :p02, 
                        total = :p03, 
                        servico = :p04, 
                        fecha_conta = :p05 
                        WHERE id = :p06";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue("p01", $vTotalVenda);
                    $stmt->bindValue("p02", 0);
                    $stmt->bindValue("p03", $vTotalVenda);
                    $stmt->bindValue("p04", $vServico);
                    $stmt->bindValue("p05", 'N');
                    $stmt->bindValue("p06", $vUltimaVenda);
                    $stmt->execute();
                }

                http_response_code(201);

                $response = array(
                    'message' => 'Registro inserido com sucesso',
                    'request' => array(
                        'type' => 'POST',
                        'description' => 'Inclusão de registro',
                        'request' => array(
                            'type' => 'PUT',
                            'description' => 'Registro inserido com sucesso',
                            'url' => 'api/venda'
                        )
                    )
                );

                return $response;
            } else { // Nova comanda
                $vNomeCupom = "";

                if ($tipo === "B") {
                    $vNomeCupom = "cupom_balcao";

                    $qry = "INSERT INTO cupom_balcao(campo)VALUES('B')";
                    $stmt = $this->conexao->prepare($qry);
                    $stmt->execute();

                    $queryUltimoCupomBalcao = "SELECT cupom_balcao FROM cupom_balcao ORDER BY cupom_balcao DESC LIMIT 1";
                    $stmt = $this->conexao->prepare($queryUltimoCupomBalcao);
                    $stmt->execute();
                    $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($results as $rowBalcao) {
                        $vCupom = $rowBalcao['cupom_balcao'];
                    }

                    if ($tipo == "B") {
                        $comanda = 0;
                    }
                } else if ($tipo === "E") {
                    $vNomeCupom = "cupom_entrega";

                    $qry = "INSERT INTO cupom_entrega(campo)VALUES('E')";
                    $stmt = $this->conexao->prepare($qry);
                    $stmt->execute();

                    $queryUltimoCupomEntrega = "SELECT cupom_entrega FROM cupom_entrega ORDER BY cupom_entrega DESC LIMIT 1";
                    $stmt = $this->conexao->prepare($queryUltimoCupomEntrega);
                    $stmt->execute();
                    $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($results as $rowEntrega) {
                        $vCupom = $rowEntrega['cupom_entrega'];
                    }
                } else if ($tipo === "M") {
                    $vNomeCupom = "cupom_mesa";

                    $qry = "INSERT INTO cupom_mesa(campo)VALUES('M')";
                    $stmt = $this->conexao->prepare($qry);
                    $stmt->execute();

                    $queryUltimoCupomMesa = "SELECT cupom_mesa FROM cupom_mesa ORDER BY cupom_mesa DESC LIMIT 1";
                    $stmt = $this->conexao->prepare($queryUltimoCupomMesa);
                    $stmt->execute();
                    $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($results as $rowMesa) {
                        $vCupom = $rowMesa['cupom_mesa'];
                    }
                }

                $resumo_pagto = "";
                $vSituacao = "A";
                if ($tipo == "V") {
                    $vNomeCupom = "cupom_mesa";
                    $vSituacao = "F";

                    $vDescricaoPagamentos = "";
                    foreach ($listaFormasPagtos as $chave => $valor) {
                        if ($valor['amount'] > 0) {
                            $vDescricaoPagamentos = $vDescricaoPagamentos . ', ' .
                                ($valor['descricao'] . ' ' . number_format($valor['amount'], 2, ',', '.'));
                        }

                        $resumo_pagto = substr($vDescricaoPagamentos, 2, 200);
                    }
                }

                if (!$idcliente) {
                    $idcliente = 0;
                }

                if ($tipo != 'M' && $tipo != 'E' && $tipo != 'B') {
                    if ($tipo_lancamento == "ORCAMENTO") {
                        $tipo = "O";
                        $vSituacao = "A";
                    } else {
                        $tipo = "V";
                        $vSituacao = "F";
                    }
                }

                $query = "INSERT INTO venda (
                    idcliente, 
                    data, 
                    hora, 
                    subtotal, 
                    desconto, 
                    total, 
                    idatendente, 
                    idempresa, 
                    vlpagto, 
                    nota, 
                    idcaixa, 
                    nome_cliente_sem_cadastro, 
                    resumo_pagto, 
                    observacoes, 
                    id_movimento, 
                    id_usuario, 
                    tipo, 
                    situacao, 
                    troco, 
                    transferido, 
                    numeromesa, 
                    agrupada, 
                    servico, 
                    origem, 
                    npessoas, 
                    balcao_espera, 
                    $vNomeCupom,
                    endereco_cliente_sem_cadastro, 
                    pagtoparcial) 
                        VALUES (
                            :p01, 
                            :p02, 
                            :p03, 
                            :p04, 
                            :p05, 
                            :p06, 
                            :p07, 
                            :p08, 
                            :p09, 
                            :p10, 
                            :p11, 
                            :p12, 
                            :p13, 
                            :p14, 
                            :p15, 
                            :p16, 
                            :p17, 
                            :p18, 
                            :p19, 
                            :p20,
                            :p21, 
                            :p22, 
                            :p23, 
                            :p24, 
                            :p25, 
                            :p26, 
                            :p27, 
                            :p28, 
                            :p29)";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue("p01", $idcliente);
                $stmt->bindValue("p02", $dataAtual);
                $stmt->bindValue("p03", $hora);
                $stmt->bindValue("p04", $subtotal);
                $stmt->bindValue("p05", $desconto);
                $stmt->bindValue("p06", $total);
                $stmt->bindValue("p07", '1');
                $stmt->bindValue("p08", '1');
                $stmt->bindValue("p09", $vlpagto);
                $stmt->bindValue("p10", $nota);
                $stmt->bindValue("p11", $caixaId);
                $stmt->bindValue("p12", $nome_cliente);
                $stmt->bindValue("p13", $resumo_pagto);
                $stmt->bindValue("p14", $observacoes);
                $stmt->bindValue("p15", $movimentoId);
                $stmt->bindValue("p16", $id_usuario);
                $stmt->bindValue("p17", $tipo);
                $stmt->bindValue("p18", $vSituacao);
                $stmt->bindValue("p19", $troco);
                $stmt->bindValue("p20", 'N');
                $stmt->bindValue("p21", $comanda);
                $stmt->bindValue("p22", 'N');
                $stmt->bindValue("p23", $vServico);
                $stmt->bindValue("p24", 'T');
                $stmt->bindValue("p25", '1');
                $stmt->bindValue("p26", $balcao_espera);
                $stmt->bindValue("p27", $vCupom);
                $stmt->bindValue("p28", 'VENDA FEITA NO APP');
                $stmt->bindValue("p29", 0);
                $stmt->execute();

                //Último registro
                $vUltimoId = "0";
                $queryUltimoRegistro = "SELECT id FROM venda ORDER BY id DESC LIMIT 1";
                $stmt = $this->conexao->prepare($queryUltimoRegistro);
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($results as $row) {
                    $vUltimoId = $row['id'];
                }
                //Último registro

                foreach ($listaItens as $item) {
                    $vContador = floatval($vContador + 1);
                    $vServicoItem = ($item['total'] * $vPerServico / 100);

                    //$vTotalVenda = ($vTotalVenda + $item['total']);

                    $query = "INSERT INTO vendaitens (
                                       idvenda, 
                                       idproduto, 
                                       desconto, 
                                       vlunitario, 
                                       total, 
                                       quantidade, 
                                       preco_custo,
                                       id_atendente, 
                                       custo_medio, 
                                       observacoes, 
                                       descricao_item,
                                       servico, 
                                       origem, 
                                       contador, 
                                       fracionada, 
                                       id_referencia)
                                        VALUES (
                                           :p01, 
                                           :p02, 
                                           :p03,
                                           :p04, 
                                           :p05, 
                                           :p06, 
                                           :p07, 
                                           :p08, 
                                           :p09, 
                                           :p10, 
                                           :p11, 
                                           :p12, 
                                           :p13, 
                                           :p14, 
                                           :p15, 
                                           :p16)";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue("p01", $vUltimoId);
                    $stmt->bindValue("p02", $item['id_produto']);
                    $stmt->bindValue("p03", $item['desconto']);
                    $stmt->bindValue("p04", $item['unitario']);
                    $stmt->bindValue("p05", $item['total']);
                    $stmt->bindValue("p06", $item['quantidade']);
                    $stmt->bindValue("p07", $item['unitario']);
                    $stmt->bindValue("p08", $id_usuario);
                    $stmt->bindValue("p09", $item['unitario']);
                    $stmt->bindValue("p10", $item['observacoes']);
                    $stmt->bindValue("p11", $item['descricao_produto']);
                    $stmt->bindValue("p12", $vServicoItem);
                    $stmt->bindValue("p13", 'T');
                    $stmt->bindValue("p14", $vContador);
                    $stmt->bindValue("p15", $item['fracionada']);
                    $stmt->bindValue("p16", $numeroAleatorio);
                    $stmt->execute();

                    //Complementos
                    $id_complemento = "0";
                    $vDescricao = "";
                    if (is_array(json_decode($item['complementos'], true))) {
                        foreach (json_decode($item['complementos'], true) as $itemComplemento) {
                            $queryCompl = "SELECT * FROM complemento WHERE id = '$itemComplemento'";
                            $stmt = $this->conexao->prepare($queryCompl);
                            $stmt->execute();
                            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                            foreach ($results as $rowComplemento) {
                                $id_complemento = $rowComplemento['id'];
                                $vDescricao = $rowComplemento['descricao'];
                            }

                            $query = "INSERT INTO venda_complemento (
                                           id_venda, 
                                           id_complemento, 
                                           situacao, 
                                           id_produto, 
                                           quantidade, 
                                           descricao, 
                                           contador, 
                                           uid)
                                           VALUES (
                                               :p01, 
                                               :p02, 
                                               :p03, 
                                               :p04, 
                                               :p05, 
                                               :p06, 
                                               :p07, 
                                               :p08)";
                            $stmt = $this->conexao->prepare($query);
                            $stmt->bindValue("p01", $vUltimoId);
                            $stmt->bindValue("p02", $id_complemento);
                            $stmt->bindValue("p03", 'N');
                            $stmt->bindValue("p04", $item['id_produto']);
                            $stmt->bindValue("p05", 1);
                            $stmt->bindValue("p06", $vDescricao);
                            $stmt->bindValue("p07", $vContador);
                            $stmt->bindValue("p08", $numeroAleatorio);
                            $stmt->execute();
                        }
                    }

                    //Borda      
                    $id_borda = "0";
                    $vDescricao = "";

                    if (is_array(json_decode($item['bordas'], true))) {
                        foreach (json_decode($item['bordas'], true) as $itemBordas) {
                            $queryBorda = "SELECT * FROM bordas WHERE id = '$itemBordas'";
                            $stmt = $this->conexao->prepare($queryBorda);
                            $stmt->execute();
                            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                            foreach ($results as $rowBorda) {
                                $id_borda = $rowBorda['id'];
                                $vDescricao = $rowBorda['descricao'];
                            }

                            $query = "INSERT INTO venda_itens_borda (
                                           id_venda, 
                                           id_borda, 
                                           quantidade, 
                                           id_produto, 
                                           impresso, 
                                           id_referencia)
                                       VALUES (
                                           :p01, 
                                           :p02, 
                                           :p03, 
                                           :p04, 
                                           :p05, 
                                           :p06)";
                            $stmt = $this->conexao->prepare($query);
                            $stmt->bindValue("p01", $vUltimoId);
                            $stmt->bindValue("p02", $id_borda);
                            $stmt->bindValue("p03", 1);
                            $stmt->bindValue("p04", $item['id_produto']);
                            $stmt->bindValue("p05", 'N');
                            $stmt->bindValue("p06", $numeroAleatorio);
                            $stmt->execute();
                        }
                    }

                    //Fracao
                    $id_fracao = "0";
                    $vDescricao = "";
                    $vValor = 0;
                    if (is_array(json_decode($item['fracao'], true))) {
                        foreach (json_decode($item['fracao'], true) as $itemFracao) {
                            $id_pizza = '0';
                            $id_pizza = $itemFracao['id_pizza'];

                            $queryFracao = "SELECT id, descricao, precovenda FROM produto WHERE id = '$id_pizza'";
                            $stmt = $this->conexao->prepare($queryFracao);
                            $stmt->execute();
                            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                            foreach ($results as $rowFracao) {
                                $id_fracao = $rowFracao['id'];
                                $vDescricao = $rowFracao['descricao'];
                                $vValor = $rowFracao['precovenda'];
                            }

                            $query = "INSERT INTO venda_itens_fracao (
                                           id_venda, 
                                           valor, 
                                           quantidade, 
                                           id_produto, 
                                           id_produto_principal, 
                                           impresso, 
                                           id_referencia, 
                                           uid)
                                           VALUES (
                                               :p01, 
                                               :p02, 
                                               :p03, 
                                               :p04, 
                                               :p05, 
                                               :p06, 
                                               :p07, 
                                               :p08)";
                            $stmt = $this->conexao->prepare($query);
                            $stmt->bindValue("p01", $vUltimoId);
                            $stmt->bindValue("p02", $vValor);
                            $stmt->bindValue("p03", 1);
                            $stmt->bindValue("p04", $id_fracao);
                            $stmt->bindValue("p05", $item['id_produto']);
                            $stmt->bindValue("p06", 'N');
                            $stmt->bindValue("p07", $numeroAleatorio);
                            $stmt->bindValue("p08", $numeroAleatorio);
                            $stmt->execute();
                        }
                    }
                }

                if ($tipo_lancamento == "VENDA") {
                    foreach ($listaFormasPagtos as $chave => $valor) {
                        if ($valor['amount'] > 0) {
                            $vNovoVencimento = $valor['datavencimento'];
                            // $vNovoVencimento = explode("/", $vNovoVencimento);
                            // $vNovoVencimento = $vNovoVencimento[2] . "-" . $vNovoVencimento[1] . "-" . $vNovoVencimento[0];

                            //Verifica o parcela
                            $vStatus = "N";
                            $FormaPagto = $valor['descricao'];
                            $vStatus = "S";
                            // if ($FormaPagto === "DINHEIRO") {
                            //     $vStatus = "S";
                            // }

                            //Verifica o cliente
                            $nome_cliente = "CONSUMIDOR";
                            $queryCliente = "SELECT id, razaosocial, nomefantasia FROM cliente WHERE id = '$idcliente' LIMIT 1";
                            $stmt = $this->conexao->prepare($queryCliente);
                            $stmt->execute();
                            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                            foreach ($results as $row) {
                                $nome_cliente = $row['nomefantasia'];
                            }
                            //Verifica o cliente     

                            //Verifica o forma de pagamento
                            $id_pagto = "0";
                            $queryForma = "SELECT id, descricao FROM tipo_pagamento WHERE descricao = '$FormaPagto' LIMIT 1";
                            $stmt = $this->conexao->prepare($queryForma);
                            $stmt->execute();
                            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                            foreach ($results as $row) {
                                $id_pagto = $row['id'];
                            }
                            //Verifica o forma de pagamento

                            //Verifica a competência
                            $anoAtual = date("Y");
                            $mesAtual = date("m");

                            $competencia = $mesAtual . '/' . $anoAtual;
                            //Verifica a competência

                            $totalParcela = $valor['amount'];
                            if ($FormaPagto == "DINHEIRO") {
                                $totalParcela = floatval($totalParcela - $troco);
                            }

                            $qry = "INSERT INTO lancamentoscaixa (
                            idusuario, 
                            idcaixa, 
                            idcentrocusto,
                            dataemissao, 
                            tipomovimento, 
                            ndoc, 
                            valor, 
                            cadastro, 
                            observacoes,
                            parcela, 
                            id_origem, 
                            sigla_origem, 
                            hora, 
                            descricao_recebimento,
                            id_plano_contas, 
                            situacao, 
                            id_movimento, 
                            vencimento, 
                            valor_deduzido,
                            lancamento_futuro, 
                            id_pagto, 
                            desc_tipo, 
                            valor_real, 
                            titulo, 
                            id_parcelamento,
                            situacao_os, 
                            id_aluno, 
                            nome_cliente, 
                            data_pagamento, 
                            valor_pago, 
                            tipo_documento,
                            competencia,
                            confirma_pagto)
                        VALUES (
                            :p01, 
                            :p02, 
                            :p03, 
                            :p04, 
                            :p05, 
                            :p06, 
                            :p07, 
                            :p08, 
                            :p09, 
                            :p10, 
                            :p11, 
                            :p12, 
                            :p13, 
                            :p14, 
                            :p15, 
                            :p16, 
                            :p17, 
                            :p18, 
                            :p19, 
                            :p20, 
                            :p21, 
                            :p22, 
                            :p23, 
                            :p24, 
                            :p25, 
                            :p26, 
                            :p27, 
                            :p28, 
                            :p29, 
                            :p30, 
                            :p31, 
                            :p32,
                            :p33)";
                            $stmt = $this->conexao->prepare($qry);

                            $stmt->bindValue("p01", 2);
                            $stmt->bindValue("p02", 2);
                            $stmt->bindValue("p03", 1);
                            $stmt->bindValue("p04", $dataAtual);
                            $stmt->bindValue("p05", 'E');
                            $stmt->bindValue("p06", $vUltimoId);
                            $stmt->bindValue("p07", $totalParcela);
                            $stmt->bindValue("p08", $dataAtual);
                            $stmt->bindValue("p09", 'Movimento de venda (' . $FormaPagto . ')');
                            $stmt->bindValue("p10", 1);
                            $stmt->bindValue("p11", $vUltimoId);
                            $stmt->bindValue("p12", 'V');
                            $stmt->bindValue("p13", $hora);
                            $stmt->bindValue("p14", 'Movimento de venda (' . $FormaPagto . ')');
                            $stmt->bindValue("p15", 1);
                            $stmt->bindValue("p16", $vStatus);
                            $stmt->bindValue("p17", 2);
                            $stmt->bindValue("p18", $vNovoVencimento);
                            $stmt->bindValue("p19", $totalParcela);
                            $stmt->bindValue("p20", 'N');
                            $stmt->bindValue("p21", $id_pagto);
                            $stmt->bindValue("p22", $FormaPagto);
                            $stmt->bindValue("p23", $totalParcela);
                            $stmt->bindValue("p24", $vUltimoId);
                            $stmt->bindValue("p25", 1);
                            $stmt->bindValue("p26", $vStatus);
                            $stmt->bindValue("p27", $idcliente);
                            $stmt->bindValue("p28", $nome_cliente);
                            $stmt->bindValue("p29", $dataAtual);
                            $stmt->bindValue("p30", $totalParcela);
                            $stmt->bindValue("p31", 'DUPLICATA');
                            $stmt->bindValue("p32", $competencia);
                            $stmt->bindValue("p33", 'S');
                            $stmt->execute();
                        }
                    }
                }
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
                    'url' => 'api/usuario'
                )
            )
        );

        return $response;
    }

    function fechaConta($id)
    {
        $response = "";
        http_response_code(200);

        try {
            $qry = "UPDATE venda SET 
                    fecha_conta =:p01,
                    npessoas =:p02,
                    imp_conta_tablet =:p03
                WHERE id =:p04";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", 'S');
            $stmt->bindValue("p02", 1);
            $stmt->bindValue("p03", 'N');
            $stmt->bindValue("p04", $id);
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
                    'url' => 'api/venda'
                )
            )
        );

        return $response;
    }

    function receberComanda($data)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        //Verifica a competência
        $anoAtual = date("Y");
        $mesAtual = date("m");

        $competencia = $mesAtual . '/' . $anoAtual;
        //Verifica a competência        

        try {
            $vDescricaoPagamentos = "";

            $vendaId = $data['id_pedido'];
            $valorTotal = $data['totalComanda'];
            $valores = $data['valores'];

            //Verifica o caixa
            $caixaId = 2;
            $movimentoId = 2;
            $queryCaixa = "SELECT mc.id, mc.idcaixa, cx.descricao as caixa, cx.caixa_web 
                            FROM movimentocaixa mc LEFT JOIN caixa cx ON (mc.idcaixa = cx.id) 
                            WHERE mc.status = 'A' LIMIT 1";

            $stmt = $this->conexao->prepare($queryCaixa);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $caixaId = $row['idcaixa'];
                $queryCaixa = $row['id'];
            }
            //Verifica o caixa

            $vDescricaoPagamentos = "";
            foreach ($valores as $chave => $valor) {
                // console.log('ID:', valor.id);
                // console.log('Descrição:', valor.descricao);
                // console.log('Nome:', valor.name);
                // console.log('Valor:', valor.amount);

                if ($valor['amount'] > 0) {
                    $query = "INSERT INTO lancamentoscaixa (
                            idusuario, 
                            idcaixa, 
                            idcentrocusto,
                            dataemissao, 
                            tipomovimento, 
                            ndoc, 
                            valor, 
                            cadastro, 
                            observacoes,
                            parcela, 
                            id_origem, 
                            sigla_origem, 
                            hora, 
                            descricao_recebimento,
                            id_plano_contas, 
                            situacao, 
                            id_movimento, 
                            vencimento, 
                            valor_deduzido,
                            lancamento_futuro, 
                            id_pagto, 
                            desc_tipo, 
                            valor_real, 
                            titulo, 
                            id_parcelamento,
                            situacao_os, 
                            id_aluno, 
                            nome_cliente, 
                            data_pagamento, 
                            valor_pago, 
                            tipo_documento, 
                            competencia)
                                VALUES(
                                    :p01, 
                                    :p02, 
                                    :p03, 
                                    :p04, 
                                    :p05, 
                                    :p06, 
                                    :p07, 
                                    :p08, 
                                    :p09, 
                                    :p10, 
                                    :p11, 
                                    :p12, 
                                    :p13, 
                                    :p14, 
                                    :p15, 
                                    :p16, 
                                    :p17, 
                                    :p18, 
                                    :p19, 
                                    :p20, 
                                    :p21, 
                                    :p22, 
                                    :p23, 
                                    :p24, 
                                    :p25, 
                                    :p26, 
                                    :p27, 
                                    :p28, 
                                    :p29, 
                                    :p30, 
                                    :p31, 
                                    :p32)";
                    $stmt = $this->conexao->prepare($query);
                    $stmt->bindValue("p01", 2);
                    $stmt->bindValue("p02", $caixaId);
                    $stmt->bindValue("p03", 1);
                    $stmt->bindValue("p04", $dataAtual);
                    $stmt->bindValue("p05", 'E');
                    $stmt->bindValue("p06", $vendaId);
                    $stmt->bindValue("p07", $valor['amount']);
                    $stmt->bindValue("p08", $dataAtual);
                    $stmt->bindValue("p09", 'Movimento de venda ( ' . $valor['descricao'] . ' )');
                    $stmt->bindValue("p10", 1);
                    $stmt->bindValue("p11", $vendaId);
                    $stmt->bindValue("p12", 'V');
                    $stmt->bindValue("p13", $hora);
                    $stmt->bindValue("p14", 'Movimento de venda ( ' . $valor['descricao'] . ' )');
                    $stmt->bindValue("p15", 1);
                    $stmt->bindValue("p16", 'S');
                    $stmt->bindValue("p17", $movimentoId);
                    $stmt->bindValue("p18", $dataAtual);
                    $stmt->bindValue("p19", $valor['amount']);
                    $stmt->bindValue("p20", 'N');
                    $stmt->bindValue("p21", $valor['id']);
                    $stmt->bindValue("p22", $valor['descricao']);
                    $stmt->bindValue("p23", $valor['amount']);
                    $stmt->bindValue("p24", $vendaId);
                    $stmt->bindValue("p25", 1);
                    $stmt->bindValue("p26", 'S');
                    $stmt->bindValue("p27", 0);
                    $stmt->bindValue("p28", 'CONSUMIDOR');
                    $stmt->bindValue("p29", $dataAtual);
                    $stmt->bindValue("p30", $valor['amount']);
                    $stmt->bindValue("p31", 'DUPLICATA');
                    $stmt->bindValue("p32", $competencia);
                    $stmt->execute();

                    $vDescricaoPagamentos = $vDescricaoPagamentos . ', ' .
                        ($valor['descricao'] . ' ' . number_format($valor['amount'], 2, ',', '.'));
                }
            }

            // Obter a substring a partir do 4º caractere (índice 3) até o 200º caractere
            $substring = substr($vDescricaoPagamentos, 2, 200);

            // Remover espaços em branco no início e no final da substring
            $substring = trim($substring);

            $qry = "UPDATE venda SET 
                    situacao =:p01,
                    vlpagto =:p02,
                    resumo_pagto =:p03       
                WHERE id =:p04";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", 'F');
            $stmt->bindValue("p02", $valorTotal);
            $stmt->bindValue("p03", $substring);
            $stmt->bindValue("p04", $vendaId);
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
                    'url' => 'api/usuario'
                )
            )
        );

        return $response;
    }

    function alteraAnalise($id)
    {
        $response = "";
        http_response_code(200);

        try {
            $qry = "UPDATE venda SET 
                analise =:p01       
            WHERE id =:p02";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", 'N');
            $stmt->bindValue("p02", $id);
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
                    'url' => 'api/venda'
                )
            )
        );

        return $response;
    }

    function converteVenda($id)
    {
        $response = "";
        http_response_code(200);

        try {
            $qry = "UPDATE venda SET 
                tipo =:p01,
                situacao =:p02,
                analise =:p03    
            WHERE id =:p04";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", 'V');
            $stmt->bindValue("p02", 'F');
            $stmt->bindValue("p03", 'N');
            $stmt->bindValue("p04", $id);
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
                    'url' => 'api/venda'
                )
            )
        );

        return $response;
    }

    function alteraDelivery($data)
    {
        $response = "";
        http_response_code(200);

        $id_venda = $data["id_venda"];
        $observacoes = $data["observacoes"];
        $forma_pagto = $data["forma_pagto"];

        $observacoes = $observacoes . "\n" . $forma_pagto;

        try {
            $qry = "UPDATE venda SET 
                observacoes =:p01,
                entrega_realizada =:p02
            WHERE id =:p03";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $observacoes);
            $stmt->bindValue("p02", 'S');
            $stmt->bindValue("p03", $id_venda);
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
                    'url' => 'api/venda'
                )
            )
        );

        return $response;
    }

    function inserirPedidoOnline($data)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        try {
            $idcliente = $data['id_cliente'];
            $subtotal = $data['subtotal'];
            $desconto = $data['desconto'];
            $total = $data['total'];
            $troco = $data['troco'];
            $vlpagto = $data['vlpagto'];
            $nota = $data['nota'];
            $nome_cliente = $data['nome_cliente'];
            $id_usuario = $data['id_usuario'];
            $listaItens = $data['produtos'];
            $observacoes = $data['observacoes'];
            $comanda = $data['comanda'];
            $tipo = $data['tipo'];
            $qtde_produtos = $data['qtde_produtos'];
            $nome_usuario = $data['nome_usuario'];
            $hash = $data['hash'];
            $channel = $data['channel'];
            $telefone = $data['telefone'];
            $resumo_pagto = $data['resumo_pagto'];
            $entrega_taxa = $data['entrega_taxa'];
            $endereco = $data['endereco'];
            $numero = $data['numero'];
            $bairro = $data['bairro'];
            $id_bairro = $data['id_bairro'];
            $balcao_espera = $data['balcao_espera'];

            $vUltimoId = 0;
            $vContador = 0;
            $vCupom = 0;

            if ($idcliente === '') {
                $idcliente = '0';
            }

            //Verifica o cliente            
            $SQLCliente = "SELECT * FROM cliente WHERE telefone1 = '$telefone' LIMIT 1";
            $stmt = $this->conexao->prepare($SQLCliente);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            if ($count > 0) {
                foreach ($results as $rowCliente) {
                    $idcliente = $rowCliente['id'];
                }

                $qry = "UPDATE cliente SET 
                    idcategoria = :p01, 
                    cfpcnpj = :p02, 
                    nomefantasia = :p03, 
                    razaosocial = :p04, 
                    endereco = :p05, 
                    numero = :p06, 
                    bairro = :p07, 
                    cidade = :p08, 
                    uf = :p09, 
                    cep = :p10, 
                    telefone1 = :p11, 
                    telefone2 = :p12, 
                    correio = :p13, 
                    rginsestadual = :p14, 
                    observacoes = :p15, 
                    codcidade = :p16,
                    id_cidade = :p17, 
                    id_bairro = :p18, 
                    endereco_entrega = :p19, 
                    numero_entrega = :p20, 
                    bairro_entrega = :p21, 
                    telefone1_entrega = :p22 
                    WHERE id = :p23";
                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", 0);
                $stmt->bindValue("p02", 0);
                $stmt->bindValue("p03", $nome_cliente);
                $stmt->bindValue("p04", $nome_cliente);
                $stmt->bindValue("p05", $endereco);
                $stmt->bindValue("p06", $numero);
                $stmt->bindValue("p07", $bairro);
                $stmt->bindValue("p08", 'CUIABA');
                $stmt->bindValue("p09", 'MT');
                $stmt->bindValue("p10", '78000000');
                $stmt->bindValue("p11", $telefone);
                $stmt->bindValue("p12", $telefone);
                $stmt->bindValue("p13", 'teste@teste.com.br');
                $stmt->bindValue("p14", '0');
                $stmt->bindValue("p15", 'CLIENTE VIA APP');
                $stmt->bindValue("p16", '5103403');
                $stmt->bindValue("p17", 0);
                $stmt->bindValue("p18", $id_bairro);
                $stmt->bindValue("p19", $endereco);
                $stmt->bindValue("p20", $numero);
                $stmt->bindValue("p21", $bairro);
                $stmt->bindValue("p22", $telefone);
                $stmt->bindValue("p23", $idcliente);
                $stmt->execute();
            } else {
                $qry = "INSERT INTO cliente (
                    idcategoria, 
                    cfpcnpj, 
                    nomefantasia, 
                    razaosocial, 
                    endereco, 
                    numero, 
                    bairro, 
                    cidade, 
                    uf, 
                    cep, 
                    telefone1, 
                    telefone2,
                    correio, 
                    rginsestadual, 
                    observacoes, 
                    codcidade, 
                    id_cidade, 
                    id_bairro, 
                    idempresa, 
                    situacao, 
                    endereco_entrega, 
                    numero_entrega, 
                    bairro_entrega, 
                    telefone1_entrega) 
                    VALUES (
                        :p01, 
                        :p02, 
                        :p03, 
                        :p04, 
                        :p05, 
                        :p06, 
                        :p07, 
                        :p08, 
                        :p09, 
                        :p10, 
                        :p11, 
                        :p12, 
                        :p13, 
                        :p14, 
                        :p15, 
                        :p16, 
                        :p17, 
                        :p18, 
                        :p19, 
                        :p20, 
                        :p21, 
                        :p22, 
                        :p23, 
                        :p24)";
                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", 0);
                $stmt->bindValue("p02", 0);
                $stmt->bindValue("p03", $nome_cliente);
                $stmt->bindValue("p04", $nome_cliente);
                $stmt->bindValue("p05", $endereco);
                $stmt->bindValue("p06", $numero);
                $stmt->bindValue("p07", $bairro);
                $stmt->bindValue("p08", 'CUIABA');
                $stmt->bindValue("p09", 'MT');
                $stmt->bindValue("p10", '78000000');
                $stmt->bindValue("p11", $telefone);
                $stmt->bindValue("p12", $telefone);
                $stmt->bindValue("p13", 'teste@teste.com.br');
                $stmt->bindValue("p14", '0');
                $stmt->bindValue("p15", 'CLIENTE VIA APP');
                $stmt->bindValue("p16", '5103403');
                $stmt->bindValue("p17", 0);
                $stmt->bindValue("p18", $id_bairro);
                $stmt->bindValue("p19", 1);
                $stmt->bindValue("p20", 'A');
                $stmt->bindValue("p21", $endereco);
                $stmt->bindValue("p22", $numero);
                $stmt->bindValue("p23", $bairro);
                $stmt->bindValue("p24", $telefone);
                $stmt->execute();

                $ultimoIDSQL = "SELECT id FROM cliente ORDER BY id DESC LIMIT 1";
                $stmt = $this->conexao->prepare($ultimoIDSQL);
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($results as $rowCliente) {
                    $idcliente = $rowCliente['id'];
                }
            }
            //Verifica o cliente

            //Verifica o caixa
            $caixaId = 2;
            $movimentoId = 2;
            $queryCaixa = "SELECT mc.id, mc.idcaixa, cx.descricao as caixa, cx.caixa_web 
                            FROM movimentocaixa mc LEFT JOIN caixa cx ON (mc.idcaixa = cx.id) 
                            WHERE mc.status = 'A' LIMIT 1";

            $stmt = $this->conexao->prepare($queryCaixa);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $caixaId = $row['idcaixa'];
                $queryCaixa = $row['id'];
            }
            // //Verifica o caixa

            $numeroAleatorio = generateRandomString(15);

            $vPerServico = 0;
            $queryParametro = "SELECT per_servico FROM parametro LIMIT 1";
            $stmt = $this->conexao->prepare($queryParametro);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $vPerServico = $row['per_servico'];
            }

            $vServico = ($total * $vPerServico / 100);

            $vNomeCupom = "";

            if ($tipo === "B") {
                $vNomeCupom = "cupom_balcao";

                $qry = "INSERT INTO cupom_balcao(campo)VALUES('B')";
                $stmt = $this->conexao->prepare($qry);
                $stmt->execute();

                $queryUltimoCupomBalcao = "SELECT cupom_balcao FROM cupom_balcao ORDER BY cupom_balcao DESC LIMIT 1";
                $stmt = $this->conexao->prepare($queryUltimoCupomBalcao);
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($results as $rowBalcao) {
                    $vCupom = $rowBalcao['cupom_balcao'];
                }
            } else if ($tipo === "E") {
                $vNomeCupom = "cupom_entrega";

                $qry = "INSERT INTO cupom_entrega(campo)VALUES('E')";
                $stmt = $this->conexao->prepare($qry);
                $stmt->execute();

                $queryUltimoCupomEntrega = "SELECT cupom_entrega FROM cupom_entrega ORDER BY cupom_entrega DESC LIMIT 1";
                $stmt = $this->conexao->prepare($queryUltimoCupomEntrega);
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($results as $rowEntrega) {
                    $vCupom = $rowEntrega['cupom_entrega'];
                }
            } else if ($tipo === "M") {
                $vNomeCupom = "cupom_mesa";

                $qry = "INSERT INTO cupom_mesa(campo)VALUES('M')";
                $stmt = $this->conexao->prepare($qry);
                $stmt->execute();

                $queryUltimoCupomMesa = "SELECT cupom_mesa FROM cupom_mesa ORDER BY cupom_mesa DESC LIMIT 1";
                $stmt = $this->conexao->prepare($queryUltimoCupomMesa);
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($results as $rowMesa) {
                    $vCupom = $rowMesa['cupom_mesa'];
                }
            }

            $query = "INSERT INTO venda (
                            idcliente, 
                            data, 
                            hora, subtotal, 
                            desconto, 
                            total, 
                            idatendente, 
                            idempresa, 
                            vlpagto, 
                            nota, 
                            idcaixa, 
                            nome_cliente_sem_cadastro, 
                            resumo_pagto, 
                            observacoes, 
                            id_movimento, 
                            id_usuario, 
                            tipo, 
                            situacao, 
                            troco, 
                            transferido, 
                            numeromesa, 
                            agrupada, 
                            servico, 
                            origem, 
                            npessoas, 
                            balcao_espera, 
                            $vNomeCupom,
                            endereco_cliente_sem_cadastro, 
                            valor_entrega,
                            entrega_idpagto, 
                            imp_conta_tablet, 
                            pagtoparcial) 
                            VALUES (
                                :p01, 
                                :p02, 
                                :p03, 
                                :p04, 
                                :p05, 
                                :p06, 
                                :p07, 
                                :p08, 
                                :p09, 
                                :p10, 
                                :p11, 
                                :p12, 
                                :p13, 
                                :p14, 
                                :p15, 
                                :p16, 
                                :p17, 
                                :p18, 
                                :p19, 
                                :p20,
                                :p21, 
                                :p22, 
                                :p23, 
                                :p24, 
                                :p25,
                                :p26, 
                                :p27, 
                                :p28, 
                                :p29, 
                                :p30, 
                                :p31, 
                                :p32)";
            $stmt = $this->conexao->prepare($query);
            $stmt->bindValue("p01", $idcliente);
            $stmt->bindValue("p02", $dataAtual);
            $stmt->bindValue("p03", $hora);
            $stmt->bindValue("p04", $subtotal);
            $stmt->bindValue("p05", $desconto);
            $stmt->bindValue("p06", $total);
            $stmt->bindValue("p07", '1');
            $stmt->bindValue("p08", '1');
            $stmt->bindValue("p09", $vlpagto);
            $stmt->bindValue("p10", $nota);
            $stmt->bindValue("p11", $caixaId);
            $stmt->bindValue("p12", $nome_cliente);
            $stmt->bindValue("p13", $resumo_pagto);
            $stmt->bindValue("p14", $observacoes);
            $stmt->bindValue("p15", $movimentoId);
            $stmt->bindValue("p16", $id_usuario);
            $stmt->bindValue("p17", $tipo);
            $stmt->bindValue("p18", 'A');
            $stmt->bindValue("p19", $troco);
            $stmt->bindValue("p20", 'N');
            $stmt->bindValue("p21", $comanda);
            $stmt->bindValue("p22", 'N');
            $stmt->bindValue("p23", $vServico);
            $stmt->bindValue("p24", 'T');
            $stmt->bindValue("p25", '1');
            $stmt->bindValue("p26", $balcao_espera);
            $stmt->bindValue("p27", $vCupom);
            $stmt->bindValue("p28", 'VENDA FEITA NA MESA');
            $stmt->bindValue("p29", $entrega_taxa);
            $stmt->bindValue("p30", trim($resumo_pagto . substr(2, 120)));
            $stmt->bindValue("p31", 'S');
            $stmt->bindValue("p32", 0);
            $stmt->execute();

            //Último registro
            $vUltimoId = "0";
            $queryUltimoRegistro = "SELECT id FROM venda ORDER BY id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($queryUltimoRegistro);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $vUltimoId = $row['id'];
            }
            //Último registro

            foreach ($listaItens as $item) {
                $vContador = floatval($vContador + 1);
                $vServicoItem = ($item['total'] * $vPerServico / 100);

                $query = "INSERT INTO vendaitens (
                                       idvenda, 
                                       idproduto, 
                                       desconto, 
                                       vlunitario, 
                                       total, 
                                       quantidade, 
                                       preco_custo,
                                       id_atendente, 
                                       custo_medio, 
                                       observacoes, 
                                       descricao_item,
                                       servico, 
                                       origem, 
                                       contador, 
                                       fracionada, 
                                       id_referencia)
                                        VALUES (
                                           :p01, 
                                           :p02, 
                                           :p03,
                                           :p04, 
                                           :p05, 
                                           :p06, 
                                           :p07, 
                                           :p08, 
                                           :p09, 
                                           :p10, 
                                           :p11, 
                                           :p12, 
                                           :p13, 
                                           :p14, 
                                           :p15, 
                                           :p16)";
                $stmt = $this->conexao->prepare($query);
                $stmt->bindValue("p01", $vUltimoId);
                $stmt->bindValue("p02", $item['id_produto']);
                $stmt->bindValue("p03", $item['desconto']);
                $stmt->bindValue("p04", $item['unitario']);
                $stmt->bindValue("p05", $item['total']);
                $stmt->bindValue("p06", $item['quantidade']);
                $stmt->bindValue("p07", $item['unitario']);
                $stmt->bindValue("p08", $id_usuario);
                $stmt->bindValue("p09", $item['unitario']);
                $stmt->bindValue("p10", $item['observacoes']);
                $stmt->bindValue("p11", $item['descricao_produto']);
                $stmt->bindValue("p12", $vServicoItem);
                $stmt->bindValue("p13", 'T');
                $stmt->bindValue("p14", $vContador);
                $stmt->bindValue("p15", ''); //$item['fracionada']
                $stmt->bindValue("p16", $numeroAleatorio);
                $stmt->execute();

                //Complementos
                $id_complemento = "0";
                $vDescricao = "";
                if (is_array(json_decode($item['complementos'], true))) {
                    foreach (json_decode($item['complementos'], true) as $itemComplemento) {
                        $queryCompl = "SELECT * FROM complemento WHERE id = '$itemComplemento'";
                        $stmt = $this->conexao->prepare($queryCompl);
                        $stmt->execute();
                        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        foreach ($results as $rowComplemento) {
                            $id_complemento = $rowComplemento['id'];
                            $vDescricao = $rowComplemento['descricao'];
                        }

                        $query = "INSERT INTO venda_complemento (
                                           id_venda, 
                                           id_complemento, 
                                           situacao, 
                                           id_produto, 
                                           quantidade, 
                                           descricao, 
                                           contador, 
                                           uid)
                                           VALUES (
                                               :p01, 
                                               :p02, 
                                               :p03, 
                                               :p04, 
                                               :p05, 
                                               :p06, 
                                               :p07, 
                                               :p08)";
                        $stmt = $this->conexao->prepare($query);
                        $stmt->bindValue("p01", $vUltimoId);
                        $stmt->bindValue("p02", $id_complemento);
                        $stmt->bindValue("p03", 'N');
                        $stmt->bindValue("p04", $item['id_produto']);
                        $stmt->bindValue("p05", 1);
                        $stmt->bindValue("p06", $vDescricao);
                        $stmt->bindValue("p07", $vContador);
                        $stmt->bindValue("p08", $numeroAleatorio);
                        $stmt->execute();
                    }
                }

                //Borda      
                $id_borda = "0";
                $vDescricao = "";

                if (is_array(json_decode($item['bordas'], true))) {
                    foreach (json_decode($item['bordas'], true) as $itemBordas) {
                        $queryBorda = "SELECT * FROM bordas WHERE id = '$itemBordas'";
                        $stmt = $this->conexao->prepare($queryBorda);
                        $stmt->execute();
                        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        foreach ($results as $rowBorda) {
                            $id_borda = $rowBorda['id'];
                            $vDescricao = $rowBorda['descricao'];
                        }

                        $query = "INSERT INTO venda_itens_borda (
                                           id_venda, 
                                           id_borda, 
                                           quantidade, 
                                           id_produto, 
                                           impresso, 
                                           id_referencia)
                                       VALUES (
                                           :p01, 
                                           :p02, 
                                           :p03, 
                                           :p04, 
                                           :p05, 
                                           :p06)";
                        $stmt = $this->conexao->prepare($query);
                        $stmt->bindValue("p01", $vUltimoId);
                        $stmt->bindValue("p02", $id_borda);
                        $stmt->bindValue("p03", 1);
                        $stmt->bindValue("p04", $item['id_produto']);
                        $stmt->bindValue("p05", 'N');
                        $stmt->bindValue("p06", $numeroAleatorio);
                        $stmt->execute();
                    }
                }

                //Fracao
                $id_fracao = "0";
                $vDescricao = "";
                $vValor = 0;
                $vIdPizza = 0;

                if (is_array(json_decode($item['fracao'], true))) {
                    foreach (json_decode($item['fracao'], true) as $itemFracao) {
                        $queryFracao = "SELECT id, descricao, precovenda FROM produto WHERE id = '$itemFracao'";
                        $stmt = $this->conexao->prepare($queryFracao);
                        $stmt->execute();
                        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                        foreach ($results as $rowFracao) {
                            $id_fracao = $rowFracao['id'];
                            $vDescricao = $rowFracao['descricao'];
                        }

                        $query = "INSERT INTO venda_itens_fracao (
                                           id_venda, 
                                           valor, 
                                           quantidade, 
                                           id_produto, 
                                           id_produto_principal, 
                                           impresso, 
                                           id_referencia, 
                                           uid)
                                           VALUES (
                                               :p01, 
                                               :p02, 
                                               :p03, 
                                               :p04, 
                                               :p05, 
                                               :p06, 
                                               :p07, 
                                               :p08)";
                        $stmt = $this->conexao->prepare($query);
                        $stmt->bindValue("p01", $vUltimoId);
                        $stmt->bindValue("p02", $vValor);
                        $stmt->bindValue("p03", 1);
                        $stmt->bindValue("p04", $vIdPizza);
                        $stmt->bindValue("p05", $item['id_produto']);
                        $stmt->bindValue("p06", 'N');
                        $stmt->bindValue("p07", $numeroAleatorio);
                        $stmt->bindValue("p08", '');
                        $stmt->execute();
                    }
                }
            }

            $qry = "UPDATE venda SET transferido = :p01 WHERE hash = :p02";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", 'S');
            $stmt->bindValue("p02", $hash);
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
                    'url' => 'api/vendas/save/pedidoonline'
                )
            )
        );

        return $response;
    }

    function inserirPedidoClienteFinal($data)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");
        $hora = date('H:i:s');

        try {
            $id_cliente = $data['id_cliente'];
            $data_venda = $data['data_venda'];
            $subtotal = $data['total'];
            $desconto = 0;
            $total = $data['total'];
            $troco = 0;
            $vlpagto = $data['total'];
            $nota = $data['nota'];
            $nome_cliente = $data['nome_cliente'];
            $id_usuario = $data['id_usuario'];
            $listaItens = $data['produtos'];
            $observacoes = $data['observacoes'];
            $tipo = $data['tipo'];
            $situacao = $data['situacao'];
            $idatendente = $data['idatendente'];
            $id_caixa = $data['id_caixa'];
            $id_movimento = $data['id_movimento'];
            $condicoes_pagtos = $data['condicoes_pagtos'];
            $qtde_vendida = $data['qtde_vendida'];
            $cliente = $data['cliente'];

            $observacoes = ($observacoes . " \nCondição de pagto: " . $condicoes_pagtos);
            $resumo_pagto = "Condição de pagto: " . $condicoes_pagtos;

            $vUltimoId = 0;
            $idEmpresa = 1;

            //Verifica o cliente;   
            $CPF = $cliente['cpf'];
            // Remove todos os caracteres que não são números
            $CPF = preg_replace('/\D/', '', $CPF);

            $SQLCliente = "SELECT id FROM cliente WHERE cfpcnpj = '$CPF'";
            $stmt = $this->conexao->prepare($SQLCliente);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();

            if ($count > 0) {
                foreach ($results as $row) {
                    $id_cliente = $row['id'];
                }

                $qry = "UPDATE cliente SET 
                    razaosocial =:p01,
                    nomefantasia =:p02,
                    cfpcnpj =:p03,
                    correio =:p04,
                    telefone1 =:p05,
                    endereco =:p06,
                    numero =:p07,
                    bairro =:p08,
                    cidade =:p09,
                    cep =:p10,
                    uf =:p11                 
                WHERE cfpcnpj =:p12";
                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", $cliente['nome']);
                $stmt->bindValue("p02", $cliente['nome']);
                $stmt->bindValue("p03", $cliente['cpf']);
                $stmt->bindValue("p04", $cliente['email']);
                $stmt->bindValue("p05", $cliente['telefone']);
                $stmt->bindValue("p06", $cliente['endereco']);
                $stmt->bindValue("p07", $cliente['numero']);
                $stmt->bindValue("p08", $cliente['bairro']);
                $stmt->bindValue("p09", $cliente['cidade']);
                $stmt->bindValue("p10", $cliente['cep']);
                $stmt->bindValue("p11", "MT");
                $stmt->bindValue("p12", $cliente['cpf']);
                $stmt->execute();
            } else {
                $qry = "INSERT INTO cliente(
                    razaosocial,
                    nomefantasia,
                    cfpcnpj,
                    correio,
                    telefone1,
                    endereco,
                    numero,
                    bairro,
                    cidade,
                    cep,
                    uf)VALUES(
                        :p01,
                        :p02,
                        :p03,
                        :p04,
                        :p05,
                        :p06,
                        :p07,
                        :p08,
                        :p09,
                        :p10,
                        :p11)";
                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", $cliente['nome']);
                $stmt->bindValue("p02", $cliente['nome']);
                $stmt->bindValue("p03", $cliente['cpf']);
                $stmt->bindValue("p04", $cliente['email']);
                $stmt->bindValue("p05", $cliente['telefone']);
                $stmt->bindValue("p06", $cliente['endereco']);
                $stmt->bindValue("p07", $cliente['numero']);
                $stmt->bindValue("p08", $cliente['bairro']);
                $stmt->bindValue("p09", $cliente['cidade']);
                $stmt->bindValue("p10", $cliente['cep']);
                $stmt->bindValue("p11", "MT");
                $stmt->execute();

                $ultimoID = "SELECT id FROM cliente ORDER BY id DESC LIMIT 1";
                $stmt = $this->conexao->prepare($ultimoID);
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($results as $row) {
                    $id_cliente = $row['id'];
                }
            }
            //Verifica o cliente

            $numeroAleatorio = generateRandomString(15);

            $qry = "INSERT INTO venda (
                idcliente, 
                data, 
                hora, 
                subtotal, 
                desconto, 
                total,                                                                        
                tipo, 
                situacao, 
                idatendente, 
                id_filial, 
                id_usuario,
                idcaixa, 
                id_movimento, 
                observacoes, 
                origem, 
                pedido_web,
                vlpagto, 
                nota, 
                resumo_pagto, 
                data_vencimento, 
                total_custo,
                total_lucro, 
                hora_fechamento, 
                aliqicms, 
                vlbcicms, 
                valoricms,
                aliqipi, 
                vlipi, 
                vlfrete, 
                vlseguro, 
                vloutras, 
                desctotitens,
                troco, 
                aliqicmsst, 
                vlbcicmsst, 
                vlicmsst, 
                idempresa, 
                hash, 
                analise,
                qtde_vendida,
                pagtoparcial) 
                VALUES (
                    :p01, 
                    :p02, 
                    :p03, 
                    :p04, 
                    :p05, 
                    :p06, 
                    :p07, 
                    :p08, 
                    :p09, 
                    :p10, 
                    :p11, 
                    :p12, 
                    :p13, 
                    :p14, 
                    :p15, 
                    :p16, 
                    :p17, 
                    :p18, 
                    :p19, 
                    :p20, 
                    :p21, 
                    :p22, 
                    :p23, 
                    :p24, 
                    :p25, 
                    :p26, 
                    :p27, 
                    :p28, 
                    :p29, 
                    :p30, 
                    :p31, 
                    :p32, 
                    :p33, 
                    :p34, 
                    :p35, 
                    :p36, 
                    :p37, 
                    :p38, 
                    :p39,
                    :p40,
                    :p41)";
            $stmt = $this->conexao->prepare($qry);

            $stmt->bindValue("p01", $id_cliente);
            $stmt->bindValue("p02", $dataAtual);
            $stmt->bindValue("p03", $hora);
            $stmt->bindValue("p04", $subtotal);
            $stmt->bindValue("p05", $desconto);
            $stmt->bindValue("p06", $total);
            $stmt->bindValue("p07", $tipo);
            $stmt->bindValue("p08", $situacao);
            $stmt->bindValue("p09", $idatendente);
            $stmt->bindValue("p10", '999');
            $stmt->bindValue("p11", $id_usuario);
            $stmt->bindValue("p12", $id_caixa);
            $stmt->bindValue("p13", $id_movimento);
            $stmt->bindValue("p14", $observacoes);
            $stmt->bindValue("p15", 'W');
            $stmt->bindValue("p16", 'S');
            $stmt->bindValue("p17", $vlpagto);
            $stmt->bindValue("p18", $nota);
            $stmt->bindValue("p19", $resumo_pagto);
            $stmt->bindValue("p20", $dataAtual);
            $stmt->bindValue("p21", 0);
            $stmt->bindValue("p22", 0);
            $stmt->bindValue("p23", $hora);
            $stmt->bindValue("p24", 0);
            $stmt->bindValue("p25", 0);
            $stmt->bindValue("p26", 0);
            $stmt->bindValue("p27", 0);
            $stmt->bindValue("p28", 0);
            $stmt->bindValue("p29", 0);
            $stmt->bindValue("p30", 0);
            $stmt->bindValue("p31", 0);
            $stmt->bindValue("p32", 0);
            $stmt->bindValue("p33", $troco);
            $stmt->bindValue("p34", 0);
            $stmt->bindValue("p35", 0);
            $stmt->bindValue("p36", 0);
            $stmt->bindValue("p37", $idEmpresa);
            $stmt->bindValue("p38", $numeroAleatorio);
            $stmt->bindValue("p39", 'N');
            $stmt->bindValue("p40", $qtde_vendida);
            $stmt->bindValue("p41", 0);
            $stmt->execute();

            $vUltimoId = "0";
            $ultimoIDSQL = "SELECT id FROM venda ORDER BY id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($ultimoIDSQL);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $vUltimoId = $row['id'];
            }

            foreach ($listaItens as $chave => $valor) {
                //disponível variáveis $chave e $valor
                $pCusto = 0;
                $vTotalCusto = 0;
                $vTotalItem = floatval($valor['estoqueatual'] * $valor['precovenda']);

                $vCodigoItem = $valor['id'];
                $vQtdeItem = $valor['estoqueatual'];

                //Verifica o Custo ok
                $queryProduto = "SELECT id, precovenda, precocusto 
                    FROM produto WHERE id = '$vCodigoItem' LIMIT 1";
                $stmt = $this->conexao->prepare($queryProduto);
                $stmt->execute();
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                foreach ($results as $row) {
                    $pCusto = $row['precocusto'];
                }
                $vTotalCusto = floatval($vQtdeItem) * floatval($pCusto);
                //Verifica o Custo             

                $qry = "INSERT INTO vendaitens (
                    idvenda, 
                    idproduto, 
                    desconto, 
                    vlunitario, 
                    total, 
                    quantidade, 
                    preco_custo,
                    id_atendente, 
                    custo_medio, 
                    observacoes, 
                    descricao_item, 
                    uid, 
                    aliqicms, 
                    vlbcicms, 
                    vlicms, 
                    aliqipi, 
                    vlipi, 
                    vlfrete, 
                    vlseguro, 
                    vloutras, 
                    aliqicmsst, 
                    vlbcicmsst, 
                    vlicmsst, 
                    descvaloritens, 
                    largura, 
                    altura, 
                    producao, 
                    precovenda_cadastro,                 
                    xped)VALUES (
                        :p01, 
                        :p02, 
                        :p03, 
                        :p04, 
                        :p05, 
                        :p06, 
                        :p07, 
                        :p08, 
                        :p09, 
                        :p10, 
                        :p11, 
                        :p12, 
                        :p13, 
                        :p14, 
                        :p15, 
                        :p16, 
                        :p17, 
                        :p18, 
                        :p19, 
                        :p20, 
                        :p21, 
                        :p22, 
                        :p23, 
                        :p24, 
                        :p25, 
                        :p26, 
                        :p27, 
                        :p28, 
                        :p29)";
                $stmt = $this->conexao->prepare($qry);

                $stmt->bindValue("p01", $vUltimoId);
                $stmt->bindValue("p02", $vCodigoItem);
                $stmt->bindValue("p03", 0);
                $stmt->bindValue("p04", $valor['precovenda']);
                $stmt->bindValue("p05", $vTotalItem);
                $stmt->bindValue("p06", $valor['estoqueatual']);
                $stmt->bindValue("p07", $vTotalCusto);
                $stmt->bindValue("p08", $idatendente);
                $stmt->bindValue("p09", $pCusto);
                $stmt->bindValue("p10", 'WEB');
                $stmt->bindValue("p11", $valor['descricao']);
                $stmt->bindValue("p12", $numeroAleatorio);
                $stmt->bindValue("p13", 0);
                $stmt->bindValue("p14", 0);
                $stmt->bindValue("p15", 0);
                $stmt->bindValue("p16", 0);
                $stmt->bindValue("p17", 0);
                $stmt->bindValue("p18", 0);
                $stmt->bindValue("p19", 0);
                $stmt->bindValue("p20", 0);
                $stmt->bindValue("p21", 0);
                $stmt->bindValue("p22", 0);
                $stmt->bindValue("p23", 0);
                $stmt->bindValue("p24", 0);
                $stmt->bindValue("p25", 0);
                $stmt->bindValue("p26", 0);
                $stmt->bindValue("p27", 'N');
                $stmt->bindValue("p28", $valor['precovenda']);
                $stmt->bindValue("p29", '');

                $stmt->execute();

                if ($tipo == "V") {
                    $this->processaEstoque($valor['codigo'], $valor['quantidade'], 'S');
                }
            }
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
                    'url' => 'api/usuario'
                )
            )
        );

        return $response;
    }

    function printVenda($dados)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        $dtInicial = $dados['dtInicial'];
        $dtFinal = $dados['dtFinal'];
        $id_funcionario = $dados['id_funcionario'];
        $id_cliente = $dados['id_cliente'];
        $situacao = $dados['situacao'];

        $SQLAtentendente = "";
        if ($id_funcionario != "0") {
            $SQLAtentendente = "AND v.idatendente = '$id_funcionario'";
        }

        $SQLCliente = "";
        if ($id_cliente != "0") {
            $SQLCliente = "AND v.idcliente = '$id_cliente'";
        }

        $SQLSituacao = "";
        if ($situacao != "0") {
            $SQLSituacao = "AND v.situacao = '$situacao'";
        }

        try {
            $qry = "SELECT v.id, v.data, v.idatendente, v.id_usuario, CASE v.idcliente 
                WHEN '0' THEN 'CONSUMIDOR' WHEN '' THEN 'CONSUMIDOR' 
                ELSE c.nomefantasia END AS nomefantasia, f.nome AS vendedor,
                c.correio, c.telefone1, v.subtotal, v.desconto, v.total, v.situacao
                FROM venda v
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                LEFT JOIN funcionario f ON (v.idatendente = f.id)
                WHERE v.venda_ativa = 'S' AND v.data >= '$dtInicial' 
                AND v.data <= '$dtFinal' $SQLCliente $SQLAtentendente $SQLSituacao 
                ORDER BY v.data";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data[] = [
                        "id" => $row['id'],
                        "data" => $row['data'],
                        "nomefantasia" => $row['nomefantasia'],
                        "correio" => $row['correio'],
                        "telefone1" => $row['telefone1'],
                        "subtotal" => $row['subtotal'],
                        "desconto" => $row['desconto'],
                        "total" => $row['total'],
                        "situacao" => $row['situacao'],
                        "vendedor" => $row['vendedor'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/vendas/print/",
                        ]
                    ];
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "vendas" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "vendas" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "vendas" => $data,
        ];
    }
}
