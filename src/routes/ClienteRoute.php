<?php
$router->get('/clientes/listar', 'ClienteController@index');
$router->get('/clientes/listar/partial/{qt}', 'ClienteController@partial');
$router->get('/clientes/listar/vendedor/{idvendedor}', 'ClienteController@listarVendedor');
$router->get('/clientes/view/{id}', 'ClienteController@view');
$router->get('/clientes/delete/{id}', 'ClienteController@delete');
$router->post('/clientes/addaction', 'ClienteController@addAction');
$router->post('/clientes/editaction', 'ClienteController@editAction');
$router->post('/clientes/search', 'ClienteController@pesquisar');
$router->get('/clientes/search/vendedor/{texto}/{idvendedor}', 'ClienteController@pesquisarComVendedor');
$router->get('/produtos/totalprodutos', 'ProdutoController@contaTotaClientes');

$router->get('/clientes/listarmobile', 'ClienteController@listarMobile');