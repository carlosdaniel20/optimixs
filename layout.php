<?php
/** @var string $content */
// Expects $content

use App\Support\Branding;
use App\Support\ConfigStore;
use App\Support\HtmlHelper;
use App\Support\I18n;

$appName = (string) ConfigStore::get('app.name', 'TOTAL FIND ALL');
$logoPath = Branding::logo();
$appLogo = $logoPath !== '' ? url($logoPath) : '';
$appInitial = mb_strtoupper(mb_substr($appName, 0, 1));
$isCatalogueMode = ConfigStore::isCatalogueMode();
$versionFile = __DIR__ . '/../../version.json';
$versionData = file_exists($versionFile) ? json_decode(file_get_contents($versionFile), true) : null;
$appVersion = $versionData['version'] ?? '0.1.0';
$currentLocale = I18n::getLocale();
$htmlLang = substr($currentLocale, 0, 2);

// Variables para el menú - sistema de permisos por módulo
$userRole = $_SESSION['user']['tipo_utente'] ?? '';
$isAdminUser = ($userRole === 'admin');
$isStaffUser = ($userRole === 'staff');
$isRegularUser = ($userRole === 'user');
$isLoggedIn = !empty($_SESSION['user'] ?? null);
$userId = $_SESSION['user']['id'] ?? null;

// Cargar permisos específicos del usuario desde la sesión o de una variable global
// Estos permisos deberían ser cargados por el controlador principal y pasarle al layout
$userPermissions = $_SESSION['user_permissions'] ?? [];

// Función helper para verificar si el usuario tiene permiso para un módulo
function hasModulePermission($moduleId, $action = 'view', $userPermissions = [], $isAdminUser = false) {
    // Admin tiene todos los permisos
    if ($isAdminUser) return true;
    
    // Verificar permiso específico del módulo
    if (isset($userPermissions[$moduleId][$action]) && $userPermissions[$moduleId][$action]) {
        return true;
    }
    
    return false;
}

// Definición de módulos con sus IDs y permisos requeridos
$modules = [
    'dashboard' => ['id' => 1, 'name' => 'Dashboard', 'perm' => 'view'],
    'organizations' => ['id' => 2, 'name' => 'Organizaciones', 'perm' => 'view'],
    'risk_config' => ['id' => 3, 'name' => 'Configuración Riesgos', 'perm' => 'view'],
    'impacts' => ['id' => 4, 'name' => 'Impactos', 'perm' => 'view'],
    'criteria' => ['id' => 5, 'name' => 'Criterios', 'perm' => 'view'],
    'classification' => ['id' => 6, 'name' => 'Clasificación', 'perm' => 'view'],
    'risk_matrix' => ['id' => 7, 'name' => 'Matriz Riesgos', 'perm' => 'view'],
    'workflow' => ['id' => 8, 'name' => 'Flujo Trabajo', 'perm' => 'view'],
    'checklist' => ['id' => 9, 'name' => 'Checklist', 'perm' => 'view'],
    'reports' => ['id' => 10, 'name' => 'Reportes', 'perm' => 'view'],
    'ai_analysis' => ['id' => 11, 'name' => 'Análisis IA', 'perm' => 'view'],
    'geo_analysis' => ['id' => 12, 'name' => 'Geo-Análisis', 'perm' => 'view'],
    'maritime' => ['id' => 13, 'name' => 'Inteligencia Marítima', 'perm' => 'view'],
    'chat' => ['id' => 14, 'name' => 'Chat', 'perm' => 'view'],
    'user_management' => ['id' => 15, 'name' => 'Gestión Usuarios', 'perm' => 'view'],
    'system_settings' => ['id' => 16, 'name' => 'Configuración', 'perm' => 'view'],
];

// Verificaciones de permisos por módulo
$canViewDashboard = $isLoggedIn;
$canViewOrganizations = hasModulePermission(2, 'view', $userPermissions, $isAdminUser);
$canViewRiskConfig = hasModulePermission(3, 'view', $userPermissions, $isAdminUser);
$canViewImpacts = hasModulePermission(4, 'view', $userPermissions, $isAdminUser);
$canViewCriteria = hasModulePermission(5, 'view', $userPermissions, $isAdminUser);
$canViewClassification = hasModulePermission(6, 'view', $userPermissions, $isAdminUser);
$canViewRiskMatrix = hasModulePermission(7, 'view', $userPermissions, $isAdminUser);
$canViewWorkflow = hasModulePermission(8, 'view', $userPermissions, $isAdminUser);
$canViewChecklist = hasModulePermission(9, 'view', $userPermissions, $isAdminUser);
$canViewReports = hasModulePermission(10, 'view', $userPermissions, $isAdminUser);
$canViewAIAnalysis = hasModulePermission(11, 'view', $userPermissions, $isAdminUser);
$canViewGeoAnalysis = hasModulePermission(12, 'view', $userPermissions, $isAdminUser);
$canViewMaritime = hasModulePermission(13, 'view', $userPermissions, $isAdminUser);
$canViewChat = hasModulePermission(14, 'view', $userPermissions, $isAdminUser);
$canViewUserManagement = hasModulePermission(15, 'view', $userPermissions, $isAdminUser);
$canViewSystemSettings = hasModulePermission(16, 'view', $userPermissions, $isAdminUser);
?>
<!doctype html>
<html lang="<?= htmlspecialchars($htmlLang, ENT_QUOTES, 'UTF-8') ?>">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo HtmlHelper::e($appName); ?> - Sistema de gestión de riesgos Optimixs Risk</title>
  <meta name="csrf-token" content="<?php echo App\Support\Csrf::ensureToken(); ?>" />
  <link rel="icon" type="image/x-icon" href="<?= htmlspecialchars(url('/favicon.ico'), ENT_QUOTES, 'UTF-8') ?>">
  <script>window.BASE_PATH = <?= json_encode(\App\Support\HtmlHelper::getBasePath(), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
  <link rel="stylesheet" href="<?= htmlspecialchars(assetUrl('vendor.css'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" />
  <link rel="stylesheet" href="<?= htmlspecialchars(assetUrl('flatpickr-custom.css'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" />
  <link rel="stylesheet" href="<?= htmlspecialchars(assetUrl('main.css'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" />
  <link rel="stylesheet" href="<?= htmlspecialchars(assetUrl('css/swal-theme.css'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" />

  <!-- Assets del módulo de riesgos -->
  <link rel="stylesheet" href="<?= assetUrl('risk/risk-common.css') ?>">
  <script src="<?= assetUrl('risk/riskData.js') ?>"></script>
  <script src="<?= assetUrl('risk/riskCalculations.js') ?>"></script>
  <script src="<?= assetUrl('risk/risk-migration.js') ?>"></script>
  <script src="<?= assetUrl('risk/risk-main.js') ?>"></script>

  <meta name="csrf-token" content="<?= htmlspecialchars(App\Support\Csrf::ensureToken(), ENT_QUOTES, 'UTF-8') ?>">

  <script>
    (function () {
      if (typeof window.__ !== 'function') {
        window.__ = function (message, ...args) {
          if (typeof message !== 'string') {
            return '';
          }
          if (!args.length) {
            return message;
          }
          let argIndex = 0;
          return message.replace(/%(\d+\$)?[sd]/g, function () {
            const value = args[argIndex++];
            return value !== undefined ? String(value) : '';
          });
        };
      }
      if (typeof window.__n !== 'function') {
        window.__n = function (singular, plural, count, ...args) {
          const base = count === 1 ? singular : plural;
          return window.__(base, ...args);
        };
      }
    })();
  </script>

  <?php do_action('assets.head'); ?>

  <style>
    @media (max-width: 1024px) {
      #notifications-badge {
        margin-top: 8px;
      }
    }
    
    .nav-section-header {
      transition: all 0.2s ease;
    }
    
    .nav-section-header:hover {
      background-color: #f8fafc;
    }
    
    .section-arrow {
      transition: transform 0.2s ease;
    }
    
    .nav-section-content {
      transition: all 0.2s ease;
      overflow: hidden;
    }
    
    .nav-section-content.hidden {
      display: none;
    }
  </style>
