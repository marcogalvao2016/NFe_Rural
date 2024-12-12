<?php
$router->get('/empresas/listar', 'EmpresaController@index');
$router->get('/empresas/listar/partial/{qt}', 'EmpresaController@partial');
$router->get('/empresas/view/{id}', 'EmpresaController@view');
$router->get('/empresas/delete/{id}', 'EmpresaController@delete');
$router->post('/empresas/addaction', 'EmpresaController@addAction');
$router->post('/empresas/editaction', 'EmpresaController@editAction');
$router->get('/empresas/search/{texto}', 'EmpresaController@pesquisar');