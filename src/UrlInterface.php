<?php

namespace VS\Url;

/**
 * Interface UrlInterface
 * @package VS\Url
 * @author Varazdat Stepanyan
 */
interface UrlInterface
{
    /**
     * @param bool $withParams
     * @return string
     */
    public function current(bool $withParams = false): string;

    /**
     * @param int $index
     * @return string
     */
    public function segment(int $index): string;

    /**
     * @param string $additional
     * @return string
     */
    public function base(string $additional = ''): string;

    /**
     * @param bool $withParams
     * @return string
     */
    public function full(bool $withParams = false): string;
}