<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

defined('TEST_PATH')
    || define('TEST_PATH', realpath(dirname(__FILE__) . '/../test'));

// Define application environment
define('APPLICATION_ENV', 'testing');

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
	realpath(APPLICATION_PATH . '/../library/PHPUnit'),
	get_include_path(),
)));

require_once 'PHPUnit/Autoload.php';


$configFile = TEST_PATH . "/configs/phpunit.xml";


$configuration = PHPUnit_Util_Configuration::getInstance($configFile);
$configuration->handlePHPConfiguration();

$phpunit = $configuration->getPHPUnitConfiguration();
isset($phpunit['bootstrap']) && 
    PHPUnit_Util_Fileloader::load($phpunit['bootstrap']);

$testSuite = $configuration->getTestSuiteConfiguration();

$arguments = array(
	'listeners' => array(new PHPUnit_Util_Log_JUnit()),
	'configuration' => $configFile);


echo "<pre>";
PHPUnit_TextUI_TestRunner::run($testSuite, $arguments);
echo "</pre>";

