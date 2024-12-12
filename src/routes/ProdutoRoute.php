<?php
$router->get('/produtos/listar', 'ProdutoController@index');
$router->get('/produtos/listar/tabela/{idtabela}', 'ProdutoController@buscarPorTabela');
$router->get('/produtos/listar/ordemtabela/{idtabela}/{tipo}', 'ProdutoController@ordenarPorTabela');
$router->get('/produtos/listar/grupo/{idgrupo}/{idtabela}', 'ProdutoController@buscarPorGrupo');
$router->get('/produtos/listar/subgrupo/{idsubgrupo}/{idtabela}', 'ProdutoController@buscarPorSubGrupo');
$router->get('/produtos/listar/marca/{idmarca}/{idtabela}', 'ProdutoController@buscarPorMarca');
$router->get('/produtos/listar/grupopizza/{idgrupo}/{idsubgrupo}', 'ProdutoController@buscarPorGrupoPizza');
$router->get('/produtos/listar/partial/{qt}', 'ProdutoController@partial');
$router->get('/produtos/view/{id}/{idtabela}', 'ProdutoController@view');
$router->get('/produtos/delete/{id}', 'ProdutoController@delete');
$router->post('/produtos/addaction/{idtabela}', 'ProdutoController@addAction');
$router->post('/produtos/editaction/{idtabela}', 'ProdutoController@editAction');
$router->post('/produtos/searchitem', 'ProdutoController@pesquisar');
$router->post('/produtos/searchitemcliente', 'ProdutoController@pesquisarPDVCliente');
$router->get('/produtos/searchean/{texto}/{idtabela}', 'ProdutoController@pesquisarEAN');
$router->get('/produtos/searchgrupo/{texto}/{idgrupo}', 'ProdutoController@pesquisarGrupo');
$router->get('/produtos/searchgrupopizza/{texto}/{idgrupo}/{idsubgrupo}', 'ProdutoController@pesquisarGrupoPizza');
$router->get('/produtos/totalprodutos', 'ProdutoController@contaTotalProdutos');
$router->get('/produtos/listar/ordenar/{tipo}/{idtabela}', 'ProdutoController@listarOrdem');

$router->get('/produtos/listar/paginacao/tabela/{idtabela}/{pagina}/{limite}', 'ProdutoController@listarPorPaginacaoTabela');