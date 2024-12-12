<?php
$router->get('/cargos/listar', 'CargoController@index');
$router->get('/cargos/listar/partial/{qt}', 'CargoController@partial');
$router->get('/cargos/view/{id}', 'CargoController@view');
$router->get('/cargos/delete/{id}', 'CargoController@delete');
$router->post('/cargos/addaction', 'CargoController@addAction');
$router->post('/cargos/editaction', 'CargoController@editAction');
$router->get('/cargos/search/{texto}', 'CargoController@pesquisar');