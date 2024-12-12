<?php
$router->get('/funcionarios/listar', 'FuncionarioController@index');
$router->get('/funcionarios/listar/partial/{qt}', 'FuncionarioController@partial');
$router->get('/funcionarios/view/{id}', 'FuncionarioController@view');
$router->get('/funcionarios/delete/{id}', 'FuncionarioController@delete');
$router->post('/funcionarios/addaction', 'FuncionarioController@addAction');
$router->post('/funcionarios/editaction', 'FuncionarioController@editAction');
$router->post('/funcionarios/search', 'FuncionarioController@pesquisar');