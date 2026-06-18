<?php
declare(strict_types=1);

namespace App\Helpers;

/**
 * RiskAIHelper - Utilidades para el módulo de análisis inteligente de riesgos
 * 
 * Proporciona funciones auxiliares para:
 * - Procesamiento de lenguaje natural básico
 * - Formateo de resultados estilo IA
 * - Generación de respuestas contextuales
 * - Cálculo de métricas de riesgo
 */
class RiskAIHelper
{
    /**
     * Palabras clave para detección de riesgos por categoría
     */
    private static array $riskKeywords = [
        'financiero' => [
            'perdida', 'pérdida', 'quiebra', 'insolvencia', 'liquidez', 'morosidad',
            'deuda', 'credito', 'crédito', 'inversion', 'inversión', 'rentabilidad',
            'flujo', 'caja', 'presupuesto', 'sobrecosto'
        ],
        'operativo' => [
            'proceso', 'procedimiento', 'operacion', 'operación', 'produccion', 'producción',
            'logistica', 'logística', 'cadena', 'suministro', 'proveedor', 'calidad',
            'mantenimiento', 'equipo', 'maquinaria', 'sistema'
        ],
        'normativo' => [
            'legal', 'ley', 'reglamento', 'norma', 'cumplimiento', 'incumplimiento',
            'sancion', 'sanción', 'multa', 'auditoria', 'auditoría', 'regulador',
            'licencia', 'permiso', 'contrato'
        ],
        'reputacional' => [
            'imagen', 'reputacion', 'reputación', 'marca', 'confianza', 'cliente',
            'opinion', 'opinión', 'redes', 'sociales', 'prensa', 'medios',
            'publico', 'público', 'escandalo', 'escándalo'
        ],
        'seguridad' => [
            'seguridad', 'proteccion', 'protección', 'vulnerabilidad', 'amenaza',
            'ataque', 'ciber', 'informatico', 'informático', 'dato', 'informacion',
            'información', 'privacidad', 'acceso', 'control', 'brecha'
        ],
        'ambiental' => [
            'ambiente', 'ambiental', 'ecologico', 'ecológico', 'residuo', 'contaminacion',
            'contaminación', 'clima', 'climatico', 'climático', 'sostenibilidad',
            'recurso', 'natural', 'energia', 'energía', 'huella'
        ]
    ];
    
    /**
     * Matriz de severidad para cálculo de impacto
     */
    private static array $severityMatrix = [
        'Extremo' => ['valor' => 0.95, 'color' => '#EF4444', 'accion' => 'Acción inmediata requerida'],
        'Alto' => ['valor' => 0.75, 'color' => '#F97316', 'accion' => 'Revisión prioritaria'],
        'Moderado' => ['valor' => 0.50, 'color' => '#F59E0B', 'accion' => 'Monitoreo continuo'],
        'Bajo' => ['valor' => 0.25, 'color' => '#10B981', 'accion' => 'Seguimiento periódico']
    ];
    
    /**
     * Analiza el sentimiento del texto (riesgo positivo/negativo)
     */
    public static function analyzeSentiment(string $text): array
    {
        $positiveWords = ['controlado', 'mitigado', 'resuelto', 'efectivo', 'correcto', 'aprobado'];
        $negativeWords = ['crítico', 'urgente', 'grave', 'incumplimiento', 'falla', 'deficiencia', 'riesgo'];
        
        $lowerText = strtolower($text);
        $positiveCount = 0;
        $negativeCount = 0;
        
        foreach ($positiveWords as $word) {
            if (strpos($lowerText, $word) !== false) $positiveCount++;
        }
        foreach ($negativeWords as $word) {
            if (strpos($lowerText, $word) !== false) $negativeCount++;
        }
        
        $total = $positiveCount + $negativeCount;
        if ($total == 0) return ['sentiment' => 'neutral', 'score' => 0.5];
        
        $score = $negativeCount / $total;
        
        if ($score > 0.7) return ['sentiment' => 'very_negative', 'score' => $score];
        if ($score > 0.5) return ['sentiment' => 'negative', 'score' => $score];
        if ($score > 0.3) return ['sentiment' => 'neutral', 'score' => $score];
        return ['sentiment' => 'positive', 'score' => $score];
    }
    
