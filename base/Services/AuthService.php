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
use Quantum\Libraries\Auth\User as AuthUser;
use Quantum\Factory\ModelFactory;
use Quantum\Loader\Loader;
use Quantum\Loader\Setup;
use Base\Models\User;

/**
 * Class AuthService
 * @package Base\Services
 */
class AuthService extends BaseService implements AuthServiceInterface
{
    private $userModel;
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
    public function __init(Loader $loader, ModelFactory $modelFactory, Setup $setup, array $args = [])
    {
        $this->userModel = $modelFactory->get(User::class);
    }

    /**
     * User Schema
     * @return array
     */
    public function userSchema(): array
    {
        return [
            // 'id' => ['name' => 'id', 'visible' => false],
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
    public function get(string $field, ?string $value): ?AuthUser
    {
        $user = $this->userModel->findOneBy($field, $value);
        return (new AuthUser())->setData($user->asArray());
    }

    /**
     * Add user
     * @param array $data
     * @return \Quantum\Libraries\Auth\User
     * @throws \Quantum\Exceptions\DiException
     */
    public function add(array $data): AuthUser
    {
        $authUser = new AuthUser();

        $authUser->setFields($this->userSchema());

        $user = $this->userModel->create();

        foreach ($data as $key => $value) {
            foreach ($this->userSchema() as $field) {
                if ($field['name'] == $key) {
                    $authUser->setFieldValue($key, $value ?? '');
                }
            }
        }
        
        $user->fillObjectProps($authUser->getData());

        $user->save();

        return $authUser;
    }

    /**
     * Update user
     * @param string $field
     * @param string|null $value
     * @param array $data
     * @return \Quantum\Libraries\Auth\User|null
     * @throws \Quantum\Exceptions\DiException
     */
    public function update(string $field, ?string $value, array $data): ?AuthUser
    {
        $user = $this->userModel->findOneBy($field, $value);

        if (empty($user->asArray())) {
            return null;
        }

        foreach ($data as $key => $val) {
            $user->$key = $val;
        }
  
        $user->save();

        return (new AuthUser())->setData($user->asArray());
    }

}
