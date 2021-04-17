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
 * @since 2.0.0
 */

namespace Base\Services;

use Quantum\Libraries\Auth\AuthServiceInterface;
use Quantum\Exceptions\ConfigException;
use Quantum\Loader\Loader;

/**
 * Class AuthService
 * @package Base\Services
 */
class AuthService extends BaseService implements AuthServiceInterface
{

    /**
     * Users
     * @var array
     */
    protected static $users = [];

    /**
     * Path to users repository
     * @var string
     */
    protected $userRepository = 'base' . DS . 'repositories' . DS . 'users.php';

    /**
     * Init
     * @param Loader $loader
     * @throws \Exception
     */
    public function __init(Loader $loader)
    {
        $loaderSetup = (object) [
                    'module' => null,
                    'hierarchical' => true,
                    'env' => 'base' . DS . 'repositories',
                    'fileName' => 'users',
                    'exceptionMessage' => ConfigException::CONFIG_FILE_NOT_FOUND
        ];

        self::$users = $loader->setup($loaderSetup)->load();
    }

    /**
     * Auth object fields
     * @return array
     */
    public function getFields()
    {

        return [
            'email',
            'firstname',
            'lastname',
            'role'
        ];
    }

    /**
     * Visible fields of Auth object
     * @return array|mixed
     */
    public function getVisibleFields()
    {
        return [
            'email',
            'firstname',
            'lastname',
            'role'
        ];
    }

    /**
     * Key map
     * @return array
     */
    public function getDefinedKeys()
    {
        return [
            'usernameKey' => 'email',
            'passwordKey' => 'password',
            'activationTokenKey' => 'activation_token',
            'rememberTokenKey' => 'remember_token',
            'resetTokenKey' => 'reset_token',
            'accessTokenKey' => 'access_token',
            'refreshTokenKey' => 'refresh_token',
            'otpKey' => 'otp',
            'otpExpiryKey' => 'otp_expires',
            'otpTokenKey' => 'otp_token'
        ];
    }

    /**
     * Get
     * @param string $field
     * @param mixed $value
     * @return array
     */
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

    /**
     * Add
     * @param array $data
     * @return array|mixed
     * @throws \Exception
     */
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

        $this->persist(base_dir() . DS . $this->userRepository, self::$users);

        return $user;
    }

    /**
     * Update
     * @param string $field
     * @param mixed $value
     * @param array $data
     * @return mixed|void
     * @throws \Exception
     */
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

        $this->persist(base_dir() . DS . $this->userRepository, self::$users);
    }

}
