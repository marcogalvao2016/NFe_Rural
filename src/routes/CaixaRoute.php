<?php
$router->get('/caixas/listar', 'CaixaController@index');
$router->get('/caixas/listar/partial/{qt}', 'CaixaController@partial');
$router->post('/caixas/addaction', 'CaixaController@addAction');
$router->get('/caixas/view/{id}', 'CaixaController@view');
$router->post('/caixas/editaction', 'CaixaController@editAction');
$router->get('/caixas/delete', 'CaixaController@delete');
$router->get('/caixas/search/{texto}', 'CaixaController@search');
$router->get('/caixas/listar/caixaaberto', 'CaixaController@caixaAberto');