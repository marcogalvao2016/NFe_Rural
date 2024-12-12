<?php
$router->get('/usuarios/listar', 'UsuarioController@index');
$router->get('/usuarios/listar/partial/{qt}', 'UsuarioController@partial');
$router->post('/usuarios/addaction', 'UsuarioController@addAction');
$router->get('/usuarios/view/{id}', 'UsuarioController@view');
$router->post('/usuarios/editaction', 'UsuarioController@editAction');
$router->get('/usuarios/delete/{id}', 'UsuarioController@delete');
$router->post('/usuarios/login', 'UsuarioController@login');
$router->post('/usuarios/login/mail', 'UsuarioController@loginMail');
$router->post('/usuarios/loginclient', 'UsuarioController@loginClient');
$router->get('/usuarios/search/{texto}', 'UsuarioController@pesquisar');
$router->post('/usuarios/update/pass', 'UsuarioController@updatePasswordUsuario');
$router->post('/usuarios/update/pass/cli', 'UsuarioController@updatePasswordCliente');
$router->post('/usuarios/update/foto/cli', 'UsuarioController@updateFotoCliente');