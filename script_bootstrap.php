<?php
/**
 * Bootstrap for scripts
 */
error_reporting(E_ALL);
date_default_timezone_set('Australia/Sydney');

// accept the first argument as the application environment, if any
$application_env = isset($argv[1]) ? $argv[1] : 'production';

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : $application_env));

// Define constants and server paths
define('PS', PATH_SEPARATOR);
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_PATH', ROOT . '/application');
set_include_path(
	get_include_path()
	. PS . APPLICATION_PATH . '/../library'
	. PS . APPLICATION_PATH . '/models'
	. PS . APPLICATION_PATH . '/views/scripts'
);

/** Autoloader */
require_once 'Zend/Loader.php';
require_once 'Base.php';
Zend_Loader::loadClass('Zend_Loader_Autoloader');
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->registerNamespace('Base_');
$autoloader->registerNamespace('Modules_');

/** Config */
$config = new Zend_Config_Ini(APPLICATION_PATH . '/config/config.ini', APPLICATION_ENV);

/** Database */
$db = Zend_Db::factory($config->database->adapter, $config->database->params->toArray());
Zend_Db_Table::setDefaultAdapter($db);
Zend_Registry::set('db', $db);

