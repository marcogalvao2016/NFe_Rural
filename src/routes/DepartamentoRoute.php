<?php
$router->get('/departamentos/listar', 'DepartamentoController@index');
$router->get('/departamentos/listar/partial/{qt}', 'DepartamentoController@partial');
$router->get('/departamentos/view/{id}', 'DepartamentoController@view');
$router->get('/departamentos/delete/{id}', 'DepartamentoController@delete');
$router->post('/departamentos/addaction', 'DepartamentoController@addAction');
$router->post('/departamentos/editaction', 'DepartamentoController@editAction');
$router->get('/departamentos/search/{texto}', 'DepartamentoController@pesquisar');
