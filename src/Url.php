<?php

namespace VS\Url;

/**
 * Class Url
 * @package VS\Url
 * @author Varazdat Stepanyan
 */
class Url implements UrlInterface
{
    /**
     * @param bool $withParams
     * @return string
     */
    public function current(bool $withParams = false): string
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'cli';

        if ($method === 'cli') {
            return $this->currentCli($withParams);
        }

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
        $base = sprintf(
            "%s://%s%s",
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
            $_SERVER['SERVER_NAME'],
            $_SERVER['SERVER_PORT'] ? sprintf(':%s/', $_SERVER['SERVER_PORT']) : '/'
        );

        return !empty($additional) ? $base . $additional : $base;
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

    /**
     * @param bool $withParams
     * @return string
     */
    protected function currentCli(bool $withParams): string
    {
        global $argv;
        unset($argv[0]);

        $url = "/";

        if (count($argv)) {
            $url .= implode('/', $argv);
        }

        return $withParams ? $url : explode("?", $url, 2)[0];
    }
}