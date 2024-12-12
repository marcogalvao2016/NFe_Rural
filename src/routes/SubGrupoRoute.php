<?php
$router->get('/subgrupos/listar', 'SubGrupoController@index');
$router->get('/subgrupos/listar/grupo/{id}', 'SubGrupoController@listarPorGrupo');
$router->get('/subgrupos/listar/partial/{qt}', 'SubGrupoController@partial');
$router->get('/subgrupos/view/{id}', 'SubGrupoController@view');
$router->get('/subgrupos/delete/{id}', 'SubGrupoController@delete');
$router->post('/subgrupos/addaction', 'SubGrupoController@addAction');
$router->post('/subgrupos/editaction', 'SubGrupoController@editAction');
$router->get('/subgrupos/search/{texto}', 'SubGrupoController@pesquisar');