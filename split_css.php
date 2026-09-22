<?php

$modules = ['teknis', 'umum', 'ppk'];

foreach ($modules as $mod) {
    $file = "resources/css/{$mod}.css";
    if (!file_exists($file)) continue;
    
    // Read lines
    $lines = file($file);
    if ($lines === false) continue;
    
    // Windows might have \r\n, normalize
    $content = file_get_contents($file);
    
    $layoutEnd = strpos($content, "/* =========================\n   STATISTICS");
    if ($layoutEnd === false) $layoutEnd = strpos($content, "/* =========================\r\n   STATISTICS");
    
    $tableStart = strpos($content, "/* =========================\n   TABLE");
    if ($tableStart === false) $tableStart = strpos($content, "/* =========================\r\n   TABLE");
    
    $workflowStart = strpos($content, "/* =========================\n   WORKFLOW");
    if ($workflowStart === false) $workflowStart = strpos($content, "/* =========================\r\n   WORKFLOW");
    
    $responsiveStart = strpos($content, "/* =========================\n   RESPONSIVE");
    if ($responsiveStart === false) $responsiveStart = strpos($content, "/* =========================\r\n   RESPONSIVE");
    
    if ($layoutEnd === false || $tableStart === false || $workflowStart === false || $responsiveStart === false) {
        echo "Could not find all sections in {$mod}.css\n";
        continue;
    }
    
    $layoutContent = substr($content, 0, $layoutEnd);
    $responsiveContent = substr($content, $responsiveStart);
    
    file_put_contents("resources/css/{$mod}/layout.css", $layoutContent . "\n" . $responsiveContent);
    
    $dashboardContent = substr($content, $layoutEnd, $tableStart - $layoutEnd);
    file_put_contents("resources/css/{$mod}/dashboard.css", $dashboardContent);
    
    $riwayatContent = substr($content, $tableStart, $workflowStart - $tableStart);
    file_put_contents("resources/css/{$mod}/riwayat.css", $riwayatContent);
    
    $spjContent = substr($content, $workflowStart, $responsiveStart - $workflowStart);
    file_put_contents("resources/css/{$mod}/spj.css", $spjContent);
    
    file_put_contents("resources/css/{$mod}/profil.css", "/* Profil CSS untuk {$mod} */\n");
    
    echo "Splitted {$mod}.css\n";
}
