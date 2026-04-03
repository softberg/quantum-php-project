<?php

declare(strict_types=1);

/**
 * Quantum PHP Framework
 *
 * An open source software development framework for PHP
 *
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 3.0.0
 */

namespace Shared\Models;

use Quantum\Model\Traits\HasTimestamps;
use Quantum\Model\DbModel;

/**
 * Class User
 * @package Shared\Models
 */
class User extends DbModel
{
    use HasTimestamps;

    /**
     * ID column of table
     * @var string
     */
    public string $idColumn = 'id';

    /**
     * The table name
     * @var string
     */
    public string $table = 'users';

    /**
     * Fillable properties
     * @var array<string>
     */
    public array $fillable = [
        'uuid',
        'firstname',
        'lastname',
        'role',
        'email',
        'password',
        'image',
        'activation_token',
        'remember_token',
        'reset_token',
        'access_token',
        'refresh_token',
        'otp',
        'otp_expires',
        'otp_token',
    ];
}
