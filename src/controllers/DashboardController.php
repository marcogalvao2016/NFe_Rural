<?php

namespace src\controllers;

use \core\Controller;
use \src\models\DashboardModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class DashboardController extends Controller
{

    public function index()
    {
        $dados = new DashboardModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalClientes()
    {
        $dados = new DashboardModel();
        $data = $dados->totalClientes();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalClientesVendedor($args)
    {
        $idVendedor = $args['idvendedor'];

        $dados = new DashboardModel();
        $data = $dados->totalClientesVendedor($idVendedor);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalClientesMes()
    {
        $dados = new DashboardModel();
        $data = $dados->totalClientesMes();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalClientesMesVendedor($args)
    {
        $idVendedor = $args['idvendedor'];

        $dados = new DashboardModel();
        $data = $dados->totalClientesMesVendedor($idVendedor);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalVendasMes()
    {
        $dados = new DashboardModel();
        $data = $dados->totalVendasMes();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalVendasCliente($args)
    {
        $idcliente = $args['idcliente'];

        $dados = new DashboardModel();
        $data = $dados->totalVendasCliente($idcliente);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalQtVendasCliente($args)
    {
        $idcliente = $args['idcliente'];

        $dados = new DashboardModel();
        $data = $dados->totalQtVendasCliente($idcliente);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }


    public function totalVendasMesVendedor($args)
    {
        $idVendedor = $args['idvendedor'];

        $dados = new DashboardModel();
        $data = $dados->totalVendasMesVendedor($idVendedor);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalCPMes()
    {
        $dados = new DashboardModel();
        $data = $dados->totalCPMes();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalCRMes()
    {
        $dados = new DashboardModel();
        $data = $dados->totalCRMes();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalCP()
    {
        $dados = new DashboardModel();
        $data = $dados->totalCP();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totalCR()
    {
        $dados = new DashboardModel();
        $data = $dados->totalCR();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function contaTotalProductEcommerce()
    {
        $dados = new DashboardModel();
        $data = $dados->contaTotalProductEcommerce();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function createCategoryAPI()
    {
        $dados = new DashboardModel();
        $retorno = $dados->createCategoryAPI();

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function createProductAPI()
    {
        $dados = new DashboardModel();
        $retorno = $dados->createProdcutAPI();

        echo json_encode($retorno, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totComandasAbertas()
    {
        $dados = new DashboardModel();
        $data = $dados->totComandasAbertas();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totPedidoEspera()
    {
        $dados = new DashboardModel();
        $data = $dados->totPedidoEspera();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totPedidosAbertos()
    {
        $dados = new DashboardModel();
        $data = $dados->totPedidosAbertos();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function listaClienteQt($args)
    {
        $vTipoCliente = $args['tipo'];

        if ($vTipoCliente === "undefined") {
            $vTipoCliente = "NA";
        }

        $vTipoCliente = strtoupper(trim($vTipoCliente));

        $dados = new DashboardModel();
        $data = $dados->totClientes($vTipoCliente);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totContratosAtivos()
    {
        $dados = new DashboardModel();
        $data = $dados->totContratosAtivos();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function totOSAbertas()
    {
        $dados = new DashboardModel();
        $data = $dados->totOSAbertas();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    function CorSistema()
    {
        $dados = new DashboardModel();
        $data = $dados->CorSistema();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
