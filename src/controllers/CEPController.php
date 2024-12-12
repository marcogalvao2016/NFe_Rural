<?php
namespace src\controllers;

use \core\Controller;
use GuzzleHttp\Client;

class CEPController extends Controller
{
    public function index()
    {
        echo "Rota CEP";
        exit;
    }

    public function consultaCEP($args)
    {
        try {
            $vCEP = $args['cep'];

            // Realizar a consulta do CNPJ
            $result = $this->consultaCEPFromAPI($vCEP);

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

    private function consultaCEPFromAPI($cep)
    {
        $client = new Client();
        $url = "https://viacep.com.br/ws/{$cep}/json/";
    
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
            throw new \Exception('Erro ao consultar CEP: ' . $e->getMessage());
        }
    }
}
