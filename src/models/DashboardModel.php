<?php

namespace src\models;

use \core\Model;

function getImagensAPI($arrayImagens)
{
    $vMidias = "";

    if (is_array($arrayImagens)) {
        if (count($arrayImagens) > 0) {
            foreach ($arrayImagens as $index => $item) {
                if ($index !== 0) {
                    $vMidias .= "; ";
                }
                $vMidias .= $item['valor'];
            }
        } else {
            error_log("O array está vazio.");
        }
    } else {
        error_log("A variável não é um array.");
    }

    return $vMidias;
}

function getCaracteristicas($vTexto)
{
    if (!empty($vTexto)) {
        $vTexto = str_replace("'", "''", $vTexto);
    }

    return $vTexto;
}

function getDescricao($vTexto)
{
    if (!empty($vTexto)) {
        $vTexto = str_replace("'", "''", $vTexto);
    }

    return $vTexto;
}

function getTitulo($vTexto)
{
    if (!empty($vTexto)) {
        $vTexto = str_replace("'", "''", $vTexto);
    }

    return $vTexto;
}

function getPrimeiroDiaMes()
{
    // Obtém o ano e mês atuais
    $anoAtual = date("Y");
    $mesAtual = date("m");

    // Define a data para o primeiro dia do mês atual
    $primeiroDiaDoMes = "{$anoAtual}-{$mesAtual}-01";

    return $primeiroDiaDoMes;
}

