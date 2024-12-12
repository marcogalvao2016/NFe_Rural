<?php
$router->get('/catvideos/listar', 'CatVideoController@index');
$router->get('/catvideos/listar/partial/{qt}', 'CatVideoController@partial');
$router->get('/catvideos/view/{id}', 'CatVideoController@view');
$router->get('/catvideos/delete/{id}', 'CatVideoController@delete');
$router->post('/catvideos/addaction', 'CatVideoController@addAction');
$router->post('/catvideos/editaction', 'CatVideoController@editAction');
$router->get('/catvideos/search/{texto}', 'CatVideoController@pesquisar');