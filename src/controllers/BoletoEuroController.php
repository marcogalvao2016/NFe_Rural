<?php
namespace src\controllers;

use \core\Controller;
use \src\models\BoletoEuroModel;

use \src\controllers\WhatsAppController;
use \src\controllers\EmailController;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class BoletoEuroController extends Controller
{

    public function index()
    {
        $dados = new BoletoEuroModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarNotification()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $status_cobranca = $requestData['status_cobranca'];
        $id_categoria = $requestData['id_categoria'];
        $diainicial = $requestData['txtdiainicial'];
        $diafinal = $requestData['txtdiafinal'];
        $tipo_cobranca = $requestData['tipo_cobranca'];

        $data = [
            "status_cobranca" => $status_cobranca,
            "id_categoria" => $id_categoria,
            "diainicial" => $diainicial,
            "diafinal" => $diafinal,
            "tipo_cobranca" => $tipo_cobranca,
        ];

        $dados = new BoletoEuroModel();
        $retorno = $dados->listarNotification($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function sendNotification($args)
    {
        $vTipoEnvioMensagem = $args['tipo'];

        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $boletos = json_decode($input, true); // Decodifique o JSON para um array associativo  

        $objBoleto = new BoletoEuroModel();
        $objWhats = new WhatsAppController();
        $objEmail = new EmailController();

        $mensagem = "";
        foreach ($boletos as $key => $boleto) {
            try {
                if ($vTipoEnvioMensagem == "0") {
                    $mensagem = $objBoleto->getMensagemWhats(
                        $boleto['situacao'],
                        $boleto['razaosocial'],
                        $boleto['id_movimento'],
                        $boleto['vencimento'],
                        $boleto['link_boleto'],
                        $boleto['mensalidade']
                    );

                    sleep(5);
                    $objEmail->enviarSemAnexoParams($boleto['e_mail'], $mensagem, $boleto['id_movimento']);
                } else {
                    $mensagem = $objBoleto->getMensagemWhats(
                        $boleto['situacao'],
                        $boleto['razaosocial'],
                        $boleto['id_movimento'],
                        $boleto['vencimento'],
                        $boleto['link_boleto'],
                        $boleto['mensalidade']
                    );

                    sleep(5);
                    $objWhats->enviarMensagemParams($boleto['telefone'], $mensagem);
                }
            } catch (\Exception $e) {
                // Tratar exceção, se necessário
                return [
                    "error" => true,
                    "length" => 0,
                    "boletosweb" => [],
                ];
            }
        }

        echo json_encode([], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = strtoupper(trim($requestData['pesquisa']));

        $dados = new BoletoEuroModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarCNPJ($args)
    {
        $vTextoPesqusia = $args['cnpj'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new BoletoEuroModel();
        $data = $dados->pesquisarCNPJ($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new BoletoEuroModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new BoletoEuroModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new BoletoEuroModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        


        $id = trim($requestData['txttitulo']);
        $vencimento = $requestData['txtvencimento'];

        $data = [
            "titulo" => $id,
            "vencimento" => $vencimento,
        ];

        $dados = new BoletoEuroModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function cancelarBoleto()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        


        $id = trim($requestData['txttitulo']);

        $data = [
            "titulo" => $id,
        ];

        $dados = new BoletoEuroModel();
        $retorno = $dados->cancelar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $descricao = $requestData['descricao'];
        $taxa = floatval($requestData['taxa']);

        $taxa = str_replace(",", ".", $taxa);

        $data = [
            "descricao" => $descricao,
            "taxa" => $taxa
        ];

        $dados = new BoletoEuroModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function gerarBoletoPeriodo()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $dt_inicial = $requestData['txtdiainicial'];
        $dt_final = $requestData['txtdiafinal'];
        $mes = $requestData['cbmes'];
        $origem = $requestData['cborigem'];
        $ano = $requestData['txtano'];

        $data = [
            "dt_inicial" => $dt_inicial,
            "dt_final" => $dt_final,
            "mes" => $mes,
            "origem" => $origem,
            "ano" => $ano,
        ];

        $dados = new BoletoEuroModel();
        $retorno = $dados->generateBillet($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function criarBoletoPeriodo()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $dados = new BoletoEuroModel();
        $retorno = $dados->createBillet($requestData);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function gerarBoletoSelect()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_cliente = $requestData['txtidcliente'];
        $mensalidade = $requestData['txtmensalidade'];
        $vencimento = $requestData['txtvencimento'];

        $data = [
            "id_cliente" => $id_cliente,
            "mensalidade" => $mensalidade,
            "vencimento" => $vencimento,
        ];

        $dados = new BoletoEuroModel();
        $retorno = $dados->generateBilletSelect($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pulseUpdate()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        // $dados = new BoletoEuroModel();
        // $retorno = $dados->createBillet($requestData);

        echo json_encode([], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}