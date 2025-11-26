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

namespace Shared\Commands;

use Quantum\Libraries\Validation\Validator;

/**
 * Trait CommandValidationTrait
 * @package Shared\Commands
 */
trait CommandValidationTrait
{

    /**
     * @var Validator
     */
    protected $validator;

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