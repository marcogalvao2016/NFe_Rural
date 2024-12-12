<?php
namespace src\controllers;

use \core\Controller;
use \src\models\ClienteEuroModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class ClienteEuroController extends Controller
{

    public function index($args)
    {
        $vTipoCliente = $args['tipo'];

        if ($vTipoCliente === "undefined"){
            $vTipoCliente = "NA";
        }

        $vTipoCliente = strtoupper(trim($vTipoCliente));

        $dados = new ClienteEuroModel();
        $data = $dados->listar($vTipoCliente);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = strtoupper(trim($requestData['pesquisa']));

        $vTipoCliente = $requestData['tipo'];

        if ($vTipoCliente === "undefined"){
            $vTipoCliente = "NA";
        }

        $dados = new ClienteEuroModel();
        $data = $dados->pesquisar($vTextoPesqusia, $vTipoCliente);

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarCNPJ($args)
    {
        $vTextoPesqusia = $args['cnpj'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $vTipoCliente = $args['tipo'];

        if ($vTipoCliente === "undefined"){
            $vTipoCliente = "NA";
        }

        $dados = new ClienteEuroModel();
        $data = $dados->pesquisarCNPJ($vTextoPesqusia, $vTipoCliente);

        header('Content-Type: application/json');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new ClienteEuroModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new ClienteEuroModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction()
    {
        $avatar = "";
        $temIMG = "N";

        $arquivo1 = "";
        $temArq1 = "N";

        $arquivo2 = "";
        $temArq2 = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temIMG = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/clients/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }

        } else {
            //  echo "No file uploaded.";
        }

        if (isset($_FILES['file1'])) {
            $file = $_FILES['file1'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $arquivo1 = $fileName;
            $temArq1 = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/clients/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }

        } else {
            //  echo "No file uploaded.";
        }

        if (isset($_FILES['file2'])) {
            $file = $_FILES['file2'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $arquivo2 = $fileName;
            $temArq2 = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/clients/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }

        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['cliente'], true);

        $id = trim($requestData['id']);
        $razaosocial = trim($requestData['razaosocial']);
        $mensalidade = trim($requestData['mensalidade']);
        $nomeproprietario = trim($requestData['nomeproprietario']);
        $cnpj = trim($requestData['cnpj']);
        $telefone = trim($requestData['telefone']);
        $e_mail = trim($requestData['e_mail']);
        $observacoes = trim($requestData['observacoes']);
        $situacao = trim($requestData['situacao']);
        $vencimento = trim($requestData['vencimento']);
        $maquinas = trim($requestData['maquinas']);
        $data_vencimento = trim($requestData['data_vencimento']);
        $cpf = trim($requestData['cpf']);
        $origem = trim($requestData['origem']);
        $email_contador = trim($requestData['email_contador']);
        $emite_nota = trim($requestData['emite_nota']);
        $notas = trim($requestData['notas']);
        $id_categoria = trim($requestData['id_categoria']);
        $link = trim($requestData['link']);
        $venc_certificado = trim($requestData['venc_certificado']);
        $inicio = trim($requestData['inicio']);
        $fim = trim($requestData['fim']);
        $enviar_xml = trim($requestData['enviar_xml']);
        $xml_validos = trim($requestData['xml_validos']);
        $id_seguimento = trim($requestData['id_seguimento']);
        $status = trim($requestData['status']);
        $avatar = trim($requestData['caminho_arquivo']);
        $caminho_arquivo1 = trim($requestData['caminho_arquivo1']);
        $caminho_arquivo2 = trim($requestData['caminho_arquivo2']);

        $razaosocial = strtoupper($razaosocial);
        $nomeproprietario = strtoupper($nomeproprietario);

        $mensalidade = str_replace(",", ".", $mensalidade);

        $data = [
            "id" => $id,
            "razaosocial" => $razaosocial,
            "cnpj" => $cnpj,
            "telefone" => $telefone,
            "e_mail" => $e_mail,
            "observacoes" => $observacoes,
            "situacao" => $situacao,
            "vencimento" => $vencimento,
            "maquinas" => $maquinas,
            "data_vencimento" => $data_vencimento,
            "nomeproprietario" => $nomeproprietario,
            "cpf" => $cpf,
            "origem" => $origem,
            "email_contador" => $email_contador,
            "emite_nota" => $emite_nota,
            "notas" => $notas,
            "id_categoria" => $id_categoria,
            "link" => $link,
            "venc_certificado" => $venc_certificado,
            "inicio" => $inicio,
            "fim" => $fim,
            "enviar_xml" => $enviar_xml,
            "xml_validos" => $xml_validos,
            "id_seguimento" => $id_seguimento,
            "mensalidade" => $mensalidade,
            "status" => $status,
            "avatar" => $avatar,
            "caminho_arquivo1" => $arquivo1,
            "caminho_arquivo2" => $arquivo2,
            "temIMG" => $temIMG,
            "temArq1" => $temArq1,
            "temArq2" => $temArq2,
        ];

        $dados = new ClienteEuroModel();
        $retorno = $dados->alterar($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        $avatar = "";
        $temIMG = "N";

        $arquivo1 = "";
        $temArq1 = "N";

        $arquivo2 = "";
        $temArq2 = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temIMG = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/clients/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }

        } else {
            //  echo "No file uploaded.";
        }

        if (isset($_FILES['file1'])) {
            $file = $_FILES['file1'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $arquivo1 = $fileName;
            $temArq1 = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/clients/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }

        } else {
            //  echo "No file uploaded.";
        }

        if (isset($_FILES['file2'])) {
            $file = $_FILES['file2'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $arquivo2 = $fileName;
            $temArq2 = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/clients/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }

        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['cliente'], true);

        $razaosocial = trim($requestData['razaosocial']);
        $mensalidade = trim($requestData['mensalidade']);
        $nomeproprietario = trim($requestData['nomeproprietario']);
        $cnpj = trim($requestData['cnpj']);
        $telefone = trim($requestData['telefone']);
        $e_mail = trim($requestData['e_mail']);
        $observacoes = trim($requestData['observacoes']);
        $situacao = trim($requestData['situacao']);
        $vencimento = trim($requestData['vencimento']);
        $maquinas = trim($requestData['maquinas']);
        $data_vencimento = trim($requestData['data_vencimento']);
        $cpf = trim($requestData['cpf']);
        $origem = trim($requestData['origem']);
        $email_contador = trim($requestData['email_contador']);
        $emite_nota = trim($requestData['emite_nota']);
        $notas = trim($requestData['notas']);
        $id_categoria = trim($requestData['id_categoria']);
        $link = trim($requestData['link']);
        $venc_certificado = trim($requestData['venc_certificado']);
        $inicio = trim($requestData['inicio']);
        $fim = trim($requestData['fim']);
        $enviar_xml = trim($requestData['enviar_xml']);
        $xml_validos = trim($requestData['xml_validos']);
        $id_seguimento = trim($requestData['id_seguimento']);
        $status = trim($requestData['status']);
        $avatar = trim($requestData['caminho_arquivo']);
        $caminho_arquivo1 = trim($requestData['caminho_arquivo1']);
        $caminho_arquivo2 = trim($requestData['caminho_arquivo2']);

        $razaosocial = strtoupper($razaosocial);
        $nomeproprietario = strtoupper($nomeproprietario);

        $mensalidade = str_replace(",", ".", $mensalidade);

        $data = [
            "razaosocial" => $razaosocial,
            "cnpj" => $cnpj,
            "telefone" => $telefone,
            "e_mail" => $e_mail,
            "observacoes" => $observacoes,
            "situacao" => $situacao,
            "vencimento" => $vencimento,
            "maquinas" => $maquinas,
            "data_vencimento" => $data_vencimento,
            "nomeproprietario" => $nomeproprietario,
            "cpf" => $cpf,
            "origem" => $origem,
            "email_contador" => $email_contador,
            "emite_nota" => $emite_nota,
            "notas" => $notas,
            "id_categoria" => $id_categoria,
            "link" => $link,
            "venc_certificado" => $venc_certificado,
            "inicio" => $inicio,
            "fim" => $fim,
            "enviar_xml" => $enviar_xml,
            "xml_validos" => $xml_validos,
            "id_seguimento" => $id_seguimento,
            "mensalidade" => $mensalidade,
            "status" => $status,
            "avatar" => $avatar,
            "caminho_arquivo1" => $arquivo1,
            "caminho_arquivo2" => $arquivo2,
            "temIMG" => $temIMG,
            "temArq1" => $temArq1,
            "temArq2" => $temArq2,
        ];

        $dados = new ClienteEuroModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new ClienteEuroModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}