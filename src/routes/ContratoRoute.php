<?php
$router->get('/contratos/listar', 'ContratoController@index');
$router->get('/contratos/listar/partial/{qt}', 'ContratoController@partial');
$router->get('/contratos/view/{id}', 'ContratoController@view');
$router->get('/contratos/delete/{id}', 'ContratoController@delete');
$router->post('/contratos/addaction', 'ContratoController@addAction');
$router->post('/contratos/editaction', 'ContratoController@editAction');

$router->post('/contratos/search', 'ContratoController@pesquisar');
$router->get('/contratos/search/contrato/{texto}', 'ContratoController@pesquisarContrato');
$router->get('/contratos/search/cnpj/{texto}', 'ContratoController@pesquisarCNPJ');

$router->get('/contratos/itens/view/{id}', 'ContratoController@viewItensContrato');
$router->get('/contratos/itens/view/pregao/{id}', 'ContratoController@viewItensPregao');
$router->get('/contratos/listar/ativos', 'ContratoController@contratosAtivos');
$router->get('/contratos/listar/imp', 'ContratoController@contratosImp');
$router->get('/contratos/item/{id}', 'ContratoController@viewItenmContrato');

$router->post('/contratos/updateestoque', 'ContratoController@AtualizaEstoque');
$router->get('/contratos/saldoatual/{id}/{lote}', 'ContratoController@saldoAtualItem');

$router->post('/contratos/itens/print', 'ContratoController@printItensContrato');
$router->post('/contratos/print', 'ContratoController@printContrato');
$router->post('/contratos/upadteitem', 'ContratoController@updateItem');