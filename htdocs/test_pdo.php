<?php
define('INITIALIZED', true);
include 'config/config.php';
include 'system/load.init.php';
include 'system/load.database.php';

echo "Testando PDO...\n";
try {
    if (isset($SQL) && method_exists($SQL, 'query')) {
        echo "✓ \$SQL está disponível e tem método query\n";
        echo "Tipo do \$SQL: " . get_class($SQL) . "\n";
        
        // Testar uma query simples
        $result = $SQL->query("SELECT 1 as test");
        if ($result) {
            echo "✓ Query PDO executada com sucesso\n";
        } else {
            echo "✗ Falha na query PDO\n";
        }
    } else {
        echo "✗ \$SQL não está disponível ou não tem método query\n";
    }
} catch (Exception $e) {
    echo "✗ ERRO PDO: " . $e->getMessage() . "\n";
}
?>
