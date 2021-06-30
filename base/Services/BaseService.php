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
 * @since 2.2.0
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
     * Persists the changes
     * @param string $file
     * @param array $collection
     * @throws \Quantum\Exceptions\DiException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    protected function persist(string $file, array $collection)
    {
        $fs = Di::get(FileSystem::class);

        if ($fs->exists($file)) {
            $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' . export($collection) . ';';
            $fs->put($file, $content);
        } else {
            throw new \Exception(_message(ConfigException::CONFIG_FILE_NOT_FOUND, $file));
        }
    }

}
