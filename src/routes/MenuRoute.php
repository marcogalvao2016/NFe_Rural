<?php
$router->get('/menus/listar', 'MenuController@index');
$router->get('/menus/listar/partial/{qt}', 'MenuController@partial');
$router->get('/menus/view/{id}', 'MenuController@view');
$router->get('/menus/delete/{id}', 'MenuController@delete');
$router->post('/menus/addaction', 'MenuController@addAction');
$router->post('/menus/editaction', 'MenuController@editAction');
$router->get('/menus/search/{texto}', 'MenuController@pesquisar');