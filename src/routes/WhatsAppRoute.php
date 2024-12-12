<?php
$router->get('/whatsapp', 'WhatsAppController@index');
$router->get('/whatsapp/recusa/{texto}', 'WhatsAppController@recusa');
$router->post('/whatsapp/text', 'WhatsAppController@enviarMensagem');
$router->post('/whatsapp/textmany', 'WhatsAppController@enviarVariasMensagens');
$router->post('/whatsapp/doctext', 'WhatsAppController@enviarDocumentText');