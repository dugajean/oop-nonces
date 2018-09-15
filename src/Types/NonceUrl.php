<?php

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
     * @param string|integer $action
     * @param string $name
     * @param string $url
     */
	public function __construct($hash = null, $action = -1, $name = '_wpnonce', $url = '')
	{
		parent::__construct($hash, $action, $name);
		$this->url = $url;
	}

	/**
	 * Retrieve the nonce-d URL.
	 * 
	 * @return string
	 */
	public function get()
	{
		$url = str_replace('&amp;', '&', $this->url());
        return $this->addQueryArg($this->name(), $this->create()->hash(), $this->url());
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
	 */
	private function addQueryArg($name, $value, $url)
	{
	    $query = parse_url($url, PHP_URL_QUERY);
	    $connector = $query ? '&' : '?';
	    $newQueryString = http_build_query([htmlspecialchars($name) => $value]);

	    return $url . $connector . $newQueryString;
	}
}
