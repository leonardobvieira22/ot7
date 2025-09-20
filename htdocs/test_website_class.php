<?php
define('INITIALIZED', true);
include 'config/config.php';
include 'system/load.init.php';

echo "Testando classe Website...\n";
if (class_exists('Website')) {
    echo "✓ Classe Website encontrada\n";
    try {
        $serverConfig = Website::getServerConfig();
        echo "✓ ServerConfig carregado\n";
    } catch (Exception $e) {
        echo "✗ ERRO ServerConfig: " . $e->getMessage() . "\n";
    }
} else {
    echo "✗ Classe Website NÃO encontrada\n";
}
?>
