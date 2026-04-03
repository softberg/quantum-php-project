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

namespace Shared\Commands;

use Quantum\Config\Exceptions\ConfigException;
use Quantum\Lang\Exceptions\LangException;
use Quantum\Di\Exceptions\DiException;
use Quantum\Validation\Validator;
use ReflectionException;

/**
 * Trait CommandValidationTrait
 * @package Shared\Commands
 */
trait CommandValidationTrait
{
    /**
     * @var Validator
     */
    protected Validator $validator;

    /**
     * Initiates the validator
     */
    protected function initValidator(): void
    {
        $this->validator = new Validator();
    }

    /**
     * Validates rules
     * @param array $rules
     * @param array $data
     * @return bool
     */
    protected function validate(array $rules, array $data): bool
    {
        $this->validator->setRules($rules);

        if (!$this->validator->isValid($data)) {
            return false;
        }

        return true;
    }

    /**
     * Gets the first validation error
     * @return string|null
     */

    /**
     * @return string|null
     * @throws ConfigException|DiException|LangException|ReflectionException
     */
    public function firstError(): ?string
    {
        $errors = $this->validator->getErrors();

        foreach ($errors as $fieldErrors) {
            if (!empty($fieldErrors)) {
                return $fieldErrors[0];
            }
        }

        return null;
    }
}
