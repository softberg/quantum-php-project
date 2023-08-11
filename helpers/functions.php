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
 * @since 2.9.0
 */

use Quantum\Libraries\Storage\FileSystem;
use Quantum\Exceptions\DiException;
use Quantum\Di\Di;

/**
 * Gets the url with selected language
 * @param string $lang
 * @return string
 */
function url_with_lang(string $lang): string
{
    if (!in_array($lang, (array)config()->get('langs'))) {
        $lang = config()->get('lang_default');
    }

    if (trim(route_uri()) == '/') {
        return base_url(true) . '/' . $lang;
    }

    if (preg_match('/' . preg_quote(current_lang()) . '/', route_uri())) {
        return base_url() . preg_replace('/' . preg_quote(current_lang(), '/') . "/", $lang, route_uri(), 1);
    }

    $url = base_url(true);

    $langSegmentIndex = config()->get('lang_segment');

    $uri = '';

    if (!empty(route_prefix()) && $langSegmentIndex == 1) {
        $langSegmentIndex += 1;

        $uri = preg_replace('/' . preg_quote(route_prefix(), '/') . "/", '', route_uri(), 1);
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
 * @param string $imageUrl
 * @param string $userDirectory
 * @param string $imageName
 * @return string
 * @throws DiException
 * @throws ReflectionException
 */
function save_remote_image(string $imageUrl, string $userDirectory, string $imageName): string
{
    $fs = Di::get(FileSystem::class);

    $imageName = slugify($imageName) . '.jpg';

    $fs->put(uploads_dir() . DS . $userDirectory . DS . $imageName, $fs->get($imageUrl));

    return $imageName;
}

function getRecaptcha()
{
    if (env('RECAPTCHA_SITE_KEY_V2')) {
        echo partial('partials/recaptchaVersions/recaptchaCheckbox');
    } elseif (env('RECAPTCHA_SITE_KEY_V3')) {
        echo partial('partials/recaptchaVersions/recaptchaInvisibleV3');
    }
}