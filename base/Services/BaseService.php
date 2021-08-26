<?php

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 2.5.0
 */

namespace Base\Services;

use Quantum\Exceptions\ConfigException;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Mvc\QtService;
use Quantum\Di\Di;

/**
 * Class BaseService
 * @package Base\Services
 */
class BaseService extends QtService
{

    /**
     * @var string|null
     */
    protected $repository = null;

    /**
     * Persists the changes
     * @param array $collection
     * @throws \Quantum\Exceptions\DiException
     * @throws \ReflectionException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     * @throws \Exception
     */
    protected function persist(array $collection)
    {
        $fs = Di::get(FileSystem::class);

        if ($fs->exists($this->repository)) {
            $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' . export($collection) . ';';
            $fs->put($this->repository, $content);
        } else {
            throw new \Exception(_message(ConfigException::CONFIG_FILE_NOT_FOUND, $this->repository));
        }
    }

}
