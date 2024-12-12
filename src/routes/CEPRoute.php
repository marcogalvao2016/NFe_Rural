<?php
$router->get('/consulta/cep', 'CEPController@index');
$router->get('/consulta/cep/{cep}', 'CEPController@consultaCEP');