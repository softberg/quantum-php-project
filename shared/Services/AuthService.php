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
 * @since 2.9.7
 */

namespace Shared\Services;

use Quantum\Libraries\Auth\Contracts\AuthServiceInterface;
use Quantum\Libraries\Storage\Factories\FileSystemFactory;
use Quantum\Libraries\Config\Exceptions\ConfigException;
use Quantum\Model\Exceptions\ModelException;
use Quantum\Libraries\Auth\User as AuthUser;
use Quantum\Model\Factories\ModelFactory;
use Quantum\Di\Exceptions\DiException;
use Quantum\Exceptions\BaseException;
use Quantum\Model\ModelCollection;
use Quantum\Service\QtService;
use ReflectionException;
use Shared\Models\User;
use Faker\Factory;

/**
 * Class AuthService
 * @package Shared\Services
 */
class AuthService extends QtService implements AuthServiceInterface
{

    /**
     * Get users
     * @return ModelCollection
     * @throws ModelException
     */
    public function getAll(): ModelCollection
    {
        return ModelFactory::get(User::class)->get();
    }

    /**
     * Get user
     * @param string $uuid
     * @return User
     * @throws ModelException
     */
    public function getUserByUuid(string $uuid): User
    {
        return ModelFactory::get(User::class)->findOneBy('uuid', $uuid);
    }

    /**
     * Get user
     * @param string $field
     * @param $value
     * @return AuthUser|null
     * @throws ModelException
     */
    public function get(string $field, $value): ?AuthUser
    {
        $user = ModelFactory::get(User::class)->findOneBy($field, $value);

        if ($user->isEmpty()) {
            return null;
        }

        return (new AuthUser())->setData($user->asArray());
    }

    /**
     * Add user
     * @param array $data
     * @return AuthUser
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ModelException
     * @throws ReflectionException
     */
    public function add(array $data): AuthUser
    {
        $data['uuid'] = Factory::create()->uuid();
        $data['role'] = $data['role'] ?? 'editor';

        $this->createUserDirectory($data['uuid']);

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
     * @return AuthUser|null
     * @throws ModelException
     */
    public function update(string $field, ?string $value, array $data): ?AuthUser
    {
        $userModel = ModelFactory::get(User::class);

        $user = $userModel->findOneBy($field, $value);

        if ($user->isEmpty()) {
            return null;
        }

        $user->fillObjectProps($data);
        $user->save();

        return (new AuthUser())->setData($userModel->findOneBy($field, $value)->asArray());
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
     * Delete users table
     * @throws ModelException
     */
    public function deleteTable()
    {
        ModelFactory::get(User::class)->deleteTable();
    }

    /**
     * Creates user directory
     * @param string $uuid
     * @return void
     * @throws BaseException
     * @throws ConfigException
     * @throws DiException
     * @throws ReflectionException
     */
    private function createUserDirectory(string $uuid)
    {
        $fs = FileSystemFactory::get();

        $fs->makeDirectory(uploads_dir() . DS . $uuid);
    }
}
