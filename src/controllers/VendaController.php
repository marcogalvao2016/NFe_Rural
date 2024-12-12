<?php

namespace src\controllers;

use \core\Controller;
use \src\models\VendaModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class VendaController extends Controller
{

    public function index()
    {
        $data = [];
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisaVendasTipo()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $tipo = trim($requestData['tipo']);

        $dados = new VendaModel();
        $data = $dados->pesquisaVendasTipo($vTextoPesqusia, $tipo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisaVendasVendedorTipo()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $tipo = trim($requestData['tipo']);
        $idvendedor = $requestData['idvendedor'];

        $dados = new VendaModel();
        $data = $dados->pesquisaVendasVendedorTipo($vTextoPesqusia, $idvendedor, $tipo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function pesquisaVendasClienteTipo()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vTextoPesqusia = trim($requestData['pesquisa']);
        $tipo = trim($requestData['tipo']);
        $idcliente = $requestData['idcliente'];

        $dados = new VendaModel();
        $data = $dados->pesquisaVendasClienteTipo($vTextoPesqusia, $idcliente, $tipo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function vendasTipo($args)
    {
        $tipo = $args['tipo'];

        $dados = new VendaModel();
        $data = $dados->vendasTipo($tipo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function vendasDelivery($args)
    {
        $id_vendedor = $args['id'];

        $dados = new VendaModel();
        $data = $dados->vendasDelivery($id_vendedor);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function vendasClienteTipo($args)
    {
        $id = $args['id'];
        $tipo = $args['tipo'];

        $dados = new VendaModel();
        $data = $dados->vendasClienteTipo($id, $tipo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function vendasVendedorTipo($args)
    {
        $id = $args['id'];
        $tipo = $args['tipo'];

        $dados = new VendaModel();
        $data = $dados->vendasVendedorTipo($id, $tipo);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function view($args)
    {
        $id = $args['id'];

        $dados = new VendaModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function vendaProdutos($args)
    {
        $idvenda = $args['id'];

        $dados = new VendaModel();
        $data = $dados->vendaProdutos($idvenda);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function vendasMes()
    {
        $dados = new VendaModel();
        $data = $dados->vendasMes();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalVendas()
    {
        $dados = new VendaModel();
        $data = $dados->totalVendas();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function addAction()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_cliente = $requestData['id_cliente'];
        $data_venda = $requestData['data_venda'];
        $subtotal = floatval($requestData['total_pedido']);
        $desconto = floatval($requestData['desconto']);
        $total = floatval($requestData['total_pedido']);
        $troco = floatval($requestData['troco']);
        $vlpagto = floatval($requestData['vlpagto']);
        $nota = "0";
        $nome_cliente = "";
        $id_usuario = $requestData['id_usuario'];
        $listaItens = $requestData['produtos'];
        $listaFormasPagtos = $requestData['pagamentos'];
        $qtde_produtos = $requestData['qtde_produtos'];
        $observacoes = $requestData['observacoes'];
        $tipo = $requestData['tipo'];
        $situacao = $requestData['situacao'];
        $idatendente = $requestData['idatendente'];
        $id_caixa = $requestData['id_caixa'];
        $id_movimento = $requestData['id_movimento'];

        $data = [
            "id_cliente" => $id_cliente,
            "data_venda" => $data_venda,
            "subtotal" => $subtotal,
            "desconto" => $desconto,
            "total" => $total,
            "troco" => $troco,
            "vlpagto" => $vlpagto,
            "nota" => $nota,
            "nome_cliente" => $nome_cliente,
            "id_usuario" => $id_usuario,
            "listaItens" => $listaItens,
            "listaFormasPagtos" => $listaFormasPagtos,
            "observacoes" => $observacoes,
            "tipo" => $tipo,
            "situacao" => $situacao,
            "idatendente" => $idatendente,
            "id_caixa" => $id_caixa,
            "id_movimento" => $id_movimento,
            "qtde_produtos" => $qtde_produtos,
        ];

        $dados = new VendaModel();
        $retorno = $dados->inserir($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function inserirPedidoCliente()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_cliente = $requestData['id_cliente'];
        $data_venda = $requestData['data_venda'];
        $subtotal = floatval($requestData['total_pedido']);
        $desconto = floatval($requestData['desconto']);
        $total = floatval($requestData['total_pedido']);
        $troco = 0;
        $vlpagto = floatval($requestData['total_pedido']);
        $nota = "0";
        $nome_cliente = "";
        $id_usuario = $requestData['id_usuario'];
        $listaItens = $requestData['produtos'];
        $listaFormasPagtos = $requestData['pagamentos'];
        $qtde_produtos = $requestData['qtde_produtos'];
        $observacoes = $requestData['observacoes'];
        $tipo = $requestData['tipo'];
        $situacao = $requestData['situacao'];
        $idatendente = $requestData['idatendente'];
        $id_caixa = $requestData['id_caixa'];
        $id_movimento = $requestData['id_movimento'];
        $condicoes_pagtos = $requestData['condicoes_pagtos'];

        // // Inicializa o total do pedido como 0
        // $vTotalPedido = 0.0;
        // // Itera sobre cada item na lista de itens
        // foreach ($listaItens as $item) {
        //     // Multiplica a quantidade pelo preço de venda e soma ao total do pedido
        //     $vTotalPedido += floatval($item['estoqueatual']) * floatval($item['precovenda']);
        // }

        $data = [
            "id_cliente" => $id_cliente,
            "data_venda" => $data_venda,
            "subtotal" => $subtotal,
            "desconto" => $desconto,
            "total" => $total,
            "troco" => $troco,
            "vlpagto" => $vlpagto,
            "nota" => $nota,
            "nome_cliente" => $nome_cliente,
            "id_usuario" => $id_usuario,
            "produtos" => $listaItens,
            "listaFormasPagtos" => $listaFormasPagtos,
            "observacoes" => $observacoes,
            "tipo" => $tipo,
            "situacao" => $situacao,
            "idatendente" => $idatendente,
            "id_caixa" => $id_caixa,
            "id_movimento" => $id_movimento,
            "condicoes_pagtos" => $condicoes_pagtos,
            "qtde_vendida" => $qtde_produtos,
        ];

        $dados = new VendaModel();
        $retorno = $dados->inserirPedidoCliente($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function inserirPedidoClienteFinal()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_cliente = $requestData['id_cliente'];
        $data_venda = $requestData['data_venda'];
        $subtotal = floatval($requestData['total_pedido']);
        $desconto = floatval($requestData['desconto']);
        $total = floatval($requestData['total_pedido']);
        $troco = 0;
        $vlpagto = floatval($requestData['total_pedido']);
        $nota = "0";
        $nome_cliente = "";
        $id_usuario = $requestData['id_usuario'];
        $listaItens = $requestData['produtos'];
        $listaFormasPagtos = $requestData['pagamentos'];
        $qtde_produtos = $requestData['qtde_produtos'];
        $observacoes = $requestData['observacoes'];
        $tipo = $requestData['tipo'];
        $situacao = $requestData['situacao'];
        $idatendente = $requestData['idatendente'];
        $id_caixa = $requestData['id_caixa'];
        $id_movimento = $requestData['id_movimento'];
        $condicoes_pagtos = $requestData['condicoes_pagtos'];
        $cliente = $requestData['cliente'];

        $data = [
            "id_cliente" => $id_cliente,
            "data_venda" => $data_venda,
            "subtotal" => $subtotal,
            "desconto" => $desconto,
            "total" => $total,
            "troco" => $troco,
            "vlpagto" => $vlpagto,
            "nota" => $nota,
            "nome_cliente" => $nome_cliente,
            "id_usuario" => $id_usuario,
            "produtos" => $listaItens,
            "listaFormasPagtos" => $listaFormasPagtos,
            "observacoes" => $observacoes,
            "tipo" => $tipo,
            "situacao" => $situacao,
            "idatendente" => $idatendente,
            "id_caixa" => $id_caixa,
            "id_movimento" => $id_movimento,
            "condicoes_pagtos" => $condicoes_pagtos,
            "qtde_vendida" => $qtde_produtos,
            "cliente" => $cliente,
        ];

        $dados = new VendaModel();
        $retorno = $dados->inserirPedidoClienteFinal($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function fechaConta($args)
    {
        $id_venda = $args['id'];

        $dados = new VendaModel();
        $retorno = $dados->fechaConta($id_venda);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function alteraAnalise($args)
    {
        $id_venda = $args['id'];

        $dados = new VendaModel();
        $retorno = $dados->alteraAnalise($id_venda);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function converteVenda($args)
    {
        $id_venda = $args['id'];

        $dados = new VendaModel();
        $retorno = $dados->converteVenda($id_venda);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function alteraDelivery()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vIdVenda = $requestData['id_venda'];
        $vObservacoes = $requestData['observacoes'];
        $vFormaPagto = $requestData['forma_pagto'];

        $data = [
            "id_venda" => $vIdVenda,
            "observacoes" => $vObservacoes,
            "forma_pagto" => $vFormaPagto,
        ];

        $dados = new VendaModel();
        $retorno = $dados->alteraDelivery($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function inserirApp()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $id_cliente = $requestData['id_cliente'];
        $data_venda = $requestData['data_venda'];
        $subtotal = floatval($requestData['total_pedido']);
        $desconto = floatval($requestData['desconto']);
        $total = floatval($requestData['total_pedido']);
        $troco = floatval($requestData['troco']);
        $vlpagto = floatval($requestData['vlpagto']);
        $nota = "0";
        $nome_cliente = "";
        $id_usuario = $requestData['id_usuario'];
        $listaItens = $requestData['produtos'];
        $listaFormasPagtos = $requestData['pagamentos'];
        $qtde_produtos = $requestData['qtde_produtos'];
        $observacoes = $requestData['observacoes'];
        $tipo = $requestData['tipo'];
        $situacao = $requestData['situacao'];
        $idatendente = $requestData['idatendente'];
        $id_caixa = $requestData['id_caixa'];
        $id_movimento = $requestData['id_movimento'];

        $data = [
            "id_cliente" => $id_cliente,
            "data_venda" => $data_venda,
            "subtotal" => $subtotal,
            "desconto" => $desconto,
            "total" => $total,
            "troco" => $troco,
            "vlpagto" => $vlpagto,
            "nota" => $nota,
            "nome_cliente" => $nome_cliente,
            "id_usuario" => $id_usuario,
            "listaItens" => $listaItens,
            "listaFormasPagtos" => $listaFormasPagtos,
            "observacoes" => $observacoes,
            "tipo" => $tipo,
            "situacao" => $situacao,
            "idatendente" => $idatendente,
            "id_caixa" => $id_caixa,
            "id_movimento" => $id_movimento,
            "qtde_produtos" => $qtde_produtos,
        ];

        $dados = new VendaModel();
        $retorno = $dados->inserirApp($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarComandasAbertas()
    {
        $dados = new VendaModel();
        $data = $dados->listarComandasAbertas();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarRetiradasAbertas()
    {
        $dados = new VendaModel();
        $data = $dados->listarRetiradasAbertas();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarPedidosOnlineAbertos()
    {
        $dados = new VendaModel();
        $data = $dados->listarPedidosOnlineAbertos();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarPedidosOnlineItens($args)
    {
        $idvenda = $args['hash'];

        $dados = new VendaModel();
        $data = $dados->listarPedidosOnlineItens($idvenda);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listarComandasItens($args)
    {
        $idvenda = $args['id'];

        $dados = new VendaModel();
        $data = $dados->listarComandasItens($idvenda);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function inserirComanda()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo                

        $id_cliente = $requestData['idcliente'];
        $data_venda = $requestData['data'];
        $subtotal = floatval($requestData['subtotal']);
        $desconto = floatval($requestData['desconto']);
        $total = floatval($requestData['total']);
        $troco = floatval($requestData['troco']);
        $vlpagto = floatval($requestData['total']);
        $nota = "0";
        $nome_cliente = "";
        $id_usuario = $requestData['id_usuario'];
        $listaItens = $requestData['listaItens'];
        $listaFormasPagtos = $requestData['listaFormasPagtos'];
        $qtde_produtos = $requestData['qtde_produtos'];
        $observacoes = $requestData['observacoes'];
        $tipo = $requestData['tipo'];
        $situacao = $requestData['situacao'];
        $idatendente = $requestData['id_usuario'];
        $comanda = $requestData['comanda'];
        $tipo_lancamento = $requestData['tipo_lancamento'];
        $id_caixa = 0;
        $id_movimento = 0;

        $data = [
            "id_cliente" => $id_cliente[0],
            "data_venda" => $data_venda,
            "subtotal" => $subtotal,
            "desconto" => $desconto,
            "total" => $total,
            "troco" => $troco,
            "vlpagto" => $vlpagto,
            "nota" => $nota,
            "nome_cliente" => $nome_cliente,
            "id_usuario" => $id_usuario,
            "produtos" => $listaItens,
            "listaFormasPagtos" => $listaFormasPagtos,
            "observacoes" => $observacoes,
            "tipo" => $tipo,
            "situacao" => $situacao,
            "idatendente" => $idatendente,
            "id_caixa" => $id_caixa,
            "id_movimento" => $id_movimento,
            "qtde_produtos" => $qtde_produtos,
            "comanda" => $comanda,
            "tipo_lancamento" => $tipo_lancamento
        ];

        $dados = new VendaModel();
        $retorno = $dados->inserirComanda($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function receberComanda($args)
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo   

        $id_pedido = $args['id'];
        $valores = $requestData['valores'];
        $totalComanda = $requestData['totalComanda'];

        $totalComanda = str_replace(",", ".", $totalComanda);

        $data = [
            "id_pedido" => $id_pedido,
            "valores" => $valores,
            "totalComanda" => $totalComanda,
        ];

        $dados = new VendaModel();
        $retorno = $dados->receberComanda($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    function inserirPedidoOnline()
    {
        // Obtenha o conteúdo JSON enviado no corpo da solicitação
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo                

        $id_cliente = $requestData['idcliente'];
        $data_venda = $requestData['data'];
        $subtotal = floatval($requestData['subtotal']);
        $desconto = floatval($requestData['desconto']);
        $total = floatval($requestData['total']);
        $troco = floatval($requestData['troco']);
        $vlpagto = floatval($requestData['total']);
        $nome_cliente = $requestData['nome_cliente'];
        $id_usuario = $requestData['id_usuario'];
        $nome_usuario = $requestData['nome_usuario'];
        $listaItens = $requestData['listaItens'];
        $listaFormasPagtos = $requestData['listaFormasPagtos'];
        $qtde_produtos = $requestData['qtde_produtos'];
        $observacoes = $requestData['observacoes'];
        $tipo = $requestData['tipo'];
        $idatendente = $requestData['id_usuario'];
        $comanda = $requestData['numero_mesa'];
        $hash = $requestData['hash'];
        $channel = $requestData['channel'];
        $telefone = $requestData['telefone'];
        $resumo_pagto = $requestData['resumo_pagto'];
        $entrega_taxa = $requestData['entrega_taxa'];
        $endereco = $requestData['endereco'];
        $numero = $requestData['numero'];
        $bairro = $requestData['bairro'];
        $id_bairro = $requestData['id_bairro'];
        $balcao_espera = "N";
        $situacao = "A";
        $nota = "0";
        $id_caixa = 0;
        $id_movimento = 0;

        if ($tipo == "B") {
            $balcao_espera = "S";
        }

        $data = [
            "id_cliente" => $id_cliente,
            "data_venda" => $data_venda,
            "subtotal" => $subtotal,
            "desconto" => $desconto,
            "total" => $total,
            "troco" => $troco,
            "vlpagto" => $vlpagto,
            "nota" => $nota,
            "nome_cliente" => $nome_cliente,
            "id_usuario" => $id_usuario,
            "produtos" => $listaItens,
            "listaFormasPagtos" => $listaFormasPagtos,
            "observacoes" => $observacoes,
            "tipo" => $tipo,
            "situacao" => $situacao,
            "idatendente" => $idatendente,
            "id_caixa" => $id_caixa,
            "id_movimento" => $id_movimento,
            "qtde_produtos" => $qtde_produtos,
            "comanda" => $comanda,
            "nome_usuario" => $nome_usuario,
            "hash" => $hash,
            "channel" => $channel,
            "telefone" => $telefone,
            "resumo_pagto" => $resumo_pagto,
            "entrega_taxa" => $entrega_taxa,
            "endereco" => $endereco,
            "numero" => $numero,
            "bairro" => $bairro,
            "id_bairro" => $id_bairro,
            "balcao_espera" => $balcao_espera,
        ];

        $dados = new VendaModel();
        $retorno = $dados->inserirPedidoOnline($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    function printVendas()
    {
        $input = file_get_contents('php://input');
        $requestData = json_decode($input, true); // Decodifique o JSON para um array associativo        

        $vIdCliente = $requestData['id_cliente'];
        $vIdFuncionario = $requestData['id_funcionario'];
        $dtInicial = $requestData['data_inicial'];
        $dtFinal = $requestData['data_final'];
        $situacao = $requestData['situacao'];

        $data = [
            "id_cliente" => $vIdCliente,
            "id_funcionario" => $vIdFuncionario,
            "dtInicial" => $dtInicial,
            "dtFinal" => $dtFinal,
            "situacao" => $situacao,
        ];
        $dados = new VendaModel();
        $retorno = $dados->printVenda($data);

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
