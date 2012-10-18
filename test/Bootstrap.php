<?php

/** Zend_Application */
require_once 'Zend/Application.php';

//require_once __DIR__ . "/SchemaManager.php";
//require_once __DIR__ . '/../tests/TestCase.php';
//require_once __DIR__ . '/../tests/ServiceTestCase.php';

//require_once('./application/EcampTestCase.php');
$application = new Zend_Application(
    APPLICATION_ENV,
	APPLICATION_PATH . '/configs/application.ini'
);

clearstatcache();