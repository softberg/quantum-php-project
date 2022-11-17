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
if (!function_exists('url_with_lang')) {

    /**
     * Gets the url with selected language
     * @param string $lang
     * @return string
     */
    function url_with_lang(string $lang): string
    {
        if (!in_array($lang, (array) config()->get('langs'))) {
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

}