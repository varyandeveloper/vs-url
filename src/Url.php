<?php

namespace VS\Url;

use VS\General\Singleton\{
    SingletonInterface, SingletonTrait
};

/**
 * Class Url
 * @package VS\Url
 * @author Varazdat Stepanyan
 */
class Url implements UrlInterface, SingletonInterface
{
    use SingletonTrait;

    /**
     * @param bool $withParams
     * @return string
     */
    public function current(bool $withParams = false): string
    {
        $key = $withParams ? 'REQUEST_URI' : 'PATH_INFO';
        $url = trim(stripcslashes(rtrim($_SERVER[$key] ?? '', '/')));
        return empty($url) ? '/' : $url;
    }

    /**
     * @param int $index
     * @return null|string
     */
    public function segment(int $index): string
    {
        static $partials;
        if (!$partials) {
            $partials = explode('/', ltrim($this->current(), '/'));
        }
        return $partials[$index] ?? '';
    }

    /**
     * @param string $additional
     * @throws \Exception
     * @return string
     */
    public function base(string $additional = ''): string
    {
        return sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER['SERVER_PORT'] ? sprintf(':%s/', $_SERVER['SERVER_PORT']) : '/'
        );
    }

    /**
     * @param bool $withParams
     * @return string
     * @throws \Exception
     */
    public function full(bool $withParams = false): string
    {
        return $this->base() . ltrim($this->current($withParams), '/');
    }
}