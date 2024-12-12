<?php
$router->get('/dirigentes/listar', 'DirigenteController@index');
$router->get('/dirigentes/listar/partial/{qt}', 'DirigenteController@partial');
$router->get('/dirigentes/view/{id}', 'DirigenteController@view');
$router->get('/dirigentes/delete/{id}', 'DirigenteController@delete');
$router->post('/dirigentes/addaction', 'DirigenteController@addAction');
$router->post('/dirigentes/editaction', 'DirigenteController@editAction');
$router->get('/dirigentes/search/{texto}', 'DirigenteController@pesquisar');