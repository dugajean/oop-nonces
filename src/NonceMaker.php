<?php

namespace Nonces;

class NonceMaker
{
	/**
     * Create a nonce url.
     *
     * @param  Nonce $nonce
     * @param  string $url
     * @return string
     */
	public static function url(Nonce $nonce, $url)
	{
		return esc_html(add_query_arg($nonce->name(), $nonce->create()->get(), $url));
	}

	/**
     * Create a nonce HTML form field.
     *
     * @param  Nonce $nonce
     * @param  boolean $echo
     * @return string
     */
	public static function field(Nonce $nonce, $echo = true)
	{
		$nonceField = '<input type="hidden" id="' . $nonce->name() . '" name="';
		$nonceField .= $nonce->name() . '" value="' . $nonce->create()->get() . '" />';

        if ($echo) {
            echo $nonceField;
        }

        return $nonceField;
	}
}