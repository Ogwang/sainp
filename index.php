<?php
define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('BASE_PATH', realpath(dirname(__FILE__) . '/../'));
define('APPLICATION_PATH', '/application');

include 'application/Bootstrap.php';
Bootstrap::run();