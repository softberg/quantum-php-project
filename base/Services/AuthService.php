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
 * @since 1.9.0
 */

namespace Base\Services;

use Quantum\Libraries\Auth\AuthServiceInterface;
use Quantum\Exceptions\ExceptionMessages;
use Quantum\Libraries\Storage\FileSystem;
use Quantum\Mvc\Qt_Service;
use Quantum\Loader\Loader;

/**
 *
 */
class AuthService extends Qt_Service implements AuthServiceInterface
{

    protected static $users = [];
    protected $userRepository = 'base' . DS . 'repositories' . DS . 'users.php';

    public function __init()
    {
        $loaderSetup = (object) [
            'module' => current_module(),
            'env' => 'base/repositories',
            'fileName' => 'users',
            'exceptionMessage' => ExceptionMessages::CONFIG_FILE_NOT_FOUND
        ];

        $loader = new Loader($loaderSetup);

        self::$users = is_array($loader->load()) ? $loader->load() : [];
    }

    public function getFields()
    {
        return [
            'username',
            'firstname',
            'lastname',
            'role'
        ];
    }

    public function getVisibleFields()
    {
        return [
            'username',
            'firstname',
            'lastname',
            'role'
        ];
    }

    public function getDefinedKeys()
    {
        return [
            'usernameKey' => 'username',
            'passwordKey' => 'password',
            'activationTokenKey' => 'activation_token',
            'rememberTokenKey' => 'remember_token',
            'resetTokenKey' => 'reset_token',
            'accessTokenKey' => 'access_token',
            'refreshTokenKey' => 'refresh_token',
        ];
    }

    public function get($field, $value): array
    {
        if ($value) {
            foreach (self::$users as $user) {
                if (in_array($value, $user)) {
                    return $user;
                }
            }
        }
        return [];
    }

    public function add($data)
    {
        $user = [];
        $allFields = array_merge($this->getFields(), array_values($this->getDefinedKeys()));
        foreach ($allFields as $field) {
            $user[$field] = $data[$field] ?? '';
        }

        if (count(self::$users) > 0) {
            array_push(self::$users, $user);
        } else {
            self::$users[1] = $user;
        }

        $this->persist();
        
        return $user;
    }

    public function update($field, $value, $data)
    {
        $allFields = array_merge($this->getFields(), array_values($this->getDefinedKeys()));

        if ($value) {
            foreach (self::$users as &$user) {
                if (in_array($value, $user)) {
                    foreach ($data as $key => $val) {
                        if (in_array($key, $allFields)) {
                            $user[$key] = $data[$key] ?? '';
                        }
                    }
                }
            }
        }

        $this->persist();
    }

    private function persist()
    {
        $fileSystem = new FileSystem();
        $file = base_dir() . DS . $this->userRepository;
        if ($fileSystem->exists($file)) {
            $content = '<?php' . PHP_EOL . PHP_EOL . 'return ' . var_export(self::$users, true) . ';';
            $fileSystem->put($file, $content);
        } else {
            throw new \Exception(_message(ExceptionMessages::CONFIG_FILE_NOT_FOUND, $file));
        }
    }

}
