<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class Warehouse3DController
{
    public function index(Request $request, Response $response): Response
    {
        // Renderizar la vista que contiene el visualizador 3D
        ob_start();
        require __DIR__ . '/../Views/warehouse3d/index.php';
        $content = ob_get_clean();
        ob_start();
        require __DIR__ . '/../Views/layout.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }
}
