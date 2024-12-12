<?php
$router->get('/contaspagar/listar', 'ContasPagarController@index');
$router->get('/contaspagar/view/{id}', 'ContasPagarController@view');
$router->get('/contaspagar/delete/{id}', 'ContasPagarController@delete');
$router->get('/contaspagar/listar/partial/{qt}', 'ContasPagarController@partial');
$router->get('/contaspagar/search/{texto}', 'ContasPagarController@pesquisar');
$router->post('/contaspagar/addaction', 'ContasPagarController@addAction');
$router->post('/contaspagar/editaction', 'ContasPagarController@editAction');
$router->post('/contaspagar/quitar/{id}', 'ContasPagarController@quitar');