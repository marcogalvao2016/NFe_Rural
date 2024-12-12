<?php
$router->get('/nfe/emissao/{idvenda}', 'NFeController@emsisao');
$router->get('/nfe/lastnfe', 'NFeController@getLastNFe');