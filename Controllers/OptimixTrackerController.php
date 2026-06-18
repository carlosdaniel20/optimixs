<?php

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OptimixTrackerController
{
    public function index(Request $request, Response $response): Response
    {
        $html = '<!DOCTYPE html>
<html>
<head>
    <title>Optimix Risk - Sistema de Gestión de riesgo por Geo-Análisis</title>
    <meta http-equiv="Content-Security-Policy" content="frame-src *;">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Arial, sans-serif; }
        .tracker-header {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .logo { display: flex; align-items: center; gap: 12px; }
        .logo i { font-size: 28px; color: #3b82f6; }
        .logo h1 { font-size: 20px; font-weight: 700; }
        .logo span { font-size: 12px; color: #94a3b8; }
        .back-btn {
            background: rgba(255,255,255,0.1);
            padding: 8px 20px;
            border-radius: 8px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .back-btn:hover { background: rgba(255,255,255,0.2); }
        .tracker-container { height: calc(100vh - 65px); width: 100%; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="tracker-header">
        <div class="logo">
            <i class="fas fa-satellite-dish"></i>
            <div>
                <h1>Optimix Risk</h1>
                <span>Sistema de Gestión de riesgo por Geo-Análisis</span>
            </div>
        </div>
        <a href="/admin/dashboard" class="back-btn">
            <i class="fas fa-arrow-left"></i> Volver a Optimix Risk
        </a>
    </div>
    <div class="tracker-container">
        <iframe src="http://181.198.203.75:8082?token=RzBFAiEAkJV3IVWtTE0aAAV0rshWDJJbuqLPt8ezLWZ-eJyPzLwCIAmnjxVydyUgmwD2k5n8pshZleNzNPmytvLNhQvrLCEReyJpIjo0MDkzMDMwMTg5MjczNzk2NjM5LCJ1IjoxLCJlIjoiMjAzMS0xMS0zMFQwNTowMDowMC4wMDArMDA6MDAifQ"></iframe>
    </div>
</body>
</html>';
        
        $response->getBody()->write($html);
        return $response;
    }
} 
