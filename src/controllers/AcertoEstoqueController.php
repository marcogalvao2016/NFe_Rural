<?php

namespace src\controllers;

use \core\Controller;
use \src\models\AcertoEstoqueModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class AcertoEstoqueController extends Controller
{

    public function index()
    {
        $dados = new AcertoEstoqueModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new AcertoEstoqueModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new AcertoEstoqueModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $cadastro = $requestData['cadastro'];
        $id_produto = $requestData['id_produto'];
        $saldoatual = $requestData['saldoatual'];
        $qtdeacerto = $requestData['qtdacerto'];
        $tipo = $requestData['tipo'];
        $motivo = $requestData['motivo'];
        $id_usuario = $requestData['id_usuario'];
        $valor_custo_acerto = $requestData['valor_custo_acerto'];
        $valor_venda_acerto = $requestData['valor_venda_acerto'];

        $valor_custo_acerto = str_replace(",", ".", $valor_custo_acerto);
        $valor_venda_acerto = str_replace(",", ".", $valor_venda_acerto);
        $qtdeacerto = str_replace(",", ".", $qtdeacerto);

        $data = [
            "cadastro" => $cadastro,
            "id_produto" => $id_produto,
            "saldoatual" => $saldoatual,
            "qtdeacerto" => $qtdeacerto,
            "tipo" => $tipo,
            "motivo" => $motivo,
            "id_usuario" => $id_usuario,
            "valor_custo_acerto" => $valor_custo_acerto,
            "valor_venda_acerto" => $valor_venda_acerto,
        ];

        $dados = new AcertoEstoqueModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
