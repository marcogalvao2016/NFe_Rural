<?php

namespace src\models;

use \core\Model;

class ProdutoModel extends Model
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
            $qry = "SELECT p.id, p.ean, p.reffabricante, p.id_marca,
                p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                FROM produto p 
                LEFT JOIN grupo g ON (p.idgrupo = g.id)
                LEFT JOIN ncm n ON (p.idncm = n.id) 
                LEFT JOIN marca m ON (p.id_marca = m.id)
                WHERE p.tablet = 'S'         
                ORDER BY p.descricao";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/listar",
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

    function buscarPorTabela($id_tabela)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        try {
            if ($id_tabela == "0") {
                $qry = "SELECT p.id, p.id as id_produto, p.ean, p.reffabricante, p.id_marca,
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'         
                    ORDER BY p.id DESC LIMIT 30";
            } else {
                $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                    p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                    p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                    p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                    FROM produto_tabela pt
                    LEFT JOIN produto p ON (pt.id_produto = p.id) 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE pt.id_tabela_preco = '$id_tabela' AND
                    p.tablet = 'S' AND pt.preco > 0 
                    ORDER BY p.id DESC LIMIT 30";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id_produto'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/listar",
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

    function ordenarPorTabela($id_tabela, $tipo)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        try {
            if ($id_tabela == "0") {
                if ($tipo == "C") {
                    $qry = "SELECT p.id, p.ean, p.reffabricante, p.nome_foto, p.id_marca,
                        p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                        g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                        p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                        FROM produto p 
                        LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE p.tablet = 'S'         
                        ORDER BY p.precovenda ASC";
                } else if ($tipo == "D") {
                    $qry = "SELECT p.id, p.ean, p.reffabricante, p.nome_foto, p.id_marca,
                        p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                        g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                        p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                        FROM produto p 
                        LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE p.tablet = 'S'         
                        ORDER BY p.precovenda DESC";
                } else {
                    $qry = "SELECT p.id, p.ean, p.reffabricante, p.nome_foto, p.id_marca,
                        p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                        g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                        p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                        FROM produto p 
                        LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE p.tablet = 'S'         
                        ORDER BY p.descricao DESC LIMIT 30";
                }
            } else {
                if ($tipo == "C") {
                    $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                        p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                        p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                        p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                        FROM produto_tabela pt
                        LEFT JOIN produto p ON (pt.id_produto = p.id) LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE pt.id_tabela_preco = '$id_tabela' AND
                        p.tablet = 'S' AND pt.preco > 0 ORDER BY pt.preco ASC";
                } else if ($tipo == "D") {
                    $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                        p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                        p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                        p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                        FROM produto_tabela pt
                        LEFT JOIN produto p ON (pt.id_produto = p.id) LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE pt.id_tabela_preco = '$id_tabela' AND
                        p.tablet = 'S' AND pt.preco > 0 ORDER BY pt.preco DESC";
                } else {
                    $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                        p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                        p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                        p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                        FROM produto_tabela pt
                        LEFT JOIN produto p ON (pt.id_produto = p.id) LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE pt.id_tabela_preco = '$id_tabela' AND
                        p.tablet = 'S' AND pt.preco > 0 p.descricao DESC LIMIT 30";
                }
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/listar",
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

    function ordenarPorDescricao($id_tabela, $tipo)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        try {
            if ($id_tabela == "0") {
                if ($tipo == "A-Z") {
                    $qry = "SELECT p.id, p.ean, p.reffabricante, p.nome_foto, p.id_marca,
                        p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                        g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                        p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                        FROM produto p 
                        LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE p.tablet = 'S'         
                        ORDER BY p.descricao ASC";
                } else if ($tipo == "Z-A") {
                    $qry = "SELECT p.id, p.ean, p.reffabricante, p.nome_foto, p.id_marca,
                        p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                        g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                        p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                        FROM produto p 
                        LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE p.tablet = 'S'         
                        ORDER BY p.descricao DESC";
                } else {
                    $qry = "SELECT p.id, p.ean, p.reffabricante, p.nome_foto, p.id_marca,
                        p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                        g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                        p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                        FROM produto p 
                        LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE p.tablet = 'S'         
                        ORDER BY p.descricao DESC LIMIT 30";
                }
            } else {
                if ($tipo == "A-Z") {
                    $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                        p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                        p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                        p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                        FROM produto_tabela pt
                        LEFT JOIN produto p ON (pt.id_produto = p.id) LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE pt.id_tabela_preco = '$id_tabela' AND
                        p.tablet = 'S' AND pt.preco > 0 ORDER BY p.descricao ASC";
                } else if ($tipo == "Z-A") {
                    $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                        p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                        p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                        p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                        FROM produto_tabela pt
                        LEFT JOIN produto p ON (pt.id_produto = p.id) LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE pt.id_tabela_preco = '$id_tabela' AND
                        p.tablet = 'S' AND pt.preco > 0 ORDER BY p.descricao DESC";
                } else {
                    $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                        p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                        p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                        p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                        FROM produto_tabela pt
                        LEFT JOIN produto p ON (pt.id_produto = p.id) LEFT JOIN grupo g ON (p.idgrupo = g.id)
                        LEFT JOIN ncm n ON (p.idncm = n.id) 
                        LEFT JOIN marca m ON (p.id_marca = m.id)
                        WHERE pt.id_tabela_preco = '$id_tabela' AND
                        p.tablet = 'S' AND pt.preco > 0 p.descricao DESC LIMIT 30";
                }
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/listar",
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

    function buscarPorGrupo($id_grupo, $id_tabela)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        try {
            if ($id_tabela == "0") {
                $qry = "SELECT p.id as id_produto, p.ean, p.reffabricante, p.id_marca,
                p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                FROM produto p 
                LEFT JOIN grupo g ON (p.idgrupo = g.id)
                LEFT JOIN ncm n ON (p.idncm = n.id)
                LEFT JOIN marca m ON (p.id_marca = m.id)
                WHERE p.tablet = 'S'
                AND p.idgrupo = '$id_grupo' 
                ORDER BY p.descricao LIMIT 1000";
            } else {
                $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                    p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                    p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                    p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                    FROM produto_tabela pt
                    LEFT JOIN produto p ON (pt.id_produto = p.id) 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE pt.id_tabela_preco = '$id_tabela' 
                    AND p.idgrupo = '$id_grupo' AND p.tablet = 'S' AND pt.preco > 0 
                    ORDER BY p.descricao LIMIT 1000";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id_produto'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/listar",
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

    function buscarPorSubGrupo($id_subgrupo, $id_tabela)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        try {
            if ($id_tabela == "0") {
                $qry = "SELECT p.id, p.ean, p.reffabricante, p.id_marca,
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id)
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'
                    AND p.id_subgrupo = '$id_subgrupo' 
                    ORDER BY p.descricao LIMIT 5000";
            } else {
                $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                    p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                    p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                    p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                    FROM produto_tabela pt
                    LEFT JOIN produto p ON (pt.id_produto = p.id) 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE pt.id_tabela_preco = '$id_tabela' 
                    AND p.id_subgrupo = '$id_subgrupo' AND p.tablet = 'S' 
                    AND pt.preco > 0 
                    ORDER BY p.descricao LIMIT 1000";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/listar",
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

    function buscarPorMarca($id_marca, $id_tabela)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        try {
            if ($id_tabela == "0") {
                $qry = "SELECT p.id as id_produto, p.ean, p.reffabricante, p.id_marca,
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'
                    AND p.id_marca = '$id_marca' 
                    ORDER BY p.descricao LIMIT 1000";
            } else {
                $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                    p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                    p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                    p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                    FROM produto_tabela pt
                    LEFT JOIN produto p ON (pt.id_produto = p.id) 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE pt.id_tabela_preco = '$id_tabela' 
                    AND p.id_marca = '$id_marca' AND p.tablet = 'S' AND pt.preco > 0 
                    ORDER BY p.descricao LIMIT 1000";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id_produto'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/listar",
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

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT p.id, p.ean, p.reffabricante, p.id_marca,
                p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                FROM produto p 
                LEFT JOIN grupo g ON (p.idgrupo = g.id)
                LEFT JOIN ncm n ON (p.idncm = n.id) 
                LEFT JOIN marca m ON (p.id_marca = m.id)
                WHERE p.tablet = 'S'                                         
                ORDER BY p.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
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

    function pesquisar($texto, $id_tabela)
    {
        $data = [];
        $qry = "";

        try {
            if ($id_tabela === "0") {
                $qry = "SELECT p.id as id_produto, p.ean, p.reffabricante, p.id_marca,
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id)
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'                         
                    AND p.descricao LIKE '%$texto%'  
                    ORDER BY p.descricao LIMIT 500";
            } else {
                $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.id_marca,
                    p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, p.id_marca,
                    p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                    p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto_tabela pt
                    LEFT JOIN produto p ON (pt.id_produto = p.id) LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.descricao LIKE '%$texto%'  
                    AND p.tablet = 'S' AND pt.preco > 0 AND pt.id_tabela_preco = '$id_tabela' 
                    ORDER BY p.descricao LIMIT 500";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id_produto'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/search/{texto}/{id_tabela}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
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

    function pesquisarEAN($ean, $id_tabela)
    {
        $data = [];
        $qry = "";

        try {
            if ($id_tabela === "0") {
                $qry = "SELECT p.id as id_produto, p.ean, p.reffabricante, p.id_marca,
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id)
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'                         
                    AND p.ean LIKE '%$ean%' 
                    ORDER BY p.descricao LIMIT 30";
            } else {
                $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, 
                    p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, p.id_marca,
                    p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                    p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto_tabela pt
                    LEFT JOIN produto p ON (pt.id_produto = p.id) LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.ean LIKE '%$ean%'  
                    AND p.tablet = 'S' AND pt.preco > 0 AND pt.id_tabela_preco = '$id_tabela' 
                    ORDER BY p.descricao LIMIT 30";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id_produto'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/search/{texto}/{id_tabela}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
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

    function consultaPDVCliente($texto, $id_tabela, $id_grupo, $id_marca)
    {
        $data = [];
        $qry = "";

        $SQLConsGrupo = "";
        if ($id_grupo != "0") {
            $SQLConsGrupo = " AND p.idgrupo = '$id_grupo'";
        }

        $SQLConsMarca = "";
        if ($id_marca != "0") {
            $SQLConsMarca = " AND p.id_marca = '$id_marca'";
        }

        try {
            if ($id_tabela === "0") {
                $qry = "SELECT p.id as id_produto, p.ean, p.reffabricante, p.id_marca,
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'                         
                    AND p.descricao LIKE '%$texto%' $SQLConsGrupo $SQLConsMarca
                    ORDER BY p.descricao LIMIT 500";
            } else {
                $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.id_marca,
                    p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, p.id_marca,
                    p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                    p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto_tabela pt
                    LEFT JOIN produto p ON (pt.id_produto = p.id) 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.descricao LIKE '%$texto%'  
                    AND p.tablet = 'S' AND pt.preco > 0 AND pt.id_tabela_preco = '$id_tabela'
                    $SQLConsGrupo $SQLConsMarca 
                    ORDER BY p.descricao LIMIT 500";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id_produto'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/search/{texto}/{id_tabela}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
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

    function view($id, $id_tabela)
    {
        $retorno = true;
        $data = "";
        $qry = "";
        http_response_code(200);

        try {
            if ($id_tabela == "0") {
                $qry = "SELECT p.id, p.id as id_produto, p.ean, p.reffabricante, p.id_marca,
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'                         
                    AND p.id = '$id' ORDER BY p.id DESC LIMIT 1";
            } else {
                $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.id_marca,
                    p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                    p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, 
                    p.observacoes, p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, 
                    p.nome_foto, m.descricao as marca FROM produto_tabela pt 
                    LEFT JOIN produto p ON (pt.id_produto = p.id) 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE pt.id_produto = '$id' 
                    AND p.tablet = 'S' AND pt.preco > 0 
                    AND pt.id_tabela_preco = '$id_tabela' 
                    ORDER BY p.id DESC LIMIT 30";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "descricao" => removeCaracterEspecial($row['descricao']),
                        "ean" => $row['ean'],
                        "reffabricante" => $row['reffabricante'],
                        "precocusto" => $row['precocusto'],
                        "precovenda" => $row['precovenda'],
                        "estoqueatual" => $row['estoqueatual'],
                        "idgrupo" => $row['idgrupo'],
                        "grupo" => $row['grupo'],
                        "idncm" => $row['idncm'],
                        "unidsaida" => $row['unidsaida'],
                        "observacoes" => $row['observacoes'],
                        "tipo_produto" => $row['tipo_produto'],
                        "id_subgrupo" => $row['id_subgrupo'],
                        "id_marca" => $row['id_marca'],
                        "ncm" => $row['ncm'],
                        "url" => $row['url'],
                        "nome_foto" => $row['nome_foto'],
                        "precotebela" => $row['precovenda'],
                        "marca" => $row['marca'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/produto/{id}/{id_tabela}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "produto" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "produto" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "produto" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM produto WHERE id = '$id'";
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
                'url' => 'api/produto',
                'body' => array(
                    'type' => 'DELETE',
                    'descricao' => 'String',
                )
            )
        );

        return $response;
    }

    function alterar($data, $id_tabela)
    {
        $response = "";
        $qry = "";
        http_response_code(200);

        $id = $data['id'];
        $descricao = $data['descricao'];
        $ean = $data['ean'];
        $reffabricante = $data['reffabricante'];
        $precocusto = $data['precocusto'];
        $precovenda = $data['precovenda'];
        $estoqueatual = $data['estoqueatual'];
        $idgrupo = $data['idgrupo'];
        $idncm = $data['idncm'];
        $unidsaida = $data['unidsaida'];
        $observacoes = $data['observacoes'];
        $tipo_produto = $data['tipo_produto'];
        $id_subgrupo = $data['id_subgrupo'];
        $url = $data['url'];
        $id_marca = intval($data['id_marca']);
        $nome_foto = $data['nome_foto'];
        $temIMG = $data['temIMG'];

        if ($temIMG == 'N') {
            $SQLProduto = "SELECT id, nome_foto FROM produto WHERE id = '$id'";
            $stmt = $this->conexao->prepare($SQLProduto);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $nome_foto = $row['nome_foto'];
            }
        }

        try {
            if ($id_tabela == "0") {
                $qry = "UPDATE produto
                SET descricao      =:p01,
                     ean           =:p02,
                     reffabricante =:p03,
                     precocusto    =:p04,
                     precovenda    =:p05,
                     estoqueatual  =:p06,
                     idgrupo       =:p07,
                     idncm         =:p08,
                     unidentrada   =:p09,
                     unidsaida     =:p10,
                     observacoes   =:p11,
                     tipo_produto  =:p12,
                     id_subgrupo   =:p13,
                     url           =:p14,
                     id_marca      =:p15,
                     nome_foto     =:p16
                 WHERE id          =:p17";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", $descricao);
                $stmt->bindValue("p02", $ean);
                $stmt->bindValue("p03", $reffabricante);
                $stmt->bindValue("p04", $precocusto);
                $stmt->bindValue("p05", $precovenda);
                $stmt->bindValue("p06", $estoqueatual);
                $stmt->bindValue("p07", $idgrupo);
                $stmt->bindValue("p08", $idncm);
                $stmt->bindValue("p09", $unidsaida);
                $stmt->bindValue("p10", $unidsaida);
                $stmt->bindValue("p11", $observacoes);
                $stmt->bindValue("p12", $tipo_produto);
                $stmt->bindValue("p13", $id_subgrupo);
                $stmt->bindValue("p14", $url);
                $stmt->bindValue("p15", $id_marca);
                $stmt->bindValue("p16", $nome_foto);
                $stmt->bindValue("p17", $id);
                $stmt->execute();
            } else {
                $qry = "UPDATE produto
                SET descricao      =:p01,
                     ean           =:p02,
                     reffabricante =:p03,
                     precocusto    =:p04,                   
                     estoqueatual  =:p05,
                     idgrupo       =:p06,
                     idncm         =:p07,
                     unidentrada   =:p09,
                     unidsaida     =:p09,
                     observacoes   =:p10,
                     tipo_produto  =:p11,
                     id_subgrupo   =:p12,
                     url           =:p13,
                     id_marca      =:p14,
                     nome_foto     =:p15
                 WHERE id          =:p15";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", $descricao);
                $stmt->bindValue("p02", $ean);
                $stmt->bindValue("p03", $reffabricante);
                $stmt->bindValue("p04", $precocusto);
                $stmt->bindValue("p05", $estoqueatual);
                $stmt->bindValue("p06", $idgrupo);
                $stmt->bindValue("p07", $idncm);
                $stmt->bindValue("p09", $unidsaida);
                $stmt->bindValue("p09", $unidsaida);
                $stmt->bindValue("p10", $observacoes);
                $stmt->bindValue("p11", $tipo_produto);
                $stmt->bindValue("p12", $id_subgrupo);
                $stmt->bindValue("p13", $url);
                $stmt->bindValue("p14", $id_marca);
                $stmt->bindValue("p15", $nome_foto);
                $stmt->bindValue("p16", $id);
                $stmt->execute();

                $qry = "UPDATE produto_tabela
                    SET preco =:p01                     
                    WHERE id_produto =:p02 
                    AND id_tabela_preco =:P03";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", $precovenda);
                $stmt->bindValue("p02", $id);
                $stmt->bindValue("p03", $id_tabela);
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
                    'url' => 'api/produto/' . $id
                )
            )
        );

        return $response;
    }

    function inserir($data, $id_tabela)
    {
        $response = "";
        http_response_code(200);

        $dataAtual = date("Y-m-d");

        $descricao = $data['descricao'];
        $ean = $data['ean'];
        $reffabricante = $data['reffabricante'];
        $precocusto = $data['precocusto'];
        $precovenda = $data['precovenda'];
        $estoqueatual = $data['estoqueatual'];
        $idgrupo = $data['idgrupo'];
        $idncm = $data['idncm'];
        $unidsaida = $data['unidsaida'];
        $observacoes = $data['observacoes'];
        $tipo_produto = $data['tipo_produto'];
        $id_subgrupo = $data['id_subgrupo'];
        $url = $data['url'];
        $id_marca = intval($data['id_marca']);
        $nome_foto = $data['nome_foto'];

        try {
            if ($id_tabela == "0") {
                $qry = "INSERT INTO produto(
                     descricao,
                     ean,
                     reffabricante,
                     precocusto,
                     precovenda,
                     estoqueatual,
                     idgrupo,
                     idncm,
                     unidentrada,
                     unidsaida,
                     observacoes,
                     tipo_produto,
                     id_subgrupo,
                     url,
                     nome_foto,
                     id_marca,
                     cadastro,
                     depreciado,
                     estoqueminimo,
                     pontopedido,
                     fabricacao,
                     validade,
                     imobilizado,
                     fator,
                     idempresa,
                     inativo,
                     bloqueado,
                     controlaestoque,
                     producao,
                     mva,
                     impressao,
                     id_similar,
                     per_lucro,
                     total_custo_valor,
                     total_custo_qtde,
                     frete,
                     outras_despesas,
                     micra,
                     largura,
                     altura,
                     webfacil,
                     cardapio,
                     per_comissao,
                     vende_tabela,
                     qtde_bonificacao,
                     volume,
                     tablet,
                     descricaonfe,
                     cst,
                     icms,
                     ipi,
                     icmsst,
                     pis,
                     cofins,
                     dadoscomposicao,
                     foto,
                     per_desconto,
                     custo_medio,
                     custo_real,
                     ultima_compra,
                     cest,
                     data_alteracao)VALUES(
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
                        :p41,
                        :p42,
                        :p43,
                        :p44,
                        :p45,
                        :p46,
                        :p47,
                        :p48,
                        :p49,
                        :p50,
                        :p51,
                        :p52,
                        :p53,
                        :p54,
                        :p55,
                        :p56,
                        :p57,
                        :p58,
                        :p59,
                        :p60,
                        :p61,
                        :p62)";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", $descricao);
                $stmt->bindValue("p02", $ean);
                $stmt->bindValue("p03", $reffabricante);
                $stmt->bindValue("p04", $precocusto);
                $stmt->bindValue("p05", $precovenda);
                $stmt->bindValue("p06", $estoqueatual);
                $stmt->bindValue("p07", $idgrupo);
                $stmt->bindValue("p08", $idncm);
                $stmt->bindValue("p09", $unidsaida);
                $stmt->bindValue("p10", $unidsaida);
                $stmt->bindValue("p11", $observacoes);
                $stmt->bindValue("p12", $tipo_produto);
                $stmt->bindValue("p13", $id_subgrupo);
                $stmt->bindValue("p14", $url);
                $stmt->bindValue("p15", $nome_foto);
                $stmt->bindValue("p16", $id_marca);
                $stmt->bindValue("p17", $dataAtual);
                $stmt->bindValue("p18", 'SIM');
                $stmt->bindValue("p19", 0);
                $stmt->bindValue("p20", 0);
                $stmt->bindValue("p21", $dataAtual);
                $stmt->bindValue("p22", $dataAtual);
                $stmt->bindValue("p23", 'N');
                $stmt->bindValue("p24", 0);
                $stmt->bindValue("p25", 1);
                $stmt->bindValue("p26", 'N');
                $stmt->bindValue("p27", 'N');
                $stmt->bindValue("p28", 'S');
                $stmt->bindValue("p29", 'S');
                $stmt->bindValue("p30", 0);
                $stmt->bindValue("p31", 0);
                $stmt->bindValue("p32", 0);
                $stmt->bindValue("p33", 0);
                $stmt->bindValue("p34", 0);
                $stmt->bindValue("p35", 0);
                $stmt->bindValue("p36", 0);
                $stmt->bindValue("p37", 0);
                $stmt->bindValue("p38", 0);
                $stmt->bindValue("p39", 0);
                $stmt->bindValue("p40", 0);
                $stmt->bindValue("p41", 'N');
                $stmt->bindValue("p42", 'N');
                $stmt->bindValue("p43", 0);
                $stmt->bindValue("p44", 0);
                $stmt->bindValue("p45", 0);
                $stmt->bindValue("p46", 0);
                $stmt->bindValue("p47", 'S');
                $stmt->bindValue("p48", $descricao);
                $stmt->bindValue("p49", 0);
                $stmt->bindValue("p50", 0);
                $stmt->bindValue("p51", 0);
                $stmt->bindValue("p52", 0);
                $stmt->bindValue("p53", 0);
                $stmt->bindValue("p54", 0);
                $stmt->bindValue("p55", '');
                $stmt->bindValue("p56", '');
                $stmt->bindValue("p57", 0);
                $stmt->bindValue("p58", 0);
                $stmt->bindValue("p59", 0);
                $stmt->bindValue("p60", $dataAtual);
                $stmt->bindValue("p61", 0);
                $stmt->bindValue("p62", $dataAtual);
                $stmt->execute();
            } else {
                $qry = "INSERT INTO produto(
                     descricao,
                     ean,
                     reffabricante,
                     precocusto,
                     precovenda,
                     estoqueatual,
                     idgrupo,
                     idncm,
                     unidentrada,
                     unidsaida,
                     observacoes,
                     tipo_produto,
                     id_subgrupo,
                     url,
                     nome_foto,
                     id_marca,
                     cadastro,
                     depreciado,
                     estoqueminimo,
                     pontopedido,
                     fabricacao,
                     validade,
                     imobilizado,
                     fator,
                     idempresa,
                     inativo,
                     bloqueado,
                     controlaestoque,
                     producao,
                     mva,
                     impressao,
                     id_similar,
                     per_lucro,
                     total_custo_valor,
                     total_custo_qtde,
                     frete,
                     outras_despesas,
                     micra,
                     largura,
                     altura,
                     webfacil,
                     cardapio,
                     per_comissao,
                     vende_tabela,
                     qtde_bonificacao,
                     volume,
                     cst,
                     icms,
                     ipi,
                     icmsst,
                     pis,
                     cofins,
                     dadoscomposicao,
                     foto,
                     per_desconto,
                     custo_medio,
                     custo_real,
                     ultima_compra,
                     cest,
                     data_alteracao)VALUES(
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
                        :p41,
                        :p42,
                        :p43,
                        :p44,
                        :p45,
                        :p46,
                        :p47,
                        :p48,
                        :p49,
                        :p50,
                        :p51,
                        :p52,
                        :p53,
                        :p54,
                        :p55,
                        :p56,
                        :p57,
                        :p58,
                        :p59,
                        :p60)";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", $descricao);
                $stmt->bindValue("p02", $ean);
                $stmt->bindValue("p03", $reffabricante);
                $stmt->bindValue("p04", $precocusto);
                $stmt->bindValue("p05", $precovenda);
                $stmt->bindValue("p06", $estoqueatual);
                $stmt->bindValue("p07", $idgrupo);
                $stmt->bindValue("p08", $idncm);
                $stmt->bindValue("p09", $unidsaida);
                $stmt->bindValue("p10", $unidsaida);
                $stmt->bindValue("p11", $observacoes);
                $stmt->bindValue("p12", $tipo_produto);
                $stmt->bindValue("p13", $id_subgrupo);
                $stmt->bindValue("p14", $url);
                $stmt->bindValue("p15", $nome_foto);
                $stmt->bindValue("p16", $id_marca);
                $stmt->bindValue("p17", $dataAtual);
                $stmt->bindValue("p18", 'SIM');
                $stmt->bindValue("p19", 0);
                $stmt->bindValue("p20", 0);
                $stmt->bindValue("p21", $dataAtual);
                $stmt->bindValue("p22", $dataAtual);
                $stmt->bindValue("p23", 'N');
                $stmt->bindValue("p24", 0);
                $stmt->bindValue("p25", 1);
                $stmt->bindValue("p26", 'N');
                $stmt->bindValue("p27", 'N');
                $stmt->bindValue("p28", 'S');
                $stmt->bindValue("p29", 'S');
                $stmt->bindValue("p30", 0);
                $stmt->bindValue("p31", 0);
                $stmt->bindValue("p32", 0);
                $stmt->bindValue("p33", 0);
                $stmt->bindValue("p34", 0);
                $stmt->bindValue("p35", 0);
                $stmt->bindValue("p36", 0);
                $stmt->bindValue("p37", 0);
                $stmt->bindValue("p38", 0);
                $stmt->bindValue("p39", 0);
                $stmt->bindValue("p40", 0);
                $stmt->bindValue("p41", 'N');
                $stmt->bindValue("p42", 'N');
                $stmt->bindValue("p43", 0);
                $stmt->bindValue("p44", 0);
                $stmt->bindValue("p45", 0);
                $stmt->bindValue("p46", 0);
                $stmt->bindValue("p47", 0);
                $stmt->bindValue("p48", 0);
                $stmt->bindValue("p49", 0);
                $stmt->bindValue("p50", 0);
                $stmt->bindValue("p51", 0);
                $stmt->bindValue("p52", 0);
                $stmt->bindValue("p53", '');
                $stmt->bindValue("p54", '');
                $stmt->bindValue("p55", 0);
                $stmt->bindValue("p56", 0);
                $stmt->bindValue("p57", 0);
                $stmt->bindValue("p58", $dataAtual);
                $stmt->bindValue("p59", 0);
                $stmt->bindValue("p60", $dataAtual);
                $stmt->execute();

                $vIdUltimoProduto = "0";
                $ultimoID = "SELECT id FROM produto ORDER BY id DESC LIMIT 1";
                $stmt = $this->conexao->prepare($ultimoID);
                $stmt->execute();

                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $count = $stmt->rowCount();

                foreach ($results as $row) {
                    $vIdUltimoProduto = $row['id'];
                }

                $qry = "INSERT INTO produto_tabela(
                            preco, 
                            id_tabela_preco, 
                            id_produto)VALUES(
                                :p01, 
                                :p02, 
                                :p03)";

                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", $precovenda);
                $stmt->bindValue("p02", $id_tabela);
                $stmt->bindValue("p03", $vIdUltimoProduto);
                $stmt->execute();
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
                    'url' => 'api/produtos'
                )
            )
        );

        return $response;
    }

    function contaTotalProdutos()
    {
        $data = [];
        $vTotalConta = 0;
        http_response_code(200);

        try {
            $qry = "SELECT COUNT(*) AS totalproduto FROM produto";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "total" => $row['totalproduto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/totalprodutos",
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

    function buscarPorGrupoPizza($id_grupo, $id_subgrupo)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        try {
            $qry = "SELECT p.id, p.ean, p.reffabricante, 
                p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                FROM produto p 
                LEFT JOIN grupo g ON (p.idgrupo = g.id)
                LEFT JOIN ncm n ON (p.idncm = n.id) 
                LEFT JOIN marca m ON (p.id_marca = m.id)
                WHERE p.tablet = 'S'  
                AND p.idgrupo = '$id_grupo' AND p.id_subgrupo = '$id_subgrupo' 
                GROUP BY p.descricao";

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/grupopizza/{id_grupo}/{id_subgrupo}",
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

    function pesquisarGrupo($texto, $id_grupo)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        try {
            if ($id_grupo != '0') {
                $qry = "SELECT p.id, p.ean, p.reffabricante, 
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'  
                    AND p.idgrupo = '$id_grupo' 
                    AND p.descricao LIKE '%$texto%' GROUP BY p.id";
            } else {
                $qry = "SELECT p.id, p.ean, p.reffabricante, 
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'             
                    AND p.descricao LIKE '%$texto%' GROUP BY p.id";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/searchgrupo/{texto}/{id_grupo}",
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

    function pesquisarGrupoPizza($texto, $id_grupo, $id_subgrupo)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        try {
            $qry = "SELECT p.id, p.ean, p.reffabricante, 
                p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                FROM produto p 
                LEFT JOIN grupo g ON (p.idgrupo = g.id)
                LEFT JOIN ncm n ON (p.idncm = n.id) 
                LEFT JOIN marca m ON (p.id_marca = m.id)
                WHERE p.tablet = 'S'  
                AND p.idgrupo = '$id_grupo' AND p.id_subgrupo = '$id_subgrupo' 
                AND p.descricao LIKE '%$texto%' GROUP BY p.id";

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/searchgrupo/{texto}/{id_grupo}",
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

    function listarPorPaginacaoTabela($id_tabela, $pagina, $limite)
    {
        $data = [];
        $qry = "";
        http_response_code(200);

        // Garantir que a página seja sempre 1 ou mais
        $page = max(1, intval($pagina));
        $limit = max(1, intval($limite));
        $offset = ($page - 1) * $limit; // Cálculo do offset corrigido

        try {
            // Contar o total de registros
            $countQry = "SELECT COUNT(*) as total FROM produto WHERE tablet = 'S'";
            $countStmt = $this->conexao->prepare($countQry);
            $countStmt->execute();
            $totalResults = $countStmt->fetch(\PDO::FETCH_ASSOC)['total'];

            if ($id_tabela == "0") {
                $qry = "SELECT p.id, p.id as id_produto, p.ean, p.reffabricante, p.id_marca,
                    p.descricao, p.precocusto, p.precovenda, p.estoqueatual, p.idgrupo, 
                    g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, p.tipo_produto,
                    p.id_subgrupo, n.idncm as ncm, p.url, p.nome_foto, m.descricao as marca 
                    FROM produto p 
                    LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE p.tablet = 'S'         
                    ORDER BY p.id DESC LIMIT $limit OFFSET $offset";
            } else {
                $qry = "SELECT pt.id, pt.id_tabela_preco, pt.id_produto, p.nome_foto, p.id_marca,
                    p.ean, p.reffabricante, p.descricao, p.precocusto, pt.preco AS precovenda, 
                    p.estoqueatual, p.idgrupo, g.descricao as grupo, p.idncm, p.unidsaida, p.observacoes, 
                    p.tipo_produto, p.id_subgrupo, n.idncm as ncm, p.url, m.descricao as marca 
                    FROM produto_tabela pt
                    LEFT JOIN produto p ON (pt.id_produto = p.id) LEFT JOIN grupo g ON (p.idgrupo = g.id)
                    LEFT JOIN ncm n ON (p.idncm = n.id) 
                    LEFT JOIN marca m ON (p.id_marca = m.id)
                    WHERE pt.id_tabela_preco = '$id_tabela' 
                    AND p.tablet = 'S' AND pt.preco > 0 
                    ORDER BY p.id DESC LIMIT $limit OFFSET $offset";
            }

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id_produto'],
                    "descricao" => removeCaracterEspecial($row['descricao']),
                    "ean" => $row['ean'],
                    "reffabricante" => $row['reffabricante'],
                    "precocusto" => $row['precocusto'],
                    "precovenda" => $row['precovenda'],
                    "estoqueatual" => $row['estoqueatual'],
                    "idgrupo" => $row['idgrupo'],
                    "grupo" => $row['grupo'],
                    "idncm" => $row['idncm'],
                    "unidsaida" => $row['unidsaida'],
                    "observacoes" => $row['observacoes'],
                    "tipo_produto" => $row['tipo_produto'],
                    "id_subgrupo" => $row['id_subgrupo'],
                    "id_marca" => $row['id_marca'],
                    "ncm" => $row['ncm'],
                    "url" => $row['url'],
                    "nome_foto" => $row['nome_foto'],
                    "precotebela" => $row['precovenda'],
                    "marca" => $row['marca'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/produtos/listar",
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
            "totalQt" => $totalResults,
            "page" => $page,
            "limit" => $limit,
            "produtos" => $data,
        ];
    }
}
