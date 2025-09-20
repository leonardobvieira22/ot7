<?php
if (!defined('INITIALIZED'))
        exit;

$time_start = microtime(TRUE);

// Load website configuration
include_once("./config/config.php");

session_start();

function autoLoadClass($className)
{
        if (!class_exists($className)) {
                if (file_exists('./classes/' . strtolower($className) . '.php')) {
                        include_once('./classes/' . strtolower($className) . '.php');
                } else {
                        // Ignorar erro para TwoFactorAuth temporariamente
                        if (strpos($className, 'TwoFactorAuth') === false) {
                                throw new RuntimeException('#E-7 -Cannot load class <b>' . $className . '</b>, file <b>./classes/class.' . strtolower($className) . '.php</b> doesn\'t exist');
                        }
                }
        }
}

spl_autoload_register('autoLoadClass');
