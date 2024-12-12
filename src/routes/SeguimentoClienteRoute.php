<?php
$router->get('/seguimentos/listar', 'SeguimentoClienteController@index');
$router->get('/seguimentos/listar/partial/{qt}', 'SeguimentoClienteController@partial');
$router->get('/seguimentos/view/{id}', 'SeguimentoClienteController@view');
$router->get('/seguimentos/delete/{id}', 'SeguimentoClienteController@delete');
$router->post('/seguimentos/addaction', 'SeguimentoClienteController@addAction');
$router->post('/seguimentos/editaction', 'SeguimentoClienteController@editAction');
$router->get('/seguimentos/search/{texto}', 'SeguimentoClienteController@pesquisar');