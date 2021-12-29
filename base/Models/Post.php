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

namespace Base\Models;

use Quantum\Mvc\QtModel;

/**
 * Class User
 * @package Modules\Web\Models
 */
class Post extends QtModel
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
    public $table = 'posts';

    /**
     * Fillable properties
     * @var array
     */
    public $fillable = [
        'title',
        'content',
        'image',
        'author',
        'updated_at'
    ];

}
