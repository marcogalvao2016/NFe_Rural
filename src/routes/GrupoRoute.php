<?php
$router->get('/grupos/listar', 'GrupoController@index');
$router->get('/grupos/listar/partial/{qt}', 'GrupoController@partial');
$router->get('/grupos/add', 'GrupoController@add');
$router->post('/grupos/addaction', 'GrupoController@addAction');
$router->get('/grupos/view/{id}', 'GrupoController@view');
$router->post('/grupos/editaction', 'GrupoController@editAction');
$router->post('/grupos/delete', 'GrupoController@delete');
$router->get('/grupos/search/{texto}', 'GrupoController@search');