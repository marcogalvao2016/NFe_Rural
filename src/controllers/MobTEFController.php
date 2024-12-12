<?php
namespace src\controllers;

use \core\Controller;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class MobTEFController extends Controller
{

    public function index()
    {
        $data = array();
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function createPay()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $pedido_id = $requestData['pedido_id'];
        $valor = $requestData['valor'];
        $parcelas = $requestData['parcelas'];
        $tipo = $requestData['tipo'];
        $cliente_nome = $requestData['cliente_nome'];
        $obs = $requestData['obs'];
        $webhook = $requestData['webhook'];
        $xml = $requestData['xml'];

        $data = [
            "pedido_id" => $pedido_id,
            "valor" => $valor,
            "parcelas" => $parcelas,
            "tipo" => $tipo,
            "cliente_nome" => $cliente_nome,
            "obs" => $obs,
            "webhook" => $webhook,
            "xml" => $xml,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.meupdvmovel.com.br/webhook/mpm/tef",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "empresa: 213",
                "token: 2135771464423",
                "usuario: 191"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function takeReturn($args)
    {
        $id = $args['id'];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.meupdvmovel.com.br/webhook/mpm/tef",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "empresa: 213",
                "id: " . $id,
                "token: 2135771464423",
                "usuario: 191"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }

    public function takeConsultaReturn($args)
    {
        $id = $args['id'];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.meupdvmovel.com.br/webhook/mpm/tef",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "empresa: 213",
                "id: " . $id,
                "token: 2135771464423",
                "usuario: 191"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
    }
}