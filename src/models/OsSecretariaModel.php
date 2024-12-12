<?php
namespace src\models;

use \core\Model;

class OsSecretariaModel extends Model
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
            $qry = "SELECT os.id, os.lancamento, os.hora, os.tipo_evento, os.numero_pregao,
                f.nomefantasia, os.contato, os.tel_contato, os.descricao,
                CASE os.tipo_evento WHEN '0' THEN 'NENHUM' WHEN '1' THEN 'CULTURA' 
                WHEN '2' THEN 'ESPORTE' WHEN '3' THEN 'OUTROS' END AS desc_tipo_evento,
                os.data_inicio, os.hora_inicio, os.data_fim, os.hora_fim,
                os.situacao, CASE os.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA' 
                WHEN 'C' THEN 'CANCELADA' END AS desc_situacao, os.email_enviado
                FROM ordem_servico_secretaria os 
                LEFT JOIN fornecedor f ON (os.id_fornecedor = f.id)
                WHERE os.situacao <> 'C' ORDER BY os.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "lancamento" => $row['lancamento'],
                    "hora" => $row['hora'],
                    "tipo_evento" => $row['tipo_evento'],
                    "desc_tipo_evento" => $row['desc_tipo_evento'],
                    "descricao" => $row['descricao'],
                    "nomefantasia" => $row['nomefantasia'],
                    "contato" => $row['contato'],
                    "tel_contato" => $row['tel_contato'],
                    "data_inicio" => $row['data_inicio'],
                    "hora_inicio" => $row['hora_inicio'],
                    "data_fim" => $row['data_fim'],
                    "hora_fim" => $row['hora_fim'],
                    "situacao" => $row['situacao'],
                    "desc_situacao" => $row['desc_situacao'],
                    "numero_pregao" => $row['numero_pregao'],
                    "email_enviado" => $row['email_enviado'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/ossecretarias/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "ossecretarias" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "ossecretarias" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT os.id, os.lancamento, os.hora, os.tipo_evento, os.numero_pregao,
                f.nomefantasia, os.contato, os.tel_contato, os.descricao,
                CASE os.tipo_evento WHEN '0' THEN 'NENHUM' WHEN '1' THEN 'CULTURA' 
                WHEN '2' THEN 'ESPORTE' WHEN '3' THEN 'OUTROS' END AS desc_tipo_evento,
                os.data_inicio, os.hora_inicio, os.data_fim, os.hora_fim,
                os.situacao, CASE os.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA' 
                WHEN 'C' THEN 'CANCELADA' END AS desc_situacao, os.email_enviado
                FROM ordem_servico_secretaria os 
                LEFT JOIN fornecedor f ON (os.id_fornecedor = f.id) 
                WHERE 1=1 ORDER BY os.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "lancamento" => $row['lancamento'],
                    "hora" => $row['hora'],
                    "tipo_evento" => $row['tipo_evento'],
                    "desc_tipo_evento" => $row['desc_tipo_evento'],
                    "descricao" => $row['descricao'],
                    "nomefantasia" => $row['nomefantasia'],
                    "contato" => $row['contato'],
                    "tel_contato" => $row['tel_contato'],
                    "data_inicio" => $row['data_inicio'],
                    "hora_inicio" => $row['hora_inicio'],
                    "data_fim" => $row['data_fim'],
                    "hora_fim" => $row['hora_fim'],
                    "situacao" => $row['situacao'],
                    "desc_situacao" => $row['desc_situacao'],
                    "numero_pregao" => $row['numero_pregao'],
                    "email_enviado" => $row['email_enviado'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/ossecretarias/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "ossecretarias" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "ossecretarias" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT os.id, os.lancamento, os.hora, os.tipo_evento, os.numero_pregao,
                f.nomefantasia, os.contato, os.tel_contato, os.descricao,
                CASE os.tipo_evento WHEN '0' THEN 'NENHUM' WHEN '1' THEN 'CULTURA' 
                WHEN '2' THEN 'ESPORTE' WHEN '3' THEN 'OUTROS' END AS desc_tipo_evento,
                os.data_inicio, os.hora_inicio, os.data_fim, os.hora_fim,
                os.situacao, CASE os.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA' 
                WHEN 'C' THEN 'CANCELADA' END AS desc_situacao, os.email_enviado
                FROM ordem_servico_secretaria os 
                LEFT JOIN fornecedor f ON (os.id_fornecedor = f.id) 
                WHERE os.situacao <> 'C' AND f.nomefantasia like '%" . $texto . "%' LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "lancamento" => $row['lancamento'],
                    "hora" => $row['hora'],
                    "tipo_evento" => $row['tipo_evento'],
                    "desc_tipo_evento" => $row['desc_tipo_evento'],
                    "descricao" => $row['descricao'],
                    "nomefantasia" => $row['nomefantasia'],
                    "contato" => $row['contato'],
                    "tel_contato" => $row['tel_contato'],
                    "data_inicio" => $row['data_inicio'],
                    "hora_inicio" => $row['hora_inicio'],
                    "data_fim" => $row['data_fim'],
                    "hora_fim" => $row['hora_fim'],
                    "situacao" => $row['situacao'],
                    "desc_situacao" => $row['desc_situacao'],
                    "numero_pregao" => $row['numero_pregao'],
                    "email_enviado" => $row['email_enviado'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/ossecretarias/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "ossecretarias" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "ossecretarias" => $data,
        ];
    }

    function pesquisarContrato($texto)
    {
        $data = [];

        try {
            $qry = "SELECT os.id, os.lancamento, os.hora, os.tipo_evento, os.numero_pregao,
                f.nomefantasia, os.contato, os.tel_contato, os.descricao,
                CASE os.tipo_evento WHEN '0' THEN 'NENHUM' WHEN '1' THEN 'CULTURA' 
                WHEN '2' THEN 'ESPORTE' WHEN '3' THEN 'OUTROS' END AS desc_tipo_evento,
                os.data_inicio, os.hora_inicio, os.data_fim, os.hora_fim,
                os.situacao, CASE os.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA' 
                WHEN 'C' THEN 'CANCELADA' END AS desc_situacao, os.email_enviado
                FROM ordem_servico_secretaria os 
                LEFT JOIN fornecedor f ON (os.id_fornecedor = f.id) 
                WHERE os.situacao <> 'C' AND os.numero_pregao = '$texto' LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "lancamento" => $row['lancamento'],
                    "hora" => $row['hora'],
                    "tipo_evento" => $row['tipo_evento'],
                    "desc_tipo_evento" => $row['desc_tipo_evento'],
                    "descricao" => $row['descricao'],
                    "nomefantasia" => $row['nomefantasia'],
                    "contato" => $row['contato'],
                    "tel_contato" => $row['tel_contato'],
                    "data_inicio" => $row['data_inicio'],
                    "hora_inicio" => $row['hora_inicio'],
                    "data_fim" => $row['data_fim'],
                    "hora_fim" => $row['hora_fim'],
                    "situacao" => $row['situacao'],
                    "desc_situacao" => $row['desc_situacao'],
                    "numero_pregao" => $row['numero_pregao'],
                    "email_enviado" => $row['email_enviado'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/ossecretarias/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "ossecretarias" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "ossecretarias" => $data,
        ];
    }

    function pesquisarNumOS($texto)
    {
        $data = [];

        try {
            $qry = "SELECT os.id, os.lancamento, os.hora, os.tipo_evento, os.numero_pregao,
                f.nomefantasia, os.contato, os.tel_contato, os.descricao,
                CASE os.tipo_evento WHEN '0' THEN 'NENHUM' WHEN '1' THEN 'CULTURA' 
                WHEN '2' THEN 'ESPORTE' WHEN '3' THEN 'OUTROS' END AS desc_tipo_evento,
                os.data_inicio, os.hora_inicio, os.data_fim, os.hora_fim,
                os.situacao, CASE os.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA' 
                WHEN 'C' THEN 'CANCELADA' END AS desc_situacao, os.email_enviado
                FROM ordem_servico_secretaria os 
                LEFT JOIN fornecedor f ON (os.id_fornecedor = f.id) 
                WHERE os.situacao <> 'C' AND os.id = '$texto' LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "lancamento" => $row['lancamento'],
                    "hora" => $row['hora'],
                    "tipo_evento" => $row['tipo_evento'],
                    "desc_tipo_evento" => $row['desc_tipo_evento'],
                    "descricao" => $row['descricao'],
                    "nomefantasia" => $row['nomefantasia'],
                    "contato" => $row['contato'],
                    "tel_contato" => $row['tel_contato'],
                    "data_inicio" => $row['data_inicio'],
                    "hora_inicio" => $row['hora_inicio'],
                    "data_fim" => $row['data_fim'],
                    "hora_fim" => $row['hora_fim'],
                    "situacao" => $row['situacao'],
                    "desc_situacao" => $row['desc_situacao'],
                    "numero_pregao" => $row['numero_pregao'],
                    "email_enviado" => $row['email_enviado'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/ossecretarias/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "ossecretarias" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "ossecretarias" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT os.*, u.nome_completo as nome, ct.objeto FROM ordem_servico_secretaria os
                LEFT JOIN contrato ct ON (os.numero_pregao = ct.numero_pregao) 
                LEFT JOIN usuario u ON (os.id_usuario = u.idusuario)
                WHERE os.id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "lancamento" => $row['lancamento'],
                        "hora" => $row['hora'],
                        "id_fornecedor" => $row['id_fornecedor'],
                        "id_usuario" => $row['id_usuario'],
                        "tipo_evento" => $row['tipo_evento'],
                        "descricao" => $row['descricao'],
                        "data_inicio" => $row['data_inicio'],
                        "hora_inicio" => $row['hora_inicio'],
                        "data_fim" => $row['data_fim'],
                        "hora_fim" => $row['hora_fim'],
                        "contato" => $row['contato'],
                        "tel_contato" => $row['tel_contato'],
                        "endereco" => $row['endereco'],
                        "observacoes" => $row['observacoes'],
                        "solicitante" => $row['solicitante'],
                        "situacao" => $row['situacao'],
                        "email_enviado" => $row['email_enviado'],
                        "numero_pregao" => $row['numero_pregao'],
                        "nome_usuario" => $row['nome'],
                        "objeto" => $row['objeto'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/ossecretarias/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "ossecretaria" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "ossecretaria" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "ossecretaria" => $data,
        ];
    }

    function viewItensOSSecretaria($id)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT osi.*, ci.descricao_curta, ci.unidade, osi.lote, ci.item,
                ci.descricao_longa FROM os_secretaria_itens osi
                LEFT JOIN contrato_itens ci ON (osi.id_item = ci.id)
                WHERE osi.id_os = '$id' GROUP BY osi.id";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data[] = [
                        "id" => $row['id'],
                        "id_os" => $row['id_os'],
                        "id_item" => $row['id_item'],
                        "quantidade" => $row['quantidade'],
                        "unitario" => $row['unitario'],
                        "total" => $row['total'],
                        "descricao_curta" => $row['descricao_curta'],
                        "descricao_longa" => $row['descricao_longa'],
                        "unidade" => $row['unidade'],
                        "lote" => $row['lote'],
                        "item" => $row['item'],
                        "qt_lanca" => $row['quantidade'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/ossecretarias/itens/{id}",
                        ]
                    ];
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "ossecretariaitens" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "ossecretariaitens" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "ossecretariaitens" => $data,
        ];
    }

    function deletar($data)
    {
        $response = "";
        http_response_code(200);

        $id = $data['id'];
        $itens = $data['itens'];

        try {
            // Decodifica a string JSON para um array associativo
            $dataItens = $itens;

            // Verifica se a decodificação foi bem-sucedida
            if (json_last_error() === JSON_ERROR_NONE) {
                // Verifica se o array $dataItens está vazio
                if (!empty($dataItens)) {

                    // Itera sobre os elementos do array
                    foreach ($dataItens as $item) {
                        $this->AtualizaEstoque(
                            $item['id_item'],
                            $item['lote'],
                            $item['qt_lanca'],
                            '+'
                        );
                    }
                }
            }

            $qry = "UPDATE ordem_servico_secretaria SET situacao = 'C' WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            // $qry = "DELETE FROM os_secretaria_itens WHERE id_os = '$id'";
            // $stmt = $this->conexao->prepare($qry);
            // $stmt->execute();

        } catch (\Exception $e) {
            http_response_code(500);

            $response = $e->getMessage();
        }

        $response = array(
            'message' => 'Registro removido com sucesso',
            'request' => array(
                'description' => 'Deleta um registro',
                'url' => 'api/ossecretarias',
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

        $id = trim($data['id']);
        $data_lancamento = $data['data_lancamento'];
        $numero_pregao = $data['numero_pregao'];
        $id_fornecedor = $data['id_fornecedor'];
        $solicitante = $data['solicitante'];
        $tipo_evento = $data['tipo_evento'];
        $descricao = $data['descricao'];
        $endereco = $data['endereco'];
        $data_inicial = $data['data_inicial'];
        $hora_inicial = $data['hora_inicial'];
        $data_final = $data['data_final'];
        $hora_fim = $data['hora_fim'];
        $contato = $data['contato'];
        $tel_contato = $data['tel_contato'];
        $observacoes = $data['observacoes'];
        $situacao = $data['situacao'];
        $email_enviado = $data['email_enviado'];
        $itens = $_POST['itens'];

        try {
            $qry = "UPDATE ordem_servico_secretaria SET 
                    lancamento =:p01,
                    id_fornecedor =:p02,
                    id_usuario =:p03,
                    tipo_evento =:p04,
                    descricao =:p05,
                    data_inicio =:p06,
                    hora_inicio =:p07,
                    data_fim =:p08,
                    hora_fim =:p09,
                    contato =:p10,
                    tel_contato =:p11,
                    endereco =:p12,
                    observacoes =:p13,
                    solicitante =:p14,
                    situacao =:p15,
                    numero_pregao =:p16,
                    email_enviado =:p17
                WHERE id =:p18";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $data_lancamento);
            $stmt->bindValue("p02", $id_fornecedor);
            $stmt->bindValue("p03", 2);
            $stmt->bindValue("p04", $tipo_evento);
            $stmt->bindValue("p05", $descricao);
            $stmt->bindValue("p06", $data_inicial);
            $stmt->bindValue("p07", $hora_inicial);
            $stmt->bindValue("p08", $data_final);
            $stmt->bindValue("p09", $hora_fim);
            $stmt->bindValue("p10", $contato);
            $stmt->bindValue("p11", $tel_contato);
            $stmt->bindValue("p12", $endereco);
            $stmt->bindValue("p13", $observacoes);
            $stmt->bindValue("p14", $solicitante);
            $stmt->bindValue("p15", $situacao);
            $stmt->bindValue("p16", $numero_pregao);
            $stmt->bindValue("p17", $email_enviado);
            $stmt->bindValue("p18", $id);
            $stmt->execute();

            // Decodifica a string JSON para um array associativo
            $dataItens = json_decode($itens, true);

            // Verifica se a decodificação foi bem-sucedida
            if (json_last_error() === JSON_ERROR_NONE) {
                // Verifica se o array $dataItens está vazio
                if (!empty($dataItens)) {

                    // Remove os ítens do contrato
                    $qry = "DELETE FROM os_secretaria_itens WHERE id_os = '$id'";
                    $stmt = $this->conexao->prepare($qry);
                    $stmt->execute();

                    // Itera sobre os elementos do array
                    foreach ($dataItens as $item) {
                        $qry = "INSERT INTO os_secretaria_itens(
                        id_os,
                        id_item,
                        quantidade,
                        unitario,
                        total,
                        lote)VALUES(
                            :p01,
                            :p02,
                            :p03,
                            :p04,
                            :p05,
                            :p06)";
                        $stmt = $this->conexao->prepare($qry);
                        $stmt->bindValue("p01", $id);
                        $stmt->bindValue("p02", $item['id_item']);
                        $stmt->bindValue("p03", $item['qt_lanca']);
                        $stmt->bindValue("p04", $item['unitario']);
                        $stmt->bindValue("p05", $item['total']);
                        $stmt->bindValue("p06", $item['lote']);
                        $stmt->execute();

                        // $this->AtualizaEstoque(
                        //     $item['item'],
                        //     $item['lote'],
                        //     $item['quantidade'],
                        //     '-'
                        // );
                    }
                }
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
                    'url' => 'api/ossecretarias/' . $id
                )
            )
        );

        return $response;
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $hora = date('H:i:s');

        $data_lancamento = $data['data_lancamento'];
        $numero_pregao = $data['numero_pregao'];
        $id_fornecedor = $data['id_fornecedor'];
        $solicitante = $data['solicitante'];
        $tipo_evento = $data['tipo_evento'];
        $descricao = $data['descricao'];
        $endereco = $data['endereco'];
        $data_inicial = $data['data_inicial'];
        $hora_inicial = $data['hora_inicial'];
        $data_final = $data['data_final'];
        $hora_fim = $data['hora_fim'];
        $contato = $data['contato'];
        $tel_contato = $data['tel_contato'];
        $observacoes = $data['observacoes'];
        $situacao = $data['situacao'];
        $id_usuario = $data['id_usuario'];
        $email_enviado = $data['email_enviado'];
        $itens = $_POST['itens'];

        try {
            $qry = "INSERT INTO ordem_servico_secretaria ( 
                    lancamento,
                    id_fornecedor,
                    id_usuario,
                    tipo_evento,
                    descricao,
                    data_inicio,
                    hora_inicio,
                    data_fim,
                    hora_fim,
                    contato,
                    tel_contato,
                    endereco,
                    observacoes,
                    solicitante,
                    situacao,
                    numero_pregao,
                    hora,
                    email_enviado)VALUES(
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
                        :p18)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $data_lancamento);
            $stmt->bindValue("p02", $id_fornecedor);
            $stmt->bindValue("p03", $id_usuario);
            $stmt->bindValue("p04", $tipo_evento);
            $stmt->bindValue("p05", $descricao);
            $stmt->bindValue("p06", $data_inicial);
            $stmt->bindValue("p07", $hora_inicial);
            $stmt->bindValue("p08", $data_final);
            $stmt->bindValue("p09", $hora_fim);
            $stmt->bindValue("p10", $contato);
            $stmt->bindValue("p11", $tel_contato);
            $stmt->bindValue("p12", $endereco);
            $stmt->bindValue("p13", $observacoes);
            $stmt->bindValue("p14", $solicitante);
            $stmt->bindValue("p15", $situacao);
            $stmt->bindValue("p16", $numero_pregao);
            $stmt->bindValue("p17", $hora);
            $stmt->bindValue("p18", $email_enviado);
            $stmt->execute();

            // Decodifica a string JSON para um array associativo
            $dataItens = json_decode($itens, true);

            // Verifica se a decodificação foi bem-sucedida
            if (json_last_error() === JSON_ERROR_NONE) {
                // Verifica se o array $dataItens está vazio
                if (!empty($dataItens)) {

                    // Pega o ultimo código
                    $idOS = "";
                    $ultimoID = "SELECT id FROM ordem_servico_secretaria ORDER BY id DESC LIMIT 1";
                    $stmt = $this->conexao->prepare($ultimoID);
                    $stmt->execute();
                    $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                    foreach ($results as $row) {
                        $idOS = $row['id'];
                    }

                    // Itera sobre os elementos do array
                    foreach ($dataItens as $item) {
                        $qry = "INSERT INTO os_secretaria_itens(
                        id_os,
                        id_item,
                        quantidade,
                        unitario,
                        total,
                        lote)VALUES(
                            :p01,
                            :p02,
                            :p03,
                            :p04,
                            :p05,
                            :p06)";
                        $stmt = $this->conexao->prepare($qry);
                        $stmt->bindValue("p01", $idOS);
                        $stmt->bindValue("p02", $item['id']);
                        $stmt->bindValue("p03", $item['qt_lanca']);
                        $stmt->bindValue("p04", $item['unitario']);
                        $stmt->bindValue("p05", $item['total']);
                        $stmt->bindValue("p06", $item['lote']);
                        $stmt->execute();

                        $this->AtualizaEstoque(
                            $item['id'],
                            $item['lote'],
                            $item['qt_lanca'],
                            '-'
                        );
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
                    'url' => 'api/ossecretarias'
                )
            )
        );

        return $response;
    }

    function printOS($dados)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        $dtInicial = $dados['dtInicial'];
        $dtFinal = $dados['dtFinal'];
        $id_fornecedor = $dados['id_fornecedor'];
        $numero_pregao = $dados['numero_pregao'];
        $tipo_evento = $dados['tipo_evento'];
        $solicitante = $dados['solicitante'];
        $situacao = $dados['situacao'];

        $SQLPregao = "";
        if (!empty($numero_pregao) && $numero_pregao != "0") {
            $SQLPregao = "AND os.numero_pregao = '$numero_pregao'";
        }

        $SQLFornecedor = "";
        if ($id_fornecedor != "0") {
            $SQLFornecedor = "AND os.id_fornecedor = '$id_fornecedor'";
        }

        $SQLTipoEvento = "";
        if ($tipo_evento != "0") {
            $SQLTipoEvento = "AND os.tipo_evento = '$tipo_evento'";
        }

        $SQLSolicitante = "";
        if ($solicitante != "") {
            $SQLSolicitante = "AND os.solicitante LIKE '%" . $solicitante . "%'";
        }

        $SQLSituacao = "";
        if ($situacao != "0") {
            $SQLSituacao = "AND os.situacao = '$situacao'";
        }

        try {
            $qry = "SELECT os.*, 
                        f.nomefantasia, 
                        f.correio, 
                        f.telefone1, 
                        os.endereco,       
                        (SELECT SUM(osi.total) 
                        FROM os_secretaria_itens osi 
                        WHERE osi.id_os = os.id) AS total,
                        (SELECT SUM(
                                CASE 
                                    WHEN ci.unidade != 'UNID' 
                                    THEN (DATEDIFF(os.data_fim, os.data_inicio) + 1) * osi.total 
                                    ELSE osi.total 
                                END
                                ) 
                        FROM os_secretaria_itens osi 
                        LEFT JOIN contrato_itens ci ON (osi.id_item = ci.id)
                        WHERE osi.id_os = os.id) AS total_multiplicado                
                FROM ordem_servico_secretaria os 
                LEFT JOIN fornecedor f ON os.id_fornecedor = f.id
                WHERE os.lancamento >= '$dtInicial' AND os.lancamento <= '$dtFinal'
                $SQLPregao $SQLFornecedor $SQLTipoEvento $SQLSolicitante 
                $SQLSituacao ORDER BY f.nomefantasia";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data[] = [
                        "id" => $row['id'],
                        "lancamento" => $row['lancamento'],
                        "descricao" => $row['descricao'],
                        "contato" => $row['contato'],
                        "tel_contato" => $row['tel_contato'],
                        "solicitante" => $row['solicitante'],
                        "id_fornecedor" => $row['id_fornecedor'],
                        "numero_pregao" => $row['numero_pregao'],
                        "nomefantasia" => $row['nomefantasia'],
                        "endereco" => $row['endereco'],
                        "correio" => $row['correio'],
                        "telefone1" => $row['telefone1'],
                        "total" => $row['total_multiplicado'],
                        "situacao" => $row['situacao'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/ossecretarias/print/",
                        ]
                    ];
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "ossecretarias" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "ossecretarias" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "ossecretarias" => $data,
        ];
    }

    function AtualizaEstoque($id_item, $lote, $quantidade, $tipoupdate)
    {
        $response = "";
        http_response_code(200);

        $qtAtual = 0;
        $SQLqtAtual = "SELECT id, lote, quantidade FROM contrato_itens 
            WHERE id = '$id_item' AND lote = '$lote'";
        $stmt = $this->conexao->prepare($SQLqtAtual);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $qtAtual = $row['quantidade'];
        }

        try {
            switch ($tipoupdate) {
                case '+':
                    $qry = "UPDATE contrato_itens SET                
                        quantidade =:p01
                        WHERE id =:p02 
                        AND lote =:p03";
                    $stmt = $this->conexao->prepare($qry);
                    $stmt->bindValue("p01", (floatval($qtAtual) + floatval($quantidade)));
                    $stmt->bindValue("p02", $id_item);
                    $stmt->bindValue("p03", $lote);
                    $stmt->execute();
                    break;
                case '-':
                    $qry = "UPDATE contrato_itens SET                
                        quantidade =:p01
                        WHERE id =:p02 
                        AND lote =:p03";
                    $stmt = $this->conexao->prepare($qry);
                    $stmt->bindValue("p01", (floatval($qtAtual) - floatval($quantidade)));
                    $stmt->bindValue("p02", $id_item);
                    $stmt->bindValue("p03", $lote);
                    $stmt->execute();
                    break;

                default:
                    # code...
                    break;
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
                    'type' => 'POST',
                    'description' => 'Registro atualizado com sucesso',
                    'url' => 'api/contrato/update'
                )
            )
        );

        return $response;
    }

    function finalizaOS($id)
    {
        $retorno = false;

        try {
            $qry = "UPDATE ordem_servico_secretaria SET situacao =:p01 WHERE id =:p02";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", 'F');
            $stmt->bindValue("p02", $id);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            $retorno = false;
        }

        return $retorno;
    }

    function enviadaOS($id, $status)
    {
        $retorno = false;

        try {
            $qry = "UPDATE ordem_servico_secretaria 
                SET email_enviado =:p01 WHERE id =:p02";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", 'S');
            $stmt->bindValue("p02", $status);
            $stmt->execute();

            $retorno = true;
        } catch (\Exception $e) {
            $retorno = false;
        }

        return $retorno;
    }


    function finalizaOSAuto()
    {
        $retorno = false;

        try {
            $qry = "SELECT o.id, o.data_inicio, o.hora_inicio, o.data_fim, 
                o.hora_fim, o.situacao FROM ordem_servico_secretaria o
                WHERE CONCAT(o.data_fim, ' ', o.hora_fim) <= NOW()";

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $qry = "UPDATE ordem_servico_secretaria SET situacao =:p01 WHERE id =:p02";
                $stmt = $this->conexao->prepare($qry);
                $stmt->bindValue("p01", 'F');
                $stmt->bindValue("p02", $row['id']);
                $stmt->execute();
            }

            $retorno = true;
        } catch (\Exception $e) {
            $retorno = false;
        }

        return $retorno;
    }

    function listarPorPaginacao($pagina, $limite)
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
            $countQry = "SELECT COUNT(*) as total FROM ordem_servico_secretaria WHERE situacao <> 'C'";
            $countStmt = $this->conexao->prepare($countQry);
            $countStmt->execute();
            $totalResults = $countStmt->fetch(\PDO::FETCH_ASSOC)['total'];

            $qry = "SELECT os.id, os.lancamento, os.hora, os.tipo_evento, os.numero_pregao,
                f.nomefantasia, os.contato, os.tel_contato, os.descricao,
                CASE os.tipo_evento WHEN '0' THEN 'NENHUM' WHEN '1' THEN 'CULTURA' 
                WHEN '2' THEN 'ESPORTE' WHEN '3' THEN 'OUTROS' END AS desc_tipo_evento,
                os.data_inicio, os.hora_inicio, os.data_fim, os.hora_fim,
                os.situacao, CASE os.situacao WHEN 'A' THEN 'ABERTA' WHEN 'F' THEN 'FECHADA' 
                WHEN 'C' THEN 'CANCELADA' END AS desc_situacao, os.email_enviado
                FROM ordem_servico_secretaria os 
                LEFT JOIN fornecedor f ON (os.id_fornecedor = f.id)             
                ORDER BY os.id DESC LIMIT $limit OFFSET $offset";

            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "lancamento" => $row['lancamento'],
                    "hora" => $row['hora'],
                    "tipo_evento" => $row['tipo_evento'],
                    "desc_tipo_evento" => $row['desc_tipo_evento'],
                    "descricao" => $row['descricao'],
                    "nomefantasia" => $row['nomefantasia'],
                    "contato" => $row['contato'],
                    "tel_contato" => $row['tel_contato'],
                    "data_inicio" => $row['data_inicio'],
                    "hora_inicio" => $row['hora_inicio'],
                    "data_fim" => $row['data_fim'],
                    "hora_fim" => $row['hora_fim'],
                    "situacao" => $row['situacao'],
                    "desc_situacao" => $row['desc_situacao'],
                    "numero_pregao" => $row['numero_pregao'],
                    "email_enviado" => $row['email_enviado'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/ossecretarias/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "ossecretarias" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "totalQt" => $totalResults,
            "page" => $page,
            "limit" => $limit,
            "ossecretarias" => $data,
        ];
    }

}