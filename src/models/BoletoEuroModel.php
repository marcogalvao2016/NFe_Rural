<?php
namespace src\models;

use \core\Model;

use Gerencianet\Exception\GerencianetException;

require ("gerencianet/vendor/autoload.php");

function consultaGerenciaNet($titulo, $conexao)
{
    $descricao = "";
    $vDataHoraRetorno = "";

    // Configuração do boleto produção
    $vClienteID = "Client_Id_3d267b73ea8aa23932c8269448229d96bb5dc6f4";
    $vClienteSecret = "Client_Secret_7927441d28a1ea7bc7159d98c77fcf2def7c4969";

    $options = [
        'client_id' => $vClienteID,
        'client_secret' => $vClienteSecret,
        'sandbox' => false
        // altere conforme o ambiente (true = desenvolvimento e false = producao)
    ];

    $params = [
        'id' => $titulo
        // $charge_id refere-se ao ID da transaÃ§Ã£o ("charge_id")
    ];

    try {
        $api = new \Gerencianet\Gerencianet($options);
        $charge = $api->detailCharge($params, []);

        foreach ($charge['data']['history'] as $history) {
            //var_dump($history['message']);//PRA VC CONSEGUIR VER SE TÃ� PEGANDO O DADO CERTO do array
            $descricao = "";
            $vDataHoraRetorno = $history['created_at'];
            $descricao = $history['message'];
        }

        $qry = "UPDATE boletos_gerados_web SET historico =:historico 
            WHERE id_movimento =:idmovimento";

        $stmt = $conexao->prepare($qry);
        $stmt->bindValue("historico", $descricao);
        $stmt->bindValue("idmovimento", $titulo);
        $stmt->execute();

    } catch (GerencianetException $e) {
        print_r($e->code);
        print_r($e->error);
        print_r($e->errorDescription);
    } catch (\Exception $e) {
        print_r($e->getMessage());
    }

    return $descricao;
}

class BoletoEuroModel extends Model
{
    private $conexao; // Variável para armazenar a conexão PDO

    function __construct()
    {
        require_once 'conexao/db_connection.php';
        $this->conexao = new \DB_Con();
    }

    function getMensagemWhats(
        $status,
        $nomeCliente,
        $titulo,
        $vencimento,
        $link,
        $valor
    ) {
        $vDescricao = "";

        $vencimento = date('d/m/Y', strtotime($vencimento));

        if ($status != "1" && $status != "2") {
            $vDescricao = "Prezado (a) *" . $nomeCliente . "* " .
                "Este é um lembrete de que o Boleto de Nr. " . $titulo . "\n" .
                "foi gerado com vencimento para " . $vencimento .
                ". Ele encontra-se em Aberto. \nSe você já efetuou o pagamento, " .
                "favor *desconsiderar esta mensagem.* \n\nAtente para o seguintes prazos: \n" .
                "Em 3 dias após o vencimento o Sistema poderá estar expirado, " .
                "se já houver efetuado o pagamento, e só clicar no botão " .
                "*Ativar pela Web.* \n\n" .
                "Segue link do beleto para pagamento: \n" . $link . "\n\n" .
                "att, \n" . "Eurosistemas MT\n" . "https://eurosistemasmt.com.br";
        } else {
            $vDescricao = "Prezado (a) *" . $nomeCliente . "* " . "\n" .
                "*A sua cobrança foi confirmada com sucesso*" . "\n" .
                $titulo . " no valor de R$ " . $valor . "\n" .
                "Data de vencimento: " . $vencimento . "\n\n" .
                "Se o sistema ainda estiver bloqueado, e só clicar no botão " .
                "*Ativar pela Web.* \n\n" .
                "att, \n" . "Eurosistemas MT\n" . "https://eurosistemasmt.com.br";
        }

        return $vDescricao;
    }

