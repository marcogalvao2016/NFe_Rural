<?php
$router->get('/tipohospitalar/listar', 'TipoHospitalarController@index');
$router->get('/tipohospitalar/listar/partial/{qt}', 'TipoHospitalarController@partial');
$router->get('/tipohospitalar/view/{id}', 'TipoHospitalarController@view');
$router->get('/tipohospitalar/delete/{id}', 'TipoHospitalarController@delete');
$router->post('/tipohospitalar/addaction', 'TipoHospitalarController@addAction');
$router->post('/tipohospitalar/editaction', 'TipoHospitalarController@editAction');
$router->get('/tipohospitalar/search/{texto}', 'TipoHospitalarController@pesquisar');