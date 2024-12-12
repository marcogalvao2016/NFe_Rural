<?php
$router->get('/ncms/listar', 'NCMController@index');
$router->get('/ncms/listar/partial/{qt}', 'NCMController@partial');
$router->get('/ncms/view/{id}', 'NCMController@view');
$router->get('/ncms/delete/{id}', 'NCMController@delete');
$router->post('/ncms/addaction', 'NCMController@addAction');
$router->post('/ncms/editaction', 'NCMController@editAction');
$router->get('/ncms/search/{texto}', 'NCMController@pesquisar');