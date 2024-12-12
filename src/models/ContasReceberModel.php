<?php

namespace src\models;

use \core\Model;

class ContasReceberModel extends Model
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
            $qry = "SELECT lc.id, lc.idusuario, lc.idcaixa, lc.dataemissao,
                lc.tipomovimento, lc.ndoc, lc.valor, lc.cadastro, lc.observacoes, 
                lc.parcela, lc.id_origem, lc.sigla_origem, lc.hora, lc.situacao, 
                lc.descricao_recebimento, lc.id_plano_contas,
                lc.id_movimento, lc.vencimento, lc.detalhes, lc.id_pagto 
                FROM lancamentoscaixa lc
                WHERE lc.situacao = 'N' AND lc.sigla_origem = 'CR' 
                ORDER BY lc.id DESC LIMIT 30";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "idusuario" => $row['idusuario'],
                    "idcaixa" => $row['idcaixa'],
                    "dataemissao" => $row['dataemissao'],
                    "tipomovimento" => $row['tipomovimento'],
                    "ndoc" => $row['ndoc'],
                    "valor" => $row['valor'],
                    "cadastro" => $row['cadastro'],
                    "observacoes" => $row['observacoes'],
                    "parcela" => $row['parcela'],
                    "id_origem" => $row['id_origem'],
                    "sigla_origem" => $row['sigla_origem'],
                    "hora" => $row['hora'],
                    "situacao" => $row['situacao'],
                    "descricao_recebimento" => $row['descricao_recebimento'],
                    "id_plano_contas" => $row['id_plano_contas'],
                    "id_movimento" => $row['id_movimento'],
                    "vencimento" => $row['vencimento'],
                    "detalhes" => $row['detalhes'],
                    "id_pagto" => $row['id_pagto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contasreceber/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "contasreceber" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contasreceber" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT lc.id, lc.idusuario, lc.idcaixa, lc.dataemissao,
                lc.tipomovimento, lc.ndoc, lc.valor, lc.cadastro, lc.observacoes, 
                lc.parcela, lc.id_origem, lc.sigla_origem, lc.hora, lc.situacao, 
                lc.descricao_recebimento, lc.id_plano_contas,
                lc.id_movimento, lc.vencimento, lc.detalhes, lc.id_pagto 
                FROM lancamentoscaixa lc
                WHERE lc.situacao = 'N' AND lc.sigla_origem = 'CR' 
                AND lc.observacoes LIKE '%" . $texto . "%'            
                ORDER BY lc.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "idusuario" => $row['idusuario'],
                    "idcaixa" => $row['idcaixa'],
                    "dataemissao" => $row['dataemissao'],
                    "tipomovimento" => $row['tipomovimento'],
                    "ndoc" => $row['ndoc'],
                    "valor" => $row['valor'],
                    "cadastro" => $row['cadastro'],
                    "observacoes" => $row['observacoes'],
                    "parcela" => $row['parcela'],
                    "id_origem" => $row['id_origem'],
                    "sigla_origem" => $row['sigla_origem'],
                    "hora" => $row['hora'],
                    "situacao" => $row['situacao'],
                    "descricao_recebimento" => $row['descricao_recebimento'],
                    "id_plano_contas" => $row['id_plano_contas'],
                    "id_movimento" => $row['id_movimento'],
                    "vencimento" => $row['vencimento'],
                    "detalhes" => $row['detalhes'],
                    "id_pagto" => $row['id_pagto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna os detalhes de um registro específico",
                        "url" => "api/contasreceber/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "contasreceber" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contasreceber" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT lc.id, lc.idusuario, lc.idcaixa, lc.dataemissao,
                lc.tipomovimento, lc.ndoc, lc.valor, lc.cadastro, lc.observacoes, 
                lc.parcela, lc.id_origem, lc.sigla_origem, lc.hora, lc.situacao, 
                lc.descricao_recebimento, lc.id_plano_contas,
                lc.id_movimento, lc.vencimento, lc.detalhes, lc.id_pagto 
                FROM lancamentoscaixa lc
                WHERE lc.situacao = 'N' AND lc.sigla_origem = 'CR' 
                ORDER BY lc.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "idusuario" => $row['idusuario'],
                    "idcaixa" => $row['idcaixa'],
                    "dataemissao" => $row['dataemissao'],
                    "tipomovimento" => $row['tipomovimento'],
                    "ndoc" => $row['ndoc'],
                    "valor" => $row['valor'],
                    "cadastro" => $row['cadastro'],
                    "observacoes" => $row['observacoes'],
                    "parcela" => $row['parcela'],
                    "id_origem" => $row['id_origem'],
                    "sigla_origem" => $row['sigla_origem'],
                    "hora" => $row['hora'],
                    "situacao" => $row['situacao'],
                    "descricao_recebimento" => $row['descricao_recebimento'],
                    "id_plano_contas" => $row['id_plano_contas'],
                    "id_movimento" => $row['id_movimento'],
                    "vencimento" => $row['vencimento'],
                    "detalhes" => $row['detalhes'],
                    "id_pagto" => $row['id_pagto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contasreceber/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "contasreceber" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contasreceber" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT lc.id, lc.idusuario, lc.idcaixa, lc.dataemissao,
                lc.tipomovimento, lc.ndoc, lc.valor, lc.cadastro, lc.observacoes, lc.parcela, lc.id_origem,
                lc.sigla_origem, lc.hora, lc.situacao, lc.descricao_recebimento, lc.id_plano_contas,
                lc.id_movimento, lc.vencimento, lc.detalhes, lc.id_pagto FROM lancamentoscaixa lc
                WHERE lc.situacao = 'N' AND lc.sigla_origem = 'CR' AND lc.id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "idusuario" => $row['idusuario'],
                        "idcaixa" => $row['idcaixa'],
                        "dataemissao" => $row['dataemissao'],
                        "tipomovimento" => $row['tipomovimento'],
                        "ndoc" => $row['ndoc'],
                        "valor" => $row['valor'],
                        "cadastro" => $row['cadastro'],
                        "observacoes" => $row['observacoes'],
                        "parcela" => $row['parcela'],
                        "id_origem" => $row['id_origem'],
                        "sigla_origem" => $row['sigla_origem'],
                        "hora" => $row['hora'],
                        "situacao" => $row['situacao'],
                        "descricao_recebimento" => $row['descricao_recebimento'],
                        "id_plano_contas" => $row['id_plano_contas'],
                        "id_movimento" => $row['id_movimento'],
                        "vencimento" => $row['vencimento'],
                        "detalhes" => $row['detalhes'],
                        "id_pagto" => $row['id_pagto'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/contasreceber/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "contareceber" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "contareceber" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "contareceber" => $data,
        ];
    }

    function alterar($data)
    {
        $response = "";
        http_response_code(200);

        $id = $data['id'];
        $observacoes = $data['observacoes'];
        $vencimento = $data['vencimento'];
        $id_plano_contas = $data['id_plano_contas'];
        $valor = $data['valor'];
        $detalhes = $data['detalhes'];
        $parcela = $data['parcela'];

        try {
            $qry = "UPDATE lancamentoscaixa
                SET observacoes =:p01,
                vencimento =:p02,                       
                id_plano_contas =:p03,
                valor =:p04,
                detalhes =:p05,
                parcela =:p06
            WHERE id  =:p07";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $observacoes);
            $stmt->bindValue("p02", $vencimento);
            $stmt->bindValue("p03", $id_plano_contas);
            $stmt->bindValue("p04", $valor);
            $stmt->bindValue("p05", $detalhes);
            $stmt->bindValue("p06", $parcela);
            $stmt->bindValue("p07", $id);
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
                    'url' => 'api/contasreceber/' . $id
                )
            )
        );

        return $response;
    }

    function quitar($data)
    {
        $response = "";
        http_response_code(200);

        $id = $data['id'];
        $data_pagamento = $data['data_pagamento'];
        $hora_pagamento = $data['hora_pagamento'];
        $situacao = $data['situacao'];
        $id_plano_contas = $data['id_plano_contas'];
        $detalhes = $data['detalhes'];
        $id_pagto_quitacao = $data['id_pagto_quitacao'];

        try {
            $qry = "UPDATE lancamentoscaixa
                SET situacao =:p01,
                descricao_recebimento =:p02,                     
                id_plano_contas =:p03,
                detalhes =:p04,                
                data_pagamento =:p05,
                hora_pagamento =:p06,
                id_pagto_quitacao =:p7
            WHERE id  =:p8";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $situacao);
            $stmt->bindValue("p02", $detalhes);
            $stmt->bindValue("p03", $id_plano_contas);
            $stmt->bindValue("p04", $detalhes);
            $stmt->bindValue("p05", $data_pagamento);
            $stmt->bindValue("p06", $hora_pagamento);
            $stmt->bindValue("p7", $id_pagto_quitacao);
            $stmt->bindValue("p8", $id);
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
                    'url' => 'api/contasreceber/' . $id
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

        $observacoes = $data['observacoes'];
        $vencimento = $data['vencimento'];
        $situacao = $data['situacao'];
        $id_plano_contas = $data['id_plano_contas'];
        $valor = $data['valor'];
        $detalhes = $data['detalhes'];
        $tipomovimento = $data['tipomovimento'];
        $idcentrocusto = $data['idcentrocusto'];
        $ndoc = $data['ndoc'];
        $id_origem = $data['id_origem'];
        $sigla_origem = $data['sigla_origem'];
        $idusuario = $data['idusuario'];
        $idcaixa = 2;
        $id_movimento = 2;
        $id_pagto = $data['id_pagto'];
        $parcela = $data['parcela'];
        $id_filial = $data['id_filial'];
        $id_pagto_quitacao = $data['id_pagto_quitacao'];
        $valor_pago = $data['valor_pago'];
        $id_aluno = $data['id_aluno'];

        $desc_tipo = "";
        $ultimoID = "SELECT id, descricao FROM tipo_pagamento WHERE id = '$id_pagto'";
        $stmt = $this->conexao->prepare($ultimoID);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $desc_tipo = $row['descricao'];
        }

        try {
            $qry = "INSERT INTO lancamentoscaixa (
                cadastro, 
                hora, 
                observacoes, 
                vencimento, 
                situacao, 
                id_plano_contas, 
                valor, 
                detalhes, 
                tipomovimento, 
                idcentrocusto, 
                ndoc,
                id_origem, 
                sigla_origem, 
                idusuario, 
                idcaixa, 
                id_movimento, 
                dataemissao,
                id_pagto, 
                parcela, 
                id_filial, 
                id_pagto_quitacao, 
                valor_pago, 
                data_pagamento,
                hora_pagamento, 
                descricao_recebimento, 
                valor_deduzido, 
                desc_tipo, 
                valor_real, 
                titulo, 
                id_parcelamento, 
                nome_cliente, 
                tipo_documento,
                id_aluno) VALUES 
                    (
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
            $stmt->bindValue("p01", $dataAtual);
            $stmt->bindValue("p02", $hora);
            $stmt->bindValue("p03", $observacoes);
            $stmt->bindValue("p04", $vencimento);
            $stmt->bindValue("p05", $situacao);
            $stmt->bindValue("p06", $id_plano_contas);
            $stmt->bindValue("p07", $valor);
            $stmt->bindValue("p08", $detalhes);
            $stmt->bindValue("p09", $tipomovimento);
            $stmt->bindValue("p10", $idcentrocusto);
            $stmt->bindValue("p11", $ndoc);
            $stmt->bindValue("p12", $id_origem);
            $stmt->bindValue("p13", $sigla_origem);
            $stmt->bindValue("p14", $idusuario);
            $stmt->bindValue("p15", $idcaixa);
            $stmt->bindValue("p16", $id_movimento);
            $stmt->bindValue("p17", $dataAtual);
            $stmt->bindValue("p18", $id_pagto);
            $stmt->bindValue("p19", $parcela);
            $stmt->bindValue("p20", $id_filial);
            $stmt->bindValue("p21", $id_pagto_quitacao);
            $stmt->bindValue("p22", $valor_pago);
            $stmt->bindValue("p23", $dataAtual);
            $stmt->bindValue("p24", $hora);
            $stmt->bindValue("p25", $observacoes);
            $stmt->bindValue("p26", $valor);
            $stmt->bindValue("p27", $desc_tipo);
            $stmt->bindValue("p28", $valor);
            $stmt->bindValue("p29", $ndoc);
            $stmt->bindValue("p30", 1);
            $stmt->bindValue("p31", 'CONSUMIDOR');
            $stmt->bindValue("p32", 'DUPLICATA');
            $stmt->bindValue("p33", $id_aluno);
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
                    'url' => 'api/contasreceber'
                )
            )
        );

        return $response;
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM lancamentoscaixa WHERE id = '$id'";
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
                'url' => 'api/contasreceber',
                'body' => array(
                    'type' => 'DELETE',
                    'descricao' => 'String',
                )
            )
        );

        return $response;
    }

    function contaTotalCaixa()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT COUNT(*) AS totalCP FROM lancamentoscaixa
                WHERE situacao = 'S' AND sigla_origem = 'CR'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalCP'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contasreceber/totalcp",
                    ]
                );
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
}
