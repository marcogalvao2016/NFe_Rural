<?php
$router->get('/bordas/listar', 'BordaController@index');
$router->get('/bordas/view/{id}', 'BordaController@view');