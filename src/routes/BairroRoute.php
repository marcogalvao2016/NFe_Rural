<?php
$router->get('/bairros/listar', 'BairroController@index');
$router->get('/bairros/listar/partial/{qt}', 'BairroController@partial');
$router->get('/bairros/view/{id}', 'BairroController@view');
$router->get('/bairros/delete/{id}', 'BairroController@delete');
$router->post('/bairros/addaction', 'BairroController@addAction');
$router->post('/bairros/editaction', 'BairroController@editAction');
$router->get('/bairros/search/{texto}', 'BairroController@pesquisar');