<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RiskController
{
    /**
     * Muestra la página de clasificaciones de riesgo
     */
    public function clasificacion(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/risk/clasificacion.php';
        $content = ob_get_clean();
        return $this->renderWithLayout($response, $content);
    }

    /**
     * Muestra la matriz de riesgos
     */
    public function matriz(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/risk/matriz.php';
        $content = ob_get_clean();
        return $this->renderWithLayout($response, $content);
    }

    /**
     * Muestra la evaluación detallada de riesgos
     */
    public function evaluacion(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/risk/evaluacion.php';
        $content = ob_get_clean();
        return $this->renderWithLayout($response, $content);
    }

    /**
     * Muestra la matriz de impactos (editable)
     */
    public function impactos(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/risk/impactos.php';
        $content = ob_get_clean();
        return $this->renderWithLayout($response, $content);
    }

    /**
     * Muestra los criterios de probabilidad y la matriz visual
     */
    public function criterios(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/risk/criterios.php';
        $content = ob_get_clean();
        return $this->renderWithLayout($response, $content);
    }

    /**
     * Muestra el reporte final de 8 etapas
     */
    public function reporte(Request $request, Response $response): Response
    {
        ob_start();
        require __DIR__ . '/../Views/risk/reporte.php';
        $content = ob_get_clean();
        return $this->renderWithLayout($response, $content);
    }

    /**
     * Renderiza el contenido dentro del layout principal de Pinakes
     */
    private function renderWithLayout(Response $response, string $content): Response
    {
        // La variable $content será inyectada en el layout
        ob_start();
        require __DIR__ . '/../Views/layout.php';
        $html = ob_get_clean();
        $response->getBody()->write($html);
        return $response;
    }

public function organizacion(Request $request, Response $response): Response
{
    ob_start();
    require __DIR__ . '/../Views/risk/organizacion.php';
    $content = ob_get_clean();
    return $this->renderWithLayout($response, $content);
}


public function checklist(Request $request, Response $response): Response
{
    ob_start();
    require __DIR__ . '/../Views/risk/checklist.php';
    $content = ob_get_clean();
    return $this->renderWithLayout($response, $content);
}

public function checklistSimple(Request $request, Response $response): Response
{
    ob_start();
    require __DIR__ . '/../Views/risk/checklist-simple.php';
    $content = ob_get_clean();
    return $this->renderWithLayout($response, $content);
}

// En app/Controllers/RiskController.php
public function dashboard(Request $request, Response $response): Response
{
    ob_start();
    require __DIR__ . '/../Views/risk/dashboard.php';
    $content = ob_get_clean();
    return $this->renderWithLayout($response, $content);
}

public function reportesDashboard(Request $request, Response $response): Response
{
    ob_start();
    require __DIR__ . '/../Views/risk/reportes-dashboard.php';
    $content = ob_get_clean();
    return $this->renderWithLayout($response, $content);
}

}
