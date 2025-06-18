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

namespace Shared\Models;

use Quantum\Model\Traits\SoftDeletes;
use Quantum\Model\QtModel;

/**
 * Class User
 * @package Shared\Models
 */
class Post extends QtModel
{

    use SoftDeletes;

    /**
     * ID column of table
     * @var string
     */
    public $idColumn = 'id';

    /**
     * The table name
     * @var string
     */
    public $table = 'posts';

    public $foreignKeys = [
        'users' => 'user_id'
    ];

    /**
     * Fillable properties
     * @var array
     */
    public $fillable = [
        'uuid',
        'user_id',
        'title',
        'content',
        'image',
        'updated_at'
    ];
}
