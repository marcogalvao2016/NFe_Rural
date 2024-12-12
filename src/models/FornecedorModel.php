<?php
namespace src\models;

use \core\Model;

class FornecedorModel extends Model
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
            $qry = "SELECT f.id, f.razaosocial, f.nomefantasia, f.endereco, f.lista_emails,
                f.numero, f.cadastro, b.descricao as bairro, cd.uf as cidade, f.cnpj, f.id_bairro, 
                f.id_cidade, f.cep, f.telefone1, f.telefone2, f.observacoes, f.insestadual, f.uf,
                f.idcategoria, f.correio FROM fornecedor f
                LEFT JOIN bairro b ON (f.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (f.id_cidade = cd.id)
                WHERE f.situacao = 'A'
                ORDER BY f.id DESC LIMIT 200";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nomefantasia" => $row['nomefantasia'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cnpj" => $row['cnpj'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "insestadual" => $row['insestadual'],
                    "idcategoria" => $row['idcategoria'],
                    "lista_emails" => $row['lista_emails'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/fornecedores/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "fornecedores" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "fornecedores" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT f.id, f.razaosocial, f.nomefantasia, f.endereco, f.lista_emails,
                f.numero, f.cadastro, b.descricao as bairro, cd.uf as cidade, f.cnpj, f.id_bairro, 
                f.id_cidade, f.cep, f.telefone1, f.telefone2, f.observacoes, f.insestadual, f.uf,
                f.idcategoria, f.correio FROM fornecedor f
                LEFT JOIN bairro b ON (f.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (f.id_cidade = cd.id)
                WHERE f.situacao = 'A'      
                ORDER BY f.nomefantasia LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nomefantasia" => $row['nomefantasia'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cnpj" => $row['cnpj'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "insestadual" => $row['insestadual'],
                    "idcategoria" => $row['idcategoria'],
                    "lista_emails" => $row['lista_emails'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/fornecedores/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "fornecedores" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "fornecedores" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT f.id, f.razaosocial, f.nomefantasia, f.endereco, f.lista_emails, 
                f.numero, f.cadastro, b.descricao as bairro, cd.uf as cidade, f.cnpj, f.id_bairro, 
                f.id_cidade, f.cep, f.telefone1, f.telefone2, f.observacoes, f.insestadual, f.uf,
                f.idcategoria, f.correio FROM fornecedor f
                LEFT JOIN bairro b ON (f.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (f.id_cidade = cd.id)
                WHERE f.situacao = 'A'    
                AND f.nomefantasia like '%" . $texto . "%' 
                ORDER BY f.nomefantasia LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nomefantasia" => $row['nomefantasia'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cnpj" => $row['cnpj'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "insestadual" => $row['insestadual'],
                    "idcategoria" => $row['idcategoria'],
                    "lista_emails" => $row['lista_emails'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/fornecedores/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "fornecedores" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "fornecedores" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT f.id, f.razaosocial, f.nomefantasia, f.endereco, f.lista_emails,
                f.numero, f.cadastro, b.descricao as bairro, cd.uf as cidade, f.cnpj, f.id_bairro, 
                f.id_cidade, f.cep, f.telefone1, f.telefone2, f.observacoes, f.insestadual, f.uf,
                f.idcategoria, f.correio, f.uf FROM fornecedor f
                LEFT JOIN bairro b ON (f.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (f.id_cidade = cd.id)
                WHERE f.situacao = 'A' AND f.id = '$id'";
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
                        "razaosocial" => $row['razaosocial'],
                        "nomefantasia" => $row['nomefantasia'],
                        "endereco" => $row['endereco'],
                        "numero" => $row['numero'],
                        "bairro" => $row['bairro'],
                        "telefone1" => $row['telefone1'],
                        "telefone2" => $row['telefone2'],
                        "correio" => $row['correio'],
                        "cnpj" => $row['cnpj'],
                        "observacoes" => $row['observacoes'],
                        "id_bairro" => $row['id_bairro'],
                        "id_cidade" => $row['id_cidade'],
                        "insestadual" => $row['insestadual'],
                        "idcategoria" => $row['idcategoria'],
                        "lista_emails" => $row['lista_emails'],
                        "uf" => $row['uf'],
                        "cep" => $row['cep'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/fornecedores/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "fornecedor" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "fornecedor" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "fornecedor" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM fornecedor WHERE id = '$id'";
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
                'url' => 'api/funcionario',
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
        $cnpj = $data['cnpj'];
        $observacoes = $data['observacoes'];
        $id_cidade = $data['id_cidade'];
        $id_bairro = $data['id_bairro'];
        $correio = $data['correio'];
        $insestadual = $data['insestadual'];
        $codcidade = $data['codcidade'];
        $insmunicipal = $data['insmunicipal'];
        $lista_emails = $data['lista_emails'];

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
            $qry = "UPDATE fornecedor SET 
                idcategoria =:p01, 
                cnpj =:p02, 
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
                insestadual =:p14, 
                observacoes =:p15, 
                codcidade =:p16, 
                id_cidade =:p17, 
                id_bairro =:p18, 
                insmunicipal =:p19,
                lista_emails =:p20
            WHERE id =:p21";

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
            $stmt->bindValue("p19", $insmunicipal);
            $stmt->bindValue("p20", $lista_emails);
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
                    'url' => 'api/fornecedor/' . $id
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
        $lista_emails = $data['lista_emails'];

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
            $qry = "INSERT INTO fornecedor (
                idcategoria, 
                cnpj, 
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
                insestadual, 
                observacoes, 
                codcidade, 
                id_cidade, 
                id_bairro,       
                idempresa, 
                situacao, 
                cadastro, 
                tipo,
                lista_emails)VALUES(
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
                        :p23)";
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
            $stmt->bindValue("p23", $lista_emails);      
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

    function printFornecedor($dados)
    {
        $retorno = true;
        $data = [];
        http_response_code(200);

        $id_fornecedor = $dados['id_fornecedor'];
        $dtInicial = $dados['dtInicial'];
        $dtFinal = $dados['dtFinal'];

        $SQLFornecedor = "";
        if ($id_fornecedor != "0") {
            $SQLFornecedor = "AND f.id = '$id_fornecedor'";
        }

        try {
            $qry = "SELECT f.id, f.razaosocial, f.nomefantasia, f.endereco, f.lista_emails,
                f.numero, f.cadastro, b.descricao as bairro, cd.uf as cidade, f.cnpj, f.id_bairro, 
                f.id_cidade, f.cep, f.telefone1, f.telefone2, f.observacoes, f.insestadual, f.uf,
                f.idcategoria, f.correio, f.uf FROM fornecedor f
                LEFT JOIN bairro b ON (f.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (f.id_cidade = cd.id)
                WHERE f.cadastro >= '$dtInicial' AND f.cadastro <= '$dtFinal' 
                $SQLFornecedor";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nomefantasia" => $row['nomefantasia'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cnpj" => $row['cnpj'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "insestadual" => $row['insestadual'],
                    "idcategoria" => $row['idcategoria'],
                    "lista_emails" => $row['lista_emails'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/fornecedores/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "fornecedores" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "fornecedores" => $data,
        ];
    }
}