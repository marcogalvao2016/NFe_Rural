<?php
$router->get('/complementos/listar', 'ComplementoController@index');
$router->get('/complementos/listar/partial/{qt}', 'ComplementoController@partial');
$router->get('/complementos/view/{id}', 'ComplementoController@view');
$router->get('/complementos/delete/{id}', 'ComplementoController@delete');
$router->post('/complementos/addaction', 'ComplementoController@addAction');
$router->post('/complementos/editaction', 'ComplementoController@editAction');
$router->get('/complementos/search/{texto}', 'ComplementoController@pesquisar');