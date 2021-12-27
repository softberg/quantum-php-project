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
 * @since 2.6.0
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
     * Init
     * @param \Quantum\Loader\Loader $loader
     * @throws \Quantum\Exceptions\LoaderException
     */

    /**
     * Initialise the service
     * @param \Quantum\Loader\Loader $loader
     * @param \Quantum\Loader\Setup $setup
     * @param array $args
     * @throws \Quantum\Exceptions\LoaderException
     */
    public function __init(Loader $loader, Setup $setup, array $args = [])
    {
        if ($args) {
            $loader->setup(new Setup(...$args));
        } else {
            $loader->setup(new Setup('base' . DS . 'repositories', 'users'));
        }

        $this->repository = $loader->getFilePath();

        self::$users = $loader->load();
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
     * Add user
     * @param array $data
     * @return \Quantum\Libraries\Auth\User
     * @throws \Quantum\Exceptions\DiException
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

        $this->persist(self::$users);

        return $user;
    }

    /**
     * Update user
     * @param string $field
     * @param string|null $value
     * @param array $data
     * @return \Quantum\Libraries\Auth\User|null
     * @throws \Quantum\Exceptions\DiException
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

        $this->persist(self::$users);

        return $user;
    }

}
