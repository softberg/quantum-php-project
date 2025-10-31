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
 * @since 2.9.9
 */

namespace Shared\Models;

use Quantum\Model\Traits\SoftDeletes;
use Quantum\Model\QtModel;

/**
 * Class Comment
 * @package Shared\Models
 */
class Comment extends QtModel
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
    public $table = 'comments';

    /**
     * Model relations configuration
     * @return array[]
     */
    public function relations(): array
    {
        return [
            Post::class => [
                'foreign_key' => 'post_uuid',
                'local_key' => 'uuid',
            ],
            User::class => [
                'foreign_key' => 'user_uuid',
                'local_key' => 'uuid',
            ]
        ];
    }

    /**
     * Fillable properties
     * @var array
     */
    public $fillable = [
        'uuid',
        'post_uuid',
        'user_uuid',
        'content',
        'created_at',
        'updated_at'
    ];
}