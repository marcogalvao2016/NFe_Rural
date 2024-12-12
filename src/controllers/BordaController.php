<?php
namespace src\controllers;

use \core\Controller;
use \src\models\BordaModel;

header('Content-Type: application/json'); // Define o conteúdo como JSON

class BordaController extends Controller
{

    public function index()
    {
        $dados = new BordaModel();
        $data = $dados->listar();

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
    
    public function view($args)
    {
        $id = $args['id'];

        $dados = new BordaModel();
        $data = $dados->view($id);

        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
    
}