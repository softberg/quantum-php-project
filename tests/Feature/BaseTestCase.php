<?php

namespace Tests\Feature;

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
	protected HttpClient $client;

	protected static bool $isDatabasePrepared = false;

	protected string $baseUrl;

	public function setUp(): void
	{
		parent::setUp();
		ob_start();

//		if (!self::$isDatabasePrepared) {
//			shell_exec('php qt test_data:create');
//			self::$isDatabasePrepared = true;
//		}
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
				Request::setHeader($name, $value);
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
	}

	protected function loadAppFunctionality()
	{
		App::setBaseDir(dirname(__DIR__, 2));
		App::loadCoreFunctions(dirname(__DIR__, 2) . DS . 'vendor' . DS . 'quantum' . DS . 'framework' . DS . 'src' . DS . 'Helpers');
		Di::loadDefinitions();
		Environment::getInstance()->load(new Setup('config', 'env'));
	}
}

