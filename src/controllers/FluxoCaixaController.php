<?php
namespace src\controllers;

use \core\Controller;
use \src\models\FluxoCaixaModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class FluxoCaixaController extends Controller
{

    public function index()
    {
        $dados = new FluxoCaixaModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new FluxoCaixaModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new FluxoCaixaModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new FluxoCaixaModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new FluxoCaixaModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = $requestData['id'];
        $observacoes = $requestData['observacoes'];
        $vencimento = $requestData['vencimento'];
        $id_plano_contas = $requestData['id_plano_contas'];
        $valor = $requestData['valor'];
        $detalhes = $requestData['detalhes'];
        $parcela = $requestData['parcela'];

        $valor = str_replace(",", ".", $valor);

        $data = [
            "id" => $id,
            "observacoes" => $observacoes,
            "vencimento" => $vencimento,
            "id_plano_contas" => $id_plano_contas,
            "valor" => $valor,
            "detalhes" => $detalhes,
            "parcela" => $parcela,
        ];

        $dados = new FluxoCaixaModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $observacoes = $requestData['observacoes'];
        $vencimento = $requestData['vencimento'];
        $situacao = $requestData['situacao'];
        $id_plano_contas = $requestData['id_plano_contas'];
        $valor = $requestData['valor'];
        $detalhes = $requestData['detalhes'];
        $tipomovimento = $requestData['tipomovimento'];
        $idcentrocusto = $requestData['idcentrocusto'];
        $ndoc = $requestData['ndoc'];
        $id_origem = $requestData['id_origem'];
        $sigla_origem = $requestData['sigla_origem'];
        $idusuario = $requestData['id_usuario'];
        $id_pagto = $requestData['id_pagto'];
        $parcela = $requestData['parcela'];
        $id_filial = $requestData['id_filial'];
        $id_pagto_quitacao = $requestData['id_pagto_quitacao'];
        $valor_pago = $requestData['valor_pago'];

        $valor = str_replace(",", ".", $valor);

        $data = [
            "observacoes" => $observacoes,
            "vencimento" => $vencimento,
            "situacao" => $situacao,
            "id_plano_contas" => $id_plano_contas,
            "valor" => $valor,
            "detalhes" => $detalhes,
            "tipomovimento" => $tipomovimento,
            "idcentrocusto" => $idcentrocusto,
            "ndoc" => $ndoc,
            "id_origem" => $id_origem,
            "sigla_origem" => $sigla_origem,
            "idusuario" => $idusuario,
            "id_pagto" => $id_pagto,
            "parcela" => $parcela,
            "id_filial" => $id_filial,
            "id_pagto_quitacao" => $id_pagto_quitacao,
            "valor_pago" => $valor_pago,
        ];

        $dados = new FluxoCaixaModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function contaTotalCaixa()
    {
        $dados = new FluxoCaixaModel();
        $data = $dados->contaTotalCaixa();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}