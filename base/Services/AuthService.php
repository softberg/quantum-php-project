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
 * @since 2.4.0
 */

namespace Base\Services;

use Quantum\Libraries\Auth\AuthServiceInterface;
use Quantum\Libraries\Auth\User;
use Quantum\Loader\Loader;
use Quantum\Loader\Setup;

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
     * @param \Quantum\Loader\Loader $loader
     * @throws \Quantum\Exceptions\LoaderException
     */
    public function __init(Loader $loader)
    {
        self::$users = $loader->setup(new Setup('base' . DS . 'repositories', 'users', true))->load();
    }

    /**
     * User Schema
     * @return array
     */
    public function userSchema(): array
    {
        return [
            'id' => ['name' => 'id', 'visible' => false],
            'firstname' => ['name' => 'firstname', 'visible' => true],
            'lastname' => ['name' => 'lastname', 'visible' => true],
            'role' => ['name' => 'role', 'visible' => true],
            'username' => ['name' => 'email', 'visible' => true],
            'password' => ['name' => 'password', 'visible' => false],
            'activationToken' => ['name' => 'activation_token', 'visible' => false],
            'rememberToken' => ['name' => 'remember_token', 'visible' => false],
            'resetToken' => ['name' => 'reset_token', 'visible' => false],
            'accessToken' => ['name' => 'access_token', 'visible' => false],
            'refreshToken' => ['name' => 'refresh_token', 'visible' => false],
            'otp' => ['name' => 'otp', 'visible' => false],
            'otpExpiry' => ['name' => 'otp_expires', 'visible' => false],
            'otpToken' => ['name' => 'otp_token', 'visible' => false],
        ];
    }

    /**
     * Get
     * @param string $field
     * @param string|null $value
     * @return \Quantum\Libraries\Auth\User|null
     */
    public function get(string $field, ?string $value): ?User
    {
        foreach (self::$users as $userData) {
            if (in_array($value, $userData)) {
                return (new User())->setData($userData);
            }
        }

        return null;
    }

    /**
     * Add
     * @param array $data
     * @return \Quantum\Libraries\Auth\User
     * @throws \Quantum\Exceptions\DiException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function add(array $data): User
    {
        $user = new User();

        $user->setFields($this->userSchema());

        foreach ($data as $key => $val) {
            foreach ($this->userSchema() as $field) {
                if (isset($field['name'])) {
                    if ($field['name'] == 'id') {
                        $user->setFieldValue('id', auto_increment(self::$users, 'id'));
                    }

                    if ($field['name'] == $key) {
                        $user->setFieldValue($key, $val ?? '');
                    }
                }
            }
        }

        self::$users[] = $user->getData();

        $this->persist(base_dir() . DS . $this->userRepository, self::$users);

        return $user;
    }

    /**
     * Update
     * @param string $field
     * @param string|null $value
     * @param array $data
     * @return \Quantum\Libraries\Auth\User|null
     * @throws \Quantum\Exceptions\DiException
     * @throws \Symfony\Component\VarExporter\Exception\ExceptionInterface
     */
    public function update(string $field, ?string $value, array $data): ?User
    {
        $user = $this->get($field, $value);

        if (!$user) {
            return null;
        }

        foreach ($data as $key => $val) {
            if ($user->hasField($key)) {
                $user->setFieldValue($key, $val ?? '');
            }
        }

        foreach (self::$users as &$userData) {
            if (in_array($user->getFieldValue('id'), $userData)) {
                $userData = $user->getData();
            }
        }

        $this->persist(base_dir() . DS . $this->userRepository, self::$users);

        return $user;
    }

}
