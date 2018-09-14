<?php
require_once __DIR__ . '/vendor/autoload.php';

use Nonces\Nonce;

$nonce = new Nonce;
echo htmlentities($nonce->makeField());

$nonce2 = new Nonce($nonce->get());


var_dump($nonce->verify($nonce2));