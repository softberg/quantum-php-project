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
 * @since 3.0.0
 */

namespace Shared\DTOs;

use Quantum\Http\Request;

/**
 * Class UserDTO
 * @package Shared\DTOs
 */
class UserDTO
{
    /**
     * @var string
     */
    private string $email;

    /**
     * @var string
     */
    private string $password;

    /**
     * @var string
     */
    private string $firstname;

    /**
     * @var string
     */
    private string $lastname;

    /**
     * @var string
     */
    private string $role;

    /**
     * @var string|null
     */
    private ?string $uuid;

    /**
     * @var string
     */
    private string $image;

    /**
     * @param string $email
     * @param string $password
     * @param string $firstname
     * @param string $lastname
     * @param string $role
     * @param string|null $uuid
     * @param string $image
     */
    public function __construct(
        string $email,
        string $password,
        string $firstname,
        string $lastname,
        string $role = '',
        ?string $uuid = null,
        string $image = ''
    ) {
        $this->email = $email;
        $this->password = $password;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->role = $role;
        $this->uuid = $uuid;
        $this->image = $image;
    }

    /**
     * @param Request $request
     * @param string $role
     * @param string|null $uuid
     * @return self
     */
    public static function fromRequest(Request $request, string $role, ?string $uuid = null): self
    {
        return new self(
            $request->get('email'),
            $request->get('password'),
            $request->get('firstname'),
            $request->get('lastname'),
            $role,
            $uuid
        );
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getFirstname(): string
    {
        return $this->firstname;
    }

    public function getLastname(): string
    {
        return $this->lastname;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * Converts DTO to array for framework interface compatibility
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'uuid' => $this->uuid,
            'email' => $this->email,
            'password' => $this->password,
            'firstname' => $this->firstname,
            'lastname' => $this->lastname,
            'role' => $this->role,
            'image' => $this->image,
        ], function ($value) {
            return $value !== null && $value !== '';
        });
    }
}
