<?php
$router->get('/videos/listar', 'VideoController@index');
$router->get('/videos/listar/partial/{qt}', 'VideoController@partial');
$router->get('/videos/view/{id}', 'VideoController@view');
$router->get('/videos/delete/{id}', 'VideoController@delete');
$router->post('/videos/addaction', 'VideoController@addAction');
$router->post('/videos/editaction', 'VideoController@editAction');
$router->get('/videos/search/{texto}', 'VideoController@pesquisar');