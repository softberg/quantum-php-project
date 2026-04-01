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
 * Class PostDTO
 * @package Shared\DTOs
 */
class PostDTO
{
    /**
     * @var string
     */
    private string $title;

    /**
     * @var string
     */
    private string $content;

    /**
     * @var string|null
     */
    private ?string $userUuid;

    /**
     * @var string|null
     */
    private ?string $image;

    /**
     * @param string $title
     * @param string $content
     * @param string|null $userUuid
     * @param string|null $image
     */
    public function __construct(
        string $title,
        string $content,
        ?string $userUuid = null,
        ?string $image = null
    ) {
        $this->title = $title;
        $this->content = $content;
        $this->userUuid = $userUuid;
        $this->image = $image;
    }

    /**
     * @param Request $request
     * @param string|null $userUuid
     * @param string|null $image
     * @return self
     */
    public static function fromRequest(Request $request, ?string $userUuid = null, ?string $image = null): self
    {
        return new self(
            $request->get('title', null, true),
            $request->get('content', null, true),
            $userUuid,
            $image
        );
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getUserUuid(): ?string
    {
        return $this->userUuid;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return array_filter([
            'user_uuid' => $this->userUuid,
            'title' => $this->title,
            'content' => $this->content,
            'image' => $this->image,
        ], function ($value) {
            return $value !== null;
        });
    }
}
