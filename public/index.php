<?php

require_once '../vendor/autoload.php';

use Application\Controllers\ApplicationController;

$app = new ApplicationController();
$app->Start();

