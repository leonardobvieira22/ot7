<?php
define('INITIALIZED', true);
include 'config/config.php';
include 'system/load.init.php';

echo "Testando conexão com banco...\n";
try {
    include 'system/load.database.php';
    $db = Website::getDBHandle();
    if ($db) {
        echo "✓ Conexão com banco estabelecida!\n";
    } else {
        echo "✗ Falha na conexão com banco\n";
    }
} catch (Exception $e) {
    echo "✗ ERRO: " . $e->getMessage() . "\n";
}
?>
