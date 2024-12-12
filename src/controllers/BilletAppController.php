<?php

namespace src\controllers;

use \core\Controller;

use Efi\Exception\EfiException;
use Efi\EfiPay;

// Caminho correto para o arquivo nfe.php
require_once __DIR__ . '/../gerencianet/vendor/autoload.php';

class BilletAppController extends Controller
{
    public function index()
    {
        echo "Rota Gerencianet";
        exit;
    }

    public function createBillet()
    {
        try { // Decodifique o JSON para um array associativo        
            if ($_POST['telefone'] == "65992565018") {
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => 500,
                        'message' => 'Telefone',
                        'description' => 'Telefone inválido ou não permitido'
                    ]
                ]);

                return;
            }

            // Pega as credenciais
            $dadosCredentials = explode("__", getChaveGerenciaNetProducao());

            $options = [
                "timeout" => 30,
                "certificate" => __DIR__ . "/gerencianet/certificado/producao-215648-pixeuro2022_pro.p12",
                'client_id' => $dadosCredentials[0],
                'client_secret' => $dadosCredentials[1],
                'sandbox' => $dadosCredentials[2] // altere conforme o ambiente (true = desenvolvimento e false = producao)
            ];

            // Define a mensagem
            $vOBSok = !empty($_POST['observacoes']) ? $_POST['observacoes'] : 'Boleto para pagamento';

            $item_1 = [
                'name' => $vOBSok, // nome do item, produto ou serviço
                'amount' => 1, // quantidade
                'value' => intval($_POST['valor'] * 100) // valor
            ];

            $items = [$item_1];

            $metadata = ['notification_url' => "https://" . $_SERVER['SERVER_NAME'] . "/adm/controller/retorno_boleto.php"];

            if (strlen($_POST['cpf_cnpj']) == 14) {
                $juridical_data = [
                    'corporate_name' => $_POST['nome_cliente'],
                    'cnpj' => $_POST['cpf_cnpj']
                ];

                $customer = [
                    'name' => $_POST['nome_cliente'],
                    'phone_number' => $_POST['telefone'],
                    'juridical_person' => $juridical_data
                ];
            } else {
                $customer = [
                    'name' => $_POST['nome_cliente'],
                    'cpf' => $_POST['cpf_cnpj'],
                    'phone_number' => $_POST['telefone']
                ];
            }

            $bankingBillet = [
                'expire_at' => $_POST['vencimento'],
                'customer' => $customer
            ];

            $payment = ['banking_billet' => $bankingBillet];

            if ($dadosCredentials[2] == false) {
                $body = [
                    'items' => $items,
                    'payment' => $payment,
                    'metadata' => $metadata
                ];
            } else {
                $body = [
                    'items' => $items,
                    'payment' => $payment,
                ];
            }

            try {
                $api = new EfiPay($options);
                $pay_charge = $api->createOneStepCharge([], $body);

                // Dados do boleto gerado
                $response = [
                    'link' => $pay_charge["data"]["link"],
                    'charge_id' => $pay_charge["data"]["charge_id"],
                    'pdf' => $pay_charge["data"]['pdf']["charge"],
                    'barcode' => $pay_charge["data"]['barcode']
                ];

                echo json_encode(['success' => true, 'data' => $response]);
            } catch (EfiException $e) {
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $e->code,
                        'message' => $e->error,
                        'description' => $e->errorDescription
                    ]
                ]);
            } catch (\Exception $e) {
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'message' => $e->getMessage()
                    ]
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => ['message' => $e->getMessage()]]);
        }
    }

    public function cancelBillet($args)
    {
        try { // Decodifique o JSON para um array associativo        
            // Pega as credenciais
            $dadosCredentials = explode("__", getChaveGerenciaNetProducao());

            $options = [
                "timeout" => 30,
                "certificate" => __DIR__ . "/gerencianet/certificado/producao-215648-pixeuro2022_pro.p12",
                'client_id' => $dadosCredentials[0],
                'client_secret' => $dadosCredentials[1],
                'sandbox' => $dadosCredentials[2] // altere conforme o ambiente (true = desenvolvimento e false = producao)
            ];

            // Pega dados da API
            $id_ref = $args['id'];

            $params = [
                'id' => $id_ref
            ];

            // Pega as credenciais
            try {
                $api = new EfiPay($options);
                $charge = $api->cancelCharge($params, []);

                // Dados do boleto gerado
                $response = [];

                echo json_encode(['success' => true, 'data' => $response]);
            } catch (EfiException $e) {
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'code' => $e->code,
                        'message' => $e->error,
                        'description' => $e->errorDescription
                    ]
                ]);
            } catch (\Exception $e) {
                echo json_encode([
                    'success' => false,
                    'error' => [
                        'message' => $e->getMessage()
                    ]
                ]);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => ['message' => $e->getMessage()]]);
        }
    }
}
