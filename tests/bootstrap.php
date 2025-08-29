<?php

use Quantum\App\App;

error_reporting(E_ALL | E_STRICT);

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

if (!defined('PROJECT_ROOT')) define("PROJECT_ROOT", __DIR__ . DS . '_root');

require_once dirname(__DIR__) . DS . 'vendor' . DS . 'autoload.php';

require_once __DIR__ . DS . 'Helpers' . DS . 'functions.php';

createEnvFile();


$GLOBALS['app'] = createApp(App::CONSOLE);

createModule('Api', 'DemoApi');

$user = createUser();
createUserPosts($user);

register_shutdown_function(function () {
    removeEnvFile();
    removeModule();
    dbCleanUp();
});