    function listar()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT bw.*, e.razaosocial, e.cnpj, CASE bw.situacao WHEN '0' THEN 'PENDENTE'
                WHEN '1' THEN 'PAGO' WHEN '2' THEN 'CANCELADO' WHEN '3' THEN 'INADIMPLENTE'
                WHEN '4' THEN 'AGUARDANDO' WHEN '5' THEN 'NÃO CONFIRMADO' END AS descsituacao 
                FROM boletos_gerados_web bw 
                LEFT JOIN emp e ON (bw.id_cliente = e.id)
                -- WHERE bw.situacao = '1' 
                ORDER BY bw.id DESC LIMIT 50";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "razaosocial" => $row['razaosocial'],
                    "valor" => $row['valor'],
                    "vencimento" => $row['vencimento'],
                    "cnpj" => $row['cnpj'],
                    "descsituacao" => $row['descsituacao'],
                    "link_boleto" => $row['link_boleto'],
                    "pdf_boleto" => $row['pdf_boleto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/boletoseuro/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "boletosweb" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "boletosweb" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT bw.*, e.razaosocial, e.cnpj, CASE bw.situacao WHEN '0' THEN 'PENDENTE'
                WHEN '1' THEN 'PAGO' WHEN '2' THEN 'CANCELADO' WHEN '3' THEN 'INADIMPLENTE'
                WHEN '4' THEN 'AGUARDANDO' WHEN '5' THEN 'NÃO CONFIRMADO' END AS descsituacao 
                FROM boletos_gerados_web bw 
                LEFT JOIN emp e ON (bw.id_cliente = e.id)    
                ORDER BY bw.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "razaosocial" => $row['razaosocial'],
                    "valor" => $row['valor'],
                    "vencimento" => $row['vencimento'],
                    "cnpj" => $row['cnpj'],
                    "descsituacao" => $row['descsituacao'],
                    "link_boleto" => $row['link_boleto'],
                    "pdf_boleto" => $row['pdf_boleto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/boletoseuro/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "boletosweb" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "boletosweb" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT bw.*, e.razaosocial, e.cnpj, CASE bw.situacao WHEN '0' THEN 'PENDENTE'
                WHEN '1' THEN 'PAGO' WHEN '2' THEN 'CANCELADO' WHEN '3' THEN 'INADIMPLENTE'
                WHEN '4' THEN 'AGUARDANDO' WHEN '5' THEN 'NÃO CONFIRMADO' END AS descsituacao 
                FROM boletos_gerados_web bw 
                LEFT JOIN emp e ON (bw.id_cliente = e.id) 
                WHERE e.razaosocial like '%" . $texto . "%'             
                ORDER BY bw.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "razaosocial" => $row['razaosocial'],
                    "valor" => $row['valor'],
                    "vencimento" => $row['vencimento'],
                    "cnpj" => $row['cnpj'],
                    "descsituacao" => $row['descsituacao'],
                    "link_boleto" => $row['link_boleto'],
                    "pdf_boleto" => $row['pdf_boleto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/boletoseuro/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "boletosweb" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "boletosweb" => $data,
        ];
    }

    function pesquisarCNPJ($texto)
    {
        $data = [];

        try {
            $qry = "SELECT bw.*, e.razaosocial, e.cnpj, CASE bw.situacao WHEN '0' THEN 'PENDENTE'
                WHEN '1' THEN 'PAGO' WHEN '2' THEN 'CANCELADO' WHEN '3' THEN 'INADIMPLENTE'
                WHEN '4' THEN 'AGUARDANDO' WHEN '5' THEN 'NÃO CONFIRMADO' END AS descsituacao 
                FROM boletos_gerados_web bw 
                LEFT JOIN emp e ON (bw.id_cliente = e.id) 
                WHERE e.cnpj like '%" . $texto . "%'             
                ORDER BY bw.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "razaosocial" => $row['razaosocial'],
                    "valor" => $row['valor'],
                    "vencimento" => $row['vencimento'],
                    "cnpj" => $row['cnpj'],
                    "descsituacao" => $row['descsituacao'],
                    "link_boleto" => $row['link_boleto'],
                    "pdf_boleto" => $row['pdf_boleto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/boletoseuro/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "boletosweb" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "boletosweb" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT bw.*, e.razaosocial, e.cnpj, e.telefone, e.e_mail, e.vencimento as bloqueio,
                CASE bw.situacao WHEN '0' THEN 'PENDENTE'
                WHEN '1' THEN 'PAGO' WHEN '2' THEN 'CANCELADO' WHEN '3' THEN 'INADIMPLENTE'
                WHEN '4' THEN 'AGUARDANDO' WHEN '5' THEN 'NÃO CONFIRMADO' END AS descsituacao 
                FROM boletos_gerados_web bw 
                LEFT JOIN emp e ON (bw.id_cliente = e.id) 
                WHERE bw.id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "razaosocial" => $row['razaosocial'],
                        "telefone" => $row['telefone'],
                        "valor" => $row['valor'],
                        "vencimento" => $row['vencimento'],
                        "cnpj" => $row['cnpj'],
                        "descsituacao" => $row['descsituacao'],
                        "link_boleto" => $row['link_boleto'],
                        "pdf_boleto" => $row['pdf_boleto'],
                        "id_movimento" => $row['id_movimento'],
                        "e_mail" => $row['e_mail'],
                        "cadastro" => $row['cadastro'],
                        "bloqueio" => $row['bloqueio'],
                        "historico" => $row['historico'],
                        "situacao" => $row['situacao'],
                        "msg_whatsapp" => $this->getMensagemWhats(
                            $row['situacao'],
                            $row['razaosocial'],
                            $row['id_movimento'],
                            $row['vencimento'],
                            $row['link_boleto'],
                            $row['valor'],
                        ),
                        "consulta_gerencianet" => consultaGerenciaNet($row['id_movimento'], $this->conexao),
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/boletosweb/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "boletoweb" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "boletoweb" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "boletoweb" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM bairro WHERE id = '$id'";
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
                'url' => 'api/boletosweb',
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

        $titulo = $data['titulo'];
        $vencimento = $data['vencimento'];

        try {
            $qry = "UPDATE boletos_gerados_web SET 
                    vencimento =:p01                    
                WHERE id_movimento =:p02";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $vencimento);
            $stmt->bindValue("p02", $titulo);
            $stmt->execute();

            // Configuração do boleto produção
            $vClienteID = "Client_Id_3d267b73ea8aa23932c8269448229d96bb5dc6f4";
            $vClienteSecret = "Client_Secret_7927441d28a1ea7bc7159d98c77fcf2def7c4969";

            $options = [
                'client_id' => $vClienteID,
                'client_secret' => $vClienteSecret,
                'sandbox' => false
                // altere conforme o ambiente (true = desenvolvimento e false = producao)
            ];

            $params = [
                'id' => $titulo
                // $charge_id refere-se ao ID da transaÃ§Ã£o ("charge_id")
            ];

            $body = [
                'expire_at' => $vencimento
            ];

            try {
                $api = new \Gerencianet\Gerencianet($options);
                $charge = $api->settleCharge($params, []);
                $charge = $api->updateBillet($params, $body);

            } catch (GerencianetException $e) {
                print_r($e->code);
                print_r($e->error);
                print_r($e->errorDescription);
            } catch (\Exception $e) {
                print_r($e->getMessage());
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
                    'url' => 'api/boletosweb/' . $titulo
                )
            )
        );

        return $response;
    }

    function cancelar($data)
    {
        $response = "";
        http_response_code(200);

        $titulo = $data['titulo'];

        try {
            $qry = "UPDATE boletos_gerados_web SET 
                    situacao =:p01                    
                WHERE id_movimento =:p02";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", 2);
            $stmt->bindValue("p02", $titulo);
            $stmt->execute();

            // Configuração do boleto produção
            $vClienteID = "Client_Id_3d267b73ea8aa23932c8269448229d96bb5dc6f4";
            $vClienteSecret = "Client_Secret_7927441d28a1ea7bc7159d98c77fcf2def7c4969";

            $options = [
                'client_id' => $vClienteID,
                'client_secret' => $vClienteSecret,
                'sandbox' => false
                // altere conforme o ambiente (true = desenvolvimento e false = producao)
            ];

            $params = [
                'id' => $titulo
                // $charge_id refere-se ao ID da transaÃ§Ã£o ("charge_id")
            ];

            try {
                $api = new \Gerencianet\Gerencianet($options);
                $charge = $api->cancelCharge($params, []);

            } catch (GerencianetException $e) {
                print_r($e->code);
                print_r($e->error);
                print_r($e->errorDescription);
            } catch (\Exception $e) {
                print_r($e->getMessage());
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
                    'url' => 'api/boletosweb/' . $titulo
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
        $taxa = $data['taxa'];

        try {
            $qry = "INSERT INTO bairro(
                    descricao, 
                    taxa)VALUES(
                        :p01, 
                        :p02)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $descricao);
            $stmt->bindValue("p02", $taxa);
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
                    'url' => 'api/bairro'
                )
            )
        );

