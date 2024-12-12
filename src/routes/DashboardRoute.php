<?php
$router->get('/dashboard/totalclientes', 'DashboardController@totalClientes');
$router->get('/dashboard/totalclientesvendedor/{idvendedor}', 'DashboardController@totalClientesVendedor');
$router->get('/dashboard/totalclientesmes', 'DashboardController@totalClientesMes');
$router->get('/dashboard/totalclientesmesvendedor/{idvendedor}', 'DashboardController@totalClientesMesVendedor');
$router->get('/dashboard/totalvendasmes', 'DashboardController@totalVendasMes');
$router->get('/dashboard/totalvendascliente/{idcliente}', 'DashboardController@totalVendasCliente');
$router->get('/dashboard/totalvendasqtcliente/{idcliente}', 'DashboardController@totalQtVendasCliente');
$router->get('/dashboard/totalvendasmesvendedor/{idvendedor}', 'DashboardController@totalVendasMesVendedor');
$router->get('/dashboard/totalcpmes', 'DashboardController@totalCPMes');
$router->get('/dashboard/totalcrmes', 'DashboardController@totalCRMes');
$router->get('/dashboard/totalcp', 'DashboardController@totalCP');
$router->get('/dashboard/totalcr', 'DashboardController@totalCR');
$router->get('/dashboard/totalproductecommerce', 'DashboardController@contaTotalProductEcommerce');
$router->get('/dashboard/category/createapi', 'DashboardController@createCategoryAPI');
$router->get('/dashboard/product/createapi', 'DashboardController@createProductAPI');

$router->get('/dashboard/totcomandas', 'DashboardController@totComandasAbertas');
$router->get('/dashboard/totbalcaoespera', 'DashboardController@totPedidoEspera');

$router->get('/dashboard/online/totalpedidos', 'DashboardController@totPedidosAbertos');

$router->get('/dashboard/totcliente/{tipo}', 'DashboardController@listaClienteQt');

$router->get('/dashboard/totcontratosabertos', 'DashboardController@totContratosAtivos');
$router->get('/dashboard/totosabertas', 'DashboardController@totOSAbertas');
$router->get('/dashboard/corsistema', 'DashboardController@CorSistema');
