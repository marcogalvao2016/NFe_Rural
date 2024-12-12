<?php
namespace src\models;

use \core\Model;

class ClienteEuroModel extends Model
{
    private $conexao; // Variável para armazenar a conexão PDO

    function __construct()
    {
        require_once 'conexao/db_connection.php';
        $this->conexao = new \DB_Con();
    }

    function listar($tipo)
    {
        $data = [];
        http_response_code(200);

        $consTipo = "";
        if ($tipo == "NA"){
            $consTipo = "AND cc.contabil = 'N' AND e.situacao = 'A'";
        }
        if ($tipo == "NI"){
            $consTipo = "AND cc.contabil = 'N' AND e.situacao = 'I'";
        }
        if ($tipo == "CA"){
            $consTipo = "AND cc.contabil = 'S' AND e.situacao = 'A'";
        }
        if ($tipo == "CI"){
            $consTipo = "AND cc.contabil = 'S' AND e.situacao = 'I'";
        }

        try {
            $qry = "SELECT e.* FROM emp e 
                LEFT JOIN clientecategoria cc ON (e.id_categoria = cc.id)
                WHERE 1=1 $consTipo ORDER BY e.id DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cnpj" => $row['cnpj'],
                    "razaosocial" => $row['razaosocial'],
                    "telefone" => $row['telefone'],
                    "e_mail" => $row['e_mail'],
                    "mensalidade" => $row['mensalidade'],
                    "caminho_arquivo" => $row['caminho_arquivo'],
                    "status" => $row['situacao'] == 'A' ? "ATIVO" : 'INATIVO',
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clienteseuro/listar",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "clientesEuro" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientesEuro" => $data,
        ];
    }

    function partial($qt)
    {
        $data = [];

        try {
            $qry = "SELECT e.* FROM emp e 
                LEFT JOIN clientecategoria cc ON (e.id_categoria = cc.id)
                WHERE e.situacao = 'A' ORDER BY e.razaosocial LIMIT " . $qt;
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cnpj" => $row['cnpj'],
                    "razaosocial" => $row['razaosocial'],
                    "telefone" => $row['telefone'],
                    "e_mail" => $row['e_mail'],
                    "mensalidade" => $row['mensalidade'],
                    "caminho_arquivo" => $row['caminho_arquivo'],
                    "status" => $row['situacao'] == 'A' ? "ATIVO" : 'INATIVO',
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clienteseuro/listar/partial/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "clientesEuro" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientesEuro" => $data,
        ];
    }

    function pesquisar($texto, $tipo)
    {
        $data = [];

        $consTipo = "";
        if ($tipo == "NA"){
            $consTipo = "AND cc.contabil = 'N' AND e.situacao = 'A'";
        }
        if ($tipo == "NI"){
            $consTipo = "AND cc.contabil = 'N' AND e.situacao = 'I'";
        }
        if ($tipo == "CA"){
            $consTipo = "AND cc.contabil = 'S' AND e.situacao = 'A'";
        }
        if ($tipo == "CI"){
            $consTipo = "AND cc.contabil = 'S' AND e.situacao = 'I'";
        }

        try {
            $qry = "SELECT e.* FROM emp e 
                LEFT JOIN clientecategoria cc ON (e.id_categoria = cc.id)
                WHERE 1=1 $consTipo
                AND e.razaosocial like '%" . $texto . "%' 
                ORDER BY e.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cnpj" => $row['cnpj'],
                    "razaosocial" => $row['razaosocial'],
                    "telefone" => $row['telefone'],
                    "e_mail" => $row['e_mail'],
                    "mensalidade" => $row['mensalidade'],
                    "caminho_arquivo" => $row['caminho_arquivo'],
                    "status" => $row['situacao'] == 'A' ? "ATIVO" : 'INATIVO',
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clienteseuro/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "clientesEuro" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientesEuro" => $data,
        ];
    }

