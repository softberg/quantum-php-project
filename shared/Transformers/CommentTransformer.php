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

namespace Shared\Transformers;

use Quantum\Libraries\Transformer\Contracts\TransformerInterface;

/**
 * Class CommentTransformer
 * @package Shared\Transformers
 */
class CommentTransformer implements TransformerInterface
{
    public function transform($item): array
    {
        return [
            'uuid' => $item->uuid,
            'author' => [
                'firstname' => $item->firstname,
                'lastname' => $item->lastname,
                'image' => $item->image,
            ],
            'content' => $item->content,
            'date' => date('Y-m-d H:i', strtotime($item->created_at)),
        ];
    }
}
