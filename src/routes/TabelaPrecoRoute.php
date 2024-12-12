<?php
$router->get('/tabelaprecos/listar', 'TabelaPrecoController@index');
$router->get('/tabelaprecos/view/{id}', 'TabelaPrecoController@view');
$router->get('/tabelaprecos/listar/partial/{qt}', 'TabelaPrecoController@partial');
$router->get('/tabelaprecos/view/{id}', 'TabelaPrecoController@view');
$router->get('/tabelaprecos/delete/{id}', 'TabelaPrecoController@delete');
$router->post('/tabelaprecos/addaction', 'TabelaPrecoController@addAction');
$router->post('/tabelaprecos/editaction', 'TabelaPrecoController@editAction');
$router->get('/tabelaprecos/search/{texto}', 'TabelaPrecoController@pesquisar');
