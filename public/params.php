<?php
if (!defined('ROOT_PATH')) {
define('ROOT_PATH', dirname(__DIR__));
}
if (!defined('MYSQL_HOST')) {
define('MYSQL_HOST', 'localhost');
}
if (!defined('MYSQL_USER')) {
define('MYSQL_USER', 'root');
}
if (!defined('MYSQL_PASSWORD')) {
define('MYSQL_PASSWORD', '111');
}
if (!defined('MYSQL_DB')) {
define('MYSQL_DB', 'adv-avtomoder');
}

spl_autoload_register(function ($class) {
    $file = ROOT_PATH . '/' . str_replace("\\", "/", $class) . '.php';
    if(!is_readable($file)){
        return false;
    }
    require_once $file;
});

