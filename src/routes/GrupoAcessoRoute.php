<?php
$router->get('/grupoacesso/listar', 'GrupoAcessoController@index');
$router->get('/grupoacesso/listar/partial/{qt}', 'GrupoAcessoController@partial');
$router->get('/grupoacesso/view/{id}', 'GrupoAcessoController@view');
$router->get('/grupoacesso/delete/{id}', 'GrupoAcessoController@delete');
$router->post('/grupoacesso/addaction', 'GrupoAcessoController@addAction');
$router->post('/grupoacesso/editaction', 'GrupoAcessoController@editAction');
$router->get('/grupoacesso/search/{texto}', 'GrupoAcessoController@pesquisar');