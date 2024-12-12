<?php
namespace src\models;

use \core\Model;

class FuncionarioModel extends Model
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
            $qry = "SELECT f.id, f.nome, f.endereco, f.numero, f.cadastro, 
                b.descricao as bairro, cd.uf as cidade, f.cfp, 
                f.id_bairro, f.id_cidade, f.cep, f.telefone1, f.telefone2, f.observacoes, 
                f.apelido, f.uf, f.idcargo, f.correio
                FROM funcionario f
                LEFT JOIN bairro b ON (f.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (f.id_cidade = cd.id)
                ORDER BY f.nome LIMIT 30";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nome" => $row['nome'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cfp" => $row['cfp'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "apelido" => $row['apelido'],
                    "uf" => $row['uf'],
                    "idcargo" => $row['idcargo'],
                    "value" => $row['id'],                    
                    "label" => $row['nome'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/funcionarios/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "funcionarios" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "funcionarios" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT f.id, f.nome, f.endereco, f.numero, f.cadastro, 
                b.descricao as bairro, cd.uf as cidade, f.cfp, 
                f.id_bairro, f.id_cidade, f.cep, f.telefone1, f.telefone2, f.observacoes, 
                f.apelido, f.uf, f.idcargo, f.correio
                FROM funcionario f
                LEFT JOIN bairro b ON (f.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (f.id_cidade = cd.id)
                ORDER BY f.nome LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nome" => $row['nome'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cfp" => $row['cfp'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "apelido" => $row['apelido'],
                    "uf" => $row['uf'],
                    "idcargo" => $row['idcargo'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/funcionarios/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "funcionarios" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "funcionarios" => $data,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];

        try {
            $qry = "SELECT f.id, f.nome, f.endereco, f.numero, f.cadastro, 
                b.descricao as bairro, cd.uf as cidade, f.cfp, 
                f.id_bairro, f.id_cidade, f.cep, f.telefone1, f.telefone2, f.observacoes, 
                f.apelido, f.uf, f.idcargo, f.correio
                FROM funcionario f
                LEFT JOIN bairro b ON (f.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (f.id_cidade = cd.id) 
                WHERE descricao like '%" . $texto . "%' 
                ORDER BY f.nome LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cadastro" => $row['cadastro'],
                    "nome" => $row['nome'],
                    "endereco" => $row['endereco'],
                    "numero" => $row['numero'],
                    "bairro" => $row['bairro'],
                    "telefone1" => $row['telefone1'],
                    "correio" => $row['correio'],
                    "cfp" => $row['cfp'],
                    "observacoes" => $row['observacoes'],
                    "id_bairro" => $row['id_bairro'],
                    "id_cidade" => $row['id_cidade'],
                    "apelido" => $row['apelido'],
                    "uf" => $row['uf'],
                    "idcargo" => $row['idcargo'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/funcionarios/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "funcionarios" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "funcionarios" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT f.id, f.nome, f.endereco, f.numero, f.cadastro, 
                b.descricao as bairro, cd.uf as cidade, f.cfp, 
                f.id_bairro, f.id_cidade, f.cep, f.telefone1, f.telefone2, f.observacoes, 
                f.apelido, f.uf, f.idcargo, f.correio, f.cep
                FROM funcionario f
                LEFT JOIN bairro b ON (f.id_bairro = b.id)
                LEFT JOIN clientecidade cd ON (f.id_cidade = cd.id)
                WHERE f.id = '$id'";
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
                        "nome" => $row['nome'],
                        "endereco" => $row['endereco'],
                        "numero" => $row['numero'],
                        "bairro" => $row['bairro'],
                        "telefone1" => $row['telefone1'],
                        "telefone2" => $row['telefone2'],
                        "correio" => $row['correio'],
                        "cfp" => $row['cfp'],
                        "observacoes" => $row['observacoes'],
                        "id_bairro" => $row['id_bairro'],
                        "id_cidade" => $row['id_cidade'],
                        "apelido" => $row['apelido'],
                        "uf" => $row['uf'],
                        "idcargo" => $row['idcargo'],
                        "cep" => $row['cep'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/funcionarios/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "funcionario" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "funcionario" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "funcionario" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM funcionario WHERE id = '$id'";
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
        $nome = $data['nome'];
        $idcargo = $data['idcargo'];
        $endereco = $data['endereco'];
        $numero = $data['numero'];
        $bairro = $data['bairro'];
        $cidade = $data['cidade'];
        $uf = $data['uf'];
        $cep = $data['cep'];
        $telefone1 = $data['telefone1'];
        $telefone2 = $data['telefone2'];
        $cfp = $data['cfp'];
        $apelido = $data['apelido'];
        $observacoes = $data['observacoes'];
        $id_cidade = $data['id_cidade'];
        $id_bairro = $data['id_bairro'];
        $correio = $data['correio'];

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
            $qry = "UPDATE funcionario SET 
                    nome =:p01,
                    idcargo =:p02,
                    endereco =:p03,
                    numero =:p04,
                    bairro =:p05,
                    cidade =:p06,
                    uf =:p07,
                    cep =:p08,
                    telefone1 =:p09,
                    telefone2 =:p10,
                    cfp =:p11,
                    apelido =:p12,
                    observacoes =:p13,
                    id_cidade =:p14,
                    id_bairro =:p15,
                    correio =:p16
                WHERE id =:p17";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $nome);
            $stmt->bindValue("p02", $idcargo);
            $stmt->bindValue("p03", $endereco);
            $stmt->bindValue("p04", $numero);
            $stmt->bindValue("p05", $bairro);
            $stmt->bindValue("p06", $cidade);
            $stmt->bindValue("p07", $uf);
            $stmt->bindValue("p08", $cep);
            $stmt->bindValue("p09", $telefone1);
            $stmt->bindValue("p10", $telefone2);
            $stmt->bindValue("p11", $cfp);
            $stmt->bindValue("p12", $apelido);
            $stmt->bindValue("p13", $observacoes);
            $stmt->bindValue("p14", $id_cidade);
            $stmt->bindValue("p15", $id_bairro);
            $stmt->bindValue("p16", $correio);
            $stmt->bindValue("p17", $id);
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
                    'url' => 'api/funcionario/' . $id
                )
            )
        );

        return $response;
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $nome = $data['nome'];
        $idcargo = $data['idcargo'];
        $endereco = $data['endereco'];
        $numero = $data['numero'];
        $bairro = $data['bairro'];
        $cidade = $data['cidade'];
        $uf = $data['uf'];
        $cep = $data['cep'];
        $telefone1 = $data['telefone1'];
        $telefone2 = $data['telefone2'];
        $cfp = $data['cfp'];
        $apelido = $data['apelido'];
        $observacoes = $data['observacoes'];
        $id_cidade = $data['id_cidade'];
        $id_bairro = $data['id_bairro'];
        $correio = $data['correio'];

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
            $qry = "INSERT INTO funcionario(
                    nome,
                    idcargo,
                    endereco,
                    numero,
                    bairro,
                    cidade,
                    uf,
                    cep,
                    telefone1,
                    telefone2,
                    cfp,
                    apelido,
                    observacoes,
                    id_cidade,
                    id_bairro,
                    correio)VALUES(
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
                        :p15)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $nome);
            $stmt->bindValue("p02", $idcargo);
            $stmt->bindValue("p03", $endereco);
            $stmt->bindValue("p04", $numero);
            $stmt->bindValue("p05", $bairro);
            $stmt->bindValue("p06", $cidade);
            $stmt->bindValue("p07", $uf);
            $stmt->bindValue("p08", $cep);
            $stmt->bindValue("p09", $telefone1);
            $stmt->bindValue("p10", $telefone2);
            $stmt->bindValue("p11", $cfp);
            $stmt->bindValue("p12", $apelido);
            $stmt->bindValue("p13", $observacoes);
            $stmt->bindValue("p14", $id_cidade);
            $stmt->bindValue("p15", $id_bairro);
            $stmt->bindValue("p16", $correio);
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
                    'url' => 'api/funcionario'
                )
            )
        );

        return $response;
    }
}