    function pesquisarCNPJ($texto, $tipo)
    {
        $data = [];

        $consTipo = "";
        if ($tipo == "NA"){
            $consTipo = "AND cc.contabil = 'N' AND e.situacao = 'A'";
        }
        if ($tipo == "NI"){
            $consTipo = "AND cc.contabil = 'N' AND e.situacao = 'I'";
        }
        if ($tipo == "CA"){
            $consTipo = "AND cc.contabil = 'S' AND e.situacao = 'A'";
        }
        if ($tipo == "CI"){
            $consTipo = "AND cc.contabil = 'S' AND e.situacao = 'I'";
        }

        try {
            $qry = "SELECT e.* FROM emp e 
                LEFT JOIN clientecategoria cc ON (e.id_categoria = cc.id)
                WHERE 1=1 $consTipo
                AND e.cnpj like '%" . $texto . "%' 
                ORDER BY e.id DESC LIMIT 300";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $count = $stmt->rowCount();
            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['id'],
                    "cnpj" => $row['cnpj'],
                    "razaosocial" => $row['razaosocial'],
                    "telefone" => $row['telefone'],
                    "e_mail" => $row['e_mail'],
                    "mensalidade" => $row['mensalidade'],
                    "caminho_arquivo" => $row['caminho_arquivo'],
                    "status" => $row['situacao'] == 'A' ? "ATIVO" : 'INATIVO',
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/clienteseuro/{id}",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            return [
                "error" => true,
                "length" => 0,
                "clientesEuro" => [],
            ];
        }

