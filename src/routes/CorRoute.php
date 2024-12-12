<?php
$router->get('/cores/listar', 'CorController@index');
$router->get('/cores/listar/partial/{qt}', 'CorController@partial');
$router->get('/cores/view/{id}', 'CorController@view');
$router->get('/cores/delete/{id}', 'CorController@delete');
$router->post('/cores/addaction', 'CorController@addAction');
$router->post('/cores/editaction', 'CorController@editAction');
$router->get('/cores/search/{texto}', 'CorController@pesquisar');
