<?php

namespace Nonces\Tests\Unit;

use Nonces\Nonce;
use Nonces\Types\NonceUrl;
use Nonces\Types\NonceField;
use PHPUnit\Framework\TestCase;

class NonceMakerTest extends TestCase
{
    public function test_nonce_url_creation_no_params()
    {
        $nonce = (new NonceUrl(null, 'make-post-url=100', 'create_url'))->url('http://inpsyde.local');

        $actual = $nonce->get();
        $expected = 'http://inpsyde.local?create_url=' . $nonce->hash();

        $this->assertEquals($expected, $actual);
    }

    public function test_nonce_url_creation_with_params()
    {
        $nonceUrl = (new NonceUrl(null, 'make-post-url=100', 'create_url'))->url('http://inpsyde.local?param1=myparamvalue');

        $actual = $nonceUrl->get();
        $expected = 'http://inpsyde.local?param1=myparamvalue&create_url=' . $nonceUrl->hash();

        $this->assertEquals($expected, $actual);
    }

    public function test_nonce_field_creation()
    {
        $nonceField = (new nonceField(null, 'update-post=150', 'post_id'))->echo(false);

        $actual = $nonceField->get();
        $expected = '<input type="hidden" id="' . $nonceField->name() . '" name="';
        $expected .= $nonceField->name() . '" value="' . $nonceField->hash() . '" />';

        $this->assertEquals($expected, $actual);
    }
}
