<?php
$router->get('/category/listar', 'CategoryEcommerceController@index');
$router->get('/category/listar/partial/{qt}', 'CategoryEcommerceController@partial');
$router->post('/category/addaction', 'CategoryEcommerceController@addAction');
$router->get('/category/view/{id}', 'CategoryEcommerceController@view');
$router->post('/category/editaction', 'CategoryEcommerceController@editAction');
$router->get('/category/delete', 'CategoryEcommerceController@delete');
$router->post('/category/search', 'CategoryEcommerceController@pesquisar');
$router->get('/category/listar/subcategories', 'CategoryEcommerceController@subCategories');