<?php
namespace src\controllers;

use \core\Controller;
use GuzzleHttp\Client;

class CNPJController extends Controller
{
    public function index()
    {
        echo "Rota CNPJ";
        exit;
    }

    public function consultaCNPJ($args)
    {
        try {
            $vCNPJ = $args['cnpj'];

            // Realizar a consulta do CNPJ
            $result = $this->consultaCNPJFromAPI($vCNPJ);

            if ($result) {
                // Retornar resultado em formato JSON
                echo json_encode($result);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'CNPJ não encontrado']);
            }
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    private function consultaCNPJFromAPI($cnpj)
    {
        $client = new Client();
        $url = "https://www.receitaws.com.br/v1/cnpj/{$cnpj}";

        try {
            $response = $client->request('GET', $url, [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ]
            ]);

            if ($response->getStatusCode() == 200) {
                return json_decode($response->getBody(), true);
            } else {
                return null;
            }
        } catch (\Exception $e) {
            throw new \Exception('Erro ao consultar CNPJ: ' . $e->getMessage());
        }
    }
}
