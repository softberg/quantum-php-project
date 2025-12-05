<?php

use Quantum\Libraries\Auth\User as AuthUser;
use Quantum\Service\Factories\ServiceFactory;
use Quantum\Model\Factories\ModelFactory;
use Quantum\App\Factories\AppFactory;
use Quantum\Libraries\Hasher\Hasher;
use Shared\Services\CommentService;
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

function createUser(array $overrides = []): AuthUser
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

function createUserPosts(AuthUser $user): array
{
    $postCountPerUser = 10;

    $faker = Factory::create();

    $posts = [];

    for ($i = 0; $i < $postCountPerUser; $i++) {
        $title = textCleanUp($faker->realText(50));

        $posts[] = ServiceFactory::create(PostService::class)->addPost([
            'title' => $title,
            'content' => textCleanUp($faker->realText(100)),
            'image' => slugify($title) . '.jpg',
            'user_uuid' => $user->uuid,
        ]);
    }

    return $posts;
}

function createPostComments(AuthUser $user, array $posts): array
{
    $commentCountPerUser = 3;

    $faker = Factory::create();

    $comments = [];

    foreach ($posts as $post) {
        for ($i = 0; $i < $commentCountPerUser; $i++) {
            $comments[] = ServiceFactory::create(CommentService::class)->addComment([
                'post_uuid' => $post->uuid,
                'user_uuid' => $user->uuid,
                'content'   => textCleanUp($faker->realText(rand(20, 100))),
            ]);
        }
    }

    return $comments;
}

function deleteUserByEmail(string $email)
{
    ModelFactory::get(User::class)
        ->findOneBy('email', $email)
        ->delete();
}

function dbCleanUp()
{
    ServiceFactory::get(AuthService::class)->deleteAllUsers();
    ServiceFactory::get(PostService::class)->deleteAllPosts();
    ServiceFactory::get(CommentService::class)->deleteAllComments();
}