<?php
$router->get('/product/listar', 'ProductEcommerceController@index');
$router->get('/product/listar/partial/{qt}', 'ProductEcommerceController@partial');
$router->post('/product/addaction', 'ProductEcommerceController@addAction');
$router->get('/product/view/{id}', 'ProductEcommerceController@view');
$router->post('/product/editaction', 'ProductEcommerceController@editAction');
$router->get('/product/delete', 'ProductEcommerceController@delete');
$router->post('/product/search', 'ProductEcommerceController@pesquisar');
$router->post('/product/createapi', 'ProductEcommerceController@createAPI');

$router->get('/product/listar/paginacao/{pagina}/{limite}', 'ProductEcommerceController@listarPorPaginacao');