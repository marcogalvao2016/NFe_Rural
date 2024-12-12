<?php
$router->get('/contasreceber/listar', 'ContasReceberController@index');
$router->get('/contasreceber/view/{id}', 'ContasReceberController@view');
$router->get('/contasreceber/delete/{id}', 'ContasReceberController@delete');
$router->get('/contasreceber/listar/partial/{qt}', 'ContasReceberController@partial');
$router->get('/contasreceber/search/{texto}', 'ContasReceberController@pesquisar');
$router->post('/contasreceber/addaction', 'ContasReceberController@addAction');
$router->post('/contasreceber/editaction', 'ContasReceberController@editAction');
$router->post('/contasreceber/quitar/{id}', 'ContasReceberController@quitar');