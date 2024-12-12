<?php

namespace src\controllers;

use \core\Controller;
use \src\models\NFeModel;
use \src\models\ClienteModel;
use \src\models\EmpresaModel;
use \src\models\VendaModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

// Caminho correto para o arquivo autoload.php do Composer
require_once __DIR__ . "/../../vendor/autoload.php";

// Caminho correto para o arquivo nfe.php
require_once __DIR__ . '/../notafiscal/nfe.php';

class NFeController extends Controller
{
    public function index()
    {
        echo "Rota NFe";
        exit;
    }

    function getLastNFe()
    {
        $dados = new NFeModel();
        $data = $dados->getLastNFe();

        return $data['nfe'][0]['idnf'];
    }

    public function emsisao($args)
    {
        ob_clean(); // Limpa qualquer saída anterior
        header('Content-Type: application/json; charset=utf-8');

        try {
            if (!isset($args['idvenda'])) {
                throw new \Exception("ID de venda não fornecido.");
            }

            $id = $args['idvenda'];

            // Numero da NF-e
            $numeroNFe = 258; //$this->getLastNFe();

            // Emitente
            $dataEmpresa = new EmpresaModel();
            $pEmitente = $dataEmpresa->view('1');
            $pEmitente = $pEmitente['empresa'];
            // Emitente

            // Pedido
            $dataPedido = new VendaModel();
            $pPedido = $dataPedido->view($id);
            $pPedido = $pPedido['venda'];
            // Pedido

            // Destinatario
            $dataDestinatario = new ClienteModel();
            $pDestinatario = $dataDestinatario->view($pPedido['id_cliente']);
            $pDestinatario = $pDestinatario['cliente'];
            // Destinatario       

            // Produtos
            $dataProdutos = new VendaModel();
            $pProdutos = $dataProdutos->vendaProdutos($pPedido['id']);
            $pProdutos = $pProdutos['produtos'];
            // Produtos    

            // Descomente as linhas abaixo se precisar de dados adicionais            
            $response = emissaoNFe(
                $id,
                $numeroNFe,
                $pEmitente,
                $pPedido,
                $pDestinatario,
                $pProdutos
            );

            // Converte a string JSON em um objeto PHP            
            $responseObject = json_decode($response);

            if (isset($responseObject->protocolo_nfe)) {
                $dataResponse = [
                    'idcli' => $pPedido['id_cliente'],
                    'idfunc' => 1,
                    'idnf' => $numeroNFe,
                    'tipo' => 'N',
                    'subtotal' => $pPedido['total'],
                    'vldesconto' => 0,
                    'chave' => $responseObject->chave_nfe,
                    'protocolo' => $responseObject->protocolo_nfe,
                    'qt_itens' => count($pProdutos),
                    'total_produtos' => $pPedido['total'],
                    'total_nf' => $pPedido['total'],
                    'id_venda' => $id,
                    'caminho_xml' => 'D:\\Projetos\\PHP\\laragon\\www\\api-euro\src\\notafiscal\\xml\\' . $responseObject->chave_nfe . '.xml',
                    'cnpj' => $pDestinatario['cfpcnpj'],
                    'nome_cliente' => $pDestinatario['nomefantasia'],
                    'ie' => $pDestinatario['rginsestadual'],
                    'xml' => $responseObject->xml,
                    'produtos' => $pProdutos,
                ];

                $dadosNFe = new NFeModel();
                $dadosNFe->gravarNFe($dataResponse);
            }

            if (isset($responseObject->response)) {
                // Caso contenha uma resposta (pode ser sucesso ou erro)
                echo json_encode([
                    "status" => "success",
                    "response" => $responseObject,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Nenhuma resposta válida recebida da emissão.",
                    "details" => $response,
                ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            }
        } catch (\Exception $e) {
            // Captura e trata erros
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage(),
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }

        http_response_code(200);
        exit;
    }
}
