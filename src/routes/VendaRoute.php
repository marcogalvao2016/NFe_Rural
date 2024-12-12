<?php
$router->get('/vendas/listar', 'VendaController@index');
$router->get('/vendas/delete/{id}', 'VendaController@delete');
$router->get('/vendas/view/{id}', 'VendaController@view');
$router->post('/vendas/search', 'VendaController@pesquisaVendasTipo');
$router->post('/vendas/search/vendedor', 'VendaController@pesquisaVendasVendedorTipo');
$router->post('/vendas/search/cliente', 'VendaController@pesquisaVendasClienteTipo');
$router->get('/vendas/produtos/{id}', 'VendaController@vendaProdutos');
$router->get('/vendas/cliente/{id}/{tipo}', 'VendaController@vendasClienteTipo');
$router->get('/vendas/vendedor/{id}/{tipo}', 'VendaController@vendasVendedorTipo');
$router->get('/vendas/tipo/{tipo}', 'VendaController@vendasTipo');
$router->get('/vendas/mesatual', 'VendaController@vendasMes');
$router->get('/vendas/totalpedidos', 'VendaController@totalVendas');
$router->get('/vendas/delivery/{id}', 'VendaController@vendasDelivery');

$router->post('/vendas/addaction', 'VendaController@addAction');
$router->post('/vendas/pedidocliente', 'VendaController@inserirPedidoCliente');
$router->post('/vendas/pedidoclientefinal', 'VendaController@inserirPedidoClienteFinal');
$router->post('/vendas/updateanalise/{id}', 'VendaController@alteraAnalise');
$router->get('/vendas/convertevenda/{id}', 'VendaController@converteVenda');

$router->post('/vendas/app', 'VendaController@inserirApp');
$router->get('/vendas/listar/comandas', 'VendaController@listarComandasAbertas');
$router->get('/vendas/listar/retiradas', 'VendaController@listarRetiradasAbertas');
$router->get('/vendas/comanda/itens/{id}', 'VendaController@listarComandasItens');
$router->post('/vendas/fechaconta/{id}', 'VendaController@fechaConta');

$router->post('/vendas/recebecomanda/{id}', 'VendaController@receberComanda');
$router->post('/vendas/save/comanda', 'VendaController@inserirComanda');

$router->get('/vendas/online/listar', 'VendaController@listarPedidosOnlineAbertos');
$router->get('/vendas/online/itens/{hash}', 'VendaController@listarPedidosOnlineItens');
$router->post('/vendas/save/pedidoonline', 'VendaController@inserirPedidoOnline');

$router->post('/vendas/updatedelivery', 'VendaController@alteraDelivery');

$router->post('/vendas/printvendas', 'VendaController@printVendas');