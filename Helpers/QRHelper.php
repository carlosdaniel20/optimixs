<?php

namespace App\Helpers;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QRHelper
{
    /**
     * Genera un código QR para un reporte
     */
    public static function generateRiskReportQR($reportId, $reportData = [])
    {
        try {
            // Construir la URL pública del reporte
            $baseUrl = self::getBaseUrl();
            $publicUrl = $baseUrl . '/risk/reporte/ver/' . $reportId;
            
            // Generar QR con la URL
            $qrCode = new QrCode(
                data: $publicUrl,
                encoding: new Encoding('UTF-8'),
                errorCorrectionLevel: ErrorCorrectionLevel::Medium,
                size: 250,
                margin: 10,
                foregroundColor: new Color(79, 70, 229),
                backgroundColor: new Color(255, 255, 255)
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            
            // Guardar archivo en disco
            $storagePath = '/var/www/optimixs/storage/qrcodes/';
            if (!is_dir($storagePath)) {
                mkdir($storagePath, 0775, true);
            }
            $filename = 'risk_report_' . $reportId . '.png';
            $filepath = $storagePath . $filename;
            $result->saveToFile($filepath);
            
            // Devolver base64 para guardar en BD
            $base64 = 'data:image/png;base64,' . base64_encode($result->getString());

            return $base64;
            
        } catch (\Throwable $e) {
            error_log("QRHelper error: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Obtiene la URL base del sistema
     */
    private static function getBaseUrl()
    {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        
        $scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
        $basePath = '';
        
        if (strpos($scriptName, '/public/index.php') !== false) {
            $basePath = str_replace('/public/index.php', '', $scriptName);
        } elseif (strpos($scriptName, '/index.php') !== false) {
            $basePath = str_replace('/index.php', '', $scriptName);
        }
        
        return $protocol . '://' . $host . $basePath;
    }
}
