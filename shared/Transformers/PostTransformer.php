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
 * Class PostTransformer
 * @package Shared\Transformers
 */
class PostTransformer implements TransformerInterface
{

    /**
     * Transforms the post data
     * @param $item
     * @return mixed
     */
    public function transform($item): array
    {
        $transformer = __NAMESPACE__ . '\\' . ucfirst(config()->get('database.current')) . 'Transformer';

        return (new $transformer)->transform($item);
    }

}