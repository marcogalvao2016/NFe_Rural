<?php
$router->get('/medidas/listar', 'MedidaController@index');
$router->get('/medidas/listar/partial/{qt}', 'MedidaController@partial');
$router->get('/medidas/view/{id}', 'MedidaController@view');
$router->get('/medidas/delete/{id}', 'MedidaController@delete');
$router->post('/medidas/addaction', 'MedidaController@addAction');
$router->post('/medidas/editaction', 'MedidaController@editAction');
$router->get('/medidas/search/{texto}', 'MedidaController@pesquisar');