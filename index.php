<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';

use Nonces\Nonce;
use Nonces\NonceMaker;

$nonce = new Nonce;
// $field = NonceMaker::field($nonce);
// echo htmlentities($field);
echo NonceMaker::url($nonce, 'http://ziggx.com?something=something');

var_dump(Nonce::verify(new Nonce($nonce->get())));