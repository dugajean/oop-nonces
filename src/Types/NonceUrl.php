<?php

declare(strict_types=1);

namespace Nonces\Types;

use Nonces\Nonce;
use Nonces\Exceptions\NonceException;

class NonceUrl extends Nonce
{
    /**
     * The URL which will have a nonce attached to it.
     *
     * @var string
     */
    private $url;

    /**
     * @inheritdoc
     * @param string $url The URL which needs to have a nonce parameter.
     */
    public function __construct(string $hash = null, $action = -1, string $name = '_wpnonce', string $url = '')
    {
        parent::__construct($hash, $action, $name);

        $this->url($url);
    }

    /**
     * Attaches a parameter to an URL containing the nonce.
     *
     * E.g: Given the URL of "http://example.com", name is "secure_nonce" and a hash with value "4grh349dbll",
     * the resulting URL would look like: http://example.com?secure_nonce=4grh349dbll
     *
     * @return string The nonce-d URL.
     * @throws \Nonces\Exceptions\NonceException
     */
    public function get(): string
    {
        $this->validateUrl();

        $url = str_replace('&amp;', '&', $this->url());
        $query = parse_url($url, PHP_URL_QUERY);
        $connector = $query ? '&' : '?';
        $newQueryString = http_build_query([htmlspecialchars($this->name()) => $this->create()->hash()]);

        return $url . $connector . $newQueryString;
    }

    /**
     * Fetch or set a new URL for an instance.
     *
     * @param  string|null $url Optional. If the argument is missing, it will return the current URL.
     *                          If a value is provided then it will set the URL to that new value and return $this.
     * @return string|$this
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
     * Basic validation for an URL, checking whether its empty or properly formatted.
     *
     * @throws \Nonces\Exceptions\NonceException
     */
    private function validateUrl()
    {
        $url = $this->url();

        if (empty($url)) {
            throw new NonceException('No URL provided.');
        }

        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new NonceException('Malformed URL provided');
        }
    }
}
