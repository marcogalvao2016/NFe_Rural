<?php
$router->get('/telassistemas/listar', 'TelasSistemaController@index');
$router->get('/telassistemas/listar/partial/{qt}', 'TelasSistemaController@partial');
$router->get('/telassistemas/view/{id}', 'TelasSistemaController@view');
$router->get('/telassistemas/delete/{id}', 'TelasSistemaController@delete');
$router->post('/telassistemas/addaction', 'TelasSistemaController@addAction');
$router->post('/telassistemas/editaction', 'TelasSistemaController@editAction');
$router->get('/telassistemas/search/{texto}', 'TelasSistemaController@pesquisar');