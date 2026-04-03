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
use Quantum\Model\Traits\SoftDeletes;
use Quantum\Database\Enums\Relation;
use Quantum\Model\DbModel;

/**
 * Class Post
 * @package Shared\Models
 *
 * @property string $uuid
 * @property string $title
 * @property string $content
 * @property string|null $image
 * @property string|null $user_directory
 * @property string|null $updated_at
 */
class Post extends DbModel
{
    use HasTimestamps;
    use SoftDeletes;

    /**
     * ID column of table
     * @var string
     */
    public string $idColumn = 'id';

    /**
     * The table name
     * @var string
     */
    public string $table = 'posts';

    /**
     * Fillable properties
     * @var array
     */
    public array $fillable = [
        'uuid',
        'user_uuid',
        'title',
        'content',
        'image',
    ];

    /**
     * Model relations configuration
     * @return array[]
     */
    public function relations(): array
    {
        return [
            User::class => [
                'type' => Relation::BELONGS_TO,
                'foreign_key' => 'user_uuid',
                'local_key' => 'uuid',
            ],
        ];
    }
}
