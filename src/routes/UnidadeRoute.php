<?php
$router->get('/unidades/listar', 'UnidadeController@index');
$router->get('/unidades/listar/partial/{qt}', 'UnidadeController@partial');
$router->get('/unidades/view/{id}', 'UnidadeController@view');
$router->get('/unidades/delete/{id}', 'UnidadeController@delete');
$router->post('/unidades/addaction', 'UnidadeController@addAction');
$router->post('/unidades/editaction', 'UnidadeController@editAction');
$router->get('/unidades/search/{texto}', 'UnidadeController@pesquisar');