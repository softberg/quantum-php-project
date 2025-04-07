<?php

use Quantum\Libraries\Module\ModuleManager;
use Quantum\App\Factories\AppFactory;
use Quantum\Router\Router;
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

function createModule(string $moduleName, string $template, bool $withAssets = false)
{
    ob_start();

    $moduleManager = new ModuleManager($moduleName, $template, 'yes', $withAssets);

    $moduleManager->writeContents();

    $moduleManager->addModuleConfig();

    Router::setCurrentRoute(['module' => $moduleName]);

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