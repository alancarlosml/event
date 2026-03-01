<?php
// Arquivo temporário de diagnóstico - DELETAR após uso
echo "<h3>Diagnóstico de arquivos</h3>";

echo "<h4>1. routes/web.php - linha da rota mercado-pago:</h4>";
$web = file_get_contents(__DIR__ . '/../routes/web.php');
foreach (explode("\n", $web) as $i => $line) {
    if (stripos($line, 'mercado-pago') !== false && stripos($line, 'link-account') !== false) {
        echo "<pre>Linha " . ($i + 1) . ": " . htmlspecialchars($line) . "</pre>";
    }
}

echo "<h4>2. MercadoPagoController.php - tem log diagnóstico?</h4>";
$ctrl = file_get_contents(__DIR__ . '/../app/Http/Controllers/MercadoPagoController.php');
echo strpos($ctrl, 'MercadoPago OAuth callback received') !== false
    ? '<p style="color:green">SIM - controller atualizado</p>'
    : '<p style="color:red">NAO - controller antigo</p>';

echo "<h4>3. Middleware na rota:</h4>";
echo strpos($web, "link-account')->middleware") !== false
    ? '<p style="color:red">AINDA TEM middleware na rota</p>'
    : '<p style="color:green">Middleware removido OK</p>';
