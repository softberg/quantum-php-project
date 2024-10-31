<?php

namespace Quantum\Tests\Feature;

use Quantum\Libraries\Hasher\Hasher;
use Quantum\Factory\ServiceFactory;
use Shared\Services\AuthService;
use Shared\Services\PostService;
use Faker\Generator;
use Faker\Factory;

class TestData
{
	protected static Generator $faker;

	protected static string $email = 'test@test.test';
	protected static string $role = 'editor';
	protected static string $password = 'password';
	public static function createUserData()
	{
		self::$faker = Factory::create();
		$authService = ServiceFactory::get(AuthService::class);

		$user = [
			'firstname' => self::$faker->firstName,
			'lastname' => self::$faker->lastName,
			'role' => self::$role,
			'email' => self::$email,
			'password' => (new Hasher())->hash(self::$password),
		];

		$authService->add($user);
	}

	public static function createPostData()
	{
		$users = ServiceFactory::get(AuthService::class)->getAll();
		$postService = ServiceFactory::get(PostService::class);

		foreach ($users as $user){
			for ($i = 0; $i < 10; $i++){
				$title = str_replace(['"', '\'', '-'], '', self::$faker->realText(50));

				$postData = [
					'title' => $title,
					'content' => str_replace(['"', '\'', '-'], '', self::$faker->realText(100)),
					'image' => slugify($title) . '.jpg',
					'user_id' => $user->id,
				];

				$postService->addPost($postData);
			}

		}
	}

	public static function deleteUserData()
	{
		ServiceFactory::get(AuthService::class)->deleteTable();
	}

	public static function deletePostData()
	{
		ServiceFactory::get(PostService::class)->deleteTable();
	}
}