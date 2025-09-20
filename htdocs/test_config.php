<?php
define('INITIALIZED', true);
include 'config/config.php';
global $config;

echo "Config carregado:\n";
if (isset($config['server'])) {
    echo "✓ \$config['server'] existe\n";
    print_r($config['server']);
} else {
    echo "✗ \$config['server'] NÃO existe\n";
    echo "Chaves disponíveis: " . implode(', ', array_keys($config)) . "\n";
}
?>
