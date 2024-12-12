<?php
$router->get('/gerencianet/listbillets', 'BilletAppController@index');
$router->post('/gerencianet/createbillet', 'BilletAppController@createBillet');
$router->get('/gerencianet/cancelbillet/{id}', 'BilletAppController@cancelBillet');