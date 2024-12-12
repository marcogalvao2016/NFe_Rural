<?php
$router->get('/prodpesquisa/listar', 'ProdutoCadastroController@index');
$router->get('/prodpesquisa/listar/partial/{qt}', 'ProdutoCadastroController@partial');
$router->get('/prodpesquisa/view/{id}', 'ProdutoCadastroController@view');
$router->get('/prodpesquisa/delete/{id}', 'ProdutoCadastroController@delete');
$router->post('/prodpesquisa/addaction', 'ProdutoCadastroController@addAction');
$router->post('/prodpesquisa/editaction', 'ProdutoCadastroController@editAction');
$router->post('/prodpesquisa/search', 'ProdutoCadastroController@pesquisar');
$router->get('/prodpesquisa/search/ean/{ean}', 'ProdutoCadastroController@pesquisarEAN');
$router->get('/prodpesquisa/search/get/{texto}', 'ProdutoCadastroController@pesquisarGet');