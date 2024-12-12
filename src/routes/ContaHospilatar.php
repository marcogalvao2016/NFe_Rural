<?php
$router->get('/contahospitalar/listar', 'ContaHospilatarController@index');
$router->get('/contahospitalar/listar/partial/{qt}', 'ContaHospilatarController@partial');
$router->get('/contahospitalar/view/{id}', 'ContaHospilatarController@view');
$router->get('/contahospitalar/delete/{id}', 'ContaHospilatarController@delete');
$router->post('/contahospitalar/addaction', 'ContaHospilatarController@addAction');
$router->post('/contahospitalar/editaction', 'ContaHospilatarController@editAction');
$router->post('/contahospitalar/search', 'ContaHospilatarController@pesquisar');
$router->get('/contahospitalar/view/itens/{id}', 'ContaHospilatarController@viewItensProntuario');
$router->get('/contahospitalar/view/itens/group/{id}', 'ContaHospilatarController@viewItensGroupProntuario');