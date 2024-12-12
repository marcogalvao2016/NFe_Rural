<?php
$router->get('/fluxocaixa/listar', 'FluxoCaixaController@index');
$router->get('/fluxocaixa/view/{id}', 'FluxoCaixaController@view');
$router->get('/fluxocaixa/delete/{id}', 'FluxoCaixaController@delete');
$router->get('/fluxocaixa/listar/partial/{qt}', 'FluxoCaixaController@partial');
$router->get('/fluxocaixa/search/{texto}', 'FluxoCaixaController@pesquisar');
$router->get('/fluxocaixa/totalcaixa', 'FluxoCaixaController@contaTotalCaixa');
$router->post('/fluxocaixa/addaction', 'FluxoCaixaController@addAction');
$router->post('/fluxocaixa/editaction', 'FluxoCaixaController@editAction');