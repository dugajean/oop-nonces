<?php

declare(strict_types=1);

namespace Nonces\Types;

use Nonces\Nonce;

class NonceUrl extends Nonce
{
    /**
     * @var string
     */
    private $url;

    /**
     * @param string  $hash
     * @param string|int $action
     * @param string $name
     * @param string $url
     */
    public function __construct(string $hash = null, $action = -1, string $name = '_wpnonce', string $url = '')
    {
        parent::__construct($hash, $action, $name);

        $this->url($url);
    }

    /**
     * Retrieve the nonce-d URL.
     *
     * @return string
     */
    public function get(): string
    {
        $url = str_replace('&amp;', '&', $this->url());
        
        return self::addQueryArg($this->name(), $this->create()->hash(), $url);
    }

    /**
     * @param  string $url
     * @return string|self
     */
    public function url($url = null)
    {
        if ($url === null) {
            return $this->url;
        }

        $this->url = $url;

        return $this;
    }

    /**
     * Adds argument to url parameters.
     *
     * @param string $name
     * @param string $value
     * @param string $url
     * @return string
     */
    private static function addQueryArg(string $name, string $value, string $url): string
    {
        $query = parse_url($url, PHP_URL_QUERY);
        $connector = $query ? '&' : '?';
        $newQueryString = http_build_query([htmlspecialchars($name) => $value]);

        return $url . $connector . $newQueryString;
    }
}
