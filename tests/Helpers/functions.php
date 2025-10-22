<?php

use Quantum\Service\Factories\ServiceFactory;
use Quantum\Model\Factories\ModelFactory;
use Quantum\App\Factories\AppFactory;
use Quantum\Libraries\Hasher\Hasher;
use Quantum\Module\ModuleManager;
use Shared\Services\PostService;
use Shared\Services\AuthService;
use Quantum\Router\Router;
use Shared\Models\User;
use Quantum\App\App;
use Faker\Factory;

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

function createApp(string $type, string $baseDir): App
{
    return AppFactory::create($type, $baseDir);
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

function createUser(array $overrides = [])
{
    return ServiceFactory::get(AuthService::class)->add(
        array_merge([
            'uuid' => uuid_ordered(),
            'firstname' => 'John',
            'lastname' => 'Doe',
            'role' => 'admin',
            'email' => 'default@quantumphp.io',
            'password' => (new Hasher())->hash('password'),
        ], $overrides));
}

function createUserPosts($user)
{
    $postCountPerUser = 10;

    $faker = Factory::create();

    for ($i = 0; $i < $postCountPerUser; $i++) {
        $title = textCleanUp($faker->realText(50));

        ServiceFactory::get(PostService::class)->addPost([
            'title' => $title,
            'content' => textCleanUp($faker->realText(100)),
            'image' => slugify($title) . '.jpg',
            'user_uuid' => $user->uuid,
        ]);
    }
}

function deleteUserByEmail(string $email)
{
    ModelFactory::get(User::class)
        ->findOneBy('email', $email)
        ->delete();
}

function dbCleanUp()
{
    ServiceFactory::get(AuthService::class)->deleteTable();
    ServiceFactory::get(PostService::class)->deleteTable();
}