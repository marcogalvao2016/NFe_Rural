<?php
namespace src\controllers;

use \core\Controller;
use src\models\WhatsAppModel;

class WhatsAppController extends Controller
{
    public function index()
    {
        echo "Rota WhatsApp";
        exit;
    }

    public function enviarMensagemParams($fone, $mensagem)
    {
        try {
            $vNumero = $fone;
            $vMessage = $mensagem;

            // Verifica se o número começa com '65' e o próximo dígito é '9'
            if (substr($vNumero, 0, 2) === '65' && $vNumero[2] === '9') {
                // Remove o '9' após '65'
                $vNumero = substr($vNumero, 0, 2) . substr($vNumero, 3);
            } elseif ($vNumero[0] === '9') {
                // Remove o primeiro '9' se não tiver código de área
                $vNumero = substr($vNumero, 1);
            }

            $vNumero = '55' . $vNumero . '@s.whatsapp.net';

            // Decodifica a mensagem
            $vMessage = quoted_printable_decode($vMessage);

            $data = [
                'messageData' => [
                    'to' => $vNumero,
                    'text' => $vMessage
                ]
            ];

            // $url = 'https://apistart03.megaapi.com.br/rest/sendMessage/megastart-MnBslpXeeTNmYE3npPMuZ0hZOA/text';
            // $accessToken = 'MnBslpXeeTNmYE3npPMuZ0hZOA';

            $dados = explode("_", getAPIWhats());
            $url = $dados[0] . "/" . $dados[1] . "/text";
            $accessToken = $dados[2];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
                'Accept: */*'
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignorar verificação do certificado SSL

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                http_response_code(500);
                echo json_encode(['error' => curl_error($ch)]);
            } else {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($http_code === 200) {
                    http_response_code(200);
                    echo "Mensagem enviada com sucesso!!!";
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => $response]);
                }
            }

            curl_close($ch);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function enviarMensagem()
    {
        try {
            // Obtenha o conteúdo JSON enviado no corpo da solicitação
            $input = file_get_contents('php://input');
            $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo

            // Verifique se os dados necessários estão presentes
            if (!isset($requestData['to']) || !isset($requestData['message'])) {
                throw new \Exception('Campos "to" e "message" são obrigatórios.');
            }

            $vNumero = trim($requestData['to']);
            $vMessage = $requestData['message'];

            // Verifica se o número começa com '65' e o próximo dígito é '9'
            if (substr($vNumero, 0, 2) === '65' && $vNumero[2] === '9') {
                // Remove o '9' após '65'
                $vNumero = substr($vNumero, 0, 2) . substr($vNumero, 3);
            } elseif ($vNumero[0] === '9') {
                // Remove o primeiro '9' se não tiver código de área
                $vNumero = substr($vNumero, 1);
            }

            $vNumero = '55' . $vNumero . '@s.whatsapp.net';
            // Decodifica a mensagem
            $vMessage = quoted_printable_decode($vMessage);

            $data = [
                'messageData' => [
                    'to' => $vNumero,
                    'text' => $vMessage
                ]
            ];

            // $url = 'https://apistart03.megaapi.com.br/rest/sendMessage/megastart-MnBslpXeeTNmYE3npPMuZ0hZOA/text';
            // $accessToken = 'MnBslpXeeTNmYE3npPMuZ0hZOA';

            $dados = explode("_", getAPIWhats());
            $url = $dados[0] . "/" . $dados[1] . "/text";
            $accessToken = $dados[2];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
                'Accept: */*'
            ]);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignorar verificação do certificado SSL

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                http_response_code(500);
                echo json_encode(['error' => curl_error($ch)]);
            } else {
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                if ($http_code === 200) {
                    http_response_code(200);
                    echo "Mensagem enviada com sucesso!!!";
                } else {
                    http_response_code(500);
                    echo json_encode(['error' => $response]);
                }
            }

            curl_close($ch);

        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function enviarVariasMensagens()
    {
        try {
            $input = file_get_contents('php://input');
            $requestData = json_decode($input, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Erro ao decodificar JSON: ' . json_last_error_msg());
            }

            if (!isset($requestData['message']) || !isset($requestData['to']) || !is_array($requestData['to'])) {
                throw new \Exception('Estrutura JSON inválida. Certifique-se de que "message" e "to" estão presentes.');
            }

            $vMessage = $requestData['message'];
            error_log('Início do envio de mensagens');
            $startTime = microtime(true);

            foreach ($requestData['to'] as $index => $vNumero) {
                if (substr($vNumero, 0, 2) === '65' && $vNumero[2] === '9') {
                    $vNumero = substr($vNumero, 0, 2) . substr($vNumero, 3);
                } elseif ($vNumero[0] === '9') {
                    $vNumero = substr($vNumero, 1);
                }

                $vNumero = '55' . $vNumero . '@s.whatsapp.net';
                $vMessageDecoded = quoted_printable_decode($vMessage);

                $data = [
                    'messageData' => [
                        'to' => $vNumero,
                        'text' => $vMessageDecoded
                    ]
                ];

                // $url = 'https://apistart03.megaapi.com.br/rest/sendMessage/megastart-MnBslpXeeTNmYE3npPMuZ0hZOA/text';
                // $accessToken = 'MnBslpXeeTNmYE3npPMuZ0hZOA';

                $dados = explode("_", getAPIWhats());
                $url = $dados[0] . "/" . $dados[1] . "/text";
                $accessToken = $dados[2];

                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                    'Accept: */*'
                ]);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout de 30 segundos
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10); // Timeout de conexão de 10 segundos

                error_log("Enviando para $vNumero com mensagem: " . json_encode($data));

                $response = curl_exec($ch);
                $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                if (curl_errno($ch)) {
                    error_log('cURL error: ' . curl_error($ch));
                    http_response_code(500);
                    echo json_encode(['error' => curl_error($ch)]);
                } else {
                    error_log('HTTP status code: ' . $http_code);
                    error_log('Response: ' . $response);
                    if ($http_code === 200) {
                        echo "Mensagem enviada com sucesso para $vNumero!\n";
                    } else {
                        http_response_code(500);
                        echo json_encode(['error' => $response]);
                    }
                }

                curl_close($ch);

                sleep(5);
                error_log("Tempo decorrido para enviar mensagem: " . (microtime(true) - $startTime) . " segundos");
            }

            error_log('Fim do envio de mensagens');
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function enviarDocumentText()
    {
        try {
            // Verifica se foi enviado algum arquivo
            if (isset($_FILES['file'])) {
                // Nome do arquivo
                $nome_arquivo = $_FILES['file']['name'];

                // Obtendo a extensão do arquivo
                $extensao = pathinfo($nome_arquivo, PATHINFO_EXTENSION);

                // Obtenha o conteúdo JSON enviado no corpo da solicitação
                // Pegando os outros parâmetros
                $vNumero = $_POST['to'];
                $vMessage = $_POST['message'];

                // Decodifica a mensagem
                $vMessage = quoted_printable_decode($vMessage);

                // Verifica se o número começa com '65' e o próximo dígito é '9'
                if (substr($vNumero, 0, 2) === '65' && $vNumero[2] === '9') {
                    // Remove o '9' após '65'
                    $vNumero = substr($vNumero, 0, 2) . substr($vNumero, 3);
                } elseif ($vNumero[0] === '9') {
                    // Remove o primeiro '9' se não tiver código de área
                    $vNumero = substr($vNumero, 1);
                }

                $vNumero = '55' . $vNumero . '@s.whatsapp.net';

                // Verifica se não houve erro no envio do arquivo
                if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
                    // Diretório onde o arquivo será salvo
                    $diretorio_destino = 'dist/img/files/';

                    // Move o arquivo enviado para o diretório de destino
                    $caminho_arquivo = $diretorio_destino . $_FILES['file']['name'];
                    move_uploaded_file($_FILES['file']['tmp_name'], $caminho_arquivo);

                    // Lê o conteúdo do arquivo
                    $conteudoArquivo = file_get_contents($caminho_arquivo);

                    // Converte o conteúdo do arquivo para base64
                    $base64Data = base64_encode($conteudoArquivo);

                    if ($extensao == "pdf") {
                        // Constrói os dados no formato esperado pela API
                        $data = [
                            'messageData' => [
                                'to' => $vNumero,
                                'base64' => 'data:application/pdf;base64,' . $base64Data,
                                'fileName' => 'document.pdf',
                                'type' => 'document',
                                'caption' => $vMessage,
                                'mimeType' => 'application/pdf'
                            ]
                        ];
                    } else if ($extensao == "xml") {
                        // Constrói os dados no formato esperado pela API
                        $data = [
                            'messageData' => [
                                'to' => $vNumero,
                                'base64' => 'data:application/xml;base64,' . $base64Data,
                                'fileName' => 'document.xml',
                                'type' => 'document',
                                'caption' => $vMessage,
                                'mimeType' => 'application/xml'
                            ]
                        ];
                    } else {
                        // Constrói os dados no formato esperado pela API
                        $data = [
                            'messageData' => [
                                'to' => $vNumero,
                                'base64' => 'data:image/png;base64,' . $base64Data,
                                'fileName' => 'imagem.' . $extensao,
                                'type' => 'image',
                                'caption' => $vMessage,
                                'mimeType' => 'image/jpeg'
                            ]
                        ];
                    }

                    // $url = 'https://apistart03.megaapi.com.br/rest/sendMessage/megastart-MnBslpXeeTNmYE3npPMuZ0hZOA/mediaBase64';
                    // $accessToken = 'MnBslpXeeTNmYE3npPMuZ0hZOA';

                    $dados = explode("_", getAPIWhats());
                    $url = $dados[0] . "/" . $dados[1] . "/mediaBase64";
                    $accessToken = $dados[2];

                    // Inicializa a sessão cURL
                    $ch = curl_init($url);

                    // Configura as opções da requisição cURL
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $accessToken,
                        'Content-Type: application/json'
                    ]);

                    // Desabilita a verificação do certificado SSL
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

                    // Executa a requisição cURL e obtém a resposta
                    $response = curl_exec($ch);

                    // Verifica se ocorreu algum erro na requisição cURL
                    if (curl_errno($ch)) {
                        throw new \Exception('Erro ao enviar a requisição: ' . curl_error($ch));
                    }

                    // Obtém o código de status HTTP da resposta
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

                    // Fecha a sessão cURL
                    curl_close($ch);

                    // Verifica se a requisição foi bem-sucedida
                    if ($httpCode === 200) {
                        echo 'Mensagem enviada com sucesso!!!';
                    } else {
                        throw new \Exception('Erro ao enviar a mensagem. Código de status: ' . $httpCode);
                    }
                } else {
                    // Erro no envio do arquivo
                    http_response_code(500);
                    echo json_encode(['error' => 'Erro no envio do arquivo.']);
                }
            } else {
                // Nenhum arquivo foi enviado
                http_response_code(400);
                echo json_encode(['error' => 'Nenhum arquivo foi enviado.']);
            }
        } catch (\Exception $e) {
            // Exceção ocorreu
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }

    }

    public function recusa($args)
    {
        try {
            $texto = $args['texto'];

            $hex_string = $texto;//"636e706a2f32393835303134343030303131392f666f6e652f3635393932353635303138";

            // Converter a string hexadecimal para texto
            $decoded_string = hex2bin($hex_string);

            // Dividir a string decodificada em partes usando '/'
            $parts = explode('/', $decoded_string);

            // Criar um array associativo para armazenar os valores
            $data = [];
            for ($i = 0; $i < count($parts); $i += 2) {
                $data[$parts[$i]] = $parts[$i + 1];
            }

            $dados = new WhatsAppModel();
            $data = $dados->recusa($data['fone']);

            echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);

        } catch (\Exception $e) {
            // Exceção ocorreu
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }

    }
}