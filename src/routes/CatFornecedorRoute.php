<?php
$router->get('/catfornecedor/listar', 'CatFornecedorController@index');
$router->get('/catfornecedor/listar/partial/{qt}', 'CatFornecedorController@partial');
$router->get('/catfornecedor/view/{id}', 'CatFornecedorController@view');
$router->get('/catfornecedor/delete/{id}', 'CatFornecedorController@delete');
$router->post('/catfornecedor/addaction', 'CatFornecedorController@addAction');
$router->post('/catfornecedor/editaction', 'CatFornecedorController@editAction');
$router->get('/catfornecedor/search/{texto}', 'CatFornecedorController@pesquisar');