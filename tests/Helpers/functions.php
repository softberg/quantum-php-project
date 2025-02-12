<?php

use Quantum\Libraries\Module\ModuleManager;
use Quantum\App\Factories\AppFactory;
use Quantum\App\App;

function createEnvFile()
{
    if (!file_exists(PROJECT_ROOT . DS . '.env.testing')) {
        copy(
            PROJECT_ROOT . DS . '.env.example',
            PROJECT_ROOT . DS . '.env.testing'
        );
    }
}

function removeEnvFile()
{
    if (file_exists(PROJECT_ROOT . DS . '.env.testing')) {
        unlink(PROJECT_ROOT . DS . '.env.testing');
    }
}

function createApp() {
    return AppFactory::create(App::WEB, dirname(__DIR__) . DS . '_root');
}

function createModule(string $moduleName, string $template)
{
    ob_start();

    $moduleManager = ModuleManager::createInstance(
        $moduleName,
        $template,
        'yes',
        true
    );

    $moduleManager->addModuleConfig();

    $moduleManager->writeContents();

    ob_end_clean();
}

function removeModule()
{
    deleteDirectory(uploads_dir());
    deleteDirectory(modules_dir());

    file_put_contents(
        base_dir() . DS . 'shared' . DS . 'config' . DS . 'modules.php',
        "<?php\n\nreturn " . export([]) . ";\n"
    );
}

function deleteDirectory(string $dir)
{
    if (!is_dir($dir)) {
        return;
    }

    $files = array_diff(scandir($dir), array('.', '..'));

    foreach ($files as $file) {
        $path = $dir . DS . $file;
        is_dir($path) ? deleteDirectory($path) : unlink($path);
    }

    if ($dir != uploads_dir()) {
        rmdir($dir);
    }
}