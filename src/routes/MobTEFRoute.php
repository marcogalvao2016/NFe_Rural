<?php
$router->post('/mobtef/createpay', 'MobTEFController@createPay');
$router->get('/mobtef/takereturn/{id}', 'MobTEFController@takeReturn');
$router->get('/mobtef/takeconsreturn/{id}', 'MobTEFController@takeConsultaReturn');