<?php
$router->get('/email', 'EmailController@index');
$router->post('/email/enviar', 'EmailController@enviar');
$router->post('/email/enviarsemanexo', 'EmailController@enviarSemAnexo');