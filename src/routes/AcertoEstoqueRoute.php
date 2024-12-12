<?php
$router->get('/acertoestoque/listar', 'AcertoEstoqueController@index');
$router->post('/acertoestoque/search', 'AcertoEstoqueController@pesquisar');
$router->get('/acertoestoque/delete/{id}', 'AcertoEstoqueController@delete');
$router->post('/acertoestoque/addaction', 'AcertoEstoqueController@addAction');
