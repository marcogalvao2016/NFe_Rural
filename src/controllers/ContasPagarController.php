<?php

namespace src\controllers;

use \core\Controller;
use \src\models\ContasPagarModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class ContasPagarController extends Controller
{

    public function index()
    {
        $dados = new ContasPagarModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new ContasPagarModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new ContasPagarModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new ContasPagarModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new ContasPagarModel();
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

        $dados = new ContasPagarModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function quitar($args)
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id = $args['id'];

        $data_pagamento = $requestData['data_pagamento'];
        $hora_pagamento = $requestData['hora_pagamento'];
        $situacao = $requestData['situacao'];
        $id_plano_contas = $requestData['id_plano_contas'];
        $detalhes = $requestData['detalhes'];
        $id_pagto_quitacao = $requestData['id_pagto_quitacao'];

        $data = [
            "id" => $id,
            "data_pagamento" => $data_pagamento,
            "hora_pagamento" => $hora_pagamento,
            "situacao" => $situacao,
            "id_plano_contas" => $id_plano_contas,
            "detalhes" => $detalhes,
            "id_pagto_quitacao" => $id_pagto_quitacao
        ];

        $dados = new ContasPagarModel();
        $retorno = $dados->quitar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        if (json_last_error() === JSON_ERROR_NONE) {
            foreach ($requestData as $item) {
                $observacoes = $item['observacoes'];
                $vencimento = $item['vencimento'];
                $situacao = "N";
                $id_plano_contas = $item['id_plano_contas'];
                $valor = $item['valor'];
                $detalhes = $item['detalhes'];
                $tipomovimento = $item['tipomovimento'];
                $idcentrocusto = 0;
                $ndoc = $item['ndoc'];
                $id_origem = $item['id_origem'];
                $sigla_origem = $item['sigla_origem'];
                $idusuario = $item['id_usuario'];
                $id_pagto = $item['id_pagto'];
                $parcela = $item['parcela'];
                $id_filial = $item['id_filial'];
                $id_pagto_quitacao = $item['id_pagto_quitacao'];
                $valor_pago = $item['valor_pago'];
                $id_aluno = $item['id_aluno'];

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
                    "id_aluno" => $id_aluno,
                ];

                $dados = new ContasPagarModel();
                $retorno = $dados->inserir($data);
            }
        }


        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function contaTotalCaixa()
    {
        $dados = new ContasPagarModel();
        $data = $dados->contaTotalCaixa();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
