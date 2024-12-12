<?php
namespace src\models;

use \core\Model;

class ContratoModel extends Model
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
            $qry = "SELECT c.*, f.nomefantasia FROM contrato c 
                LEFT JOIN fornecedor f ON (c.id_fornecedor = f.id)
                WHERE c.situacao = 'A' ORDER BY c.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "data_inicial" => $row['data_inicial'],
                    "data_final" => $row['data_final'],
                    "numero_pregao" => $row['numero_pregao'],
                    "id_fornecedor" => $row['id_fornecedor'],
                    "nomefantasia" => $row['nomefantasia'],
                    "anexo" => $row['anexo'],
                    "observacoes" => $row['observacoes'],
                    "objeto" => $row['objeto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contratos/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "contratos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contratos" => $data,
        ];
    }

    function contratosAtivos()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT c.id, c.numero_pregao, c.id_fornecedor, f.nomefantasia 
                FROM contrato c
                LEFT JOIN fornecedor f ON (c.id_fornecedor = f.id)
                WHERE c.situacao = 'A' AND c.data_final >= CURRENT_DATE()";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "numero_pregao" => $row['numero_pregao'] . ' - ' . $row['nomefantasia'],
                    "id_fornecedor" => $row['id_fornecedor'],
                    "nomefantasia" => $row['nomefantasia'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contratosativos/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "contratosativos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contratosativos" => $data,
        ];
    }

    function contratosImp()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT c.id, c.numero_pregao, c.id_fornecedor, f.nomefantasia 
                FROM contrato c
                LEFT JOIN fornecedor f ON (c.id_fornecedor = f.id)
                WHERE c.situacao = 'A' AND c.data_final >= CURRENT_DATE()
                ORDER BY f.nomefantasia";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "numero_pregao" => $row['numero_pregao'] . ' - ' . $row['nomefantasia'],
                    "id_fornecedor" => $row['id_fornecedor'],
                    "nomefantasia" => $row['nomefantasia'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contratosativos/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "contratosativos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contratosativos" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT c.*, f.nomefantasia FROM contrato c 
                LEFT JOIN fornecedor f ON (c.id_fornecedor = f.id) 
                WHERE c.situacao = 'A'
                ORDER BY c.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "data_inicial" => $row['data_inicial'],
                    "data_final" => $row['data_final'],
                    "numero_pregao" => $row['numero_pregao'],
                    "id_fornecedor" => $row['id_fornecedor'],
                    "nomefantasia" => $row['nomefantasia'],
                    "anexo" => $row['anexo'],
                    "observacoes" => $row['observacoes'],
                    "objeto" => $row['objeto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contratos/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "contratos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contratos" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT c.*, f.nomefantasia FROM contrato c 
                LEFT JOIN fornecedor f ON (c.id_fornecedor = f.id) 
                WHERE c.situacao = 'A' AND f.nomefantasia like '%" . $texto . "%' 
                ORDER BY c.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "data_inicial" => $row['data_inicial'],
                    "data_final" => $row['data_final'],
                    "numero_pregao" => $row['numero_pregao'],
                    "id_fornecedor" => $row['id_fornecedor'],
                    "nomefantasia" => $row['nomefantasia'],
                    "anexo" => $row['anexo'],
                    "observacoes" => $row['observacoes'],
                    "objeto" => $row['objeto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contratos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "contratos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contratos" => $data,
        ];
    }

    function pesquisarContrato($texto)
    {
        $data = [];

        try {
            $qry = "SELECT c.*, f.nomefantasia FROM contrato c 
                LEFT JOIN fornecedor f ON (c.id_fornecedor = f.id) 
                WHERE c.situacao = 'A' AND c.numero_pregao = '$texto' 
                ORDER BY c.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "data_inicial" => $row['data_inicial'],
                    "data_final" => $row['data_final'],
                    "numero_pregao" => $row['numero_pregao'],
                    "id_fornecedor" => $row['id_fornecedor'],
                    "nomefantasia" => $row['nomefantasia'],
                    "anexo" => $row['anexo'],
                    "observacoes" => $row['observacoes'],
                    "objeto" => $row['objeto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contratos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "contratos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contratos" => $data,
        ];
    }

    function pesquisarCNPJ($texto)
    {
        $data = [];

        try {
            $qry = "SELECT c.*, f.nomefantasia FROM contrato c 
                LEFT JOIN fornecedor f ON (c.id_fornecedor = f.id) 
                WHERE c.situacao = 'A' AND c.cnpj = '$texto' 
                ORDER BY c.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "data_inicial" => $row['data_inicial'],
                    "data_final" => $row['data_final'],
                    "numero_pregao" => $row['numero_pregao'],
                    "id_fornecedor" => $row['id_fornecedor'],
                    "nomefantasia" => $row['nomefantasia'],
                    "anexo" => $row['anexo'],
                    "observacoes" => $row['observacoes'],
                    "objeto" => $row['objeto'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contratos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "contratos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contratos" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM contrato WHERE id = '$id'";
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
                        "data_inicial" => $row['data_inicial'],
                        "data_final" => $row['data_final'],
                        "numero_pregao" => $row['numero_pregao'],
                        "id_fornecedor" => $row['id_fornecedor'],
                        "anexo" => $row['anexo'],
                        "observacoes" => $row['observacoes'],
                        "objeto" => $row['objeto'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/contratos/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "contrato" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "contrato" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "contrato" => $data,
        ];
    }

    function viewItensContrato($id)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT * FROM contrato_itens WHERE id_contrato = '$id' 
                ORDER BY descricao_curta";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data[] = [
                        "id" => $row['id'],
                        "item" => $row['item'],
                        "lote" => $row['lote'],
                        "descricao_curta" => $row['descricao_curta'],
                        "descricao_longa" => $row['descricao_longa'],
                        "unidade" => $row['unidade'],
                        "quantidade" => $row['quantidade'],
                        "unitario" => $row['unitario'],
                        "total" => $row['total'],
                        "nomeFantasia" => $row['descricao_curta'],
                        "descricao" => $row['descricao_longa'],
                        "und" => $row['unidade'],
                        "valorUnit" => $row['unitario'],
                        "valorTotal" => $row['total'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/contratos/itens/{id}",
                        ]
                    ];
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "contratoitens" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "contratoitens" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "contratoitens" => $data,
        ];
    }

    function printItensContrato($dados)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        $id_fornecedor = $dados['id_fornecedor'];
        $dtInicial = $dados['dtInicial'];
        $dtFinal = $dados['dtFinal'];
        $id_item = $dados['id_item'];
        $numero_pregao = $dados['numero_pregao'];

        $SQLPregao = "";
        if (!empty($numero_pregao) && $numero_pregao != "0") {
            $SQLPregao = "AND c.numero_pregao = '$numero_pregao'";
        }

        $SQLFornecedor = "";
        if ($id_fornecedor != "0") {
            $SQLFornecedor = "AND c.id_fornecedor = '$id_fornecedor'";
        }

        $SQLItem = "";
        if ($id_item != "0") {
            $SQLItem = "AND ci.item = '$id_item'";
        }

        try {
            $qry = "SELECT ci.*, c.id_fornecedor, c.cadastro, c.numero_pregao, 
                f.nomefantasia, f.correio, f.telefone1, c.objeto FROM contrato_itens ci 
                LEFT JOIN contrato c ON (ci.id_contrato = c.id) 
                LEFT JOIN fornecedor f ON (c.id_fornecedor = f.id)
                WHERE c.situacao = 'A' AND c.cadastro >= '$dtInicial' 
                AND c.cadastro <= '$dtFinal'
                $SQLPregao $SQLFornecedor $SQLItem ORDER BY descricao_curta";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data[] = [
                        "id" => $row['id'],
                        "item" => $row['item'],
                        "lote" => $row['lote'],
                        "descricao_curta" => $row['descricao_curta'],
                        "descricao_longa" => $row['descricao_longa'],
                        "unidade" => $row['unidade'],
                        "quantidade" => $row['quantidade'],
                        "qt_origem" => $row['qt_contrato'],
                        "unitario" => $row['unitario'],
                        "total" => $row['total'],
                        "id_fornecedor" => $row['id_fornecedor'],
                        "cadastro" => $row['cadastro'],
                        "numero_pregao" => $row['numero_pregao'],
                        "nomefantasia" => $row['nomefantasia'],
                        "correio" => $row['correio'],
                        "telefone1" => $row['telefone1'],
                        "objeto" => $row['objeto'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/contratos/itens/{id}",
                        ]
                    ];
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "contratoitens" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "contratoitens" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "contratoitens" => $data,
        ];
    }

    function printContrato($dados)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        $id_fornecedor = $dados['id_fornecedor'];
        $dtInicial = $dados['dtInicial'];
        $dtFinal = $dados['dtFinal'];
        $numero_pregao = $dados['numero_pregao'];
        $situacao = $dados['situacao'];

        $SQLPregao = "";
        if (!empty($numero_pregao) && $numero_pregao != "0") {
            $SQLPregao = "AND c.numero_pregao = '$numero_pregao'";
        }

        $SQLFornecedor = "";
        if ($id_fornecedor != "0") {
            $SQLFornecedor = "AND c.id_fornecedor = '$id_fornecedor'";
        }

        $SQLSituacao = "";
        if ($situacao != "0") {
            $SQLFornecedor = "AND c.situacao = '$situacao'";
        }

        try {
            $qry = "SELECT c.*, f.nomefantasia, f.telefone1, f.correio, (SELECT SUM(ci.quantidade) 
                        FROM contrato_itens ci 
                        WHERE ci.id_contrato = c.id) AS totqtde,
                        (SELECT SUM(ci.quantidade * ci.unitario) 
                        FROM contrato_itens ci 
                        WHERE ci.id_contrato = c.id) AS totvalor
                    FROM contrato c LEFT JOIN fornecedor f ON c.id_fornecedor = f.id
                    WHERE c.data_inicial >= '$dtInicial' 
                    AND c.data_final <= '$dtFinal' $SQLPregao $SQLFornecedor $SQLSituacao";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "hora" => $row['hora'],
                    "data_inicial" => $row['data_inicial'],
                    "data_final" => $row['data_final'],
                    "numero_pregao" => $row['numero_pregao'],
                    "id_fornecedor" => $row['id_fornecedor'],
                    "nomefantasia" => $row['nomefantasia'],
                    "anexo" => $row['anexo'],
                    "observacoes" => $row['observacoes'],
                    "objeto" => $row['objeto'],
                    "telefone" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "totqtde" => $row['totqtde'],
                    "totvalor" => $row['totvalor'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contratos/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "contratos" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "contratos" => $data,
        ];
    }

    function viewItensPregao($id)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT ci.*, c.numero_pregao FROM contrato_itens ci
                LEFT JOIN contrato c ON (ci.id_contrato = c.id) 
                WHERE c.numero_pregao = '$id' AND quantidade > 0 
                ORDER BY ci.descricao_curta";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data[] = [
                        "id" => $row['id'],
                        "item" => $row['item'],
                        "lote" => $row['lote'],
                        "descricao_curta" => $row['descricao_curta'],
                        "descricao_longa" => $row['descricao_longa'],
                        "unidade" => $row['unidade'],
                        "quantidade" => $row['quantidade'],
                        "unitario" => $row['unitario'],
                        "total" => $row['total'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/contratos/itens/{id}",
                        ]
                    ];
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "contratoitens" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "contratoitens" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "contratoitens" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "UPDATE contrato SET situacao = 'C' WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            // $qry = "DELETE FROM contrato_itens WHERE id_contrato = '$id'";
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
                'url' => 'api/contrato',
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
        $data_inicial = $data['data_inicial'];
        $data_final = $data['data_final'];
        $numero_pregao = $data['numero_pregao'];
        $id_fornecedor = $data['id_fornecedor'];
        $observacoes = $data['observacoes'];
        $objeto = $data['objeto'];
        $anexo = $data['anexo'];
        $itens = $data['itens'];
        $temAnexo = $data['temAnexo'];

        if ($temAnexo == 'N') {
            $SQLProduto = "SELECT id, anexo FROM contrato WHERE id = '$id'";
            $stmt = $this->conexao->prepare($SQLProduto);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $anexo = $row['anexo'];
            }
        }

        try {
            $qry = "UPDATE contrato SET                
                data_inicial =:p01,
                data_final =:p02,
                numero_pregao =:p03,
                id_fornecedor =:p04,
                anexo  =:p05,
                observacoes  =:p06,
                objeto =:p07
                WHERE id =:p08";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $data_inicial);
            $stmt->bindValue("p02", $data_final);
            $stmt->bindValue("p03", $numero_pregao);
            $stmt->bindValue("p04", $id_fornecedor);
            $stmt->bindValue("p05", $anexo);
            $stmt->bindValue("p06", $observacoes);
            $stmt->bindValue("p07", $objeto);
            $stmt->bindValue("p08", $id);
            $stmt->execute();

            // Decodifica a string JSON para um array associativo
            $dataItens = json_decode($itens, true);

            // Verifica se a decodificação foi bem-sucedida
            if (json_last_error() === JSON_ERROR_NONE) {
                // Verifica se o array $dataItens está vazio
                if (!empty($dataItens)) {

                    // Remove os ítens do contrato
                    $qry = "DELETE FROM contrato_itens WHERE id_contrato = '$id'";
                    $stmt = $this->conexao->prepare($qry);
                    $stmt->execute();

                    // Itera sobre os elementos do array
                    foreach ($dataItens as $item) {
                        $qry = "INSERT INTO contrato_itens(
                        id_contrato,
                        item,
                        lote,
                        descricao_curta,
                        descricao_longa,
                        unidade,
                        quantidade,
                        unitario,
                        total,
                        qt_contrato)VALUES(
                            :p01,
                            :p02,
                            :p03,
                            :p04,
                            :p05,
                            :p06,
                            :p07,
                            :p08,
                            :p09,
                            :p10)";
                        $stmt = $this->conexao->prepare($qry);
                        $stmt->bindValue("p01", $id);
                        $stmt->bindValue("p02", $item['item']);
                        $stmt->bindValue("p03", $item['lote'] == null ? 1 : $item['lote']);
                        $stmt->bindValue("p04", $item['nomeFantasia']);
                        $stmt->bindValue("p05", $item['descricao']);
                        $stmt->bindValue("p06", $item['und']);
                        $stmt->bindValue("p07", $item['quantidade']);
                        $stmt->bindValue("p08", $item['valorUnit']);
                        $stmt->bindValue("p09", $item['valorTotal']);
                        $stmt->bindValue("p10", $item['quantidade']);
                        $stmt->execute();

                        //Baixar estoque

                        //Baixar estoque
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
                    'url' => 'api/contrato/' . $id
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

        $data_inicial = $data['data_inicial'];
        $data_final = $data['data_final'];
        $numero_pregao = $data['numero_pregao'];
        $id_fornecedor = $data['id_fornecedor'];
        $observacoes = $data['observacoes'];
        $objeto = $data['objeto'];
        $anexo = $data['anexo'];
        $itens = $data['itens'];

        try {
            $qry = "INSERT INTO contrato(
                    cadastro,
                    hora,
                    data_inicial,
                    data_final,
                    numero_pregao,
                    id_fornecedor,
                    anexo,
                    observacoes,
                    objeto)VALUES(
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
            $stmt->bindValue("p01", $dataAtual);
            $stmt->bindValue("p02", $hora);
            $stmt->bindValue("p03", $data_inicial);
            $stmt->bindValue("p04", $data_final);
            $stmt->bindValue("p05", $numero_pregao);
            $stmt->bindValue("p06", $id_fornecedor);
            $stmt->bindValue("p07", $anexo);
            $stmt->bindValue("p08", $observacoes);
            $stmt->bindValue("p09", $objeto);
            $stmt->execute();

            $ultimoID = "SELECT id FROM contrato ORDER BY id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($ultimoID);
            $stmt->execute();
            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $ultimoID = $row['id'];
            }

            // Decodifica a string JSON para um array associativo
            $dataItens = json_decode($itens, true);

            // Verifica se a decodificação foi bem-sucedida
            if (json_last_error() === JSON_ERROR_NONE) {
                // Itera sobre os elementos do array
                foreach ($dataItens as $item) {
                    $qry = "INSERT INTO contrato_itens(
                        id_contrato,
                        item,
                        lote,
                        descricao_curta,
                        descricao_longa,
                        unidade,
                        quantidade,
                        unitario,
                        total)VALUES(
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
                    $stmt->bindValue("p01", $ultimoID);
                    $stmt->bindValue("p02", $item['item']);
                    $stmt->bindValue("p03", $item['lote'] == null ? 1 : $item['lote']);
                    $stmt->bindValue("p04", $item['nomeFantasia']);
                    $stmt->bindValue("p05", $item['descricao']);
                    $stmt->bindValue("p06", $item['und']);
                    $stmt->bindValue("p07", $item['quantidade']);
                    $stmt->bindValue("p08", $item['valorUnit']);
                    $stmt->bindValue("p09", $item['valorTotal']);
                    $stmt->execute();

                    //Baixar estoque

                    //Baixar estoque
                }
            } else {
                echo "Erro ao decodificar o JSON: " . json_last_error_msg();
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
                    'url' => 'api/contrato'
                )
            )
        );

        return $response;
    }

    function AtualizaEstoque($data)
    {
        $response = "";
        http_response_code(200);

        $id_item = $data['id_item'];
        $lote = $data['lote'];
        $quantidade = $data['quantidade'];
        $tipoupdate = $data['tipoupdate'];

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

    function saldoAtualItem($id, $lote)
    {
        $data = [];

        try {
            $qry = "SELECT id, lote, quantidade FROM contrato_itens 
                WHERE id = '$id' AND lote = '$lote' LIMIT 1";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "saldo" => $row['quantidade'] <= 0 ? 0 : $row['quantidade'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/contrato/saldoatual",
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

    function viewItenmContrato($id)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT ci.*, c.numero_pregao FROM contrato_itens ci
                LEFT JOIN contrato c ON (ci.id_contrato = c.id) 
                WHERE ci.id = '$id'  ORDER BY ci.descricao_curta";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "item" => $row['item'],
                        "lote" => $row['lote'],
                        "descricao_curta" => $row['descricao_curta'],
                        "descricao_longa" => $row['descricao_longa'],
                        "unidade" => $row['unidade'],
                        "quantidade" => $row['quantidade'],
                        "unitario" => $row['unitario'],
                        "total" => $row['total'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/contratos/item/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "contratoiten" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "contratoiten" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "contratoiten" => $data,
        ];
    }

    function updateItem($data)
    {
        $response = "";
        http_response_code(200);

        $id_item = $data['id_item'];
        $lote = $data['lote'];
        $quantidade = $data['quantidade'];

        try {
            $qry = "UPDATE contrato_itens SET                
            quantidade =:p01
            WHERE id =:p02 
            AND lote =:p03";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $quantidade);
            $stmt->bindValue("p02", $id_item);
            $stmt->bindValue("p03", $lote);
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
                    'type' => 'POST',
                    'description' => 'Registro atualizado com sucesso',
                    'url' => 'api/contrato/updateitem'
                )
            )
        );

        return $response;
    }
}