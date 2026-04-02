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
 * Class CommentDTO
 * @package Shared\DTOs
 */
class CommentDTO
{
    /**
     * @var string
     */
    private string $postUuid;

    /**
     * @var string
     */
    private string $userUuid;

    /**
     * @var string
     */
    private string $content;

    /**
     * @param string $postUuid
     * @param string $userUuid
     * @param string $content
     */
    public function __construct(
        string $postUuid,
        string $userUuid,
        string $content
    ) {
        $this->postUuid = $postUuid;
        $this->userUuid = $userUuid;
        $this->content = $content;
    }

    /**
     * @param Request $request
     * @param string $postUuid
     * @param string $userUuid
     * @return self
     */
    public static function fromRequest(Request $request, string $postUuid, string $userUuid): self
    {
        return new self($postUuid, $userUuid, trim((string) $request->get('content')));
    }

    public function getPostUuid(): string
    {
        return $this->postUuid;
    }

    public function getUserUuid(): string
    {
        return $this->userUuid;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'post_uuid' => $this->postUuid,
            'user_uuid' => $this->userUuid,
            'content' => $this->content,
        ];
    }
}
