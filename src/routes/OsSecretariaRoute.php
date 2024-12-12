<?php
$router->get('/ossecretarias/listar', 'OsSecretariaController@index');
$router->get('/ossecretarias/listar/partial/{qt}', 'OsSecretariaController@partial');
$router->get('/ossecretarias/view/{id}', 'OsSecretariaController@view');
$router->post('/ossecretarias/delete', 'OsSecretariaController@delete');
$router->post('/ossecretarias/addaction', 'OsSecretariaController@addAction');
$router->post('/ossecretarias/editaction', 'OsSecretariaController@editAction');
$router->post('/ossecretarias/search', 'OsSecretariaController@pesquisar');
$router->get('/ossecretarias/search/contrato/{texto}', 'OsSecretariaController@pesquisarContrato');
$router->get('/ossecretarias/search/numos/{texto}', 'OsSecretariaController@pesquisarNumOS');

$router->get('/ossecretarias/itens/view/{id}', 'OsSecretariaController@viewItensOS');
$router->get('/ossecretarias/finalizar/{id}', 'OsSecretariaController@finalizaOS');
$router->get('/ossecretarias/enviada/{id}/{status}', 'OsSecretariaController@enviadaOS');
$router->get('/ossecretarias/listar/paginacao/{pagina}/{limite}', 'OsSecretariaController@listarPorPaginacao');

$router->post('/ossecretarias/print', 'OsSecretariaController@printOS');
