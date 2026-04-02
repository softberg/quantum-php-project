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

namespace Shared\Services;

use Quantum\Storage\Exceptions\FileUploadException;
use Quantum\Storage\Exceptions\FileSystemException;
use Quantum\Storage\Factories\FileSystemFactory;
use Quantum\Environment\Exceptions\EnvException;
use Quantum\Config\Exceptions\ConfigException;
use Quantum\Model\Exceptions\ModelException;
use Quantum\App\Exceptions\BaseException;
use Shared\Transformers\PostTransformer;
use Quantum\Di\Exceptions\DiException;
use Quantum\Model\ModelCollection;
use Quantum\Storage\UploadedFile;
use Gumlet\ImageResizeException;
use Quantum\Service\QtService;
use Quantum\Model\DbModel;
use ReflectionException;
use Shared\DTOs\PostDTO;
use Shared\Models\User;
use Shared\Models\Post;

/**
 * Class PostService
 * @package Shared\Services
 */
class PostService extends QtService
{

    /**
     * @var DbModel
     */
    private $model;

    /**
     * @var PostTransformer
     */
    private PostTransformer $transformer;

    /**
     * @param PostTransformer $transformer
     * @throws BaseException
     * @throws ModelException
     */
    public function __construct(PostTransformer $transformer)
    {
        $this->model = model(Post::class);
        $this->transformer = $transformer;
    }

    /**
     * Get posts
     * @param int|null $perPage
     * @param int|null $currentPage
     * @param string|null $search
     * @return mixed
     * @throws BaseException
     * @throws ModelException
     */
    public function getPosts(?int $perPage = null, ?int $currentPage = null, ?string $search = null)
    {
        $query = $this->model
            ->joinTo(model(User::class))
            ->select(
                'posts.uuid',
                'title',
                'content',
                'image',
                'updated_at',
                ['users.firstname' => 'firstname'],
                ['users.lastname' => 'lastname'],
                ['users.uuid' => 'user_directory']
            )
            ->orderBy('updated_at', 'desc');

        if ($search) {
            $searchTerm = '%' . $search . '%';

            $criterias = [
                ['title', 'LIKE', $searchTerm],
                ['content', 'LIKE', $searchTerm]
            ];

            $query->criterias($criterias);
        }

        return $perPage ? $query->paginate($perPage, $currentPage) : $query->get();
    }

    /**
     * Get post
     * @param string $uuid
     * @return Post
     * @throws BaseException
     * @throws ModelException
     */
    public function getPost(string $uuid): Post
    {
        return $this->model
            ->joinTo(model(User::class))
            ->criteria('uuid', '=', $uuid)
            ->select(
                'posts.uuid',
                'user_uuid',
                'title',
                'content',
                'image',
                'updated_at',
                ['users.firstname' => 'firstname'],
                ['users.lastname' => 'lastname'],
                ['users.uuid' => 'user_directory']
            )
            ->first();
    }

    /**
     * Get my posts
     * @param string $userUuid
     * @return ModelCollection|null
     * @throws BaseException
     * @throws ModelException
     */
    public function getMyPosts(string $userUuid): ?ModelCollection
    {
        return $this->model
            ->joinTo(model(User::class))
            ->criteria('user_uuid', '=', $userUuid)
            ->select(
                'posts.uuid',
                'title',
                'content',
                'image',
                'updated_at',
                ['users.firstname' => 'firstname'],
                ['users.lastname' => 'lastname'],
                ['users.uuid' => 'user_directory']
            )
            ->get();
    }

    /**
     * Add post
     * @param PostDTO $postDto
     * @return Post
     * @throws BaseException
     * @throws ModelException
     */
    public function addPost(PostDTO $postDto): Post
    {
        $uuid = $postDto->getUuid() ?? uuid_ordered();

        $post = $this->model->create();
        $post->fill(array_merge(['uuid' => $uuid], $postDto->toArray()));
        $post->save();

        return $this->getPost($uuid);
    }

    /**
     * Update post
     * @param string $uuid
     * @param PostDTO $postDto
     * @return Post
     * @throws BaseException
     * @throws ModelException
     */
    public function updatePost(string $uuid, PostDTO $postDto): Post
    {
        $post = $this->model->findOneBy('uuid', $uuid);
        $post->fill($postDto->toArray());
        $post->save();

        return $this->getPost($post->uuid);
    }

    /**
     * Deletes post
     * @param string $uuid
     * @return bool
     * @throws ModelException|BaseException
     */
    public function deletePost(string $uuid): bool
    {
        return $this->model->findOneBy('uuid', $uuid)->delete();
    }

    /**
     * Delete all posts
     * @throws ModelException
     */
    public function deleteAllPosts()
    {
        $this->model->truncate();
    }

    /**
     * Saves the post images
     * @param UploadedFile $uploadedFile
     * @param string $imageDirectory
     * @param string $imageName
     * @return string
     * @throws EnvException
     * @throws FileSystemException
     * @throws FileUploadException
     * @throws ImageResizeException
     * @throws BaseException
     */
    public function saveImage(UploadedFile $uploadedFile, string $imageDirectory, string $imageName): string
    {
        $uploadedFile->setName($imageName . '-' . random_number());
        $uploadedFile->save(uploads_dir() . DS . $imageDirectory);

        return $uploadedFile->getNameWithExtension();
    }

    /**
     * Deletes the post image
     * @param string $imagePath
     * @throws BaseException
     * @throws DiException
     * @throws ReflectionException
     * @throws ConfigException
     */
    public function deleteImage(string $imagePath)
    {
        $fs = FileSystemFactory::get();

        if ($fs->exists(uploads_dir() . DS . $imagePath)) {
            $fs->remove(uploads_dir() . DS . $imagePath);
        }
    }

    /**
     * Transforms the data
     * @param array $posts
     * @return array
     */
    public function transformData(array $posts): array
    {
        return transform($posts, $this->transformer);
    }
}