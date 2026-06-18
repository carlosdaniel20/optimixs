<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">📊 Matriz de Riesgo (Heatmap)</h1>
        <div class="flex gap-2">
            <button onclick="location.reload()" class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition duration-150">
                <i class="fas fa-sync-alt"></i> Recargar
            </button>
            <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg shadow-md flex items-center gap-2 transition">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>

    <!-- Contenedor del Heatmap -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-4">
        <div id="chartdiv" style="width: 100%; height: 600px;"></div>
    </div>

    <!-- Leyenda -->
    <div class="mt-4 flex flex-wrap gap-4 justify-center">
        <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full" style="background-color: #0b7d03;"></span><span class="text-sm">Muy Bajo (0-19)</span></div>
        <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full" style="background-color: #5dbe24;"></span><span class="text-sm">Bajo (20-39)</span></div>
        <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full" style="background-color: #e1d92d;"></span><span class="text-sm">Medio (40-59)</span></div>
        <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full" style="background-color: #e17a2d;"></span><span class="text-sm">Alto (60-79)</span></div>
        <div class="flex items-center gap-2"><span class="w-4 h-4 rounded-full" style="background-color: #ca0101;"></span><span class="text-sm">Crítico (80-100)</span></div>
    </div>
</div>

<!-- amCharts locales -->
<script src="/assets/amcharts/index.js"></script>
<script src="/assets/amcharts/xy.js"></script>
<script src="/assets/amcharts/themes/Animated.js"></script>

<script>
// Esperar a que el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Verificar que amCharts está disponible
    if (typeof am5 === 'undefined') {
        console.error('amCharts no está disponible');
        document.getElementById('chartdiv').innerHTML = '<div class="text-center py-8 text-red-500">Error: No se pudieron cargar los recursos de amCharts</div>';
        return;
    }

    am5.ready(function() {
        try {
            var root = am5.Root.new("chartdiv");
            root.setThemes([am5themes_Animated.new(root)]);

            var chart = root.container.children.push(
                am5xy.XYChart.new(root, {
                    panX: false,
                    panY: false,
                    wheelX: "none",
                    wheelY: "none",
                    paddingLeft: 0,
                    paddingRight: 0,
                    layout: root.verticalLayout
                })
            );

            var nivelesProbabilidad = ['Muy Baja (20%)', 'Baja (40%)', 'Media (60%)', 'Alta (80%)', 'Muy Alta (100%)'];
            var nivelesImpacto = ['Leve (20%)', 'Menor (40%)', 'Moderado (60%)', 'Mayor (80%)', 'Catastrófico (100%)'];

            // Eje Y (Probabilidad)
            var yRenderer = am5xy.AxisRendererY.new(root, {
                visible: true,
                minGridDistance: 20,
                inversed: true,
                minorGridEnabled: true
            });
            yRenderer.labels.template.setAll({ fontSize: 12, fontWeight: "bold", fill: am5.color(0x333333) });
            yRenderer.grid.template.set("visible", true);
            yRenderer.grid.template.set("strokeOpacity", 0.1);

            var yAxis = chart.yAxes.push(
                am5xy.CategoryAxis.new(root, { renderer: yRenderer, categoryField: "y" })
            );

            // Eje X (Impacto)
            var xRenderer = am5xy.AxisRendererX.new(root, {
                visible: true,
                minGridDistance: 30,
                inversed: false,
                minorGridEnabled: true
            });
            xRenderer.labels.template.setAll({ fontSize: 12, fontWeight: "bold", fill: am5.color(0x333333), rotation: 0 });
            xRenderer.grid.template.set("visible", true);
            xRenderer.grid.template.set("strokeOpacity", 0.1);

            var xAxis = chart.xAxes.push(
                am5xy.CategoryAxis.new(root, { renderer: xRenderer, categoryField: "x" })
            );

            // Serie principal
            var series = chart.series.push(
                am5xy.ColumnSeries.new(root, {
                    calculateAggregates: true,
                    stroke: am5.color(0xffffff),
                    clustered: false,
                    xAxis: xAxis,
                    yAxis: yAxis,
                    categoryXField: "x",
                    categoryYField: "y",
                    valueField: "value"
                })
            );

            series.columns.template.setAll({
                tooltipText: "Probabilidad: {categoryY}\nImpacto: {categoryX}\nValor: {value}",
                strokeOpacity: 1,
                strokeWidth: 2,
                cornerRadiusTL: 5,
                cornerRadiusTR: 5,
                cornerRadiusBL: 5,
                cornerRadiusBR: 5,
                width: am5.percent(100),
                height: am5.percent(100),
                templateField: "columnSettings"
            });

            // Colores
            var colors = {
                muyBajo: am5.color(0x0b7d03),
                bajo: am5.color(0x5dbe24),
                medio: am5.color(0xe1d92d),
                alto: am5.color(0xe17a2d),
                critico: am5.color(0xca0101)
            };

            function getColorByValue(value) {
                if (value >= 80) return colors.critico;
                if (value >= 60) return colors.alto;
                if (value >= 40) return colors.medio;
                if (value >= 20) return colors.bajo;
                return colors.muyBajo;
            }

            // Generar datos
            var probabilidades = [
                { nombre: 'Muy Baja (20%)', valor: 20 },
                { nombre: 'Baja (40%)', valor: 40 },
                { nombre: 'Media (60%)', valor: 60 },
                { nombre: 'Alta (80%)', valor: 80 },
                { nombre: 'Muy Alta (100%)', valor: 100 }
            ];

            var impactos = [
                { nombre: 'Leve (20%)', valor: 20 },
                { nombre: 'Menor (40%)', valor: 40 },
                { nombre: 'Moderado (60%)', valor: 60 },
                { nombre: 'Mayor (80%)', valor: 80 },
                { nombre: 'Catastrófico (100%)', valor: 100 }
            ];

            function calcularRiesgo(probabilidad, impacto) {
                var v = (probabilidad / 100) * (impacto / 100);
                return Math.round(v * 100);
            }

            var data = [];
            for (var i = 0; i < probabilidades.length; i++) {
                for (var j = 0; j < impactos.length; j++) {
                    var valorRiesgo = calcularRiesgo(probabilidades[i].valor, impactos[j].valor);
                    data.push({
                        y: probabilidades[i].nombre,
                        x: impactos[j].nombre,
                        columnSettings: { fill: getColorByValue(valorRiesgo) },
                        value: valorRiesgo
                    });
                }
            }

            series.data.setAll(data);
            yAxis.data.setAll(nivelesProbabilidad.map(function(n) { return { category: n }; }));
            xAxis.data.setAll(nivelesImpacto.map(function(n) { return { category: n }; }));

            // Agregar texto dentro de las celdas
            series.bullets.push(function() {
                return am5.Bullet.new(root, {
                    sprite: am5.Label.new(root, {
                        fill: am5.color(0xffffff),
                        populateText: true,
                        centerX: am5.p50,
                        centerY: am5.p50,
                        fontSize: 12,
                        fontWeight: "bold",
                        text: "{value}"
                    })
                });
            });

            chart.appear(1000, 100);
            
        } catch (error) {
            console.error('Error al crear el heatmap:', error);
            document.getElementById('chartdiv').innerHTML = '<div class="text-center py-8 text-red-500">Error al cargar el heatmap: ' + error.message + '</div>';
        }
    });
});
</script>

<style>
#chartdiv {
    width: 100%;
    height: 600px;
}
@media print {
    #chartdiv {
        height: 500px;
    }
    button {
        display: none;
    }
}
</style>