    /**
     * Detecta la categoría de riesgo predominante en el texto
     */
    public static function detectRiskCategory(string $text): array
    {
        $lowerText = strtolower($text);
        $scores = [];
        
        foreach (self::$riskKeywords as $category => $keywords) {
            $score = 0;
            foreach ($keywords as $keyword) {
                if (strpos($lowerText, $keyword) !== false) {
                    $score++;
                }
            }
            if ($score > 0) {
                $scores[$category] = $score;
            }
        }
        
        if (empty($scores)) {
            return ['category' => 'general', 'confidence' => 0.3];
        }
        
        arsort($scores);
        $total = array_sum($scores);
        $topCategory = key($scores);
        $confidence = $scores[$topCategory] / $total;
        
        return [
            'category' => $topCategory,
            'confidence' => round($confidence, 2),
            'all_categories' => $scores
        ];
    }
    
    /**
     * Extrae entidades relevantes del texto (fechas, montos, porcentajes)
     */
    public static function extractEntities(string $text): array
    {
        $entities = [];
        
        // Extraer porcentajes
        preg_match_all('/(\d+(?:\.\d+)?)%/', $text, $percentMatches);
        if (!empty($percentMatches[1])) {
            $entities['percentages'] = array_map('floatval', $percentMatches[1]);
        }
        
        // Extraer montos (números con posibles separadores)
        preg_match_all('/(?:\$|USD|EUR|€)?\s?(\d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})?)\s?(?:dólares|euros|usd|eur)?/i', $text, $amountMatches);
        if (!empty($amountMatches[1])) {
            $entities['amounts'] = $amountMatches[1];
        }
        
        // Extraer fechas
        preg_match_all('/(\d{1,2}[/-]\d{1,2}[/-]\d{2,4})|(\d{4}-\d{2}-\d{2})/', $text, $dateMatches);
        if (!empty($dateMatches[0])) {
            $entities['dates'] = $dateMatches[0];
        }
        
        return $entities;
    }
    
    /**
     * Genera una respuesta estilo IA contextual
     */
    public static function generateAIResponse(string $context, array $analysisData): string
    {
        $responses = [
            'welcome' => [
                'Hola! Soy Optimixs Risk AI. He analizado el reporte y esto es lo que encontré:',
                '🤖 Análisis completado. Basado en el teorema de Bayes, estos son los resultados:',
                '📊 He procesado el reporte con mi motor de inteligencia artificial. Estos son los hallazgos:'
            ],
            'high_risk' => [
                '🚨 ¡ALERTA! El nivel de riesgo es extremadamente alto. Se requiere acción inmediata.',
                '⚠️ Advertencia crítica: La probabilidad de materialización del riesgo es muy elevada.',
                '🔴 Riesgo crítico detectado. Recomiendo revisar los controles urgentemente.'
            ],
            'medium_risk' => [
                '🟡 Se ha detectado un nivel de riesgo moderado que requiere atención.',
                '📈 La probabilidad del riesgo ha aumentado significativamente. Recomiendo monitoreo.',
                '⚡ El riesgo muestra una tendencia al alza. Sugiero revisar los controles existentes.'
            ],
            'low_risk' => [
                '🟢 El nivel de riesgo se encuentra dentro de parámetros aceptables.',
                '✅ Los controles actuales parecen ser efectivos. Mantener el monitoreo.',
                '📊 El riesgo está controlado. Continuar con el seguimiento periódico.'
            ]
        ];
        
        $prior = $analysisData['prior'] ?? 50;
        $posterior = $analysisData['posterior'] ?? 50;
        $variation = $posterior - $prior;
        
        // Seleccionar saludo aleatorio
        $greeting = $responses['welcome'][array_rand($responses['welcome'])];
        
        // Seleccionar respuesta según nivel
        if ($posterior >= 70) {
            $riskMsg = $responses['high_risk'][array_rand($responses['high_risk'])];
        } elseif ($posterior >= 50) {
            $riskMsg = $responses['medium_risk'][array_rand($responses['medium_risk'])];
        } else {
            $riskMsg = $responses['low_risk'][array_rand($responses['low_risk'])];
        }
        
        $variationText = $variation > 0 
            ? "📈 La probabilidad del riesgo ha **aumentado** en {$variation} puntos porcentuales."
            : ($variation < 0 
                ? "📉 La probabilidad del riesgo ha **disminuido** en " . abs($variation) . " puntos porcentuales."
                : "📊 La probabilidad del riesgo se ha **mantenido estable**.");
        
        return "{$greeting}\n\n{$riskMsg}\n\n{$variationText}\n\n📋 **Recomendación:** {$analysisData['recomendacion_principal']}";
    }
    
