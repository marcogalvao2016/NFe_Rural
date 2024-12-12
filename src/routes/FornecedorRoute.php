<?php
$router->get('/fornecedores/listar', 'FornecedorController@index');
$router->get('/fornecedores/listar/partial/{qt}', 'FornecedorController@partial');
$router->get('/fornecedores/view/{id}', 'FornecedorController@view');
$router->get('/fornecedores/delete/{id}', 'FornecedorController@delete');
$router->post('/fornecedores/addaction', 'FornecedorController@addAction');
$router->post('/fornecedores/editaction', 'FornecedorController@editAction');
$router->post('/fornecedores/search', 'FornecedorController@pesquisar');

$router->post('/fornecedores/print', 'FornecedorController@printFornecedor');