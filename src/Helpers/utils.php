<?php

/**
 * Timing attack safe string comparison
 * Compares two strings using the same time whether they're equal or not.
 *
 * Copied from Wordpress.
 *
 * @param string $a Expected string.
 * @param string $b Actual, user supplied, string.
 * @return bool Whether strings are equal.
 */
function safeEquals($a, $b) 
{
	$a_length = strlen($a);
	if ($a_length !== strlen($b)) {
		return false;
	}

	$result = 0;
	for ($i = 0; $i < $a_length; $i++) {
		$result |= ord($a[$i]) ^ ord($b[$i]);
	}

	return $result === 0;
}
