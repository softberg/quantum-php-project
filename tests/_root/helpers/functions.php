<?php

use Quantum\Storage\Factories\FileSystemFactory;
use Quantum\HttpClient\HttpClient;

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

function textCleanUp(string $text)
{
    return str_replace(['"', '\'', '-'], '', $text);
}
