<?php
namespace src\models;

use \core\Model;

class ClienteModel extends Model
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
            $qry = "SELECT c.id, c.razaosocial, c.nomefantasia, 
                c.endereco, c.numero, c.cadastro, b.descricao as bairro, cd.uf as cidade, 
                c.cfpcnpj, c.id_bairro, c.id_cidade, c.cep, c.telefone1, c.telefone2, 
                c.observacoes, c.rginsestadual, c.uf, c.insmunicipal, c.idcategoria, 
                c.id_tabela_preco, c.correio, c.id_bairro, c.id_cidade, c.correio             
                FROM cliente c
                LEFT JOIN bairro b ON (c.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (c.id_cidade = cd.id)
                WHERE c.situacao = 'A' ORDER BY c.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nomefantasia" => $row['nomefantasia'],
                    "razaosocial" => $row['razaosocial'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cfpcnpj" => $row['cfpcnpj'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "rginsestadual" => $row['rginsestadual'],
                    "id_tabela_preco" => $row['id_tabela_preco'],
                    "cnpj_nome" => $row['cfpcnpj'] . ' - ' . $row['nomefantasia'],                    
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clientes/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "clientes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientes" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT c.id, c.razaosocial, c.nomefantasia, 
                c.endereco, c.numero, c.cadastro, b.descricao as bairro, cd.uf as cidade, 
                c.cfpcnpj, c.id_bairro, c.id_cidade, c.cep, c.telefone1, c.telefone2, 
                c.observacoes, c.rginsestadual, c.uf, c.insmunicipal, c.idcategoria, 
                c.id_tabela_preco, c.correio, c.id_bairro, c.id_cidade, c.correio 
                FROM cliente c
                LEFT JOIN bairro b ON (c.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (c.id_cidade = cd.id)
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
                    "nomefantasia" => $row['nomefantasia'],
                    "razaosocial" => $row['razaosocial'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cfpcnpj" => $row['cfpcnpj'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "rginsestadual" => $row['rginsestadual'],
                    "id_tabela_preco" => $row['id_tabela_preco'],
                    "cnpj_nome" => $row['nomefantasia'] . ' - ' . $row['cfpcnpj'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clientes/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "clientes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientes" => $data,
        ];
    }

    function listarVendedor($idvendedor)
    {
        $data = [];

        try {
            $qry = "SELECT c.id, c.razaosocial, c.nomefantasia, 
                c.endereco, c.numero, c.cadastro, b.descricao as bairro, cd.uf as cidade, 
                c.cfpcnpj, c.id_bairro, c.id_cidade, c.cep, c.telefone1, c.telefone2, 
                c.observacoes, c.rginsestadual, c.uf, c.insmunicipal, c.idcategoria, 
                c.id_tabela_preco, c.correio, c.id_bairro, c.id_cidade FROM cliente c
                LEFT JOIN bairro b ON (c.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (c.id_cidade = cd.id)
                WHERE c.situacao = 'A' AND c.id_vendedor = '$idvendedor'   
                ORDER BY c.nomefantasia";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nomefantasia" => $row['nomefantasia'],
                    "razaosocial" => $row['razaosocial'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cfpcnpj" => $row['cfpcnpj'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "rginsestadual" => $row['rginsestadual'],
                    "id_tabela_preco" => $row['id_tabela_preco'],
                    "cnpj_nome" => $row['cfpcnpj'] . ' - ' . $row['nomefantasia'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clientes/listar/partial/vendedor/{idvendedor}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "clientes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientes" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT c.id, c.razaosocial, c.nomefantasia, 
                c.endereco, c.numero, c.cadastro, b.descricao as bairro, cd.uf as cidade, 
                c.cfpcnpj, c.id_bairro, c.id_cidade, c.cep, c.telefone1, c.telefone2, 
                c.observacoes, c.rginsestadual, c.uf, c.insmunicipal, c.idcategoria, 
                c.id_tabela_preco, c.correio, c.id_bairro, c.id_cidade FROM cliente c
                LEFT JOIN bairro b ON (c.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (c.id_cidade = cd.id)
                WHERE c.situacao = 'A' AND c.nomefantasia like '%" . $texto . "%' 
                ORDER BY c.nomefantasia LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nomefantasia" => $row['nomefantasia'],
                    "razaosocial" => $row['razaosocial'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cfpcnpj" => $row['cfpcnpj'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "rginsestadual" => $row['rginsestadual'],
                    "id_tabela_preco" => $row['id_tabela_preco'],
                    "cnpj_nome" => $row['cfpcnpj'] . ' - ' . $row['nomefantasia'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clientes/search/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "clientes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientes" => $data,
        ];
    }

    function pesquisarComVendedor($texto, $idvendedor)
    {
        $data = [];

        try {
            $qry = "SELECT c.id, c.razaosocial, c.nomefantasia, 
                c.endereco, c.numero, c.cadastro, b.descricao as bairro, cd.uf as cidade, 
                c.cfpcnpj, c.id_bairro, c.id_cidade, c.cep, c.telefone1, c.telefone2, 
                c.observacoes, c.rginsestadual, c.uf, c.insmunicipal, c.idcategoria, 
                c.id_tabela_preco, c.correio, c.id_bairro, c.id_cidade FROM cliente c
                LEFT JOIN bairro b ON (c.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (c.id_cidade = cd.id)
                WHERE c.situacao = 'A' AND   
                c.nomefantasia like '%" . $texto . "%' 
                AND c.id_vendedor = '$idvendedor'
                ORDER BY c.nomefantasia LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nomefantasia" => $row['nomefantasia'],
                    "razaosocial" => $row['razaosocial'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cfpcnpj" => $row['cfpcnpj'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "rginsestadual" => $row['rginsestadual'],
                    "id_tabela_preco" => $row['id_tabela_preco'],
                    "cnpj_nome" => $row['cfpcnpj'] . ' - ' . $row['nomefantasia'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clientes/search/vendedor/{texto}/{idvendedor}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "clientes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientes" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT * FROM cliente WHERE id = '$id'";
            $stmt = $this->conexao->prepare($qry);
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
                        "nomefantasia" => $row['nomefantasia'],
                        "endereco" => $row['endereco'],
                        "numero" => $row['numero'],
                        "bairro" => $row['bairro'],
                        "cidade" => $row['cidade'],
                        "telefone1" => $row['telefone1'],
                        "correio" => $row['correio'],
                        "cfpcnpj" => $row['cfpcnpj'],
                        "observacoes" => $row['observacoes'],
                        "id_bairro" => $row['id_bairro'],
                        "id_cidade" => $row['id_cidade'],
                        "cep" => $row['cep'],
                        "telefone2" => $row['telefone2'],
                        "uf" => $row['uf'],
                        "rginsestadual" => $row['rginsestadual'],
                        "idcategoria" => $row['idcategoria'],
                        "insmunicipal" => $row['insmunicipal'],
                        "id_tabela_preco" => $row['id_tabela_preco'],
                        "senha_acesso" => $row['senha_acesso'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/clientes/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "cliente" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "cliente" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "cliente" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM cliente WHERE id = '$id'";
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
                'url' => 'api/cliente',
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
        $nomefantasia = $data['nomefantasia'];
        $razaosocial = $data['razaosocial'];
        $idcategoria = $data['idcategoria'];
        $endereco = $data['endereco'];
        $numero = $data['numero'];
        $bairro = $data['bairro'];
        $cidade = $data['cidade'];
        $uf = $data['uf'];
        $cep = $data['cep'];
        $telefone1 = $data['telefone1'];
        $telefone2 = $data['telefone2'];
        $cfpcnpj = $data['cfpcnpj'];
        $observacoes = $data['observacoes'];
        $id_cidade = $data['id_cidade'];
        $id_bairro = $data['id_bairro'];
        $correio = $data['correio'];
        $codcidade = $data['codcidade'];
        $rginsestadual = $data['rginsestadual'];
        $insmunicipal = $data['insmunicipal'];
        $senha_acesso = $data['senha_acesso'];

        $bairro = "";
        $ultimoID = "SELECT id, descricao FROM bairro WHERE id = '$id_bairro'";
        $stmt = $this->conexao->prepare($ultimoID);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $bairro = $row['descricao'];
        }

        $cidade = "";
        $ultimoID = "SELECT id, uf FROM clientecidade WHERE id = '$id_cidade'";
        $stmt = $this->conexao->prepare($ultimoID);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $cidade = $row['uf'];
        }

        try {
            $qry = "UPDATE cliente SET 
                idcategoria =:p01, 
                cfpcnpj =:p02, 
                nomefantasia =:p03, 
                razaosocial =:p04, 
                endereco =:p05, 
                numero =:p06, 
                bairro =:p07, 
                cidade =:p08, 
                uf =:p09, 
                cep =:p10, 
                telefone1 =:p11, 
                telefone2 =:p12, 
                correio =:p13, 
                rginsestadual =:p14, 
                observacoes =:p15, 
                codcidade =:p16, 
                id_cidade =:p17, 
                id_bairro =:p18,
                insmunicipal =:p19,
                senha_acesso =:p20
            WHERE id =:p21";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $idcategoria);
            $stmt->bindValue("p02", $cfpcnpj);
            $stmt->bindValue("p03", $nomefantasia);
            $stmt->bindValue("p04", $razaosocial);
            $stmt->bindValue("p05", $endereco);
            $stmt->bindValue("p06", $numero);
            $stmt->bindValue("p07", $bairro);
            $stmt->bindValue("p08", $cidade);
            $stmt->bindValue("p09", $uf);
            $stmt->bindValue("p10", $cep);
            $stmt->bindValue("p11", $telefone1);
            $stmt->bindValue("p12", $telefone2);
            $stmt->bindValue("p13", $correio);
            $stmt->bindValue("p14", $rginsestadual);
            $stmt->bindValue("p15", $observacoes);
            $stmt->bindValue("p16", $codcidade);
            $stmt->bindValue("p17", $id_cidade);
            $stmt->bindValue("p18", $id_bairro);
            $stmt->bindValue("p19", $insmunicipal);
            $stmt->bindValue("p20", $senha_acesso);
            $stmt->bindValue("p21", $id);
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
                    'url' => 'api/cliente/' . $id
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

        $nomefantasia = $data['nomefantasia'];
        $razaosocial = $data['razaosocial'];
        $idcategoria = $data['idcategoria'];
        $endereco = $data['endereco'];
        $numero = $data['numero'];
        $bairro = $data['bairro'];
        $cidade = $data['cidade'];
        $uf = $data['uf'];
        $cep = $data['cep'];
        $telefone1 = $data['telefone1'];
        $telefone2 = $data['telefone2'];
        $cnpj = $data['cnpj'];
        $observacoes = $data['observacoes'];
        $id_cidade = $data['id_cidade'];
        $id_bairro = $data['id_bairro'];
        $correio = $data['correio'];
        $insestadual = $data['insestadual'];
        $codcidade = $data['codcidade'];
        $insmunicipal = $data['insmunicipal'];
        $id_vendedor = $data['id_vendedor'];
        $senha_acesso = $data['senha_acesso'];

        $bairro = "";
        $ultimoID = "SELECT id, descricao FROM bairro WHERE id = '$id_bairro'";
        $stmt = $this->conexao->prepare($ultimoID);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $bairro = $row['descricao'];
        }

        $cidade = "";
        $ultimoID = "SELECT id, uf FROM clientecidade WHERE id = '$id_cidade'";
        $stmt = $this->conexao->prepare($ultimoID);
        $stmt->execute();
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($results as $row) {
            $cidade = $row['uf'];
        }

        try {
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
                cadastro, 
                tipo,
                sexo,
                insmunicipal,
                rota,
                desconto,
                taxa, 
                id_vendedor,
                senha_acesso)VALUES(
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
            $stmt->bindValue("p01", $idcategoria);
            $stmt->bindValue("p02", $cnpj);
            $stmt->bindValue("p03", $nomefantasia);
            $stmt->bindValue("p04", $razaosocial);
            $stmt->bindValue("p05", $endereco);
            $stmt->bindValue("p06", $numero);
            $stmt->bindValue("p07", $bairro);
            $stmt->bindValue("p08", $cidade);
            $stmt->bindValue("p09", $uf);
            $stmt->bindValue("p10", $cep);
            $stmt->bindValue("p11", $telefone1);
            $stmt->bindValue("p12", $telefone2);
            $stmt->bindValue("p13", $correio);
            $stmt->bindValue("p14", $insestadual);
            $stmt->bindValue("p15", $observacoes);
            $stmt->bindValue("p16", $codcidade);
            $stmt->bindValue("p17", $id_cidade);
            $stmt->bindValue("p18", $id_bairro);
            $stmt->bindValue("p19", 1);
            $stmt->bindValue("p20", 'A');
            $stmt->bindValue("p21", $dataAtual);
            $stmt->bindValue("p22", 'J');
            $stmt->bindValue("p23", 'O');
            $stmt->bindValue("p24", $insmunicipal);
            $stmt->bindValue("p25", 'A');
            $stmt->bindValue("p26", 0);
            $stmt->bindValue("p27", 0);
            $stmt->bindValue("p28", $id_vendedor);
            $stmt->bindValue("p29", $senha_acesso);
            $stmt->execute();

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
                    'url' => 'api/fornecedor'
                )
            )
        );

        return $response;
    }

    function contaTotaClientes()
    {
        $data = [];
        $vTotalConta = 0;
        http_response_code(200);

        try {
            $qry = "SELECT COUNT(*) AS totalcliente FROM cliente";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "total" => $row['totalcliente'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clientes/totalcliente",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "clientes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientes" => $data,
        ];
    }

    function listarMobile()
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT c.id, c.nomefantasia, c.cfpcnpj FROM cliente c 
                ORDER BY c.nomefantasia DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "value" => $row['id'],
                    "label" => $row['nomefantasia'] . ' ' . ' [ ' . $row['cfpcnpj'] . ' ]',
                    "id" => $row['id'],
                    "name" => $row['nomefantasia'] . ' ' . ' [ ' . $row['cfpcnpj'] . ' ]',
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clientes/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "clientes" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientes" => $data,
        ];
    }
}