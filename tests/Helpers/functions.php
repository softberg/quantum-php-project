<?php

use Quantum\Service\Factories\ServiceFactory;
use Quantum\Model\Factories\ModelFactory;
use Quantum\App\Factories\AppFactory;
use Shared\Services\CommentService;
use Quantum\Auth\User as AuthUser;
use Quantum\Module\ModuleManager;
use Quantum\Router\MatchedRoute;
use Shared\Services\PostService;
use Shared\Services\AuthService;
use Shared\DTOs\CommentDTO;
use Quantum\Hasher\Hasher;
use Shared\DTOs\PostDTO;
use Quantum\Router\Route;
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
    AppFactory::destroy($type);
    return AppFactory::create($type, $baseDir);
}

function createModule(string $moduleName, string $template, bool $withAssets = false)
{
    ob_start();

    $moduleManager = new ModuleManager($moduleName, $template, 'yes', $withAssets);

    $moduleManager->writeContents();

    $moduleManager->addModuleConfig();

    $route = new Route(['GET'], 'dummy', null, null, function () {
    });
    $route->module($moduleName);
    $matchedRoute = new MatchedRoute($route, []);
    request()->setMatchedRoute($matchedRoute);

    ob_end_clean();
}

function removeModule()
{
    deleteDirectory(uploads_dir());
    deleteDirectory(modules_dir());

    if (!is_dir(uploads_dir())) {
        mkdir(uploads_dir(), 0777, true);
    }

    if (!file_exists(uploads_dir() . DS . '.gitkeep')) {
        file_put_contents(uploads_dir() . DS . '.gitkeep', '');
    }

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

    $files = array_diff(scandir($dir), ['.', '..']);

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
        ], $overrides)
    );
}

function createUserPosts(AuthUser $user): array
{
    $postCountPerUser = 10;

    $faker = Factory::create();

    $posts = [];

    for ($i = 0; $i < $postCountPerUser; $i++) {
        $title = textCleanUp($faker->realText(50));

        $posts[] = ServiceFactory::create(PostService::class)->addPost(new PostDTO(
            $title,
            textCleanUp($faker->realText(100)),
            $user->uuid,
            slugify($title) . '.jpg'
        ));
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
            $comments[] = ServiceFactory::create(CommentService::class)->addComment(new CommentDTO(
                $post->uuid,
                $user->uuid,
                textCleanUp($faker->realText(rand(20, 100)))
            ));
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
