<?php
$router->get('/mapas/listar', 'MapaController@index');
$router->get('/mapas/listargeral', 'MapaController@listaGeral');
$router->get('/mapas/listar/partial/{qt}', 'MapaController@partial');
$router->get('/mapas/view/{id}', 'MapaController@view');
$router->get('/mapas/delete/{id}', 'MapaController@delete');
$router->post('/mapas/addaction', 'MapaController@addAction');
$router->post('/mapas/editaction', 'MapaController@editAction');
$router->get('/mapas/search/{texto}', 'MapaController@pesquisar');