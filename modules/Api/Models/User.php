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

namespace Modules\Api\Models;

use Quantum\Mvc\QtModel;

/**
 * Class User
 * @package Modules\Main\Models
 */
class User extends QtModel
{

    /**
     * ID column of table
     * @var string
     */
    public $idColumn = 'user_id';

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
        'firstname',
        'lastname',
        'email',
        'pass',
    ];

    /**
     * Is editor
     * @return bool
     */
    public static function isEditor()
    {
        return true;
    }

    /**
     * Is blogger
     * @return bool
     */
    public static function isBlogger()
    {
        return true;
    }

}
