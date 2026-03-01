<?php
// Arquivo temporário de diagnóstico - DELETAR após uso
echo "<h3>Diagnóstico completo</h3>";

// 1. Route cache existe?
echo "<h4>1. Route cache:</h4>";
$cacheFile = __DIR__ . '/../bootstrap/cache/routes-v7.php';
echo file_exists($cacheFile)
    ? '<p style="color:red">EXISTE route cache! Isso sobrescreve web.php</p>'
    : '<p style="color:green">Sem route cache</p>';

// 2. Testar resolução de rota via Laravel
echo "<h4>2. Teste de rota via Laravel:</h4>";
try {
    require __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

    $request = Illuminate\Http\Request::create('/mercado-pago/link-account', 'GET');
    $route = $app['router']->getRoutes()->match($request);

    echo "<pre>Rota encontrada: " . htmlspecialchars($route->uri()) . "</pre>";
    echo "<pre>Action: " . htmlspecialchars($route->getActionName()) . "</pre>";
    echo "<pre>Middleware: " . htmlspecialchars(implode(', ', $route->gatherMiddleware())) . "</pre>";
} catch (Exception $e) {
    echo "<pre style='color:red'>Erro: " . htmlspecialchars($e->getMessage()) . "</pre>";
}

// 3. Verificar auth.php por rotas conflitantes
echo "<h4>3. auth.php - rotas com redirect:</h4>";
$auth = file_get_contents(__DIR__ . '/../routes/auth.php');
foreach (explode("\n", $auth) as $i => $line) {
    if (stripos($line, 'redirect') !== false || stripos($line, 'home') !== false || stripos($line, "'/'" ) !== false) {
        echo "<pre>Linha " . ($i + 1) . ": " . htmlspecialchars(trim($line)) . "</pre>";
    }
}
