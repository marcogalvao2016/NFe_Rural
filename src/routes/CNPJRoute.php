<?php
$router->get('/consulta/cnpj', 'CNPJController@index');
$router->get('/consulta/cnpj/{cnpj}', 'CNPJController@consultaCNPJ');