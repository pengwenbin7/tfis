<?php

require("vendor/autoload.php");
require("src/helper.php");
define("APP_ROOT", dirname(__FILE__));

$app = \Tfis\Application::app(APP_ROOT . "/conf/app.ini");
$app->run();
