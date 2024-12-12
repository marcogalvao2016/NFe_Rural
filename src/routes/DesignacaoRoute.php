<?php
$router->get('/designacoes/listar/{situacao}', 'DesignacaoController@index');
$router->get('/designacoes/listar/partial/{qt}', 'DesignacaoController@partial');
$router->get('/designacoes/view/{id}', 'DesignacaoController@view');
$router->get('/designacoes/delete/{id}', 'DesignacaoController@delete');
$router->post('/designacoes/addaction', 'DesignacaoController@addAction');
$router->post('/designacoes/editaction', 'DesignacaoController@editAction');
$router->post('/designacoes/search', 'DesignacaoController@pesquisar');
$router->get('/designacoes/qt_designacao/{id}', 'DesignacaoController@qtDesignacao');