class DashboardModel extends Model
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
        $count = 0;
        http_response_code(200);

        try {
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "bairros" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "bairros" => $data,
        ];
    }

    function totalClientes()
    {
        $data = [];

        try {
            $qry = "SELECT COUNT(*) AS totalClientes FROM cliente";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalClientes'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalclientes",
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

    function totalClientesVendedor($idvendedor)
    {
        $data = [];

        try {
            $qry = "SELECT COUNT(*) AS totalClientes FROM cliente 
                WHERE id_vendedor = '$idvendedor'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalClientes'] == null ? 0 : $row['totalClientes'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalclientesvendedor/{idvendedor}",
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

    function totalClientesMes()
    {
        $data = [];
        $vPrimeiroDiaMes = getPrimeiroDiaMes();

        try {
            $qry = "SELECT COUNT(*) AS totalClientes 
                FROM cliente WHERE cadastro >= '$vPrimeiroDiaMes'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalClientes'] == null ? 0 : $row['totalClientes'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalclientesmes",
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

    function totalClientesMesVendedor($idvendedor)
    {
        $data = [];
        $vPrimeiroDiaMes = getPrimeiroDiaMes();

        try {
            $qry = "SELECT COUNT(*) AS totalClientes 
                FROM cliente WHERE id_vendedor = '$idvendedor' 
                AND cadastro >= '$vPrimeiroDiaMes'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalClientes'] == null ? 0 : $row['totalClientes'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalclientesmesvendedor/{idvendedor}",
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

    function totalVendasMes()
    {
        $data = [];
        $vPrimeiroDiaMes = getPrimeiroDiaMes();

        try {
            $qry = "SELECT SUM(total) AS totalVenda 
                FROM venda WHERE situacao = 'F' AND venda_ativa = 'S' 
                AND data >= '$vPrimeiroDiaMes'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalVenda'] == null ? 0 : $row['totalVenda'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalVendasMes",
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

    function totalVendasCliente($idcliente)
    {
        $data = [];
        $vPrimeiroDiaMes = getPrimeiroDiaMes();

        try {
            $qry = "SELECT SUM(v.total) AS totalVenda 
                FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                WHERE v.situacao = 'F' AND v.venda_ativa = 'S' 
                AND v.idcliente = '$idcliente'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalVenda'] == null ? 0 : $row['totalVenda'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalVendasCliente/{idvendedor}",
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

    function totalQtVendasCliente($idcliente)
    {
        $data = [];
        $vPrimeiroDiaMes = getPrimeiroDiaMes();

        try {
            $qry = "SELECT COUNT(*) AS totalQtVenda FROM venda v 
                LEFT JOIN cliente c ON (v.idcliente = c.id)
                WHERE v.idcliente = '$idcliente' AND v.tipo = 'V'
                AND v.data >= '$vPrimeiroDiaMes'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalQtVenda'] == null ? 0 : $row['totalQtVenda'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalvendasqtcliente/{idcliente}",
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

    function totalVendasMesVendedor($idvendedor)
    {
        $data = [];
        $vPrimeiroDiaMes = getPrimeiroDiaMes();

        try {
            $qry = "SELECT SUM(total) AS totalVenda 
                FROM venda WHERE situacao = 'F' AND venda_ativa = 'S' 
                AND data >= '$vPrimeiroDiaMes' AND idatendente = '$idvendedor'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalVenda'] == null ? 0 : $row['totalVenda'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalvendasmesvendedor/{idvendedor}",
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

    function totalCPMes()
    {
        $data = [];
        $vPrimeiroDiaMes = getPrimeiroDiaMes();

        try {
            $qry = "SELECT ROUND(SUM(valor), 2) AS totalCP
                FROM lancamentoscaixa WHERE sigla_origem = 'CP'
                AND situacao = 'N' AND vencimento >= '$vPrimeiroDiaMes'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalCP'] == null ? 0 : $row['totalCP'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalcpmes",
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

    function totalCRMes()
    {
        $data = [];
        $vPrimeiroDiaMes = getPrimeiroDiaMes();

        try {
            $qry = "SELECT ROUND(SUM(valor), 2) AS totalCR
                FROM lancamentoscaixa WHERE sigla_origem = 'CR'
                AND situacao = 'N' AND vencimento >= '$vPrimeiroDiaMes'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalCR'] == null ? 0 : $row['totalCR'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalcrmes",
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

    function totalCP()
    {
        $data = [];

        try {
            $qry = "SELECT ROUND(SUM(valor), 2) AS totalCP
                FROM lancamentoscaixa WHERE sigla_origem = 'CP'
                AND situacao = 'N'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalCP'] == null ? 0 : $row['totalCP'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalcp",
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

    function totalCR()
    {
        $data = [];

        try {
            $qry = "SELECT ROUND(SUM(valor), 2) AS totalCR
                FROM lancamentoscaixa WHERE sigla_origem = 'CR'
                AND situacao = 'N'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalCR'] == null ? 0 : $row['totalCR'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totalcp",
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

    function contaTotalProductEcommerce()
    {
        $data = [];
        $vTotalConta = 0;
        http_response_code(200);

        try {
            $qry = "SELECT COUNT(*) AS totalproduct FROM product";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "total" => $row['totalproduct'] == null ? 0 : $row['totalproduct'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/totalproduct/totalproducts",
                    ]
                );
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "products" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "products" => $data,
        ];
    }

    function createCategoryAPI()
    {
        try {
            // Configuração da requisição cURL
            $url = 'https://api.dslite.com.br/v1/CrossDocking/Categoria';
            $token = '0b9a07af6184c772f95f1a8317f1e6d5';

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Token: $token",
                "Accept: application/json"
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                throw new \Exception(curl_error($ch));
            }

            curl_close($ch);

            $responseData = json_decode($response, true);

            if (isset($responseData['categorias'])) {
                // Remove registros no banco de dados          
                $qry = "TRUNCATE TABLE category";
                $stmt = $this->conexao->prepare($qry);
                $stmt->execute();

                // Insere os registros no banco de dados
                $query = 'INSERT INTO category (descricao, slug, fornecedor_id, category_id, nivel) VALUES (?, ?, ?, ?, ?)';
                $stmt = $this->conexao->prepare($query);

                foreach ($responseData['categorias'] as $registro) {
                    try {
                        $stmt->execute([
                            $registro['nome'],
                            $registro['slug'],
                            $registro['fornecedorid'],
                            $registro['categoriaid'],
                            $registro['nivel']
                        ]);
                        //echo 'Registro inserido com sucesso: ' . $registro['nome'];
                    } catch (\Exception $e) {
                        error_log('Erro ao inserir registro: ' . $e->getMessage());
                    }
                }

                // Retorna a resposta de sucesso
                http_response_code(200);
                return json_encode(['sucesso' => '200OK']);
            } else {
                http_response_code(500);
                throw new \Exception('Erro na resposta da API');
            }
        } catch (\Exception $e) {
            // Retorna a resposta de erro
            http_response_code(500);
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    function createProdcutAPI()
    {

        try {
            $pagina = 1; // Inicializa a página como 1
            $batchSize = 100; // Tamanho do lote de registros a serem buscados
            $token = '0b9a07af6184c772f95f1a8317f1e6d5';

            // Função para inserir registros na base de dados
            function inserirRegistrosNaDB($pdo, $registros)
            {
                foreach ($registros as $registro) {
                    $queryProduto = 'SELECT id, produtoid FROM product WHERE produtoid = ? LIMIT 1';
                    $stmtProduto = $pdo->prepare($queryProduto);
                    $stmtProduto->execute([$registro['produtoid']]);
                    $resultProduto = $stmtProduto->fetch(\PDO::FETCH_ASSOC);

                    $vCaracTe = getCaracteristicas($registro['caracteristicas']);
                    $vDescricao = getDescricao($registro['descricao']);
                    $vTitulo = getTitulo($registro['titulo_curto']);
                    $vMidias = getImagensAPI($registro['midias']);

                    if ($resultProduto) {
                        $vIdItem = $resultProduto['id'];
                        $queryProdutoAtualiza = 'UPDATE product 
                            SET preco_crossdocking = ?, 
                                preco_promocional = ?, 
                                preco_dropshipping = ?, 
                                preco_revenda = ?, 
                                preco_revenda_promocional = ?, 
                                valor_comissao = ?,                         
                                estoque = ?,
                                midias = ?,
                                titulo = ?,
                                tempo_garantia = ?
                            WHERE id = ?';
                        $stmtAtualiza = $pdo->prepare($queryProdutoAtualiza);
                        $stmtAtualiza->execute([
                            $registro['preco_crossdocking'],
                            $registro['preco_promocional'],
                            $registro['preco_dropshipping'],
                            $registro['preco_revenda'],
                            $registro['preco_revenda_promocional'],
                            $registro['valor_comissao'],
                            $registro['estoque'],
                            $vMidias,
                            $vTitulo,
                            $registro['tempo_garantia'],
                            $vIdItem
                        ]);

                        echo 'Registro atualizado com sucesso: ' . $registro['titulo_curto'] . "\n";
                    } else {
                        try {
                            $query = 'INSERT INTO product (
                                produtoid, 
                                fornecedorid,
                                status_empresa,
                                margem_lucro,
                                preco_normal,
                                preco_crossdocking,
                                preco_promocional,
                                preco_dropshipping,
                                preco_revenda,
                                preco_revenda_promocional,
                                origem,
                                visibilidade,
                                valor_comissao,
                                titulo,
                                titulo_curto,
                                categoriaid,
                                categoria_nome,
                                abc,
                                ean11,
                                ncm,                            
                                marca,
                                peso,
                                largura,
                                altura,
                                profundidade,
                                peso_embalagem,
                                largura_embalagem,
                                altura_embalagem,
                                profundidade_embalagem,
                                link,
                                link_imagem,
                                embalagem_unidade,
                                embalagem_quantidade,
                                local_estoque,
                                controle_estoque,
                                estoque,
                                midias,
                                caracteristicas,
                                descricao,
                                tempo_garantia
                            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                                      ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                                      ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 
                                      ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
                            $stmt = $pdo->prepare($query);
                            $stmt->execute([
                                $registro['produtoid'],
                                $registro['fornecedorid'],
                                $registro['status_empresa'],
                                $registro['margem_lucro'],
                                $registro['preco_normal'],
                                $registro['preco_crossdocking'],
                                $registro['preco_promocional'],
                                $registro['preco_dropshipping'],
                                $registro['preco_revenda'],
                                $registro['preco_revenda_promocional'],
                                $registro['origem'],
                                $registro['visibilidade'],
                                $registro['valor_comissao'],
                                $vTitulo,
                                $registro['titulo_curto'],
                                $registro['categoriaid'],
                                $registro['categoria_nome'],
                                $registro['abc'],
                                $registro['ean11'],
                                $registro['ncm'],
                                $registro['marca'],
                                $registro['peso'],
                                $registro['largura'],
                                $registro['altura'],
                                $registro['profundidade'],
                                $registro['peso_embalagem'],
                                $registro['largura_embalagem'],
                                $registro['altura_embalagem'],
                                $registro['profundidade_embalagem'],
                                $registro['link'],
                                $registro['link_imagem'],
                                $registro['embalagem_unidade'],
                                $registro['embalagem_quantidade'],
                                $registro['local_estoque'],
                                $registro['controle_estoque'],
                                $registro['estoque'],
                                $vMidias,
                                $vCaracTe,
                                $vDescricao,
                                $registro['tempo_garantia']
                            ]);

                            return 'Registro inserido com sucesso: ' . $registro['produtoid'] . "\n";
                        } catch (\Exception $e) {
                            return 'Erro ao inserir registro: ' . $registro['produtoid'] . ' - ' . $e->getMessage() . "\n";
                        }
                    }
                }
            }

            // Loop para obter e inserir registros
            while (true) {
                $url = 'https://api.dslite.com.br/v1/CrossDocking/Catalogo/2';
                $params = http_build_query([
                    'limit' => $batchSize,
                    'page' => $pagina,
                    'incluir_variacao' => 'true'
                ]);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "$url?$params");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    "Token: $token",
                    "Accept: application/json"
                ]);

                $response = curl_exec($ch);

                if (curl_errno($ch)) {
                    throw new \Exception(curl_error($ch));
                }
                curl_close($ch);

                $responseData = json_decode($response, true);
                $registros = $responseData['produtos'];

                if (!$registros) {
                    http_response_code(404);
                    return json_encode(['error' => 'Não há registros para importar.']);
                }

                inserirRegistrosNaDB($this->conexao, $registros);

                if (count($registros) < $batchSize) {
                    echo 'Todos os registros foram inseridos.' . "\n";
                    break;
                }

                $pagina++;
            }

            http_response_code(200);
            return json_encode(['sucesso' => 'Todos os registros foram inseridos.']);
        } catch (\Exception $e) {
            echo 'Erro ao obter dados da API: ' . $e->getMessage() . "\n";
            http_response_code(500);
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    function totComandasAbertas()
    {
        $data = [];

        try {
            $qry = "SELECT v.id, v.numeromesa, v.situacao, tipo FROM venda v 
                WHERE v.situacao = 'A' AND v.tipo IN ('M','C','S') AND v.agrupada = 'N'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "totalComandasAbertas" => $count <= 0 ? 0 : $count,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totcomandas",
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

    function totPedidoEspera()
    {
        $data = [];

        try {
            $qry = "SELECT id, numeromesa, situacao, tipo FROM venda 
                WHERE tipo = 'B' AND balcao_espera = 'S' AND situacao = 'A'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "totalPedidoEspera" => $count <= 0 ? 0 : $count,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totbalcaoespera",
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

    function totPedidosAbertos()
    {
        $data = [];

        try {
            $qry = "SELECT id, situacao, tipo, ponto_referencia, transferido 
                FROM venda WHERE transferido = 'N' AND tipo <> 'M'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "totalPedidosOnLineAbertos" => $count <= 0 ? 0 : $count,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/online/totpedidos",
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

    function totClientes($tipo)
    {
        $data = [];
        http_response_code(200);

        $consTipo = "";
        if ($tipo == "NA") {
            $consTipo = "AND cc.contabil = 'N' AND e.situacao = 'A'";
        }
        if ($tipo == "NI") {
            $consTipo = "AND cc.contabil = 'N' AND e.situacao = 'I'";
        }
        if ($tipo == "CA") {
            $consTipo = "AND cc.contabil = 'S' AND e.situacao = 'A'";
        }
        if ($tipo == "CI") {
            $consTipo = "AND cc.contabil = 'S' AND e.situacao = 'I'";
        }

        try {
            $qry = "SELECT e.id FROM emp e 
                LEFT JOIN clientecategoria cc ON (e.id_categoria = cc.id)
                WHERE 1=1 $consTipo";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "totalClientesAtivos" => $count <= 0 ? 0 : $count,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totcliativos",
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

    function totContratosAtivos()
    {
        $data = [];

        try {
            $qry = "SELECT id FROM contrato WHERE situacao = 'A' AND 
                data_final >= CURRENT_DATE() ORDER BY id";
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
                        "url" => "api/dashboard/totcontratosabertos",
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

    function totOSAbertas()
    {
        $data = [];

        try {
            $qry = "SELECT id FROM ordem_servico_secretaria WHERE situacao = 'A' ORDER BY id";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "totalOSAbertos" => $count <= 0 ? 0 : $count,
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/dashboard/totosabertos",
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

    function CorSistema()
    {
        $data = [];

        try {
            $qry = "SELECT descricao FROM cor WHERE status = 'S' 
                ORDER BY id DESC LIMIT 1";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data = array(
                    "cor" => $row['descricao'] == null ? '#0055aa' : $row['descricao'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/cores/coreone",
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
