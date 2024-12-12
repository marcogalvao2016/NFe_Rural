<?php
$router->get('/clienteseuro/listar/{tipo}', 'ClienteEuroController@index');
$router->get('/clienteseuro/listar/partial/{qt}', 'ClienteEuroController@partial');
$router->post('/clienteseuro/search', 'ClienteEuroController@pesquisar');
$router->get('/clienteseuro/search/cnpj/{cnpj}/{tipo}', 'ClienteEuroController@pesquisarCNPJ');
$router->get('/clienteseuro/view/{id}', 'ClienteEuroController@view');
$router->get('/clienteseuro/delete/{id}', 'ClienteEuroController@delete');
$router->post('/clienteseuro/addaction', 'ClienteEuroController@addAction');
$router->post('/clienteseuro/editaction', 'ClienteEuroController@editAction');