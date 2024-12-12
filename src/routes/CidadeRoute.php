<?php
$router->get('/cidades/listar', 'CidadeController@index');
$router->get('/cidades/listar/partial/{qt}', 'CidadeController@partial');
$router->get('/cidades/view/{id}', 'CidadeController@view');
$router->get('/cidades/delete/{id}', 'CidadeController@delete');
$router->post('/cidades/addaction', 'CidadeController@addAction');
$router->post('/cidades/editaction', 'CidadeController@editAction');
$router->get('/cidades/search/{texto}', 'CidadeController@pesquisar');