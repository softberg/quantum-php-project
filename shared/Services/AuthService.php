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
 * @since 2.8.0
 */

namespace Shared\Services;

use Quantum\Libraries\Auth\AuthServiceInterface;
use Quantum\Libraries\Auth\User as AuthUser;
use Quantum\Factory\ModelFactory;
use Quantum\Mvc\QtService;
use Shared\Models\User;
use Faker\Factory;

/**
 * Class AuthService
 * @package Shared\Services
 */
class AuthService extends QtService implements AuthServiceInterface
{

    /**
     * Get
     * @return users
     */
    public function getAll(): ?array
    {
        return ModelFactory::get(User::class)->get();
    }

    /**
     * Get user
     * @param string $uuid
     * @param bool $transformed
     * @return array|null
     */
    public function getUser(string $uuid): ?array
    {
        $user = ModelFactory::get(User::class)->criteria('uuid', '=', $uuid)->get();

        if (empty($user)) {
            return null;
        }

        return current($user);
    }


    /**
     * User Schema
     * @return array
     */
    public function userSchema(): array
    {
        return [
            'id' => ['name' => 'id', 'visible' => true],
            'uuid' => ['name' => 'uuid', 'visible' => true],
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
     * @param mixed $value
     * @return \Quantum\Libraries\Auth\User|null
     */
    public function get(string $field, $value): ?AuthUser
    {
        $user = ModelFactory::get(User::class)->findOneBy($field, $value);

        if (empty($user->asArray())) {
            return null;
        }

        return (new AuthUser())->setData($user->asArray());
    }

    /**
     * Add user
     * @param array $data
     * @return \Quantum\Libraries\Auth\User
     */
    public function add(array $data): AuthUser
    {
        if (key_exists('token', $data)) {
            unset($data['token']);
        }

        $data['uuid'] = Factory::create()->uuid();
        $data['role'] = $data['role'] ?? 'editor';

        $user = ModelFactory::get(User::class)->create();
        $user->fillObjectProps($data);
        $user->save();

        return (new AuthUser())->setData($data);
    }

    /**
     * Update user
     * @param string $field
     * @param string|null $value
     * @param array $data
     * @return \Quantum\Libraries\Auth\User|null
     */
    public function update(string $field, ?string $value, array $data): ?AuthUser
    {
        $user = ModelFactory::get(User::class)->findOneBy($field, $value);

        if (empty($user->asArray())) {
            return null;
        }

        $user->fillObjectProps($data);
        $user->save();

        return (new AuthUser())->setData($user->asArray());
    }

    /**
     * Delete users table
     */
    public function deleteTable()
    {
        ModelFactory::get(User::class)->deleteTable();
    }
}
