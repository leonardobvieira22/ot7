<?php
define('INITIALIZED', true);
include 'config/config.php';
include 'classes/configphp.php';
include 'classes/website.php';

echo "Testando Website::getServerConfig()...\n";
try {
    $serverConfig = Website::getServerConfig();
    echo "✓ Server config carregado com sucesso!\n";
    echo "mysqlHost: " . $serverConfig->getValue('mysqlHost') . "\n";
    echo "mysqlUser: " . $serverConfig->getValue('mysqlUser') . "\n";
    echo "mysqlDatabase: " . $serverConfig->getValue('mysqlDatabase') . "\n";
} catch (Exception $e) {
    echo "✗ ERRO: " . $e->getMessage() . "\n";
}
?>
