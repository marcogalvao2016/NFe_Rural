<?php
namespace src\controllers;

use \core\Controller;
use \src\models\ContratoModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class ContratoController extends Controller
{

    public function index()
    {
        $dados = new ContratoModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function contratosAtivos()
    {
        $dados = new ContratoModel();
        $data = $dados->contratosAtivos();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function contratosImp()
    {
        $dados = new ContratoModel();
        $data = $dados->contratosImp();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }


    public function pesquisar($args)
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));           

        $dados = new ContratoModel();
        $data = $dados->pesquisar($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarContrato($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new ContratoModel();
        $data = $dados->pesquisarContrato($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarCNPJ($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $dados = new ContratoModel();
        $data = $dados->pesquisarCNPJ($vTextoPesqusia);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new ContratoModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new ContratoModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function viewItensContrato($args)
    {
        $id = $args['id'];

        $dados = new ContratoModel();
        $data = $dados->viewItensContrato($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function printItensContrato($args)
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_fornecedor = $requestData['id_fornecedor'];
        $dtInicial = $requestData['data_inicial'];
        $dtFinal = $requestData['data_final'];
        $id_item = $requestData['id_item'];
        $numero_pregao = $requestData['numero_pregao'];

        $data = [
            "id_fornecedor" => $id_fornecedor,
            "dtInicial" => $dtInicial,
            "dtFinal" => $dtFinal,
            "id_item" => $id_item,
            "numero_pregao" => $numero_pregao,
        ];

        $dados = new ContratoModel();
        $data = $dados->printItensContrato($data);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function printContrato($args)
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_fornecedor = $requestData['id_fornecedor'];
        $dtInicial = $requestData['data_inicial'];
        $dtFinal = $requestData['data_final'];
        $numero_pregao = $requestData['numero_pregao'];
        $situacao = $requestData['situacao'];

        $data = [
            "id_fornecedor" => $id_fornecedor,
            "dtInicial" => $dtInicial,
            "dtFinal" => $dtFinal,
            "numero_pregao" => $numero_pregao,  
            "situacao" => $situacao 
        ];

        $dados = new ContratoModel();
        $data = $dados->printContrato($data);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function viewItensPregao($args)
    {
        $id = $args['id'];

        $dados = new ContratoModel();
        $data = $dados->viewItensPregao($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function viewItenmContrato($args)
    {
        $id = $args['id'];

        $dados = new ContratoModel();
        $data = $dados->viewItenmContrato($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new ContratoModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        $avatar = "";
        $temAnexo = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temAnexo = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/contratos/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }
        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['contrato'], true);

        $id = trim($requestData['id']);
        $data_inicial = $requestData['data_inicial'];
        $data_final = $requestData['data_final'];
        $numero_pregao = $requestData['numero_pregao'];
        $id_fornecedor = $requestData['id_fornecedor'];
        $observacoes = $requestData['observacoes'];
        $objeto = $requestData['objeto'];
        $itens = $_POST['itens'];
        $anexo = $avatar;

        $data = [
            "id" => $id,
            "data_inicial" => $data_inicial,
            "data_final" => $data_final,
            "numero_pregao" => $numero_pregao,
            "id_fornecedor" => $id_fornecedor,
            "observacoes" => $observacoes,
            "objeto" => $objeto,
            "itens" => $itens,
            "anexo" => $anexo,
            "temAnexo" => $temAnexo
        ];

        $dados = new ContratoModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        $avatar = "";
        $temAnexo = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temAnexo = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/contratos/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }
        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['contrato'], true);

        $data_inicial = $requestData['data_inicial'];
        $data_final = $requestData['data_final'];
        $numero_pregao = $requestData['numero_pregao'];
        $id_fornecedor = $requestData['id_fornecedor'];
        $observacoes = $requestData['observacoes'];
        $objeto = $requestData['objeto'];
        $itens = $_POST['itens'];
        $anexo = $avatar;

        $data = [
            "data_inicial" => $data_inicial,
            "data_final" => $data_final,
            "numero_pregao" => $numero_pregao,
            "id_fornecedor" => $id_fornecedor,
            "observacoes" => $observacoes,
            "objeto" => $objeto,
            "itens" => $itens,
            "anexo" => $anexo,
        ];

        $dados = new ContratoModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function AtualizaEstoque()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_item = $requestData['id_item'];
        $lote = $requestData['lote'];
        $quantidade = $requestData['quantidade'];
        $tipoupdate = $requestData['tipoupdate'];

        $data = [
            "id_item" => $id_item,
            "lote" => $lote,
            "quantidade" => $quantidade,
            "tipoupdate" => $tipoupdate,
        ];

        $dados = new ContratoModel();
        $retorno = $dados->AtualizaEstoque($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function saldoAtualItem($args)
    {
        $id = $args['id'];
        $lote = $args['lote'];

        $dados = new ContratoModel();
        $data = $dados->saldoAtualItem($id, $lote);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function updateItem()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_item = $requestData['id'];
        $lote = $requestData['lote'];
        $quantidade = $requestData['quantidade'];        

        $data = [
            "id_item" => $id_item,
            "lote" => $lote,
            "quantidade" => $quantidade,
        ];

        $dados = new ContratoModel();
        $retorno = $dados->updateItem($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }    

}