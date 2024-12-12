<?php
namespace src\models;

use \core\Model;

class ContaHospitalarModel extends Model
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
            $qry = "SELECT * FROM conta_hospitalar ORDER BY id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "prontuario" => $row['prontuario'],
                    "paciente" => $row['paciente'],
                    "data_entrada" => $row['data_entrada'],
                    "hora_entrada" => $row['hora_entrada'],
                    "nascimento" => $row['nascimento'],
                    "convenio" => $row['convenio'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contahospitalar/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "contahospitalares" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contahospitalares" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM conta_hospitalar ORDER BY id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "prontuario" => $row['prontuario'],
                    "paciente" => $row['paciente'],
                    "data_entrada" => $row['data_entrada'],
                    "hora_entrada" => $row['hora_entrada'],
                    "nascimento" => $row['nascimento'],
                    "convenio" => $row['convenio'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contahospitalar/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "contahospitalares" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contahospitalares" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT * FROM conta_hospitalar WHERE paciente like '%" . $texto . "%' 
                ORDER BY id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "prontuario" => $row['prontuario'],
                    "paciente" => $row['paciente'],
                    "data_entrada" => $row['data_entrada'],
                    "hora_entrada" => $row['hora_entrada'],
                    "nascimento" => $row['nascimento'],
                    "convenio" => $row['convenio'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contahospitalar/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "contahospitalares" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contahospitalares" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM conta_hospitalar WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "prontuario" => $row['prontuario'],
                        "paciente" => $row['paciente'],
                        "nascimento" => $row['nascimento'],
                        "sexo" => $row['sexo'],
                        "endereco" => $row['endereco'],
                        "cep" => $row['cep'],
                        "numero" => $row['numero'],
                        "bairro" => $row['bairro'],
                        "cidade" => $row['cidade'],
                        "estado" => $row['estado'],
                        "convenio" => $row['convenio'],
                        "motivo_alta" => $row['motivo_alta'],
                        "quarto" => $row['quarto'],
                        "leito" => $row['leito'],
                        "medico" => $row['medico'],
                        "crm" => $row['crm'],
                        "data_entrada" => $row['data_entrada'],
                        "hora_entrada" => $row['hora_entrada'],
                        "data_saida" => $row['data_saida'],
                        "hora_saida" => $row['hora_saida'],
                        "permanencia" => $row['permanencia'],
                        "cid" => $row['cid'],
                        "data_fechamento" => $row['data_fechamento'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/contahospitalar/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "contahospitalar" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "contahospitalar" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "contahospitalar" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM conta_hospitalar WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $qry = "DELETE FROM conta_hospitalar_itens WHERE id_conta_hospitalar = '$id'";
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
                'url' => 'api/contahospitalar',
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

        $dataAtual = date("Y-m-d");

        $id = $data['id'];
        $prontuario = $data['prontuario'];
        $paciente = $data['paciente'];
        $nascimento = $data['nascimento'];
        $sexo = $data['sexo'];
        $endereco = $data['endereco'];
        $cep = $data['cep'];
        $numero = $data['numero'];
        $bairro = $data['bairro'];
        $cidade = $data['cidade'];
        $estado = $data['estado'];
        $convenio = $data['convenio'];
        $motivo_alta = $data['motivo_alta'];
        $quarto = $data['quarto'];
        $leito = $data['leito'];
        $medico = $data['medico'];
        $crm = $data['crm'];
        $data_entrada = $data['data_entrada'];
        $hora_entrada = $data['hora_entrada'];
        $data_saida = $data['data_saida'];
        $hora_saida = $data['hora_saida'];
        $permanencia = $data['permanencia'];
        $cid = $data['cid'];
        $data_fechamento = $data['data_fechamento'];

        $itens = $data['itens'];

        try {
            $qry = "UPDATE conta_hospitalar SET 
                    prontuario =:p01,
                    paciente =:p02,
                    nascimento =:p03,
                    sexo =:p04,
                    endereco =:p05,
                    cep =:p06,
                    numero =:p07,
                    bairro =:p08,
                    cidade =:p09,
                    estado =:p10,
                    convenio =:p11,
                    motivo_alta =:p12,
                    quarto =:p13,
                    leito =:p14,
                    medico =:p15,
                    crm =:p16,
                    data_entrada =:p17,
                    hora_entrada =:p18,
                    data_saida =:p19,
                    hora_saida =:p20,
                    permanencia =:p21,
                    cid =:p22,
                    data_fechamento =:p23
                WHERE id =:p24";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $prontuario);
            $stmt->bindValue("p02", $paciente);
            $stmt->bindValue("p03", $nascimento);
            $stmt->bindValue("p04", $sexo);
            $stmt->bindValue("p05", $endereco);
            $stmt->bindValue("p06", $cep);
            $stmt->bindValue("p07", $numero);
            $stmt->bindValue("p08", $bairro);
            $stmt->bindValue("p09", $cidade);
            $stmt->bindValue("p10", $estado);
            $stmt->bindValue("p11", $convenio);
            $stmt->bindValue("p12", $motivo_alta);
            $stmt->bindValue("p13", $quarto);
            $stmt->bindValue("p14", $leito);
            $stmt->bindValue("p15", $medico);
            $stmt->bindValue("p16", $crm);
            $stmt->bindValue("p17", $data_entrada);
            $stmt->bindValue("p18", $hora_entrada);
            $stmt->bindValue("p19", $data_saida);
            $stmt->bindValue("p20", $hora_saida);
            $stmt->bindValue("p21", $permanencia);
            $stmt->bindValue("p22", $cid);
            $stmt->bindValue("p23", $data_fechamento);
            $stmt->bindValue("p24", $id);
            $stmt->execute();

            // Decodifica a string JSON para um array associativo
            $dataItens = $itens;

            // Verifica se a decodificação foi bem-sucedida
            if (json_last_error() === JSON_ERROR_NONE) {
                // Verifica se o array $dataItens está vazio
                if (!empty($dataItens)) {

                    // Remove os ítens do contrato
                    $qry = "DELETE FROM conta_hospitalar_itens WHERE id_conta_hospitalar = '$id'";
                    $stmt = $this->conexao->prepare($qry);
                    $stmt->execute();

                    // Itera sobre os elementos do array
                    foreach ($dataItens as $item) {
                        $qry = "INSERT INTO conta_hospitalar_itens(
                                    id_conta_hospitalar,
                                    tipo,
                                    descricao,
                                    quantidade,
                                    data,
                                    unitario,
                                    desconto,
                                    total)VALUES(
                                        :p01,
                                        :p02,
                                        :p03,
                                        :p04,
                                        :p05,
                                        :p06,
                                        :p07,
                                        :p08)";
                        $stmt = $this->conexao->prepare($qry);
                        $stmt->bindValue("p01", $id);
                        $stmt->bindValue("p02", $item['tipo']);
                        $stmt->bindValue("p03", $item['descricao']);
                        $stmt->bindValue("p04", $item['quantidade']);
                        $stmt->bindValue("p05", $dataAtual);
                        $stmt->bindValue("p06", $item['unitario']);
                        $stmt->bindValue("p07", $item['desconto']);
                        $stmt->bindValue("p08", $item['total']);
                        $stmt->execute();
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
                    'url' => 'api/contahospitalar/' . $id
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

        $prontuario = $data['prontuario'];
        $paciente = $data['paciente'];
        $nascimento = $data['nascimento'];
        $sexo = $data['sexo'];
        $endereco = $data['endereco'];
        $cep = $data['cep'];
        $numero = $data['numero'];
        $bairro = $data['bairro'];
        $cidade = $data['cidade'];
        $estado = $data['estado'];
        $convenio = $data['convenio'];
        $motivo_alta = $data['motivo_alta'];
        $quarto = $data['quarto'];
        $leito = $data['leito'];
        $medico = $data['medico'];
        $crm = $data['crm'];
        $data_entrada = $data['data_entrada'];
        $hora_entrada = $data['hora_entrada'];
        $data_saida = $data['data_saida'];
        $hora_saida = $data['hora_saida'];
        $permanencia = $data['permanencia'];
        $cid = $data['cid'];
        $data_fechamento = $data['data_fechamento'];

        $itens = $data['itens'];

        try {
            $qry = "INSERT INTO conta_hospitalar(
                    prontuario,
                    paciente,
                    nascimento,
                    sexo,
                    endereco,
                    cep,
                    numero,
                    bairro,
                    cidade,
                    estado,
                    convenio,
                    motivo_alta,
                    quarto,
                    leito,
                    medico,
                    crm,
                    data_entrada,
                    hora_entrada,
                    data_saida,
                    hora_saida,
                    permanencia,
                    cadastro,
                    hora,
                    cid,
                    data_fechamento)VALUES(
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
                        :p25)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $prontuario);
            $stmt->bindValue("p02", $paciente);
            $stmt->bindValue("p03", $nascimento);
            $stmt->bindValue("p04", $sexo);
            $stmt->bindValue("p05", $endereco);
            $stmt->bindValue("p06", $cep);
            $stmt->bindValue("p07", $numero);
            $stmt->bindValue("p08", $bairro);
            $stmt->bindValue("p09", $cidade);
            $stmt->bindValue("p10", $estado);
            $stmt->bindValue("p11", $convenio);
            $stmt->bindValue("p12", $motivo_alta);
            $stmt->bindValue("p13", $quarto);
            $stmt->bindValue("p14", $leito);
            $stmt->bindValue("p15", $medico);
            $stmt->bindValue("p16", $crm);
            $stmt->bindValue("p17", $data_entrada);
            $stmt->bindValue("p18", $hora_entrada);
            $stmt->bindValue("p19", $data_saida);
            $stmt->bindValue("p20", $hora_saida);
            $stmt->bindValue("p21", $permanencia);
            $stmt->bindValue("p22", $dataAtual);
            $stmt->bindValue("p23", $hora);
            $stmt->bindValue("p24", $cid);
            $stmt->bindValue("p25", $data_fechamento);
            $stmt->execute();

            // Pega o último registro
            $ultimo_id = 0;
            $queryUltimoRegistro = "SELECT id FROM conta_hospitalar ORDER BY id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($queryUltimoRegistro);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $rowRegistro) {
                $ultimo_id = $rowRegistro['id'];
            }
            // Pega o último registro

            // Decodifica a string JSON para um array associativo
            $dataItens = $itens;

            // Verifica se a decodificação foi bem-sucedida
            if (json_last_error() === JSON_ERROR_NONE) {
                // Verifica se o array $dataItens está vazio
                if (!empty($dataItens)) {

                    // Itera sobre os elementos do array
                    foreach ($dataItens as $item) {
                        $qry = "INSERT INTO conta_hospitalar_itens(
                                    id_conta_hospitalar,
                                    tipo,
                                    descricao,
                                    quantidade,
                                    data,
                                    unitario,
                                    desconto,
                                    total)VALUES(
                                        :p01,
                                        :p02,
                                        :p03,
                                        :p04,
                                        :p05,
                                        :p06,
                                        :p07,
                                        :p08)";
                        $stmt = $this->conexao->prepare($qry);
                        $stmt->bindValue("p01", $ultimo_id);
                        $stmt->bindValue("p02", $item['tipo']);
                        $stmt->bindValue("p03", $item['descricao']);
                        $stmt->bindValue("p04", $item['quantidade']);
                        $stmt->bindValue("p05", $dataAtual);
                        $stmt->bindValue("p06", $item['unitario']);
                        $stmt->bindValue("p07", $item['desconto']);
                        $stmt->bindValue("p08", $item['total']);
                        $stmt->execute();
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
                    'url' => 'api/contahospitalar'
                )
            )
        );

        return $response;
    }

    function viewItensProntuario($id)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT chi.*, th.descricao as desc_tipo FROM conta_hospitalar_itens chi 
                LEFT JOIN tipo_hospitalar th ON (chi.tipo = th.id) 
                WHERE chi.id_conta_hospitalar = '$id' 
                ORDER BY chi.descricao";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data[] = [
                        "id" => $row['id'],
                        "id_conta_hospitalar" => $row['id_conta_hospitalar'],
                        "tipo" => $row['tipo'],
                        "descricao" => $row['descricao'],
                        "quantidade" => $row['quantidade'],
                        "data" => $row['data'],
                        "unitario" => $row['unitario'],
                        "desconto" => $row['desconto'],
                        "total" => $row['total'],
                        "desc_tipo" => $row['desc_tipo'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/contahospitalar/itens/{id}",
                        ]
                    ];
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "contahospitalaresitens" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "contahospitalaresitens" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "contahospitalaresitens" => $data,
        ];
    }

    function viewItensGroupProntuario($id)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT ROUND(SUM(chi.total), 2) as total, th.descricao as desc_tipo,
                0 as 'total_ch' FROM conta_hospitalar_itens chi
                LEFT JOIN tipo_hospitalar th ON (chi.tipo = th.id) 
                WHERE chi.id_conta_hospitalar = '$id' GROUP BY chi.tipo";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data[] = [
                        "total" => $row['total'],
                        "desc_tipo" => $row['desc_tipo'],
                        "total_ch" => $row['total_ch'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/contahospitalar/itens/group/{id}",
                        ]
                    ];
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "contahospitalaresitensgroup" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "contahospitalaresitensgroup" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "contahospitalaresitensgroup" => $data,
        ];
    }
}