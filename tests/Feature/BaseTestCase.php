<?php

namespace Quantum\Tests\Feature;

use Quantum\Libraries\Curl\HttpClient;
use Quantum\Environment\Environment;
use Quantum\Libraries\Config\Config;
use PHPUnit\Framework\TestCase;
use Quantum\Http\Response;
use Quantum\Http\Request;
use Quantum\Loader\Setup;
use Quantum\Di\Di;
use Quantum\App;

class BaseTestCase extends TestCase
{
	/**
	 * @var HttpClient
	 */
	protected $client;

	/**
	 * @var string
	 */
	protected $baseUrl;

	public function setUp(): void
	{
		parent::setUp();
		ob_start();
		$this->loadAppFunctionality();
		TestData::createUserData();
		TestData::createPostData();
	}

	public function request(string $method, string $url, array $options = [], array $headers = [], string $contentType = 'application/x-www-form-urlencoded'): Response
	{
		$_SERVER['REQUEST_METHOD'] = strtoupper($method);
		$_SERVER['CONTENT_TYPE'] = $contentType;
		$_SERVER['REQUEST_URI'] = $url;
		$_SERVER['SERVER_NAME'] = '127.0.0.1';
		$_SERVER['SERVER_PORT'] = '8000';

		if (!empty($headers)) {
			foreach ($headers as $name => $value) {
				$_SERVER['HTTP_' . strtoupper($name)] = $value;
			}
		}

		Request::create(strtoupper($method), $url, $options);

		Config::getInstance()->flush();
		App::start(dirname(__DIR__, 2));

		return new Response();
	}

	public function tearDown(): void
	{
		parent::tearDown();
		ob_end_clean();
		TestData::deleteUserData();
		TestData::deletePostData();

		$this->deleteDirectory(uploads_dir());
	}

	private function loadAppFunctionality()
	{
		App::setBaseDir(dirname(__DIR__, 2));
		App::loadCoreFunctions(dirname(__DIR__, 2) . DS . 'vendor' . DS . 'quantum' . DS . 'framework' . DS . 'src' . DS . 'Helpers');
		Di::loadDefinitions();

		Environment::getInstance()->load(new Setup('config', 'env'));
	}

	private function deleteDirectory(string $dir)
	{
		if (!is_dir($dir)) {
			return;
		}

		$files = array_diff(scandir($dir), array('.', '..', '.gitkeep'));

		foreach ($files as $file) {
			$path = "$dir/$file";
			if (is_dir($path)) {
				$this->deleteDirectory($path);
			} else {
				unlink($path);
			}
		}

		if ($dir != uploads_dir()) {
			rmdir($dir);
		}
	}
}