        return [
            "error" => false,
            "length" => $count,
            "clientesEuro" => $data,
        ];
    }


    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT e.* FROM emp e WHERE e.id = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['id'],
                        "cnpj" => $row['cnpj'],
                        "razaosocial" => $row['razaosocial'],
                        "telefone" => $row['telefone'],
                        "e_mail" => $row['e_mail'],
                        "mensalidade" => $row['mensalidade'],
                        "nomeproprietario" => $row['nomeproprietario'],
                        "observacoes" => $row['observacoes'],
                        "cadastro" => $row['cadastro'],
                        "situacao" => $row['situacao'],
                        "vencimento" => $row['vencimento'],
                        "maquinas" => $row['maquinas'],
                        "data_vencimento" => $row['data_vencimento'],
                        "cpf" => $row['cpf'],
                        "origem" => $row['origem'],
                        "email_contador" => $row['email_contador'],
                        "emite_nota" => $row['emite_nota'],
                        "notas" => $row['notas'],
                        "id_categoria" => $row['id_categoria'],
                        "link" => $row['link'],
                        "venc_certificado" => $row['venc_certificado'],
                        "inicio" => $row['inicio'],
                        "caminho_arquivo" => $row['caminho_arquivo'],
                        "caminho_arquivo1" => $row['caminho_arquivo1'],
                        "caminho_arquivo2" => $row['caminho_arquivo2'],
                        "fim" => $row['fim'],
                        "enviar_xml" => $row['enviar_xml'],
                        "xml_validos" => $row['xml_validos'],
                        "id_seguimento" => $row['id_seguimento'],
                        "avatar" => $row['avatar'],
                        "status" => $row['status'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/clienteseuro/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "clienteEuro" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "clienteEuro" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "clienteEuro" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM emp WHERE id = '$id'";
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
                'url' => 'api/clienteseuro',
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
        $razaosocial = trim($data['razaosocial']);
        $mensalidade = trim($data['mensalidade']);
        $nomeproprietario = trim($data['nomeproprietario']);
        $cnpj = trim($data['cnpj']);
        $telefone = trim($data['telefone']);
        $e_mail = trim($data['e_mail']);
        $observacoes = trim($data['observacoes']);
        $situacao = trim($data['situacao']);
        $vencimento = trim($data['vencimento']);
        $maquinas = trim($data['maquinas']);
        $data_vencimento = trim($data['data_vencimento']);
        $cpf = trim($data['cpf']);
        $origem = trim($data['origem']);
        $email_contador = trim($data['email_contador']);
        $emite_nota = trim($data['emite_nota']);
        $notas = trim($data['notas']);
        $id_categoria = trim($data['id_categoria']);
        $link = trim($data['link']);
        $venc_certificado = trim($data['venc_certificado']);
        $inicio = trim($data['inicio']);
        $fim = trim($data['fim']);
        $enviar_xml = trim($data['enviar_xml']);
        $xml_validos = trim($data['xml_validos']);
        $id_seguimento = trim($data['id_seguimento']);
        $status = trim($data['status']);
        $avatar = trim($data['avatar']);
        $caminho_arquivo1 = trim($data['caminho_arquivo1']);
        $caminho_arquivo2 = trim($data['caminho_arquivo2']);
        $temIMG = $data['temIMG'];
        $temArq1 = $data['temArq1'];
        $temArq2 = $data['temArq2'];

        if ($temIMG == 'N') {
            $SQLUsuario = "SELECT id, caminho_arquivo FROM emp WHERE id = '$id'";
            $stmt = $this->conexao->prepare($SQLUsuario);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $avatar = $row['caminho_arquivo'];
            }
        }

        if ($temArq1 == 'N') {
            $SQLUsuario = "SELECT id, caminho_arquivo1 FROM emp WHERE id = '$id'";
            $stmt = $this->conexao->prepare($SQLUsuario);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $caminho_arquivo1 = $row['caminho_arquivo1'];
            }
        }

        if ($temArq2 == 'N') {
            $SQLUsuario = "SELECT id, caminho_arquivo2 FROM emp WHERE id = '$id'";
            $stmt = $this->conexao->prepare($SQLUsuario);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $caminho_arquivo2 = $row['caminho_arquivo2'];
            }
        }

        try {
            $qry = "UPDATE emp SET 
                        razaosocial =:p01,
                        mensalidade =:p02,
                        nomeproprietario =:p03,
                        cnpj =:p04,
                        telefone =:p05,
                        e_mail =:p06,
                        observacoes =:p07,          
                        situacao =:p08,
                        vencimento =:p09,
                        maquinas =:p10,
                        data_vencimento =:p11,
                        cpf =:p12,
                        origem =:p13,
                        email_contador =:p14,
                        emite_nota =:p15,
                        notas =:p16,
                        id_categoria =:p17,
                        link =:p18,
                        venc_certificado =:p19,
                        inicio =:p20,
                        fim =:p21,
                        enviar_xml =:p22,
                        xml_validos =:p23,
                        id_seguimento =:p24,
                        status =:p25,
                        caminho_arquivo =:p26,
                        caminho_arquivo1 =:p27,
                        caminho_arquivo2 =:p28
                    WHERE id  =:p29";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $razaosocial);
            $stmt->bindValue("p02", $mensalidade);
            $stmt->bindValue("p03", $nomeproprietario);
            $stmt->bindValue("p04", $cnpj);
            $stmt->bindValue("p05", $telefone);
            $stmt->bindValue("p06", $e_mail);
            $stmt->bindValue("p07", $observacoes);
            $stmt->bindValue("p08", $situacao);
            $stmt->bindValue("p09", $vencimento);
            $stmt->bindValue("p10", $maquinas);
            $stmt->bindValue("p11", $data_vencimento);
            $stmt->bindValue("p12", $cpf);
            $stmt->bindValue("p13", $origem);
            $stmt->bindValue("p14", $email_contador);
            $stmt->bindValue("p15", $emite_nota);
            $stmt->bindValue("p16", $notas);
            $stmt->bindValue("p17", $id_categoria);
            $stmt->bindValue("p18", $link);
            $stmt->bindValue("p19", $venc_certificado);
            $stmt->bindValue("p20", $inicio);
            $stmt->bindValue("p21", $fim);
            $stmt->bindValue("p22", $enviar_xml);
            $stmt->bindValue("p23", $xml_validos);
            $stmt->bindValue("p24", $id_seguimento);
            $stmt->bindValue("p25", $status);
            $stmt->bindValue("p26", $avatar);
            $stmt->bindValue("p27", $caminho_arquivo1);
            $stmt->bindValue("p28", $caminho_arquivo2);
            $stmt->bindValue("p29", $id);
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
                    'url' => 'api/clienteseuro/' . $id
                )
            )
        );

        return $response;
    }

    function inserir($data)
    {
        $response = "";
        http_response_code(200);

        $razaosocial = trim($data['razaosocial']);
        $mensalidade = trim($data['mensalidade']);
        $nomeproprietario = trim($data['nomeproprietario']);
        $cnpj = trim($data['cnpj']);
        $telefone = trim($data['telefone']);
        $e_mail = trim($data['e_mail']);
        $observacoes = trim($data['observacoes']);
        $situacao = trim($data['situacao']);
        $vencimento = trim($data['vencimento']);
        $maquinas = trim($data['maquinas']);
        $data_vencimento = trim($data['data_vencimento']);
        $cpf = trim($data['cpf']);
        $origem = trim($data['origem']);
        $email_contador = trim($data['email_contador']);
        $emite_nota = trim($data['emite_nota']);
        $notas = trim($data['notas']);
        $id_categoria = trim($data['id_categoria']);
        $link = trim($data['link']);
        $venc_certificado = trim($data['venc_certificado']);
        $inicio = trim($data['inicio']);
        $fim = trim($data['fim']);
        $enviar_xml = trim($data['enviar_xml']);
        $xml_validos = trim($data['xml_validos']);
        $id_seguimento = trim($data['id_seguimento']);
        $status = trim($data['status']);
        $avatar = trim($data['avatar']);
        $caminho_arquivo1 = trim($data['caminho_arquivo1']);
        $caminho_arquivo2 = trim($data['caminho_arquivo2']);
        $temIMG = $data['temIMG'];
        $temArq1 = $data['temArq1'];
        $temArq2 = $data['temArq2'];

        try {
            $qry = "INSERT INTO emp(
                        razaosocial,
                        mensalidade,
                        nomeproprietario,
                        cnpj,
                        telefone,
                        e_mail,
                        observacoes,          
                        situacao,
                        vencimento,
                        maquinas,
                        data_vencimento,
                        cpf,
                        origem,
                        email_contador,
                        emite_nota,
                        notas,
                        id_categoria,
                        link,
                        venc_certificado,
                        inicio,
                        fim,
                        enviar_xml,
                        xml_validos,
                        id_seguimento,
                        status,
                        caminho_arquivo,
                        caminho_arquivo1,
                        caminho_arquivo2
                    )VALUES(
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
                        :p28)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $razaosocial);
            $stmt->bindValue("p02", $mensalidade);
            $stmt->bindValue("p03", $nomeproprietario);
            $stmt->bindValue("p04", $cnpj);
            $stmt->bindValue("p05", $telefone);
            $stmt->bindValue("p06", $e_mail);
            $stmt->bindValue("p07", $observacoes);
            $stmt->bindValue("p08", $situacao);
            $stmt->bindValue("p09", $vencimento);
            $stmt->bindValue("p10", $maquinas);
            $stmt->bindValue("p11", $data_vencimento);
            $stmt->bindValue("p12", $cpf);
            $stmt->bindValue("p13", $origem);
            $stmt->bindValue("p14", $email_contador);
            $stmt->bindValue("p15", $emite_nota);
            $stmt->bindValue("p16", $notas);
            $stmt->bindValue("p17", $id_categoria);
            $stmt->bindValue("p18", $link);
            $stmt->bindValue("p19", $venc_certificado);
            $stmt->bindValue("p20", $inicio);
            $stmt->bindValue("p21", $fim);
            $stmt->bindValue("p22", $enviar_xml);
            $stmt->bindValue("p23", $xml_validos);
            $stmt->bindValue("p24", $id_seguimento);
            $stmt->bindValue("p25", $status);
            $stmt->bindValue("p26", $avatar);
            $stmt->bindValue("p27", $caminho_arquivo1);
            $stmt->bindValue("p28", $caminho_arquivo2);
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
                    'url' => 'api/clienteseuro'
                )
            )
        );

        return $response;
    }
}