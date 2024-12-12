<?php
$router->get('/boletoseuro/listar', 'BoletoEuroController@index');
$router->get('/boletoseuro/listar/partial/{qt}', 'BoletoEuroController@partial');
$router->post('/boletoseuro/listar/search', 'BoletoEuroController@pesquisar');
$router->get('/boletoseuro/listar/search/cnpj/{cnpj}', 'BoletoEuroController@pesquisarCNPJ');
$router->get('/boletoseuro/view/{id}', 'BoletoEuroController@view');
$router->get('/boletoseuro/delete/{id}', 'BoletoEuroController@delete');
$router->post('/boletoseuro/editaction', 'BoletoEuroController@editAction');
$router->post('/boletoseuro/cancelboleto', 'BoletoEuroController@cancelarBoleto');

$router->post('/boletoseuro/generate/period', 'BoletoEuroController@gerarBoletoPeriodo');
$router->post('/boletoseuro/create/period', 'BoletoEuroController@criarBoletoPeriodo');
$router->post('/boletoseuro/generate/select', 'BoletoEuroController@gerarBoletoSelect');

$router->post('/boletoseuro/generate/notification', 'BoletoEuroController@listarNotification');
$router->post('/boletoseuro/generate/notification/send/{tipo}', 'BoletoEuroController@sendNotification');

$router->post('/boletoseuro/pulse/update', 'BoletoEuroController@pulseUpdate');