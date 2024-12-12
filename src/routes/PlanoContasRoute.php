<?php
$router->get('/planocontas/listar', 'PlanoContasController@index');
$router->get('/planocontas/listar/partial/{qt}', 'PlanoContasController@partial');
$router->post('/planocontas/addaction', 'PlanoContasController@addAction');
$router->get('/planocontas/view/{id}', 'PlanoContasController@view');
$router->post('/planocontas/editaction', 'PlanoContasController@editAction');
$router->get('/planocontas/delete', 'PlanoContasController@delete');
$router->get('/planocontas/search/{texto}', 'PlanoContasController@pesquisar');