    /**
     * Calcula el factor de riesgo combinado usando múltiples métricas
     */
    public static function calculateCombinedRisk(array $metrics): array
    {
        $weights = [
            'bayes_probability' => 0.40,
            'sentiment_score' => 0.20,
            'evidence_count' => 0.15,
            'severity_factor' => 0.15,
            'trend_factor' => 0.10
        ];
        
        $score = 0;
        $score += ($metrics['bayes_probability'] ?? 50) / 100 * $weights['bayes_probability'];
        $score += ($metrics['sentiment_score'] ?? 0.5) * $weights['sentiment_score'];
        $score += min(($metrics['evidence_count'] ?? 0) / 10, 1) * $weights['evidence_count'];
        $score += ($metrics['severity_factor'] ?? 0.5) * $weights['severity_factor'];
        $score += ($metrics['trend_factor'] ?? 0) * $weights['trend_factor'];
        
        $finalScore = round($score * 100, 2);
        
        if ($finalScore >= 70) {
            $level = 'Extremo';
        } elseif ($finalScore >= 50) {
            $level = 'Alto';
        } elseif ($finalScore >= 30) {
            $level = 'Moderado';
        } else {
            $level = 'Bajo';
        }
        
        return [
            'score' => $finalScore,
            'level' => $level,
            'details' => $metrics
        ];
    }
    
    /**
     * Formatea un número en formato legible para IA
     */
    public static function formatForAI(float $number, string $unit = '%'): string
    {
        $rounded = round($number, 2);
        
        if ($rounded >= 70) {
            return "⚠️ **{$rounded}{$unit}** (Nivel Crítico)";
        } elseif ($rounded >= 50) {
            return "🔶 **{$rounded}{$unit}** (Nivel Alto)";
        } elseif ($rounded >= 30) {
            return "🟡 **{$rounded}{$unit}** (Nivel Moderado)";
        } else {
            return "🟢 **{$rounded}{$unit}** (Nivel Bajo)";
        }
    }
    
    /**
     * Genera una alerta temprana basada en el análisis
     */
    public static function generateEarlyWarning(array $analysisData): array
    {
        $posterior = $analysisData['posterior'] ?? 50;
        $prior = $analysisData['prior'] ?? 50;
        $variation = $posterior - $prior;
        
        $warnings = [];
        
        if ($posterior >= 70) {
            $warnings[] = [
                'type' => 'critical',
                'title' => 'Riesgo Crítico Detectado',
                'message' => 'La probabilidad del riesgo supera el umbral crítico del 70%. Se requiere acción inmediata.',
                'action' => 'Revisar controles y activar plan de contingencia'
            ];
        }
        
        if ($variation > 15) {
            $warnings[] = [
                'type' => 'warning',
                'title' => 'Incremento Significativo',
                'message => "La probabilidad del riesgo ha aumentado {$variation} puntos porcentuales en esta evaluación.",
                'action' => 'Investigar causas del incremento'
            ];
        }
        
        if (($analysisData['evidence_count'] ?? 0) >= 5) {
            $warnings[] = [
                'type' => 'info',
                'title' => 'Múltiples Evidencias',
                'message' => 'Se han detectado múltiples evidencias de riesgo en el reporte.',
                'action' => 'Validar cada evidencia y documentar hallazgos'
            ];
        }
        
        return $warnings;
    }
}
