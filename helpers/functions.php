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

use Quantum\HttpClient\Exceptions\HttpClientException;
use Quantum\Storage\Factories\FileSystemFactory;
use Quantum\Config\Exceptions\ConfigException;
use Quantum\App\Exceptions\BaseException;
use Quantum\Di\Exceptions\DiException;
use Quantum\HttpClient\HttpClient;

/**
 * Gets the url with the selected language
 * @param string $lang
 * @return string
 */
function url_with_lang(string $lang): string
{
    if (!in_array($lang, (array) config()->get('lang.supported'))) {
        $lang = config()->get('lang.default');
    }

    if (trim(route_uri()) == '/') {
        return base_url(true) . '/' . $lang;
    }

    if (preg_match('/' . preg_quote(current_lang()) . '/', route_uri())) {
        return base_url() . preg_replace('/' . preg_quote(current_lang(), '/') . '/', $lang, route_uri(), 1);
    }

    $url = base_url(true);

    $langSegmentIndex = config()->get('lang.url_segment');

    $uri = '';

    if (!empty(route_prefix()) && $langSegmentIndex == 1) {
        $langSegmentIndex += 1;

        $uri = preg_replace('/' . preg_quote(route_prefix(), '/') . '/', '', route_uri(), 1);
    }

    $segments = explode('/', $uri);

    foreach ($segments as $index => $segment) {
        if (!empty($segment)) {
            $url .= '/' . ($index == $langSegmentIndex ? $lang : $segment);
        }
    }

    return $url;
}

/**
 * Saves remote image
 * @param string $imageUrl
 * @param string $userDirectory
 * @param string $imageName
 * @return string
 * @throws ErrorException
 * @throws HttpClientException
 * @throws ReflectionException
 * @throws BaseException
 * @throws ConfigException
 * @throws DiException
 */
function save_remote_image(string $imageUrl, string $userDirectory, string $imageName): string
{
    $fs = FileSystemFactory::get();

    $imageName = slugify($imageName) . '.jpg';

    $httpClient = new HttpClient();
    $httpClient->createRequest($imageUrl);
    $httpClient->setMethod('GET');
    $httpClient->setOpt(CURLOPT_FOLLOWLOCATION, true);
    $httpClient->start();

    $fs->put(uploads_dir() . DS . $userDirectory . DS . $imageName, $httpClient->getResponseBody());

    return $imageName;
}

/**
 * Creates user directory
 * @param string $uuid
 * @throws DiException
 * @throws ReflectionException
 * @throws BaseException
 * @throws ConfigException
 */
function create_user_directory(string $uuid): void
{
    $userDirectory = uploads_dir() . DS . $uuid;

    if (!fs()->isDirectory($userDirectory)) {
        fs()->makeDirectory($userDirectory);
    }
}

/**
 * Cleans up text for titles/descriptions.
 * @param string $text
 * @return string
 */
function textCleanUp(string $text): string
{
    return str_replace(['"', '\'', '-'], '', $text);
}

/**
 * Encodes current query string to URL-safe base64.
 * @param string|null $query
 * @return string
 */
function nav_ref_encode(?string $query): string
{
    return $query ? rtrim(strtr(base64_encode($query), '+/', '-_'), '=') : '';
}

/**
 * Decodes a URL-safe base64 reference back to query string.
 *
 * @param string|null $ref
 * @return string
 */
function nav_ref_decode(?string $ref): string
{
    if ($ref === 'my-posts') {
        return '/my-posts';
    }

    $decoded = $ref ? base64_decode(strtr($ref, '-_', '+/'), true) : false;

    return '/posts' . ($decoded ? '?' . $decoded : '');
}
