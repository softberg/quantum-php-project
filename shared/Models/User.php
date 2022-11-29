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
 * @since 2.7.0
 */

namespace Shared\Models;

use Quantum\Mvc\QtModel;

/**
 * Class User
 * @package Modules\Web\Models
 */
class User extends QtModel
{

    /**
     * ID column of table
     * @var string
     */
    public $idColumn = 'id';

    /**
     * The table name
     * @var string
     */
    public $table = 'users';

    /**
     * Fillable properties
     * @var array
     */
    public $fillable = [
        'uuid',
        'firstname',
        'lastname',
        'role',
        'email',
        'password',
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
