<?php
define('INITIALIZED', true);
include 'config/config.php';
include 'system/load.init.php';

echo "Testando configuração final...\n";
if (isset($config['server']['serverName'])) {
    echo "✓ serverName: " . $config['server']['serverName'] . "\n";
} else {
    echo "✗ serverName NÃO encontrado\n";
}

if (isset($config['server']['mysqlHost'])) {
    echo "✓ mysqlHost: " . $config['server']['mysqlHost'] . "\n";
} else {
    echo "✗ mysqlHost NÃO encontrado\n";
}
?>
