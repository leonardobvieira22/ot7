<?php
date_default_timezone_set('America/Sao_Paulo');

error_reporting(E_ALL ^ E_STRICT ^ E_NOTICE);

require __DIR__ . '/vendor/autoload.php';

define('DEBUG_DATABASE', false);
define('INITIALIZED', true);
if (!defined('ONLY_PAGE'))
        define('ONLY_PAGE', false);
define('AJAXREQUEST', false);

include_once('./system/load.loadCheck.php');
include_once('./system/load.init.php');

echo "Antes de carregar load.database.php:\n";
global $config;
if (isset($config['server'])) {
    echo "✓ \$config['server'] existe\n";
} else {
    echo "✗ \$config['server'] NÃO existe\n";
}

// Agora vamos tentar carregar o load.database.php
echo "\nCarregando load.database.php...\n";
include_once('./system/load.database.php');
echo "✓ load.database.php carregado com sucesso!\n";
?>
