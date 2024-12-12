<?php
$router->get('/formapagto/listar', 'FormaPagtoController@index');
$router->get('/formapagto/listar/partial/{qt}', 'FormaPagtoController@partial');
$router->post('/formapagto/addaction', 'FormaPagtoController@addAction');
$router->get('/formapagto/view/{id}', 'FormaPagtoController@view');
$router->post('/formapagto/editaction', 'FormaPagtoController@editAction');
$router->get('/formapagto/delete', 'FormaPagtoController@delete');
$router->get('/formapagto/search/{texto}', 'FormaPagtoController@pesquisar');