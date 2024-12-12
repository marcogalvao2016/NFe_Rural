<?php
namespace src\controllers;

use \core\Controller;
use \src\models\ProdutoModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class ProdutoController extends Controller
{

    public function index()
    {
        $dados = new ProdutoModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function buscarPorTabela($args)
    {
        $id_tabela = $args['idtabela'];

        $dados = new ProdutoModel();
        $data = $dados->buscarPorTabela($id_tabela);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function ordenarPorTabela($args)
    {
        $id_tabela = $args['idtabela'];
        $tipo = $args['tipo'];

        $dados = new ProdutoModel();
        $data = $dados->ordenarPorTabela($id_tabela, $tipo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarOrdem($args)
    {
        $id_tabela = $args['idtabela'];
        $tipo = $args['tipo'];

        $dados = new ProdutoModel();
        $data = $dados->ordenarPorDescricao($id_tabela, $tipo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function buscarPorGrupo($args)
    {
        $id_grupo = $args['idgrupo'];   
        $id_tabela = $args['idtabela'];   

        $dados = new ProdutoModel();
        $data = $dados->buscarPorGrupo($id_grupo, $id_tabela);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function buscarPorSubGrupo($args)
    {
        $id_subgrupo = $args['idsubgrupo'];
        $id_tabela = $args['idtabela'];

        $dados = new ProdutoModel();
        $data = $dados->buscarPorSubGrupo($id_subgrupo, $id_tabela);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function buscarPorMarca($args)
    {
        $id_marca = $args['idmarca'];
        $id_tabela = $args['idtabela']; 

        $dados = new ProdutoModel();
        $data = $dados->buscarPorMarca($id_marca, $id_tabela);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function buscarPorGrupoPizza($args)
    {
        $id_grupo = $args['idgrupo'];
        $id_subgrupo = $args['idsubgrupo'];

        $dados = new ProdutoModel();
        $data = $dados->buscarPorGrupoPizza($id_grupo, $id_subgrupo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function partial($args)
    {
        $vQtRegistro = $args['qt'];

        $dados = new ProdutoModel();
        $data = $dados->partial($vQtRegistro);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];
        $id_tabela = $args['idtabela'];

        $dados = new ProdutoModel();
        $data = $dados->view($id, $id_tabela);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function delete($args)
    {
        $id = $args['id'];

        $dados = new ProdutoModel();
        $retorno = $dados->deletar($id);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function editAction($args)
    {
        $avatar = "";
        $temIMG = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temIMG = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/produto/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }
        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['produto'], true);

        $id_tabela = $args['idtabela'];

        $id = $requestData['id'];
        $descricao = $requestData['descricao'];
        $ean = $requestData['ean'];
        $reffabricante = $requestData['reffabricante'];
        $precocusto = $requestData['precocusto'];
        $precovenda = $requestData['precovenda'];
        $estoqueatual = $requestData['estoqueatual'];
        $idgrupo = $requestData['idgrupo'];
        $idncm = $requestData['idncm'];
        $unidsaida = $requestData['unidsaida'];
        $observacoes = $requestData['observacoes'];
        $tipo_produto = $requestData['tipo_produto'];
        $id_subgrupo = $requestData['id_subgrupo'];
        $url = $requestData['url'];
        $id_marca = $requestData['id_marca'];
        $nome_foto = $avatar;

        $data = [
            "id" => $id,
            "descricao" => $descricao,
            "ean" => $ean,
            "reffabricante" => $reffabricante,
            "precocusto" => $precocusto,
            "precovenda" => $precovenda,
            "estoqueatual" => $estoqueatual,
            "idgrupo" => $idgrupo,
            "idncm" => $idncm,
            "unidsaida" => $unidsaida,
            "observacoes" => $observacoes,
            "tipo_produto" => $tipo_produto,
            "id_subgrupo" => $id_subgrupo,
            "url" => $url,
            "id_marca" => $id_marca,
            "nome_foto" => $nome_foto,
            "temIMG" => $temIMG
        ];

        $dados = new ProdutoModel();
        $retorno = $dados->alterar($data, $id_tabela);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction($args)
    {
        $avatar = "";
        $temIMG = "N";

        $surceFiles = generateSourceString();

        // Para acessar o arquivo
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];

            $avatar = $fileName;
            $temIMG = "S";

            $documentRoot = $_SERVER['DOCUMENT_ROOT'] . $surceFiles;
            $targetDir = $documentRoot . "/rest/dist/img/produto/";

            if (move_uploaded_file($fileTmpName, $targetDir . $fileName)) {

            } else {

            }
        } else {
            //  echo "No file uploaded.";
        }

        $requestData = json_decode($_POST['produto'], true);

        $id_tabela = $args['idtabela'];

        $descricao = $requestData['descricao'];
        $ean = $requestData['ean'];
        $reffabricante = $requestData['reffabricante'];
        $precocusto = $requestData['precocusto'];
        $precovenda = $requestData['precovenda'];
        $estoqueatual = $requestData['estoqueatual'];
        $idgrupo = $requestData['idgrupo'];
        $idncm = $requestData['idncm'];
        $unidsaida = $requestData['unidsaida'];
        $observacoes = $requestData['observacoes'];
        $tipo_produto = $requestData['tipo_produto'];
        $id_subgrupo = $requestData['id_subgrupo'];
        $id_marca = $requestData['id_marca'];
        $url = $requestData['url'];
        $nome_foto = $avatar;

        $data = [
            "descricao" => $descricao,
            "ean" => $ean,
            "reffabricante" => $reffabricante,
            "precocusto" => $precocusto,
            "precovenda" => $precovenda,
            "estoqueatual" => $estoqueatual,
            "idgrupo" => $idgrupo,
            "idncm" => $idncm,
            "unidsaida" => $unidsaida,
            "observacoes" => $observacoes,
            "tipo_produto" => $tipo_produto,
            "id_subgrupo" => $id_subgrupo,
            "url" => $url,
            "id_marca" => $id_marca,
            "nome_foto" => $nome_foto,
            "temIMG" => $temIMG
        ];

        $dados = new ProdutoModel();
        $retorno = $dados->inserir($data, $id_tabela);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisar()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = strtoupper(trim($requestData['pesquisa']));
        $id_tabela = trim($requestData['idtabela']);

        $dados = new ProdutoModel();
        $data = $dados->pesquisar($vTextoPesqusia, $id_tabela);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarPDVCliente()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = strtoupper(trim($requestData['pesquisa']));
        $id_tabela = trim($requestData['idtabela']);
        $id_grupo = trim($requestData['idgrupo']);
        $id_marca = trim($requestData['idmarca']);

        $dados = new ProdutoModel();
        $data = $dados->consultaPDVCliente($vTextoPesqusia, $id_tabela, $id_grupo, $id_marca);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarEAN($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $id_tabela = $args['idtabela'];

        $dados = new ProdutoModel();
        $data = $dados->pesquisarEAN($vTextoPesqusia, $id_tabela);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarGrupo($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $id_grupo = $args['idgrupo'];

        $dados = new ProdutoModel();
        $data = $dados->pesquisarGrupo($vTextoPesqusia, $id_grupo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisarGrupoPizza($args)
    {
        $vTextoPesqusia = $args['texto'];
        $vTextoPesqusia = strtoupper(trim($vTextoPesqusia));

        $id_grupo = $args['idgrupo'];
        $id_subgrupo = $args['idsubgrupo'];

        $dados = new ProdutoModel();
        $data = $dados->pesquisarGrupoPizza($vTextoPesqusia, $id_grupo, $id_subgrupo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function contaTotalProdutos()
    {
        $dados = new ProdutoModel();
        $data = $dados->contaTotalProdutos();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarPorPaginacaoTabela($args)
    {
        $id_tabela = $args['idtabela'];
        $pagina = $args['pagina'];
        $limite = $args['limite'];

        $dados = new ProdutoModel();
        $data = $dados->listarPorPaginacaoTabela($id_tabela, $pagina, $limite);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

}