<?php
$router->get('/catcliente/listar', 'CatClienteController@index');
$router->get('/catcliente/listar/partial/{qt}', 'CatClienteController@partial');
$router->get('/catcliente/view/{id}', 'CatClienteController@view');
$router->get('/catcliente/delete/{id}', 'CatClienteController@delete');
$router->post('/catcliente/addaction', 'CatClienteController@addAction');
$router->post('/catcliente/editaction', 'CatClienteController@editAction');
$router->post('/catcliente/search', 'CatClienteController@pesquisar');