        return $response;
    }

    public function generateBillet($data)
    {
        $boletos = [];

        $response = "";
        http_response_code(200);

        $dt_inicial = $data['dt_inicial'];
        $dt_final = $data['dt_final'];
        $mes = $data['mes'];
        $origem = $data['origem'];
        $ano = $data['ano'];

        try {
            $qry = "SELECT e.id, e.razaosocial, e.cnpj, e.cpf, e.data_vencimento,
                e.mensalidade, e.telefone, e.situacao, bg.id_movimento, bg.situacao as sitboleto,
                bg.vencimento FROM emp e
                LEFT JOIN boletos_gerados_web bg ON (e.id = bg.id_cliente)
                WHERE e.data_vencimento >= '$dt_inicial' AND e.data_vencimento <= '$dt_final'
                AND e.situacao = 'A' AND e.mensalidade > 0 AND e.origem = '$origem' 
                GROUP BY e.id ORDER BY e.data_vencimento";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $vValor = $row['mensalidade'];
                $vValor = str_replace(",", ".", $vValor);
                $diaVencimento = $row['data_vencimento'];
                $idCli = $row['id'];
                $nomeCliente = $row['razaosocial'];
                $cnpj = $row['cnpj'];
                $telefone = $row['telefone'];
                $cpf = $row['cpf'];

                if ($diaVencimento == "0") {
                    $diaVencimento = "1";
                }

                $item = strlen(trim($diaVencimento));
                if ($item == 1) {
                    $vVencimento = "0" . $diaVencimento . "/" . $mes . "/" . $ano;
                } else {
                    $vVencimento = $diaVencimento . "/" . $mes . "/" . $ano;
                }

                // Pegar a observacao por categoria                              
                $SQLConsOBS = "SELECT e.id, e.razaosocial, cc.descricao, 
                                cc.msg_fatura FROM emp e
                                LEFT JOIN clientecategoria cc ON (e.id_categoria = cc.id)
                                WHERE e.id = '$idCli'";
                $stmt = $this->conexao->prepare($SQLConsOBS);
                $stmt->execute();

                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $vNum = $stmt->rowCount();

                $vvOBSok = "BOLETO PARA PAGAMENTO";
                if ($vNum > 0) {
                    foreach ($results as $item) {
                        if (empty($item['msg_fatura']) != true) {
                            $vvOBSok = $item['msg_fatura'];
                        }
                    }
                }
                // Pegar a observacao por categoria     

                $boletos[] = [
                    "mensalidade" => $vValor,
                    "vencimento" => $vVencimento,
                    "id_cliente" => $idCli,
                    "razaosocial" => $nomeCliente,
                    "cnpj" => $cnpj,
                    "telefone" => $telefone,
                    "cpf" => $cpf,
                    "observacoes" => $vvOBSok,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/boletoseuro/create",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "boletosweb" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "boletosweb" => $boletos,
        ];
    }

    public function generateBilletSelect($data)
    {
        $boletos = [];

        $response = "";
        http_response_code(200);

        $id_cliente = $data['id_cliente'];
        $mensalidade = $data['mensalidade'];
        $vencimento = $data['vencimento'];

        $mensalidade = str_replace(",", ".", $mensalidade);

        try {
            $qry = "SELECT e.id, e.razaosocial, e.cnpj, e.cpf, e.data_vencimento,
                e.mensalidade, e.telefone, e.situacao, bg.id_movimento, bg.situacao as sitboleto,
                bg.vencimento FROM emp e
                LEFT JOIN boletos_gerados_web bg ON (e.id = bg.id_cliente)
                WHERE e.id = '$id_cliente' AND e.situacao = 'A'                
                GROUP BY e.id ORDER BY e.data_vencimento";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $vValor = $row['mensalidade'];
                $vValor = str_replace(",", ".", $vValor);
                $idCli = $row['id'];
                $nomeCliente = $row['razaosocial'];
                $cnpj = $row['cnpj'];
                $telefone = $row['telefone'];
                $cpf = $row['cpf'];

                // Pegar a observacao por categoria                              
                $SQLConsOBS = "SELECT e.id, e.razaosocial, cc.descricao, 
                                cc.msg_fatura FROM emp e
                                LEFT JOIN clientecategoria cc ON (e.id_categoria = cc.id)
                                WHERE e.id = '$idCli'";
                $stmt = $this->conexao->prepare($SQLConsOBS);
                $stmt->execute();

                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
                $vNum = $stmt->rowCount();

                $vvOBSok = "BOLETO PARA PAGAMENTO";
                if ($vNum > 0) {
                    foreach ($results as $item) {
                        if (empty($item['msg_fatura']) != true) {
                            $vvOBSok = $item['msg_fatura'];
                        }
                    }
                }
                // Pegar a observacao por categoria     

                $boletos[] = [
                    "mensalidade" => $mensalidade,
                    "vencimento" => $vencimento,
                    "id_cliente" => $idCli,
                    "razaosocial" => $nomeCliente,
                    "cnpj" => $cnpj,
                    "telefone" => $telefone,
                    "cpf" => $cpf,
                    "observacoes" => $vvOBSok,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/boletoseuro/create",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "boletosweb" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "boletosweb" => $boletos,
        ];
    }

    public function createBillet($boletos)
    {
        $response = "";
        http_response_code(200);

        foreach ($boletos as $key => $boleto) {
            try {
                // Aqui eu gero o boleto
                // Configuração do boleto produção
                $vClienteID = "Client_Id_3d267b73ea8aa23932c8269448229d96bb5dc6f4";
                $vClienteSecret = "Client_Secret_7927441d28a1ea7bc7159d98c77fcf2def7c4969";

                $options = [
                    'client_id' => $vClienteID,
                    'client_secret' => $vClienteSecret,
                    'sandbox' => false // altere conforme o ambiente (true = desenvolvimento e false = producao)
                ];

                //Atribui a mensagem
                $vOBSok = $boleto['observacoes'];

                $item_1 = [
                    'name' => $vOBSok, // nome do item, produto ou serviço
                    'amount' => 1, // quantidade
                    'value' => floatval($boleto['mensalidade'] * 100) // valor (1000 = R$ 10,00) (Obs: É possível a criação de itens com valores negativos. Porém, o valor total da fatura deve ser superior ao valor mínimo para geração de transações.)
                ];

                $items = [
                    $item_1
                    //$item_2
                ];

                $metadata = [
                    'notification_url' => "https://euro-sistemas.app.br/blfitness/api-euro/rest/boletoseuro/pulse/update"
                ];

                $body = [
                    'items' => $items,
                    'metadata' => $metadata
                ];

                try {
                    $api = new \Gerencianet\Gerencianet($options);
                    $charge = $api->createCharge(array(), $body);

                    // $charge_id refere-se ao ID da transação gerada anteriormente
                    $charge_id = $charge["data"]["charge_id"];

                    $params = [
                        'id' => $charge_id
                    ];

                    if (strlen($boleto['cnpj']) == 14) {
                        $juridical_data = [
                            'corporate_name' => $boleto['razaosocial'], // nome da razão social
                            'cnpj' => $boleto['cnpj'] // CNPJ da empresa, com 14 caracteres
                        ];

                        $customer = [
                            'name' => $boleto['razaosocial'], // nome do cliente
                            'phone_number' => $boleto['telefone'], // telefone do cliente
                            'juridical_person' => $juridical_data
                        ];
                    } else {
                        $customer = [
                            'name' => $boleto['razaosocial'], // nome do cliente
                            'cpf' => $boleto['cpf'], // cpf válido do cliente
                            'phone_number' => $boleto['telefone'] // telefone do cliente
                        ];
                    }

                    $vVencimento = date('Y-d-m', strtotime($boleto['vencimento']));

                    $bankingBillet = [
                        'expire_at' => $vVencimento, // data de vencimento do boleto (formato: YYYY-MM-DD)
                        'customer' => $customer
                    ];

                    $payment = [
                        'banking_billet' => $bankingBillet // forma de pagamento (banking_billet = boleto)
                    ];

                    $body = [
                        'payment' => $payment
                    ];

                    try {
                        $charge = $api->payCharge($params, $body);
                        $link = $charge["data"]["link"];
                        $idMovimento = $charge["data"]["charge_id"];
                        $vDownload = $charge["data"]['pdf']["charge"];
                        $vLinhaDigitavel = $charge["data"]['barcode'];

                        $vPodeGravar = false;

                        $data = date("Y-m-d");
                        $hora = date('H:i:s');

                        if ($vPodeGravar == false) {
                            $SQL = "INSERT INTO boletos_gerados_web(
                              cadastro, 
                              hora,
                              id_cliente, 
                              link_boleto, 
                              pdf_boleto, 
                              id_movimento, 
                              vencimento,
                              valor, 
                              linha_digitavel)VALUES(
                                :data, 
                                :hora, 
                                :vIdCliente, 
                                :link,
                                :vDownload, 
                                :idMovimento, 
                                :novoVencimento,
                                :vValor, 
                                :vLinhaDigitavel)";

                            $sql = $this->conexao->prepare($SQL);
                            $sql->bindValue(":data", $data);
                            $sql->bindValue(":hora", $hora);
                            $sql->bindValue(":vIdCliente", $boleto['id_cliente']);
                            $sql->bindValue(":link", $link);
                            $sql->bindValue(":vDownload", $vDownload);
                            $sql->bindValue(":idMovimento", $idMovimento);
                            $sql->bindValue(":novoVencimento", $vVencimento);
                            $sql->bindValue(":vValor", floatval($boleto['mensalidade']));
                            $sql->bindValue(":vLinhaDigitavel", $vLinhaDigitavel);
                            $sql->execute();
                        }

                        //print_r($charge);
                    } catch (GerencianetException $e) {
                        print_r($e->code);
                        print_r($e->error);
                        print_r($e->errorDescription);
                    } catch (\Exception $e) {
                        //print_r($e->getMessage());
                        $erro = $e->getMessage();
                        echo "<p style='color:red'>Ocorreu o seguinte erro: </p>";
                        echo "<p style='color:red'>$erro</p>";
                        echo "<hr />";
                    }
                    //print_r($charge);
                } catch (GerencianetException $e) {
                    print_r($e->code);
                    print_r($e->error);
                    print_r($e->errorDescription);
                } catch (\Exception $e) {
                    print_r($e->getMessage());
                } // Fim aqui

            } catch (\Exception $e) {
                // Tratar exceção, se necessário
                return [
                    "error" => true,
                    "length" => 0,
                    "boletosweb" => [],
                ];
            }
        }

    }

    function listarNotification($data)
    {
        $boletos = [];

        $response = "";
        http_response_code(200);

        $status_cobranca = $data['status_cobranca'];
        $id_categoria = $data['id_categoria'];
        $diainicial = $data['diainicial'];
        $diafinal = $data['diafinal'];
        $tipo_cobranca = $data['tipo_cobranca'];

        $vSituacao = '0';
        switch ($status_cobranca) {
            case '0':
                $vSituacao = "bg.situacao IN (4,5)";
                break;
            case '1':
                $vSituacao = "bg.situacao = 4";
                break;
            case '2':
                $vSituacao = "bg.situacao = 5";
                break;
            case '3':
                $vSituacao = "bg.situacao IN (4,5)";
                break;
            case '4':
                $vSituacao = "bg.situacao IN (4,5) AND cc.contabil = 'S'";
                break;

            default:
                $vSituacao = "bg.situacao IN (4,5)";
                break;
        }

        $SQLCategoria = "";
        if ($id_categoria != "0") {
            $SQLCategoria = " AND e.id_categoria = '$id_categoria'";
        }

        try {
            if ($status_cobranca != "3") {
                $qry = "SELECT bg.id, bg.id_cliente, bg.valor as mensalidade, bg.link_boleto, bg.pdf_boleto, 
                bg.linha_digitavel, bg.situacao, bg.vencimento, e.razaosocial, e.cnpj, e.cpf, 
                e.telefone, bg.situacao, bg.id_movimento, e.e_mail,
                CASE bg.situacao WHEN '0' THEN 'PENDENTE'
                WHEN '1' THEN 'PAGO' WHEN '2' THEN 'CANCELADO' WHEN '3' THEN 'INADIMPLENTE'
                WHEN '4' THEN 'AGUARDANDO' WHEN '5' THEN 'NÃO CONFIRMADO' END AS descsituacao 
                FROM boletos_gerados_web bg 
                LEFT JOIN emp e ON (bg.id_cliente = e.id)
                LEFT JOIN clientecategoria cc ON (e.id_categoria = cc.id)
                WHERE $vSituacao AND e.data_vencimento >= '$diainicial' 
                AND e.data_vencimento <= '$diafinal' $SQLCategoria";
            } else {
                $qry = "SELECT bg.id, bg.id_cliente, bg.valor as mensalidade, bg.link_boleto, bg.pdf_boleto, 
                bg.linha_digitavel, bg.situacao, bg.vencimento, e.razaosocial, e.cnpj, e.cpf, 
                e.telefone, bg.situacao, bg.id_movimento, e.e_mail,
                CASE bg.situacao WHEN '0' THEN 'PENDENTE'
                WHEN '1' THEN 'PAGO' WHEN '2' THEN 'CANCELADO' WHEN '3' THEN 'INADIMPLENTE'
                WHEN '4' THEN 'AGUARDANDO' WHEN '5' THEN 'NÃO CONFIRMADO' END AS descsituacao 
                FROM boletos_gerados_web bg 
                LEFT JOIN emp e ON (bg.id_cliente = e.id)
                LEFT JOIN clientecategoria cc ON (e.id_categoria = cc.id)
                WHERE $vSituacao $SQLCategoria";
            }
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $vValor = $row['mensalidade'];
                $vencimento = $row['vencimento'];
                $idCli = $row['id_cliente'];
                $nomeCliente = $row['razaosocial'];
                $cnpj = $row['cnpj'];
                $telefone = $row['telefone'];
                $cpf = $row['cpf'];
                $desc_situacao = $row['descsituacao'];
                $situacao = $row['situacao'];
                $id_movimento = $row['id_movimento'];
                $link_boleto = $row['link_boleto'];
                $e_mail = $row['e_mail'];

                $boletos[] = [
                    "mensalidade" => $vValor,
                    "vencimento" => $vencimento,
                    "id_cliente" => $idCli,
                    "razaosocial" => $nomeCliente,
                    "cnpj" => $cnpj,
                    "telefone" => $telefone,
                    "cpf" => $cpf,
                    "desc_situacao" => $desc_situacao,
                    "situacao" => $situacao,
                    "id_movimento" => $id_movimento,
                    "link_boleto" => $link_boleto,
                    "valor" => $vValor,
                    "e_mail" => $e_mail,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/boletoseuro/create",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "boletosweb" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "boletosweb" => $boletos,
        ];
    }
}