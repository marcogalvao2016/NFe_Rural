<?php

namespace src\models;

use \core\Model;

class UsuarioModel extends Model
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
            $qry = "SELECT u.*, ga.descricao as grupo_acesso 
                FROM usuario u LEFT JOIN grupo_acesso ga ON (u.id_grupoacesso = ga.id)
                ORDER BY u.idusuario DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['idusuario'],
                    "nome" => $row['nome'],
                    "categoria" => $row['categoria'],
                    "situacao" => $row['situacao'] === "A" ? "ATIVO" : "INATIVO",
                    "cadastro" => $row['cadastro'],
                    "id_cliente" => $row['id_cliente'],
                    "id_func" => $row['id_func'],
                    "email_usuario" => $row['email_usuario'],
                    "nome_completo" => $row['nome_completo'],
                    "id_grupoacesso" => $row['id_grupoacesso'],
                    "id_tabela" => $row['id_tabela'],
                    "grupo" => $row['grupo_acesso'],
                    "url_avatar" => $row['url_avatar'],
                    "avatar" => $row['avatar'],
                    "value" => $row['nome'],
                    "label" => $row['nome'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/caixa/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "usuarios" => [],
            ];
        }

        return [
            "error" => false,
            "length" => count($data),
            "usuarios" => $data,
        ];
    }

    function view($id)
    {
        $retorno = true;
        $data = "";
        http_response_code(200);

        try {
            $qry = "SELECT u.*, ga.descricao as grupo_acesso 
                FROM usuario u LEFT JOIN grupo_acesso ga ON (u.id_grupoacesso = ga.id) 
                WHERE u.idusuario = '$id'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $data = array(
                        "id" => $row['idusuario'],
                        "nome" => $row['nome'],
                        "senha" => $row['senha'],
                        "categoria" => $row['categoria'],
                        "situacao" => $row['situacao'],
                        "cadastro" => $row['cadastro'],
                        "id_cliente" => $row['id_cliente'],
                        "id_func" => $row['id_func'],
                        "email_usuario" => $row['email_usuario'],
                        "nome_completo" => $row['nome_completo'],
                        "id_grupoacesso" => $row['id_grupoacesso'],
                        "id_tabela" => $row['id_tabela'],
                        "grupo" => $row['grupo_acesso'],
                        "url_avatar" => $row['url_avatar'],
                        "avatar" => $row['avatar'],
                        "observacoes" => $row['observacoes'],
                        "tel_usuario" => $row['tel_usuario'],
                        "value" => $row['nome'],
                        "label" => $row['nome'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/usuario/{id}",
                        ]
                    );
                }
            } else {
                return [
                    "error" => $retorno,
                    "length" => 0,
                    "usuario" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "usuario" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "usuario" => $data,
        ];
    }

    function deletar($id)
    {
        $response = "";
        http_response_code(202);

        try {
            $qry = "DELETE FROM usuario WHERE idusuario = '$id'";
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
                'url' => 'api/grupoacesso',
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
        $nome = $data['nome'];
        $categoria = $data['categoria'];
        $situacao = $data['situacao'];
        $senha = $data['senha'];
        $confirma = $data['senha'];
        $observacoes = $data['observacoes'];
        $email_usuario = $data['email_usuario'];
        $avatar = $data['avatar'];
        $id_grupoacesso = $data['id_grupoacesso'];
        $id_tabela = $data['id_tabela'];
        $id_func = $data['id_func'];
        $id_cliente = $data['id_cliente'];
        $nome_completo = $data['nome_completo'];
        $tel_usuario = $data['tel_usuario'];
        $temIMG = $data['temIMG'];

        if ($temIMG == 'N') {
            $SQLUsuario = "SELECT idusuario, avatar FROM usuario WHERE idusuario = '$id'";
            $stmt = $this->conexao->prepare($SQLUsuario);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($results as $row) {
                $avatar = $row['avatar'];
            }
        }

        try {
            $qry = "UPDATE usuario SET 
                nome =:p01,
                categoria =:p02,
                situacao  =:p03,                               
                senha     =:p04,
                confirma  =:p05,
                observacoes =:p06,
                email_usuario =:p07,
                avatar =:p08,
                id_grupoacesso =:p09,
                id_tabela =:p10,
                id_func =:p11,
                id_cliente =:p12,
                nome_completo =:p13,
                tel_usuario =:p14                          
            WHERE idusuario =:p15";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $nome);
            $stmt->bindValue("p02", $categoria);
            $stmt->bindValue("p03", $situacao);
            $stmt->bindValue("p04", $senha);
            $stmt->bindValue("p05", $confirma);
            $stmt->bindValue("p06", $observacoes);
            $stmt->bindValue("p07", $email_usuario);
            $stmt->bindValue("p08", $avatar);
            $stmt->bindValue("p09", $id_grupoacesso);
            $stmt->bindValue("p10", $id_tabela);
            $stmt->bindValue("p11", $id_func);
            $stmt->bindValue("p12", $id_cliente);
            $stmt->bindValue("p13", $nome_completo);
            $stmt->bindValue("p14", $tel_usuario);
            $stmt->bindValue("p15", $id);
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
                    'url' => 'api/usuario/' . $id
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

        $nome = $data['nome'];
        $categoria = $data['categoria'];
        $situacao = $data['situacao'];
        $senha = $data['senha'];
        $confirma = $data['senha'];
        $observacoes = $data['observacoes'];
        $email_usuario = $data['email_usuario'];
        $avatar = $data['avatar'];
        $id_grupoacesso = $data['id_grupoacesso'];
        $id_tabela = $data['id_tabela'];
        $id_func = $data['id_func'];
        $id_cliente = $data['id_cliente'];
        $nome_completo = $data['nome_completo'];
        $tel_usuario = $data['tel_usuario'];
        $temIMG = $data['temIMG'];

        try {
            $qry = "INSERT INTO usuario (
                nome,
                categoria,
                situacao,
                senha,
                confirma,
                observacoes,
                email_usuario,
                avatar,
                id_grupoacesso,
                id_tabela,
                id_func,
                id_cliente,
                nome_completo,
                cadastro,
                tel_usuario)VALUES(
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
                    :p15)";
            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $nome);
            $stmt->bindValue("p02", $categoria);
            $stmt->bindValue("p03", $situacao);
            $stmt->bindValue("p04", $senha);
            $stmt->bindValue("p05", $confirma);
            $stmt->bindValue("p06", $observacoes);
            $stmt->bindValue("p07", $email_usuario);
            $stmt->bindValue("p08", $avatar);
            $stmt->bindValue("p09", $id_grupoacesso);
            $stmt->bindValue("p10", $id_tabela);
            $stmt->bindValue("p11", $id_func);
            $stmt->bindValue("p12", $id_cliente);
            $stmt->bindValue("p13", $nome_completo);
            $stmt->bindValue("p14", $dataAtual);
            $stmt->bindValue("p15", $tel_usuario);
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
                    'url' => 'api/usuario'
                )
            )
        );

        return $response;
    }

    function login($data)
    {
        $retorno = true;
        $dataReturn = "";
        http_response_code(200);

        $login = $data['login'];
        $senha = $data['senha'];

        try {
            $qry = "SELECT u.*, gc.descricao as grupo, CASE u.id_cliente 
                WHEN '0' THEN 'CONSUMIDOR' ELSE
                c.nomefantasia END AS nome_cliente FROM usuario u 
                LEFT JOIN grupo_acesso gc ON (u.id_grupoacesso = gc.id)
                LEFT JOIN cliente c ON (u.id_cliente = c.id)
                WHERE u.situacao = 'A' AND u.nome = '$login' AND u.senha = '$senha'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $dataReturn = array(
                        "id" => $row['idusuario'],
                        "nome" => $row['nome'],
                        "categoria" => $row['categoria'],
                        "situacao" => $row['situacao'] === "A" ? "ATIVO" : "INATIVO",
                        "cadastro" => $row['cadastro'],
                        "id_cliente" => $row['id_cliente'],
                        "id_func" => $row['id_func'],
                        "email_usuario" => $row['email_usuario'],
                        "nome_completo" => $row['nome_completo'],
                        "id_grupoacesso" => $row['id_grupoacesso'],
                        "id_tabela" => $row['id_tabela'],
                        "grupo" => $row['grupo'],
                        "url_avatar" => $row['url_avatar'],
                        "avatar" => $row['avatar'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/usuario/{id}",
                        ]
                    );
                }
            } else {
                http_response_code(404);

                return [
                    "error" => $retorno,
                    "length" => 0,
                    "usuario" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "usuario" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "usuario" => $dataReturn,
        ];
    }

    function loginMail($data)
    {
        $retorno = true;
        $dataReturn = "";
        http_response_code(200);

        $login = $data['login'];
        $senha = $data['senha'];

        try {
            $qry = "SELECT u.*, gc.descricao as grupo, CASE u.id_cliente 
                WHEN '0' THEN 'CONSUMIDOR' ELSE
                c.nomefantasia END AS nome_cliente FROM usuario u 
                LEFT JOIN grupo_acesso gc ON (u.id_grupoacesso = gc.id)
                LEFT JOIN cliente c ON (u.id_cliente = c.id)
                WHERE u.situacao = 'A' AND u.email_usuario = '$login' AND u.senha = '$senha'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $dataReturn = array(
                        "id" => $row['idusuario'],
                        "nome" => $row['nome'],
                        "categoria" => $row['categoria'],
                        "situacao" => $row['situacao'] === "A" ? "ATIVO" : "INATIVO",
                        "cadastro" => $row['cadastro'],
                        "id_cliente" => $row['id_cliente'],
                        "id_func" => $row['id_func'],
                        "email_usuario" => $row['email_usuario'],
                        "nome_completo" => $row['nome_completo'],
                        "id_grupoacesso" => $row['id_grupoacesso'],
                        "id_tabela" => $row['id_tabela'],
                        "grupo" => $row['grupo'],
                        "url_avatar" => $row['url_avatar'],
                        "avatar" => $row['avatar'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/usuario/{id}",
                        ]
                    );
                }
            } else {
                http_response_code(404);

                return [
                    "error" => $retorno,
                    "length" => 0,
                    "usuario" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "usuario" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "usuario" => $dataReturn,
        ];
    }

    function loginClient($data)
    {
        $retorno = true;
        $dataReturn = "";
        http_response_code(200);

        $login = $data['login'];
        $senha = $data['senha'];

        try {
            $qry = "SELECT c.id as idusuario, 'CONVIDADO' AS grupo, 
                c.nomefantasia as nome_cliente, c.nomefantasia as nome,
                'USUARIO' AS categoria, c.cadastro, c.id as id_cliente,
                c.id_vendedor AS id_func, c.correio AS email_usuario, 
                c.nomefantasia as nome_completo, c.situacao,
                c.foto as avatar, '6' as id_grupoacesso, 
                c.id_tabela_preco as id_tabela, c.foto as url_avatar FROM cliente c 
            WHERE c.situacao = 'A' AND c.cfpcnpj = '$login' 
            AND c.senha_acesso = '$senha'";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $count = $stmt->rowCount();
            if ($count > 0) {
                $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

                $retorno = true;

                foreach ($results as $row) {
                    $dataReturn = array(
                        "id" => $row['idusuario'],
                        "nome" => $row['nome'],
                        "categoria" => $row['categoria'],
                        "situacao" => $row['situacao'] === "A" ? "ATIVO" : "INATIVO",
                        "cadastro" => $row['cadastro'],
                        "id_cliente" => $row['id_cliente'],
                        "id_func" => $row['id_func'],
                        "email_usuario" => $row['email_usuario'],
                        "nome_completo" => $row['nome_completo'],
                        "nome_cliente" => $row['nome_cliente'],
                        "id_grupoacesso" => $row['id_grupoacesso'],
                        "id_tabela" => $row['id_tabela'],
                        "grupo" => $row['grupo'],
                        "url_avatar" => $row['url_avatar'],
                        "avatar" => $row['avatar'],
                        "request" => [
                            "type" => "GET",
                            "description" => "Retorna os detalhes de um registro específico",
                            "url" => "api/usuario/{id}",
                        ]
                    );
                }
            } else {
                http_response_code(404);

                return [
                    "error" => $retorno,
                    "length" => 0,
                    "usuario" => [],
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => $retorno,
                "length" => 0,
                "usuario" => [],
            ];
        }

        return [
            "error" => $retorno,
            "length" => $count,
            "usuario" => $dataReturn,
        ];
    }

    function pesquisar($texto)
    {
        $data = [];
        http_response_code(200);

        try {
            $qry = "SELECT u.*, ga.descricao as grupo_acesso 
                FROM usuario u LEFT JOIN grupo_acesso ga ON (u.id_grupoacesso = ga.id)
                WHERE u.nome LIKE '%" . $texto . "%'
                ORDER BY u.idusuario DESC";
            $stmt = $this->conexao->prepare($qry);
            $stmt->execute();

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            foreach ($results as $row) {
                $data[] = [
                    "id" => $row['idusuario'],
                    "nome" => $row['nome'],
                    "categoria" => $row['categoria'],
                    "situacao" => $row['situacao'] === "A" ? "ATIVO" : "INATIVO",
                    "cadastro" => $row['cadastro'],
                    "id_cliente" => $row['id_cliente'],
                    "id_func" => $row['id_func'],
                    "email_usuario" => $row['email_usuario'],
                    "nome_completo" => $row['nome_completo'],
                    "id_grupoacesso" => $row['id_grupoacesso'],
                    "id_tabela" => $row['id_tabela'],
                    "grupo" => $row['grupo_acesso'],
                    "url_avatar" => $row['url_avatar'],
                    "avatar" => $row['avatar'],
                    "request" => [
                        "type" => "GET",
                        "description" => "Retorna todos os registros",
                        "url" => "api/caixa/",
                    ]
                ];
            }
        } catch (\Exception $e) {
            // Tratar exceção, se necessário
            http_response_code(500);

            return [
                "error" => true,
                "length" => 0,
                "usuarios" => [],
            ];
        }

        return [
            "error" => false,
            "length" => count($data),
            "usuarios" => $data,
        ];
    }

    function updatePassword($data)
    {
        $response = "";
        http_response_code(200);

        $id = $data['id'];
        $password_new = $data['password_new'];

        try {
            $qry = "UPDATE usuario SET 
                    senha =:p01,
                    confirma =:p02
                WHERE idusuario =:p03";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $password_new);
            $stmt->bindValue("p02", $password_new);
            $stmt->bindValue("p03", $id);
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
                    'url' => 'api/usuario/' . $id
                )
            )
        );

        return $response;
    }

    function updatePasswordCliente($data)
    {
        $response = "";
        http_response_code(200);

        $id = $data['id'];
        $password_new = $data['password_new'];

        try {
            $qry = "UPDATE cliente SET 
                    senha_acesso =:p01
                WHERE id =:p02";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $password_new);
            $stmt->bindValue("p02", $id);
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
                    'url' => 'api/usuario/' . $id
                )
            )
        );

        return $response;
    }

    function updateFotoCliente($data)
    {
        $response = "";
        http_response_code(200);

        $id = $data['id'];
        $avatar = $data['avatar'];
        $temIMG = $data['temIMG'];

        try {
            $qry = "UPDATE cliente SET 
                    foto =:p01
                WHERE id =:p02";

            $stmt = $this->conexao->prepare($qry);
            $stmt->bindValue("p01", $avatar);
            $stmt->bindValue("p02", $id);
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
                    'url' => 'api/usuario/' . $id
                )
            )
        );

        return $response;
    }
}
