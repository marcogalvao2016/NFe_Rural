<?php
$router->get('/marcas/listar', 'MarcaController@index');
$router->get('/marcas/listar/partial/{qt}', 'MarcaController@partial');
$router->get('/marcas/view/{id}', 'MarcaController@view');
$router->get('/marcas/delete/{id}', 'MarcaController@delete');
$router->post('/marcas/addaction', 'MarcaController@addAction');
$router->post('/marcas/editaction', 'MarcaController@editAction');
$router->get('/marcas/search/{texto}', 'MarcaController@pesquisar');