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
 * @since 2.9.6
 */

namespace Shared\Services;

use Quantum\Libraries\Database\Exceptions\DatabaseException;
use Quantum\Libraries\Database\Exceptions\ModelException;
use Quantum\Libraries\Config\Exceptions\ConfigException;
use Quantum\Libraries\Database\Contracts\DbalInterface;
use Quantum\Di\Exceptions\DiException;
use Quantum\Factory\ModelFactory;
use Quantum\Mvc\QtService;
use ReflectionException;
use Shared\Models\User;

/**
 * Class AccountService
 * @package Shared\Services
 */
class AccountService extends QtService
{
    /**
     * Update
     * @param string $uuid
     * @param array $data
     * @return DbalInterface
     * @throws ConfigException
     * @throws DatabaseException
     * @throws DiException
     * @throws ModelException
     * @throws ReflectionException
     */
    public function update(string $uuid, array $data): DbalInterface
    {
        $userModel = ModelFactory::get(User::class);
        
        $user = $userModel->findOneBy('uuid', $uuid);
        $user->fillObjectProps($data);
        $user->save();

        return $userModel->findOneBy('uuid', $uuid);
    }
}