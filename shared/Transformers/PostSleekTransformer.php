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
 * @since 2.8.0
 */

namespace Shared\Transformers;

use Quantum\Libraries\Transformer\TransformerInterface;

/**
 * Class PostSleekTransformer
 * @package Shared\Transformers
 */
class PostSleekTransformer implements TransformerInterface
{

    /**
     * Transforms the data
     * @param mixed $item
     * @return array
     */
    public function transform($item): array
    {
        return [
            'id' => $item['uuid'],
            'title' => $item['title'],
            'content' => $item['content'],
            'image' => $item['image'],
            'date' => date('Y/m/d H:i', strtotime($item['updated_at'])),
            'author' => $item['users'][0]['firstname'] . ' ' . $item['users'][0]['lastname']
        ];
    }

}