</head>

<body class="bg-gray-50 text-gray-900 antialiased">
  <div id="mobile-menu-overlay"
    class="fixed inset-0 bg-black/50 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

  <div class="min-h-screen flex">
    <?php
    // El sidebar solo se muestra para usuarios logueados
    $showSidebar = $isLoggedIn;
    $sidebarStyle = !$showSidebar ? 'display: none;' : '';
    ?>
    <aside id="sidebar"
      class="fixed lg:static inset-y-0 left-0 z-50 w-72 lg:w-64 xl:w-72 bg-white border-r border-gray-200 shadow-lg transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out flex flex-col"
      style="<?= $sidebarStyle ?>">

      <!-- Sidebar Header -->
      <div class="flex items-center justify-between px-6 py-5 flex-shrink-0">
        <a href="https://www.optimixsrisk.com/" class="flex items-center space-x-3 hover:opacity-80 transition-opacity cursor-pointer">
          <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center">
            <?php if ($appLogo !== ''): ?>
              <img src="<?php echo HtmlHelper::e($appLogo); ?>" alt="<?php echo HtmlHelper::e($appName); ?>"
                class="max-h-[45px] w-auto object-contain">
            <?php else: ?>
              <span class="text-gray-700 font-semibold text-lg"><?php echo HtmlHelper::e($appInitial); ?></span>
            <?php endif; ?>
          </div>
          <div class="leading-none">
            <span class="font-bold text-lg text-gray-900 block"><?php echo HtmlHelper::e($appName); ?></span>
            <div class="text-[11px] uppercase tracking-wide font-semibold text-gray-500">
              <?= __("Sistema de Gestión de Riesgos") ?>
            </div>
            <div class="text-[10px] text-gray-400 font-mono mt-0.5">v<?php echo HtmlHelper::e($appVersion); ?></div>
          </div>
        </a>
        <button id="close-mobile-menu" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
          <i class="fas fa-times text-gray-500"></i>
        </button>
      </div>

      <!-- Navigation Menu - ACORDEÓN con permisos por módulo integrados -->
      <nav class="flex-1 px-4 pt-2 pb-24 overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300">
        <div class="space-y-4">
          
          <!-- ========================================================= -->
          <!-- SECCIÓN: INICIO (Visible para todos los usuarios logueados) -->
          <!-- ========================================================= -->
          <?php if ($canViewDashboard): ?>
          <div class="nav-section">
            <div class="px-3 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider">
              <i class="fas fa-home mr-2"></i> <?= __("Inicio") ?>
            </div>
            <div class="space-y-1">
              <a href="<?= htmlspecialchars(url('/admin/dashboard'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-tachometer-alt text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium">Dashboard Principal</div>
                  <div class="text-xs text-gray-500">Panel de control</div>
                </div>
              </a>
              
              <?php if ($canViewOrganizations): ?>
              <a href="<?= htmlspecialchars(url('/risk/organizacion'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-building text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium">Organizaciones</div>
                  <div class="text-xs text-gray-500">Carga de Datos de las empresas</div>
                </div>
              </a>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: GESTIÓN DE RIESGOS - Permiso específico -->
          <!-- ========================================================= -->
          <?php if ($canViewRiskConfig || $canViewImpacts || $canViewCriteria || $canViewClassification || $canViewRiskMatrix): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider text-center w-full">
                <i class="fas fa-shield-alt mr-2"></i> <?= __("Configuración inicial de Riesgos") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <?php if ($canViewImpacts): ?>
              <a href="<?= htmlspecialchars(url('/risk/impactos'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-exclamation-triangle text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Impactos") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Niveles de impacto editables") ?></div>
                </div>
              </a>
              <?php endif; ?>

              <?php if ($canViewCriteria): ?>
              <a href="<?= htmlspecialchars(url('/risk/criterios'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-sliders-h text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Criterios") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Probabilidad y matriz") ?></div>
                </div>
              </a>
              <?php endif; ?>

              <?php if ($canViewClassification): ?>
              <a href="<?= htmlspecialchars(url('/risk/clasificacion'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-tags text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Clasificación") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Categorías de riesgo") ?></div>
                </div>
              </a>
              <?php endif; ?>

              <?php if ($canViewRiskMatrix): ?>
              <a href="<?= htmlspecialchars(url('/risk/matriz'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-th text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Matriz de Riesgos") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Evaluación inherente y residual") ?></div>
                </div>
              </a>
              <?php endif; ?>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: ÁREAS DE INSPECCIÓN / FLUJO DE TRABAJO -->
          <!-- ========================================================= -->
          <?php if ($canViewWorkflow): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <i class="fas fa-search mr-2"></i> <?= __("Flujo de Trabajo") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <a class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 text-gray-700 hover:text-white mt-2 mb-2"
                 href="<?= htmlspecialchars(url('/admin/risktasks/dashboard'), ENT_QUOTES, 'UTF-8') ?>"
                 style="background-color:#1da4df;"
                 onmouseover="this.style.backgroundColor='#178bbd'"
                 onmouseout="this.style.backgroundColor='#1da4df'">
                <div class="flex items-center justify-center w-10 h-10 rounded-lg bg-white/20">
                  <i class="fas fa-tasks text-white"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium text-white">Tareas Programadas</div>
                  <div class="text-xs text-white/80">Tareas de inspección</div>
                </div>
              </a>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: GESTIÓN DE CHECKLIST -->
          <!-- ========================================================= -->
          <?php if ($canViewChecklist): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <i class="fas fa-check-double mr-2"></i> <?= __("Gestión de Checklist") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <a href="<?= htmlspecialchars(url('/risk/checklist'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-check-double text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium">Checklist</div>
                  <div class="text-xs text-gray-500">Evidencias y progreso</div>
                </div>
              </a>

              <a href="/risk/dashboard" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-chart-pie text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium">Dashboard-Checklist</div>
                  <div class="text-xs text-gray-500">Ver evaluaciones</div>
                </div>
              </a>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: GESTIÓN DE REPORTES -->
          <!-- ========================================================= -->
          <?php if ($canViewReports): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <i class="fas fa-file-alt mr-2"></i> <?= __("Gestión de Reportes") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <a href="<?= htmlspecialchars(url('/risk/reporte'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-file-alt text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Reporte Final") ?></div>
                  <div class="text-xs text-gray-500"><?= __("8 etapas") ?></div>
                </div>
              </a>

              <a href="/risk/reportes-dashboard" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-archive text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium">Historial de Reportes</div>
                  <div class="text-xs text-gray-500">Ver reportes guardados</div>
                </div>
              </a>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: GESTIÓN DE ANÁLISIS IA -->
          <!-- ========================================================= -->
          <?php if ($canViewAIAnalysis): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <i class="fas fa-brain mr-2"></i> <?= __("Gestión de Análisis IA") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <a href="/risk/ai-analisis" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-brain text-gray-600 text-sm"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium">Análisis IA</div>
                  <div class="text-xs text-gray-500">Análisis Bayesiano de reportes</div>
                </div>
              </a>

              <a href="/risk/ai-dashboard" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-chart-pie text-gray-600 text-sm"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium">Dashboard IA</div>
                  <div class="text-xs text-gray-500">Estadísticas y evolución</div>
                </div>
              </a>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: GESTIÓN DE GEO-ANÁLISIS -->
          <!-- ========================================================= -->
          <?php if ($canViewGeoAnalysis): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <i class="fas fa-map-marker-alt mr-2"></i> <?= __("Geo-Análisis") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <a href="/optimix-tracker" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-satellite-dish text-gray-600 text-sm"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium">Optimix Tracker</div>
                  <div class="text-xs text-gray-500">Rastreo GPS en tiempo real</div>
                </div>
              </a>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: INTELIGENCIA MARÍTIMA -->
          <!-- ========================================================= -->
          <?php if ($canViewMaritime): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <i class="fas fa-ship mr-2"></i> <?= __("Inteligencia Marítima") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <a href="<?= htmlspecialchars(url('/maritime'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-ship text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Consulta de Embarcaciones") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Información de buques vía IMO/nombre") ?></div>
                </div>
              </a>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: CHAT INTERNO -->
          <!-- ========================================================= -->
          <?php if ($canViewChat): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <i class="fas fa-comments mr-2"></i> <?= __("Chat-Comunicación") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <a href="<?= htmlspecialchars(url('/chat'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-comments text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Chat Interno") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Mensajería entre usuarios") ?></div>
                </div>
              </a>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: GESTIÓN DE USUARIOS -->
          <!-- ========================================================= -->
          <?php if ($canViewUserManagement): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <i class="fas fa-users mr-2"></i> <?= __("Gestión de Usuarios") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <a href="<?= htmlspecialchars(url('/admin/utenti/crea'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-user-plus text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Nuevo Usuario") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Crear cuenta") ?></div>
                </div>
              </a>
              
              <a href="<?= htmlspecialchars(url('/admin/utenti'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-users text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Usuarios") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Listar, editar, eliminar") ?></div>
                </div>
              </a>
              
              <a href="<?= htmlspecialchars(url('/admin/permissions'), ENT_QUOTES, 'UTF-8') ?>" class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-user-lock text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Permisos de Usuario") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Control de acceso por módulos") ?></div>
                </div>
              </a>
            </div>
          </div>
          <?php endif; ?>

          <!-- ========================================================= -->
          <!-- SECCIÓN: CONFIGURACIÓN -->
          <!-- ========================================================= -->
          <?php if ($canViewSystemSettings): ?>
          <div class="nav-section">
            <div class="nav-section-header flex items-center justify-between cursor-pointer px-3 py-2 rounded-lg hover:bg-gray-50 transition-colors">
              <div class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                <i class="fas fa-cog mr-2"></i> <?= __("Configuración") ?>
              </div>
              <i class="fas fa-chevron-down section-arrow text-gray-400 text-xs transition-transform duration-200"></i>
            </div>
            <div class="nav-section-content space-y-1 mt-1 hidden">
              <a class="nav-link group flex items-center px-4 py-3 rounded-lg transition-all duration-200 hover:bg-gray-100 text-gray-700 hover:text-gray-900"
                 href="<?= htmlspecialchars(url('/admin/settings'), ENT_QUOTES, 'UTF-8') ?>">
                <div class="flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200">
                  <i class="fas fa-cog text-gray-600"></i>
                </div>
                <div class="ml-3">
                  <div class="font-medium"><?= __("Ajustes") ?></div>
                  <div class="text-xs text-gray-500"><?= __("Configuración del sistema") ?></div>
                </div>
              </a>
            </div>
          </div>
          <?php endif; ?>

        </div>
      </nav>

      <!-- Sidebar Footer -->
      <div class="flex-shrink-0 p-4 border-t border-gray-200 bg-white">
        <div class="flex items-center space-x-3">
          <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
            <i class="fas fa-user text-gray-600 text-sm"></i>
          </div>
          <div class="flex-1">
            <div class="text-sm font-medium text-gray-900"><?= HtmlHelper::e($_SESSION['user']['nome'] ?? $_SESSION['user']['email'] ?? 'Usuario') ?></div>
            <div class="text-xs text-gray-500">
              <?php 
              $roleLabel = '';
              if ($isAdminUser) $roleLabel = 'Administrador';
              elseif ($isStaffUser) $roleLabel = 'Staff';
              elseif ($isRegularUser) $roleLabel = 'Usuario';
              echo __($roleLabel ?: 'Sistema activo');
              ?>
            </div>
          </div>
          <?php if ($canViewSystemSettings): ?>
            <a href="<?= htmlspecialchars(url('/admin/settings'), ENT_QUOTES, 'UTF-8') ?>"
                class="p-3 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/20"
                title="<?= __('Ajustes') ?>">
                <i class="fas fa-cog text-lg text-gray-600 transform hover:rotate-12 transition-transform"></i>
            </a>
          <?php endif; ?>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 min-w-0">
      <!-- Header -->
      <header class="sticky top-0 z-30 bg-white border-b border-gray-200 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex items-center justify-between h-16 lg:h-20">
            <div class="flex items-center gap-4 lg:hidden">
              <button id="mobile-menu-button" class="p-2 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/20">
                <i class="fas fa-bars text-xl text-gray-600"></i>
              </button>
              <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-white border border-gray-100 flex items-center justify-center">
                  <?php if ($appLogo !== ''): ?>
                    <img src="<?php echo HtmlHelper::e($appLogo); ?>" alt="<?php echo HtmlHelper::e($appName); ?>" class="w-9 h-9 object-contain">
                  <?php else: ?>
                    <span class="text-gray-800 font-semibold"><?php echo HtmlHelper::e($appInitial); ?></span>
                  <?php endif; ?>
                </div>
                <div class="hidden sm:block leading-none">
                  <span class="font-bold text-base text-gray-900 block"><?php echo HtmlHelper::e($appName); ?></span>
                  <div class="text-[11px] font-semibold uppercase tracking-wide" style="color:#d70161;">
                    <?= __("Sistema de Gestión de Riesgos") ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="hidden lg:flex flex-1 max-w-2xl mx-4 lg:mx-8">
              <div class="relative group w-full">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-opacity duration-200">
                  <div class="flex items-center space-x-2">
                    <i class="fas fa-search text-gray-400 group-focus-within:text-gray-600 transition-colors"></i>
                    <span class="hidden sm:inline text-xs text-gray-400 group-focus-within:text-gray-600 transition-colors"><?= __("Buscar...") ?></span>
                  </div>
                </div>
                <input type="text" id="global-search"
                  class="w-full pl-12 pr-4 py-3 lg:py-3.5 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-2xl shadow-sm hover:shadow-md focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500/20 focus:bg-white transition-all duration-200 placeholder:text-gray-400"
                  autocomplete="off" placeholder="<?= __('Buscar riesgos, controles...') ?>">
                <div id="global-search-results" class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-2xl shadow-2xl hidden max-h-96 overflow-y-auto"></div>
              </div>
            </div>

            <div class="flex items-center gap-1 sm:gap-2">
              <button id="mobile-search-button" class="lg:hidden p-3 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/20" title="<?= __("Buscar") ?>">
                <i class="fas fa-search text-lg text-gray-600"></i>
              </button>

              <div class="relative">
                <button id="notifications-button" class="relative p-3 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/20" title="Notificaciones">
                  <i class="fas fa-bell text-lg text-gray-600"></i>
                  <span id="notifications-badge" class="hidden absolute -top-1 -right-1 w-6 h-6 rounded-full bg-red-500 text-white text-sm font-bold flex items-center justify-center shadow-lg ring-2 ring-white"></span>
                </button>
                <div id="notifications-dropdown" class="absolute left-1/2 -translate-x-1/2 md:left-auto md:right-0 md:translate-x-0 mt-2 w-[calc(100vw-2rem)] md:w-96 max-w-md bg-white border border-gray-200 rounded-2xl shadow-2xl hidden z-[100]">
                  <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900"><?= __("Notificaciones") ?></h3>
                    <button onclick="markAllNotificationsAsRead()" class="text-xs text-gray-900 hover:text-gray-700 font-medium">
                      <?= __("Marcar todas como leídas") ?>
                    </button>
                  </div>
                  <div class="max-h-96 overflow-y-auto" id="notifications-list">
                    <div id="notifications-empty" class="p-8 text-center text-sm text-gray-500">
                      <i class="fas fa-bell-slash text-3xl mb-2 text-gray-300"></i>
                      <p><?= __("No hay notificaciones") ?></p>
                    </div>
                  </div>
                  <div class="p-4 border-t border-gray-200">
                    <a href="<?= htmlspecialchars(url('/admin/notifications'), ENT_QUOTES, 'UTF-8') ?>" class="text-sm text-gray-900 hover:text-gray-700 font-medium">
                      <?= __("Ver todas") ?>
                    </a>
                  </div>
                </div>
              </div>

              <button id="shortcuts-help" aria-label="<?= __('Atajos de teclado') ?>" class="hidden md:flex p-3 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/20">
                <i class="fas fa-keyboard text-lg text-gray-600"></i>
              </button>

              <?php if ($canViewSystemSettings): ?>
                <a href="<?= htmlspecialchars(url('/admin/settings'), ENT_QUOTES, 'UTF-8') ?>" class="p-3 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/20" title="<?= __('Ajustes') ?>">
                  <i class="fas fa-cog text-lg text-gray-600 transform hover:rotate-12 transition-transform"></i>
                </a>
              <?php endif; ?>

              <div class="relative ml-2">
                <?php if ($isLoggedIn): ?>
                  <button id="user-menu-button" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-100 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-500/20">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-900 text-white shadow">
                      <i class="fas fa-user"></i>
                    </div>
                    <div class="hidden sm:block text-left">
                      <div class="text-sm font-medium text-gray-900">
                        <?php echo \App\Support\HtmlHelper::safe((string) ($_SESSION['user']['name'] ?? $_SESSION['user']['email'] ?? 'Usuario')); ?>
                      </div>
                      <div class="text-xs text-gray-500">
                        <?php echo htmlspecialchars((string) ($_SESSION['user']['email'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>
                      </div>
                    </div>
                    <i class="fas fa-chevron-down text-sm text-gray-400 hidden sm:block transition-transform duration-200" id="user-menu-arrow"></i>
                  </button>
                  <div id="user-menu-dropdown" class="absolute right-0 mt-2 w-48 sm:w-56 bg-white border border-gray-200 rounded-2xl shadow-2xl hidden z-50">
                    <div class="p-2">
                      <a href="<?= htmlspecialchars(route_path('profile'), ENT_QUOTES, 'UTF-8') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-100 transition-colors text-gray-700">
                        <i class="fas fa-user-cog w-4 h-4"></i><span class="text-sm"><?= __("Perfil") ?></span>
                      </a>
                      <a href="<?= htmlspecialchars(route_path('wishlist'), ENT_QUOTES, 'UTF-8') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-100 transition-colors text-gray-700">
                        <i class="fas fa-heart w-4 h-4"></i><span class="text-sm"><?= __("Preferidos") ?></span>
                      </a>
                      <?php if ($canViewSystemSettings): ?>
                        <a href="<?= htmlspecialchars(url('/admin/settings'), ENT_QUOTES, 'UTF-8') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-gray-100 transition-colors text-gray-700">
                          <i class="fas fa-cog w-4 h-4"></i><span class="text-sm"><?= __("Ajustes") ?></span>
                        </a>
                      <?php endif; ?>
                      <hr class="my-2 border-gray-200">
                      <a href="<?= htmlspecialchars(route_path('logout'), ENT_QUOTES, 'UTF-8') ?>" class="flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-red-50 transition-colors text-red-600">
                        <i class="fas fa-sign-out-alt w-4 h-4"></i><span class="text-sm"><?= __("Salir") ?></span>
                      </a>
                    </div>
                  </div>
                <?php else: ?>
                  <a href="<?= htmlspecialchars(route_path('login'), ENT_QUOTES, 'UTF-8') ?>" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-100 hidden sm:inline-flex items-center">
                    <i class="fas fa-sign-in-alt mr-2"></i> <?= __("Acceder") ?>
                  </a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>

        <div id="mobile-search-bar" class="lg:hidden border-t border-gray-200 bg-white hidden">
          <div class="px-4 py-3">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                <i class="fas fa-search text-gray-400"></i>
              </div>
              <input type="text" id="mobile-global-search" class="w-full pl-14 pr-12 py-3 text-sm text-gray-800 bg-gray-50 border border-gray-300 rounded-2xl focus:border-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500/20 focus:bg-white transition-all" placeholder="<?= __('Buscar...') ?>" autocomplete="off">
              <button id="mobile-search-close" class="absolute inset-y-0 right-0 pr-4 flex items-center">
                <i class="fas fa-times text-gray-400 hover:text-gray-600"></i>
              </button>
              <div id="mobile-search-results" class="absolute z-50 w-full mt-2 bg-white border border-gray-200 rounded-2xl shadow-2xl hidden max-h-96 overflow-y-auto"></div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <?php if (isset($_SESSION['error_message']) || isset($_SESSION['success_message'])): ?>
          <div class="px-4 sm:px-6 lg:px-8 pt-6">
            <?php if (isset($_SESSION['error_message'])): ?>
              <div class="mb-6 p-4 rounded-xl border border-red-200 bg-red-50 text-red-700" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <?php echo App\Support\HtmlHelper::e($_SESSION['error_message']); ?>
              </div>
              <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['success_message'])): ?>
              <div class="mb-6 p-4 rounded-xl border border-green-200 bg-green-50 text-green-700" role="alert">
                <i class="fas fa-check-circle mr-2"></i>
                <?php echo App\Support\HtmlHelper::e($_SESSION['success_message']); ?>
              </div>
              <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
          </div>
        <?php endif; ?>
        <?php echo $content; ?>
      </main>
    </div>
  </div>

  <!-- Scripts -->
  <script>
    <?php
    $currentLocale = I18n::getLocale();
    $translationFile = __DIR__ . '/../../locale/' . $currentLocale . '.json';
    $translations = [];
    if (file_exists($translationFile)) {
      $translationsContent = file_get_contents($translationFile);
      $translations = json_decode($translationsContent, true) ?? [];
    }
    ?>
    window.i18nTranslations = <?= json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_APOS) ?>;
    window.userIsAdmin = <?= json_encode($isAdminUser, JSON_HEX_TAG) ?>;
    window.userPermissions = <?= json_encode($userPermissions, JSON_HEX_TAG) ?>;

    window.__ = function (key, ...args) {
      let translated = window.i18nTranslations[key] || key;
      if (args.length > 0) {
        let argIndex = 0;
        translated = translated.replace(/%(\d+\$)?[sd]/g, () => args[argIndex++] ?? '');
      }
      return translated;
    };
  </script>
  <script src="<?= htmlspecialchars(assetUrl('vendor.bundle.js'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" defer></script>
  <script src="<?= htmlspecialchars(assetUrl('flatpickr-init.js'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" defer></script>
  <script src="<?= htmlspecialchars(assetUrl('main.bundle.js'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" defer></script>
  <script src="<?= htmlspecialchars(assetUrl('js/csrf-helper.js'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" defer></script>
  <script src="<?= htmlspecialchars(assetUrl('js/swal-config.js'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" defer></script>
  <script src="<?= htmlspecialchars(assetUrl('tinymce/tinymce.min.js'), ENT_QUOTES, 'UTF-8') ?>?v=<?= htmlspecialchars($appVersion, ENT_QUOTES, 'UTF-8') ?>" defer></script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sectionHeaders = document.querySelectorAll('.nav-section-header');
      const savedState = JSON.parse(localStorage.getItem('sidebarAccordionState') || '{}');
      
      sectionHeaders.forEach(header => {
        const section = header.closest('.nav-section');
        const content = section.querySelector('.nav-section-content');
        const arrow = header.querySelector('.section-arrow');
        const sectionKey = header.innerText.trim();
        
        if (savedState[sectionKey] === 'open') {
          content.classList.remove('hidden');
          if (arrow) arrow.style.transform = 'rotate(180deg)';
        } else {
          content.classList.add('hidden');
          if (arrow) arrow.style.transform = 'rotate(0deg)';
        }
        
        header.addEventListener('click', function(e) {
          e.stopPropagation();
          const isHidden = content.classList.contains('hidden');
          
          if (isHidden) {
            content.classList.remove('hidden');
            if (arrow) arrow.style.transform = 'rotate(180deg)';
            savedState[sectionKey] = 'open';
          } else {
            content.classList.add('hidden');
            if (arrow) arrow.style.transform = 'rotate(0deg)';
            savedState[sectionKey] = 'closed';
          }
          
          localStorage.setItem('sidebarAccordionState', JSON.stringify(savedState));
        });
      });
    });

    function escapeHtml(value) {
      const div = document.createElement('div');
      div.textContent = value ?? '';
      return div.innerHTML.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    }

    const appLocale = '<?= \App\Support\I18n::getLocale() ?>';
    function formatDateLocale(date, includeTime = false, separator = '/') {
      if (!date) return '';
      const d = date instanceof Date ? date : new Date(date);
      if (isNaN(d.getTime())) return String(date);
      const day = String(d.getDate()).padStart(2, '0');
      const month = String(d.getMonth() + 1).padStart(2, '0');
      const year = d.getFullYear();
      let result;
      if (appLocale.startsWith('it')) {
        result = separator === '/' ? `${day}/${month}/${year}` : `${day}-${month}-${year}`;
      } else {
        result = `${year}-${month}-${day}`;
      }
      if (includeTime) {
        const hours = String(d.getHours()).padStart(2, '0');
        const mins = String(d.getMinutes()).padStart(2, '0');
        result += ` ${hours}:${mins}`;
      }
      return result;
    }

    function initializeGlobalSearch() {
      const searchInput = document.getElementById('global-search');
      const resultsDiv = document.getElementById('global-search-results');
      let searchTimeout;

      if (searchInput && resultsDiv) {
        searchInput.addEventListener('focus', function () {
          const visualPlaceholder = document.querySelector('.absolute.inset-y-0.left-0.pl-4');
          if (visualPlaceholder) visualPlaceholder.style.opacity = '0';
        });
        searchInput.addEventListener('blur', function () {
          const visualPlaceholder = document.querySelector('.absolute.inset-y-0.left-0.pl-4');
          if (visualPlaceholder && this.value.trim().length === 0) visualPlaceholder.style.opacity = '1';
        });
        searchInput.addEventListener('input', function () {
          clearTimeout(searchTimeout);
          const query = this.value.trim();
          const visualPlaceholder = document.querySelector('.absolute.inset-y-0.left-0.pl-4');
          if (visualPlaceholder) visualPlaceholder.style.opacity = query.length > 0 ? '0' : '1';
          if (query.length < 2) {
            resultsDiv.classList.add('hidden');
            return;
          }
          resultsDiv.innerHTML = `<div class="p-4 text-center"><i class="fas fa-spinner fa-spin text-gray-400"></i> <span class="ml-2 text-sm text-gray-500">${window.__('Ricerca in corso...')}</span></div>`;
          resultsDiv.classList.remove('hidden');
          searchTimeout = setTimeout(async () => {
            try {
              const response = await fetch(`${window.BASE_PATH}/api/search/unified?q=${encodeURIComponent(query)}`);
              const results = await response.json();
              let html = '';
              if (results.length > 0) {
                results.forEach(item => {
                  let iconClass = 'fas fa-question';
                  let iconColor = 'text-gray-500';
                  let identifierHtml = '';
                  const safeLabel = escapeHtml(String(item.label ?? ''));
                  const rawUrl = item.url ? String(item.url) : '';
                  const safeUrl = rawUrl && !/^javascript:/i.test(rawUrl) ? encodeURI(rawUrl) : '#';
                  switch (item.type) {
                    case 'book': iconClass = 'fas fa-book-open'; iconColor = 'text-blue-500'; break;
                    case 'author': iconClass = 'fas fa-user-edit'; iconColor = 'text-purple-500'; break;
                    case 'publisher': iconClass = 'fas fa-building'; iconColor = 'text-orange-500'; break;
                    case 'user': iconClass = 'fas fa-user'; iconColor = 'text-pink-500'; break;
                  }
                  html += `<a href="${safeUrl}" class="flex items-start p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg text-sm transition-colors">
                      <i class="${iconClass} ${iconColor} w-4 h-4 mr-3 mt-1"></i>
                      <div class="flex-1"><div class="text-gray-800 dark:text-gray-200 font-medium">${safeLabel}</div>${identifierHtml}</div>
                    </a>`;
                });
              } else {
                html = `<div class="p-4 text-center"><i class="fas fa-search text-gray-300 text-2xl mb-2"></i><div class="text-sm text-gray-500 dark:text-gray-400">${window.__('Nessun risultato trovato per')} "<span class="font-medium">${escapeHtml(query)}</span>"</div></div>`;
              }
              resultsDiv.innerHTML = html;
            } catch (error) {
              resultsDiv.innerHTML = `<div class="p-4 text-center text-red-500"><i class="fas fa-exclamation-triangle mr-2"></i>${window.__('Errore durante la ricerca')}</div>`;
            }
          }, 300);
        });
        document.addEventListener('click', function (e) {
          if (!searchInput.contains(e.target) && !resultsDiv.contains(e.target)) resultsDiv.classList.add('hidden');
        });
        searchInput.addEventListener('keydown', function (e) { if (e.key === 'Escape') { resultsDiv.classList.add('hidden'); searchInput.blur(); } });
      }
    }

    function initializeDarkMode() {
      const savedTheme = localStorage.getItem('theme') || 'light';
      if (savedTheme === 'dark') document.documentElement.classList.add('dark');
    }
    function toggleDarkMode() {
      if (document.documentElement.classList.contains('dark')) {
        document.documentElement.classList.remove('dark');
        localStorage.setItem('theme', 'light');
      } else {
        document.documentElement.classList.add('dark');
        localStorage.setItem('theme', 'dark');
      }
    }

    function initializeMobileMenu() {
      const mobileMenuButton = document.getElementById('mobile-menu-button');
      const closeMobileMenuButton = document.getElementById('close-mobile-menu');
      const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
      const sidebar = document.getElementById('sidebar');
      function openMobileMenu() {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        mobileMenuOverlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
      }
      function closeMobileMenu() {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
        mobileMenuOverlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      }
      if (mobileMenuButton) mobileMenuButton.addEventListener('click', openMobileMenu);
      if (closeMobileMenuButton) closeMobileMenuButton.addEventListener('click', closeMobileMenu);
      if (mobileMenuOverlay) mobileMenuOverlay.addEventListener('click', closeMobileMenu);
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMobileMenu(); });
      const navLinks = document.querySelectorAll('.nav-link');
      navLinks.forEach(link => {
        link.addEventListener('click', () => { if (window.innerWidth < 1024) setTimeout(closeMobileMenu, 150); });
      });
    }

    function initializeDropdowns() {
      const notificationsButton = document.getElementById('notifications-button');
      const notificationsDropdown = document.getElementById('notifications-dropdown');
      if (notificationsButton && notificationsDropdown) {
        notificationsButton.addEventListener('click', async function (e) {
          e.stopPropagation();
          notificationsDropdown.classList.toggle('hidden');
          if (!notificationsDropdown.classList.contains('hidden')) await loadNotifications();
          const userDropdown = document.getElementById('user-menu-dropdown');
          if (userDropdown) userDropdown.classList.add('hidden');
        });
      }
      if (window.userIsAdmin) loadNotificationCount();

      const userMenuButton = document.getElementById('user-menu-button');
      const userMenuDropdown = document.getElementById('user-menu-dropdown');
      const userMenuArrow = document.getElementById('user-menu-arrow');
      if (userMenuButton && userMenuDropdown) {
        userMenuButton.addEventListener('click', function (e) {
          e.stopPropagation();
          userMenuDropdown.classList.toggle('hidden');
          if (userMenuArrow) userMenuArrow.classList.toggle('rotate-180');
          if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
        });
      }
      document.addEventListener('click', function () {
        if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
        if (userMenuDropdown) userMenuDropdown.classList.add('hidden');
        if (userMenuArrow) userMenuArrow.classList.remove('rotate-180');
      });

      const mobileSearchButton = document.getElementById('mobile-search-button');
      const mobileSearchBar = document.getElementById('mobile-search-bar');
      const mobileSearchClose = document.getElementById('mobile-search-close');
      const mobileGlobalSearch = document.getElementById('mobile-global-search');
      const mobileSearchResults = document.getElementById('mobile-search-results');
      if (mobileSearchButton && mobileSearchBar) {
        mobileSearchButton.addEventListener('click', () => {
          mobileSearchBar.classList.remove('hidden');
          setTimeout(() => mobileGlobalSearch.focus(), 100);
        });
      }
      if (mobileSearchClose) {
        mobileSearchClose.addEventListener('click', () => {
          mobileSearchBar.classList.add('hidden');
          if (mobileGlobalSearch) mobileGlobalSearch.value = '';
          if (mobileSearchResults) mobileSearchResults.classList.add('hidden');
        });
      }
      if (mobileGlobalSearch && mobileSearchResults) {
        let mobileSearchTimeout;
        mobileGlobalSearch.addEventListener('input', function () {
          clearTimeout(mobileSearchTimeout);
          const query = this.value.trim();
          if (query.length < 2) { mobileSearchResults.classList.add('hidden'); return; }
          mobileSearchResults.innerHTML = `<div class="p-4 text-center"><i class="fas fa-spinner fa-spin text-gray-400"></i> <span class="ml-2 text-sm text-gray-500">${window.__('Ricerca in corso...')}</span></div>`;
          mobileSearchResults.classList.remove('hidden');
          mobileSearchTimeout = setTimeout(async () => {
            try {
              const response = await fetch(`${window.BASE_PATH}/api/search/unified?q=${encodeURIComponent(query)}`);
              const results = await response.json();
              let html = '';
              if (results.length > 0) {
                results.forEach(item => {
                  let iconClass = 'fas fa-question';
                  let iconColor = 'text-gray-500';
                  let identifierHtml = '';
                  const safeLabel = escapeHtml(String(item.label || item.title || ''));
                  const rawUrl = item.url ? String(item.url) : '';
                  const safeUrl = rawUrl && !/^javascript:/i.test(rawUrl) ? encodeURI(rawUrl) : '#';
                  const safeDescription = escapeHtml(String(item.description || ''));
                  switch (item.type) {
                    case 'book': iconClass = 'fas fa-book-open'; iconColor = 'text-blue-500'; break;
                    case 'author': iconClass = 'fas fa-user-edit'; iconColor = 'text-purple-500'; break;
                    case 'publisher': iconClass = 'fas fa-building'; iconColor = 'text-orange-500'; break;
                    case 'user': iconClass = 'fas fa-user'; iconColor = 'text-pink-500'; break;
                  }
                  html += `<a href="${safeUrl}" class="flex items-start gap-3 p-3 hover:bg-gray-50 rounded-lg text-sm transition-colors">
                      <div class="flex-shrink-0 w-5 flex items-center justify-center mt-1"><i class="${iconClass} ${iconColor}"></i></div>
                      <div class="flex-1 min-w-0"><div class="font-medium text-gray-900">${safeLabel}</div>${item.description ? `<div class="text-xs text-gray-500 mt-0.5">${safeDescription}</div>` : ''}${identifierHtml}</div>
                    </a>`;
                });
              } else {
                html = `<div class="p-4 text-center text-sm text-gray-500">${window.__('Nessun risultato trovato')}</div>`;
              }
              mobileSearchResults.innerHTML = html;
            } catch (error) {
              mobileSearchResults.innerHTML = `<div class="p-4 text-center text-sm text-red-500">${window.__('Errore durante la ricerca')}</div>`;
            }
          }, 300);
        });
      }
    }

    async function loadNotifications() {
      const list = document.getElementById('notifications-list');
      const empty = document.getElementById('notifications-empty');
      try {
        const response = await fetch(window.BASE_PATH + '/admin/notifications/recent?limit=5');
        if (!response.ok) throw new Error('Failed to load notifications');
        const data = await response.json();
        const notifications = data.notifications || [];
        if (notifications.length === 0) {
          if (empty) empty.classList.remove('hidden');
          list.innerHTML = '';
        } else {
          if (empty) empty.classList.add('hidden');
          list.innerHTML = '';
          notifications.forEach(notif => {
            const item = document.createElement('div');
            item.className = 'p-4 transition-colors border-b border-gray-100 last:border-0';
            let iconClass = 'fas fa-bell';
            let iconBg = 'bg-gray-100 text-gray-600';
            switch (notif.type) {
              case 'new_message': iconClass = 'fas fa-envelope'; iconBg = 'bg-blue-100 text-blue-600'; break;
              case 'new_reservation': iconClass = 'fas fa-book'; iconBg = 'bg-green-100 text-green-600'; break;
              case 'new_user': iconClass = 'fas fa-user-plus'; iconBg = 'bg-purple-100 text-purple-600'; break;
              case 'overdue_loan': iconClass = 'fas fa-exclamation-triangle'; iconBg = 'bg-red-100 text-red-600'; break;
              case 'new_loan_request': iconClass = 'fas fa-calendar-check'; iconBg = 'bg-orange-100 text-orange-600'; break;
              case 'new_review': iconClass = 'fas fa-star'; iconBg = 'bg-yellow-100 text-yellow-600'; break;
            }
            const isUnread = !notif.is_read;
            const hasLink = Boolean(notif.link) && !/^javascript:/i.test(String(notif.link));
            const basePath = window.BASE_PATH || '';
            const link = String(notif.link || '');
            const rawLink = hasLink ? (link.startsWith('http') ? link : (basePath && link.startsWith('/') && !link.startsWith(basePath + '/') && link !== basePath ? basePath + link : link)) : '';
            const escapedLink = hasLink ? escapeHtml(rawLink) : '';
            if (hasLink) {
              item.classList.add('cursor-pointer', 'hover:bg-gray-50', 'group');
              item.dataset.link = rawLink;
              item.tabIndex = 0;
              item.setAttribute('role', 'link');
            } else {
              item.classList.add('bg-white');
            }
            item.innerHTML = `
                <div class="flex items-start gap-3">
                  <div class="${iconBg} w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"><i class="${iconClass}"></i></div>
                  <div class="flex-1 min-w-0">
                    <div class="text-xs text-gray-500 font-medium uppercase tracking-wide mb-2">${notif.relative_time || formatNotificationTime(notif.created_at)}</div>
                    <p class="text-sm font-semibold text-gray-900 group-hover:text-gray-800 transition-colors">${escapeHtml(notif.title || '')}${isUnread ? '<span class="ml-1 inline-block w-2 h-2 bg-blue-500 rounded-full"></span>' : ''}</p>
                    <p class="text-xs text-gray-600 mt-1 group-hover:text-gray-700 transition-colors">${escapeHtml(notif.message || '')}</p>
                    ${hasLink ? `<div class="mt-3"><button type="button" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-semibold text-white bg-gray-900 rounded-lg shadow-sm hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-500/40" data-open-link="${escapedLink}"><i class="fas fa-external-link-alt text-[11px]"></i> ${window.__('Apri')}</button></div>` : ''}
                  </div>
                </div>
              `;
            if (hasLink) {
              const navigate = () => { window.location.href = rawLink; };
              item.addEventListener('click', navigate);
              item.addEventListener('keydown', event => { if (event.key === 'Enter' || event.key === ' ') { event.preventDefault(); navigate(); } });
              const button = item.querySelector('[data-open-link]');
              if (button) button.addEventListener('click', event => { event.stopPropagation(); navigate(); });
            }
            list.appendChild(item);
          });
        }
      } catch (error) { console.error('Error loading notifications:', error); if (empty) empty.classList.remove('hidden'); list.innerHTML = ''; }
    }

    async function loadNotificationCount() {
      try {
        const response = await fetch(window.BASE_PATH + '/admin/notifications/unread-count');
        if (response.ok) {
          const data = await response.json();
          const badge = document.getElementById('notifications-badge');
          if (badge) {
            const count = parseInt(data.count || 0, 10);
            if (count > 0) { badge.textContent = String(count); badge.classList.remove('hidden'); }
            else badge.classList.add('hidden');
          }
        }
      } catch (error) { console.error('Error loading notification count:', error); }
    }

    async function markAllNotificationsAsRead() {
      try {
        const response = await csrfFetch(window.BASE_PATH + '/admin/notifications/mark-all-read', { method: 'POST' });
        if (response.ok) { loadNotificationCount(); loadNotifications(); }
      } catch (error) { console.error('Error marking notifications as read:', error); }
    }

    function formatNotificationTime(dateString) {
      if (!dateString) return '-';
      const date = new Date(dateString);
      if (isNaN(date.getTime())) return '-';
      const now = new Date();
      const diffMs = now - date;
      const diffMins = Math.floor(diffMs / 60000);
      const diffHours = Math.floor(diffMs / 3600000);
      const diffDays = Math.floor(diffMs / 86400000);
      if (diffMins < 1) return window.__('Adesso');
      if (diffMins < 60) return `${diffMins} ${window.__('minuti fa')}`;
      if (diffHours < 24) return `${diffHours} ${window.__('ore fa')}`;
      if (diffDays === 1) return window.__('Ieri');
      return formatDateLocale(date);
    }

    async function loadQuickStats() {
      const isLogged = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;
      if (!isLogged) return;
      try {
        const booksResponse = await fetch(window.BASE_PATH + '/api/stats/books-count', { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' }, cache: 'no-store' });
        if (booksResponse.ok) {
          const booksData = await booksResponse.json();
          const booksCount = booksData.count || 0;
          const booksEl = document.getElementById('stats-books');
          const headerBooksEl = document.getElementById('header-books-count');
          if (booksEl) booksEl.textContent = booksCount.toLocaleString();
          if (headerBooksEl) headerBooksEl.textContent = booksCount.toLocaleString();
        }
        const loansResponse = await fetch(window.BASE_PATH + '/api/stats/active-loans-count', { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' }, cache: 'no-store' });
        if (loansResponse.ok) {
          const loansData = await loansResponse.json();
          const loansCount = loansData.count || 0;
          const loansEl = document.getElementById('stats-loans');
          const headerLoansEl = document.getElementById('header-loans-count');
          if (loansEl) loansEl.textContent = loansCount.toLocaleString();
          if (headerLoansEl) headerLoansEl.textContent = loansCount.toLocaleString();
        }
      } catch (error) { console.debug('Quick stats temporarily unavailable:', error.message); }
    }

    async function checkForUpdates() {
      if (!window.userIsAdmin) return;
      try {
        const response = await fetch(window.BASE_PATH + '/admin/updates/available', { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' }, cache: 'no-store' });
        if (response.ok) {
          const data = await response.json();
          const sidebarBadge = document.getElementById('sidebar-update-badge');
          if (data.available) { if (sidebarBadge) sidebarBadge.classList.remove('hidden'); }
          else { if (sidebarBadge) sidebarBadge.classList.add('hidden'); }
        }
      } catch (error) { console.debug('Update check temporarily unavailable:', error.message); }
    }

    function initializeKeyboardShortcuts() {
      let gPrefixActive = false;
      let gPrefixTimer = null;
      let basePath = window.BASE_PATH || '';
      const gNavMap = {
        d: basePath + '/admin/dashboard',
        b: basePath + '/admin/libri',
        a: basePath + '/admin/autori',
        e: basePath + '/admin/editori',
        p: basePath + '/admin/prestiti',
        u: basePath + '/admin/utenti',
        s: basePath + '/admin/settings'
      };
      const booksSection = document.getElementById('shortcuts-books-section');
      if (booksSection && window.location.pathname.indexOf('/admin/libri') !== -1) booksSection.classList.remove('hidden');
      const isMac = (navigator.userAgentData && navigator.userAgentData.platform === 'macOS') || (navigator.platform && navigator.platform.indexOf('Mac') !== -1);
      if (isMac) document.querySelectorAll('[data-mod-key]').forEach(el => el.textContent = '⌘');
      let lastShortcutsFocus = null;
      function openShortcutsModal() { const modal = document.getElementById('shortcuts-modal'); if (!modal) return; lastShortcutsFocus = document.activeElement; modal.classList.remove('hidden'); const close = document.getElementById('close-shortcuts'); if (close) close.focus(); }
      function closeShortcutsModal() { const modal = document.getElementById('shortcuts-modal'); if (!modal) return; modal.classList.add('hidden'); if (lastShortcutsFocus && typeof lastShortcutsFocus.focus === 'function') lastShortcutsFocus.focus(); }
      const helpBtn = document.getElementById('shortcuts-help');
      if (helpBtn) helpBtn.addEventListener('click', openShortcutsModal);
      const closeBtn = document.getElementById('close-shortcuts');
      if (closeBtn) closeBtn.addEventListener('click', closeShortcutsModal);
      const shortcutsModal = document.getElementById('shortcuts-modal');
      if (shortcutsModal) shortcutsModal.addEventListener('click', e => { if (e.target === this) closeShortcutsModal(); });
      document.addEventListener('keydown', function (e) {
        const tag = e.target.tagName;
        const isInput = (tag === 'INPUT' || tag === 'TEXTAREA' || tag === 'SELECT' || e.target.isContentEditable);
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') { e.preventDefault(); e.stopPropagation(); const searchInput = document.getElementById('global-search'); if (searchInput) { searchInput.focus(); searchInput.select(); } return; }
        if (e.key === 'Escape') {
          gPrefixActive = false; clearTimeout(gPrefixTimer);
          closeShortcutsModal();
          if (window.Swal && typeof window.Swal.close === 'function') window.Swal.close();
          const searchResults = document.getElementById('global-search-results'); if (searchResults && !searchResults.classList.contains('hidden')) searchResults.classList.add('hidden');
          const mobileSearchResults = document.getElementById('mobile-search-results'); if (mobileSearchResults && !mobileSearchResults.classList.contains('hidden')) mobileSearchResults.classList.add('hidden');
          const mobileSearchBar = document.getElementById('mobile-search-bar'); if (mobileSearchBar && !mobileSearchBar.classList.contains('hidden')) mobileSearchBar.classList.add('hidden');
          const notificationsDropdown = document.getElementById('notifications-dropdown'); if (notificationsDropdown && !notificationsDropdown.classList.contains('hidden')) notificationsDropdown.classList.add('hidden');
          const userMenuDropdown = document.getElementById('user-menu-dropdown'); if (userMenuDropdown && !userMenuDropdown.classList.contains('hidden')) userMenuDropdown.classList.add('hidden');
          if (document.activeElement && (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'BUTTON')) document.activeElement.blur();
          return;
        }
        if (isInput) return;
        if (e.key === '?' && !e.ctrlKey && !e.metaKey) { e.preventDefault(); gPrefixActive = false; clearTimeout(gPrefixTimer); openShortcutsModal(); return; }
        if (gPrefixActive) {
          const dest = gNavMap[e.key.toLowerCase()];
          if (dest) { e.preventDefault(); window.location.href = dest; }
          gPrefixActive = false;
          clearTimeout(gPrefixTimer);
          return;
        }
        if (e.key === 'g' && !e.ctrlKey && !e.metaKey && !e.altKey) { gPrefixActive = true; gPrefixTimer = setTimeout(() => { gPrefixActive = false; }, 1000); return; }
      });
    }

    function initializeActiveNavigation() {
      const currentPath = window.location.pathname;
      const basePath = window.BASE_PATH || '';
      const navLinks = document.querySelectorAll('.nav-link');
      navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (currentPath.startsWith(href) && href !== '/' && href !== basePath && href !== basePath + '/') {
          link.classList.add('bg-rose-50', 'text-rose-600', 'border-r-2', 'border-rose-600');
          link.classList.remove('text-gray-700');
        }
      });
    }

    document.addEventListener('DOMContentLoaded', function () {
      window.CSRF_TOKEN = <?= json_encode(App\Support\Csrf::ensureToken(), JSON_HEX_TAG); ?>;
      initializeGlobalSearch();
      initializeDarkMode();
      initializeMobileMenu();
      initializeActiveNavigation();
      initializeDropdowns();
      initializeKeyboardShortcuts();
      loadQuickStats();
      checkForUpdates();
      setInterval(loadQuickStats, 5 * 60 * 1000);
      setInterval(checkForUpdates, 60 * 60 * 1000);
    });

    (function () {
      const nativeAlert = typeof window.alert === 'function' ? window.alert.bind(window) : null;
      const alertTitle = <?= json_encode(__('Avviso'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
      const alertButton = <?= json_encode(__('OK'), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
      window.alert = function (message) {
        const text = (message === undefined || message === null) ? '' : String(message);
        if (window.Swal && typeof window.Swal.fire === 'function') {
          window.Swal.fire({ icon: 'info', title: alertTitle, text, confirmButtonText: alertButton });
        } else if (nativeAlert) { nativeAlert(text); }
      };
    })();
  </script>

  <?php do_action('assets.footer'); ?>
  <?php require __DIR__ . '/partials/scroll-to-top.php'; ?>

  <!-- Keyboard Shortcuts Modal -->
  <?php $kbdClass = 'px-2 py-1 bg-gray-50 border border-gray-300 rounded text-xs font-mono text-gray-900'; ?>
  <div id="shortcuts-modal" role="dialog" aria-modal="true" aria-labelledby="shortcuts-title" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4">
      <div class="p-4 border-b border-gray-200 flex items-center justify-between">
        <h3 id="shortcuts-title" class="font-semibold text-gray-900 flex items-center gap-2"><i class="fas fa-keyboard text-gray-900"></i> <?= __("Scorciatoie da tastiera") ?></h3>
        <button id="close-shortcuts" aria-label="<?= __('Chiudi') ?>" class="text-gray-400 hover:text-gray-600 transition-colors"><i class="fas fa-times"></i></button>
      </div>
      <div class="p-4 space-y-4">
        <div>
          <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><?= __("Navigazione") ?></h4>
          <div class="space-y-2">
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Cerca globale") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>" data-mod-key>Ctrl</kbd><kbd class="<?= $kbdClass ?>">K</kbd></div></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Mostra scorciatoie") ?></span><kbd class="<?= $kbdClass ?>">?</kbd></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Chiudi popup") ?></span><kbd class="<?= $kbdClass ?>">Esc</kbd></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Vai a Dashboard") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>">G</kbd><span class="text-gray-400 text-xs"><?= __("poi") ?></span><kbd class="<?= $kbdClass ?>">D</kbd></div></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Vai a Libri") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>">G</kbd><span class="text-gray-400 text-xs"><?= __("poi") ?></span><kbd class="<?= $kbdClass ?>">B</kbd></div></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Vai a Autori") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>">G</kbd><span class="text-gray-400 text-xs"><?= __("poi") ?></span><kbd class="<?= $kbdClass ?>">A</kbd></div></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Vai a Editori") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>">G</kbd><span class="text-gray-400 text-xs"><?= __("poi") ?></span><kbd class="<?= $kbdClass ?>">E</kbd></div></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Vai a Prestiti") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>">G</kbd><span class="text-gray-400 text-xs"><?= __("poi") ?></span><kbd class="<?= $kbdClass ?>">P</kbd></div></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Vai a Utenti") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>">G</kbd><span class="text-gray-400 text-xs"><?= __("poi") ?></span><kbd class="<?= $kbdClass ?>">U</kbd></div></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Vai a Impostazioni") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>">G</kbd><span class="text-gray-400 text-xs"><?= __("poi") ?></span><kbd class="<?= $kbdClass ?>">S</kbd></div></div>
          </div>
        </div>
        <div id="shortcuts-books-section" class="hidden">
          <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2"><?= __("Gestione Libri") ?></h4>
          <div class="space-y-2">
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Nuova ricerca") ?></span><kbd class="<?= $kbdClass ?>">/</kbd></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Nuovo libro") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>" data-mod-key>Ctrl</kbd><kbd class="<?= $kbdClass ?>">N</kbd></div></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Seleziona tutti") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>" data-mod-key>Ctrl</kbd><kbd class="<?= $kbdClass ?>">A</kbd></div></div>
            <div class="flex items-center justify-between text-sm"><span class="text-gray-600"><?= __("Cambia vista") ?></span><div class="flex gap-1"><kbd class="<?= $kbdClass ?>" data-mod-key>Ctrl</kbd><kbd class="<?= $kbdClass ?>">G</kbd></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
