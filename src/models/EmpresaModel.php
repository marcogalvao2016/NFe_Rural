<?php

namespace src\models;

use \core\Model;

class EmpresaModel extends Model
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
            $qry = "SELECT e.id, e.razaosocial, e.nomefantasia, e.correio,
                e.endereco, e.bairro, e.cidade, e.numero, e.cadastro, e.cnpj, e.cep, 
                e.telefone1, e.telefone2, e.observacoes, e.insestadual, e.uf, e.correio, 
                e.id_estabelecimento, e.tipo_empresa, e.regimetributario, 
                e.id_regime_tributario, e.nomeproprietario, e.chave_pix,
                e.insmunicipal, e.codigo_servico FROM empresa e                
                WHERE e.situacao = 'A' ORDER BY e.id DESC LIMIT 30";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'razaosocial' => $row['razaosocial'],
                    'nomefantasia' => $row['nomefantasia'],
                    'endereco' => $row['endereco'],
                    'numero' => $row['numero'],
                    'cadastro' => $row['cadastro'],
                    'bairro' => $row['bairro'],
                    'cidade' => $row['cidade'],
                    'cnpj' => $row['cnpj'],
                    'cep' => $row['cep'],
                    'telefone1' => $row['telefone1'],
                    'telefone2' => $row['telefone2'],
                    'observacoes' => $row['observacoes'],
                    'tipo_empresa' => $row['tipo_empresa'],
                    'id_regime_tributario' => $row['id_regime_tributario'],
                    'uf' => $row['uf'],
                    'insmunicipal' => $row['insmunicipal'],
                    'insestadual' => $row['insestadual'],
                    'codigo_servico' => $row['codigo_servico'],
                    'nomeproprietario' => $row['nomeproprietario'],
                    'chave_pix' => $row['chave_pix'],
                    'correio' => $row['correio'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/empresas/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "empresas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "empresas" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT e.id, e.razaosocial, e.nomefantasia, e.correio,
                e.endereco, e.bairro, e.cidade, e.numero, e.cadastro, e.cnpj, e.cep, 
                e.telefone1, e.telefone2, e.observacoes, e.insestadual, e.uf, e.correio, 
                e.id_estabelecimento, e.tipo_empresa, e.regimetributario, 
                e.id_regime_tributario, e.nomeproprietario, e.chave_pix,
                e.insmunicipal, e.codigo_servico FROM empresa e                
                WHERE e.situacao = 'A'    
                ORDER BY e.id DESC LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'razaosocial' => $row['razaosocial'],
                    'nomefantasia' => $row['nomefantasia'],
                    'endereco' => $row['endereco'],
                    'numero' => $row['numero'],
                    'cadastro' => $row['cadastro'],
                    'bairro' => $row['bairro'],
                    'cidade' => $row['cidade'],
                    'cnpj' => $row['cnpj'],
                    'cep' => $row['cep'],
                    'telefone1' => $row['telefone1'],
                    'telefone2' => $row['telefone2'],
                    'observacoes' => $row['observacoes'],
                    'tipo_empresa' => $row['tipo_empresa'],
                    'id_regime_tributario' => $row['id_regime_tributario'],
                    'uf' => $row['uf'],
                    'insmunicipal' => $row['insmunicipal'],
                    'insestadual' => $row['insestadual'],
                    'codigo_servico' => $row['codigo_servico'],
                    'nomeproprietario' => $row['nomeproprietario'],
                    'chave_pix' => $row['chave_pix'],
                    'correio' => $row['correio'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/empresas/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "empresas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "empresas" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT e.id, e.razaosocial, e.nomefantasia, e.correio,
                e.endereco, e.bairro, e.cidade, e.numero, e.cadastro, e.cnpj, e.cep, 
                e.telefone1, e.telefone2, e.observacoes, e.insestadual, e.uf, e.correio, 
                e.id_estabelecimento, e.tipo_empresa, e.regimetributario, 
                e.id_regime_tributario, e.nomeproprietario, e.chave_pix,
                e.insmunicipal, e.codigo_servico FROM empresa e                
                WHERE e.situacao = 'A'  
                AND e.nomefantasia like '%" . $texto . "%' 
                ORDER BY e.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    'id' => intval($row['id']),
                    'razaosocial' => $row['razaosocial'],
                    'nomefantasia' => $row['nomefantasia'],
                    'endereco' => $row['endereco'],
                    'numero' => $row['numero'],
                    'cadastro' => $row['cadastro'],
                    'bairro' => $row['bairro'],
                    'cidade' => $row['cidade'],
                    'cnpj' => $row['cnpj'],
                    'cep' => $row['cep'],
                    'telefone1' => $row['telefone1'],
                    'telefone2' => $row['telefone2'],
                    'observacoes' => $row['observacoes'],
                    'tipo_empresa' => $row['tipo_empresa'],
                    'id_regime_tributario' => $row['id_regime_tributario'],
                    'uf' => $row['uf'],
                    'insmunicipal' => $row['insmunicipal'],
                    'insestadual' => $row['insestadual'],
                    'codigo_servico' => $row['codigo_servico'],
                    'nomeproprietario' => $row['nomeproprietario'],
                    'chave_pix' => $row['chave_pix'],
                    'correio' => $row['correio'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/empresas/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "empresas" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "empresas" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT e.id, e.razaosocial, e.nomefantasia, e.correio,
                e.endereco, e.bairro, e.cidade, e.numero, e.cadastro, e.cnpj, e.cep, 
                e.telefone1, e.telefone2, e.observacoes, e.insestadual, e.uf, e.correio, 
                e.id_estabelecimento, e.tipo_empresa, e.regimetributario, 
                e.id_regime_tributario, e.nomeproprietario, e.chave_pix,
                e.insmunicipal, e.codigo_servico, e.cnae, e.aliquota_iss,
                e.codigo_tributa_municipio FROM empresa e                 
                WHERE e.situacao = 'A' AND e.id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        'id' => intval($row['id']),
                        'razaosocial' => $row['razaosocial'],
                        'nomefantasia' => $row['nomefantasia'],
                        'endereco' => $row['endereco'],
                        'numero' => $row['numero'],
                        'cadastro' => $row['cadastro'],
                        'bairro' => $row['bairro'],
                        'cidade' => $row['cidade'],
                        'cnpj' => $row['cnpj'],
                        'cep' => $row['cep'],
                        'telefone1' => $row['telefone1'],
                        'telefone2' => $row['telefone2'],
                        'observacoes' => $row['observacoes'],
                        'tipo_empresa' => $row['tipo_empresa'],
                        'id_regime_tributario' => $row['id_regime_tributario'],
                        'uf' => $row['uf'],
                        'insestadual' => $row['insestadual'],
                        'insmunicipal' => $row['insmunicipal'],
                        'codigo_servico' => $row['codigo_servico'],
                        'nomeproprietario' => $row['nomeproprietario'],
                        'chave_pix' => $row['chave_pix'],
                        'cnae' => $row['cnae'],
                        'aliquota_iss' => $row['aliquota_iss'],
                        'codigo_tributacao_municipio' => $row['codigo_tributa_municipio'],
                        'correio' => $row['correio'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/empresas/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "empresa" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "empresa" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "empresa" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM empresa WHERE id = '$id'";
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
        $insestadual = $data['insestadual'];
        $codcidade = $data['codcidade'];
        $insmunicipal = $data['insmunicipal'];
        $id_regime_tributario = $data['id_regime_tributario'];
        $codigo_tributa_municipio = $data['codigo_tributa_municipio'];
        $codigo_servico = $data['codigo_servico'];
        $correio = $data['correio'];
        $tipo_empresa = $data['tipo_empresa'];
        $chave_pix = $data['chave_pix'];

        try {
            $qry = "UPDATE empresa SET        
                cnpj =:p01, 
                nomefantasia =:p02, 
                razaosocial =:p03, 
                endereco =:p04, 
                numero =:p05, 
                bairro =:p06, 
                cidade =:p07, 
                uf =:p08, 
                cep =:p09, 
                telefone1 =:p10, 
                telefone2 =:p11,               
                insestadual =:p12, 
                observacoes =:p13, 
                ibge =:p14,        
                insmunicipal =:p15,
                id_regime_tributario =:p16,
                codigo_tributa_municipio =:p17,
                codigo_servico =:p18,
                correio =:p19,
                tipo_empresa =:p20,
                chave_pix =:p21
            WHERE id =:p22";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $cnpj);
            $stmt->bindValue("p02", $nomefantasia);
            $stmt->bindValue("p03", $razaosocial);
            $stmt->bindValue("p04", $endereco);
            $stmt->bindValue("p05", $numero);
            $stmt->bindValue("p06", $bairro);
            $stmt->bindValue("p07", $cidade);
            $stmt->bindValue("p08", $uf);
            $stmt->bindValue("p09", $cep);
            $stmt->bindValue("p10", $telefone1);
            $stmt->bindValue("p11", $telefone2);
            $stmt->bindValue("p12", $insestadual);
            $stmt->bindValue("p13", $observacoes);
            $stmt->bindValue("p14", $codcidade);
            $stmt->bindValue("p15", $insmunicipal);
            $stmt->bindValue("p16", $id_regime_tributario);
            $stmt->bindValue("p17", $codigo_tributa_municipio);
            $stmt->bindValue("p18", $codigo_servico);
            $stmt->bindValue("p19", $correio);
            $stmt->bindValue("p20", $tipo_empresa);
            $stmt->bindValue("p21", $chave_pix);
            $stmt->bindValue("p22", $id);
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
        $id_regime_tributario = $data['id_regime_tributario'];
        $codigo_tributa_municipio = $data['codigo_tributacao_municipio'];
        $codigo_servico = $data['codigo_servico'];
        $correio = $data['correio'];
        $tipo_empresa = $data['tipo_empresa'];
        $chave_pix = $data['chave_pix'];

        try {
            $qry = "INSERT INTO empresa(        
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
                insestadual, 
                observacoes, 
                ibge,        
                insmunicipal,
                id_regime_tributario,
                codigo_tributa_municipio,
                codigo_servico,
                correio,
                tipo_empresa,
                chave_pix)VALUES(                       
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
                        :p21)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $cnpj);
            $stmt->bindValue("p02", $nomefantasia);
            $stmt->bindValue("p03", $razaosocial);
            $stmt->bindValue("p04", $endereco);
            $stmt->bindValue("p05", $numero);
            $stmt->bindValue("p06", $bairro);
            $stmt->bindValue("p07", $cidade);
            $stmt->bindValue("p08", $uf);
            $stmt->bindValue("p09", $cep);
            $stmt->bindValue("p10", $telefone1);
            $stmt->bindValue("p11", $telefone2);
            $stmt->bindValue("p12", $insestadual);
            $stmt->bindValue("p13", $observacoes);
            $stmt->bindValue("p14", $codcidade);
            $stmt->bindValue("p15", $insmunicipal);
            $stmt->bindValue("p16", $id_regime_tributario);
            $stmt->bindValue("p17", $codigo_tributa_municipio);
            $stmt->bindValue("p18", $codigo_servico);
            $stmt->bindValue("p19", $correio);
            $stmt->bindValue("p20", $tipo_empresa);
            $stmt->bindValue("p21", $chave_pix);
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
}
