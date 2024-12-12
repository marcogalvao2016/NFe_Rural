<?php
namespace src\models;

use \core\Model;

class FormaPagtoModel extends Model
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
            $qry = "SELECT tp.id, tp.idpagamento, tp.descricao, 
                tp.tipo, tp.id_plano_contas, tp.id_caixa, 
                pc.descricao as planocontas, cx.descricao as caixa FROM tipo_pagamento tp 
                LEFT JOIN planocontas pc ON (tp.id_plano_contas = pc.planocontas)
                LEFT JOIN caixa cx ON (tp.id_caixa = cx.id) 
                WHERE tp.aplicativo = 'S' ORDER BY tp.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "idpagamento" => $row['idpagamento'],
                    "descricao" => $row['descricao'],
                    "tipo" => $row['tipo'],
                    "id_plano_contas" => $row['id_plano_contas'],
                    "id_caixa" => $row['id_caixa'],
                    "planocontas" => $row['planocontas'],
                    "caixa" => $row['caixa'],
                    "name" => $row['descricao'],
                    "datavencimento" => date("Y-m-d"),
                    "amount" => 0,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/formaspagto/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário

            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "formapagtos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "formapagtos" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT tp.id, tp.idpagamento, tp.descricao, 
                tp.tipo, tp.id_plano_contas, tp.id_caixa, 
                pc.descricao as planocontas, cx.descricao as caixa FROM tipo_pagamento tp 
                LEFT JOIN planocontas pc ON (tp.id_plano_contas = pc.planocontas)
                LEFT JOIN caixa cx ON (tp.id_caixa = cx.id) 
                WHERE tp.id = '$id' AND tp.aplicativo = 'S'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = false;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "idpagamento" => $row['idpagamento'],
                        "descricao" => $row['descricao'],
                        "tipo" => $row['tipo'],
                        "id_plano_contas" => $row['id_plano_contas'],
                        "id_caixa" => $row['id_caixa'],
                        "name" => $row['descricao'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/formaspagto/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "formapagto" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "formapagto" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "formapagto" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM tipo_pagamento WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro removido com sucesso',
            'request' => array(
                'description' => 'Deleta um registro',
                'url' => 'api/caixa',
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

        $id = intval($data['id']);
        $descricao = $data['descricao'];
        $idpagamento = $data['idpagamento'];
        $tipo = $data['tipo'];
        $id_plano_contas = $data['id_plano_contas'];
        $id_caixa = $data['id_caixa'];

        try {
            $qry = "UPDATE tipo_pagamento
                SET descricao   =:p01,
                idpagamento     =:p02,
                tipo            =:p03,
                id_plano_contas =:p04,
                id_caixa        =:p05
            WHERE id          =:p06";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $idpagamento);
            $stmt->bindValue("p03", $tipo);
            $stmt->bindValue("p04", $id_plano_contas);
            $stmt->bindValue("p05", $id_caixa);
            $stmt->bindValue("p06", $id);
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
                    'url' => 'api/formaspagto/' . $id
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
        $idpagamento = $data['idpagamento'];
        $tipo = $data['tipo'];
        $id_plano_contas = $data['id_plano_contas'];
        $id_caixa = $data['id_caixa'];

        try {
            $qry = "INSERT INTO tipo_pagamento (
                descricao, 
                idpagamento, 
                tipo, 
                id_plano_contas, 
                id_caixa)VALUES(
                    :p01, 
                    :p02,
                    :p03,
                    :p04,
                    :p05)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $idpagamento);
            $stmt->bindValue("p03", $tipo);
            $stmt->bindValue("p04", $id_plano_contas);
            $stmt->bindValue("p05", $id_caixa);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro alterado com sucesso',
            'request' => array(
                'type' => 'POST',
                'description' => 'Altera um registro',
                'request' => array(
                    'type' => 'PUT',
                    'description' => 'Registro atualizado com sucesso',
                    'url' => 'api/formaspagto'
                )
            )
        );

        return $response;
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT tp.id, tp.idpagamento, tp.descricao, 
                tp.tipo, tp.id_plano_contas, tp.id_caixa, 
                pc.descricao as planocontas, cx.descricao as caixa FROM tipo_pagamento tp 
                LEFT JOIN planocontas pc ON (tp.id_plano_contas = pc.planocontas)
                LEFT JOIN caixa cx ON (tp.id_caixa = cx.id) 
                WHERE tp.aplicativo = 'S' AND tp.descricao like '%" . $texto . "%' 
                ORDER BY tp.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "idpagamento" => $row['idpagamento'],
                    "descricao" => $row['descricao'],
                    "tipo" => $row['tipo'],
                    "id_plano_contas" => $row['id_plano_contas'],
                    "id_caixa" => $row['id_caixa'],
                    "planocontas" => $row['planocontas'],
                    "caixa" => $row['caixa'],
                    "name" => $row['descricao'],
                    "datavencimento" => date("Y-m-d"),
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/formapagtos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "formapagtos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "formapagtos" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT tp.id, tp.idpagamento, tp.descricao, 
                tp.tipo, tp.id_plano_contas, tp.id_caixa, 
                pc.descricao as planocontas, cx.descricao as caixa FROM tipo_pagamento tp 
                LEFT JOIN planocontas pc ON (tp.id_plano_contas = pc.planocontas)
                LEFT JOIN caixa cx ON (tp.id_caixa = cx.id) 
                ORDER BY tp.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();
            
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "idpagamento" => $row['idpagamento'],
                    "descricao" => $row['descricao'],
                    "tipo" => $row['tipo'],
                    "id_plano_contas" => $row['id_plano_contas'],
                    "id_caixa" => $row['id_caixa'],
                    "planocontas" => $row['planocontas'],
                    "caixa" => $row['caixa'],
                    "name" => $row['descricao'],
                    "datavencimento" => date("Y-m-d"),
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/formapagtos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "formapagtos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "formapagtos" => $data,
        ];
    }

}