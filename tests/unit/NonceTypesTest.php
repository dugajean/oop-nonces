<?php

namespace Nonces\Tests\Unit;

use Nonces\Types\NonceUrl;
use Nonces\Tests\TestCase;
use Nonces\Types\NonceField;
use Nonces\Exceptions\NonceException;

class NonceTypesTest extends TestCase
{
    public function testNonceUrlCreationNoParams()
    {
        $nonce = (new NonceUrl(null, 'make-post-url=100', 'create_url'))->url('http://inpsyde.local');

        $actual = $nonce->get();
        $expected = 'http://inpsyde.local?create_url=' . $nonce->hash();

        $this->assertEquals($expected, $actual);
    }

    public function testNonceUrlCreationWithParams()
    {
        $nonceUrl = (new NonceUrl(null, 'make-post-url=100', 'create_url'))
            ->url('http://inpsyde.local?param1=myparamvalue');

        $actual = $nonceUrl->get();
        $expected = 'http://inpsyde.local?param1=myparamvalue&create_url=' . $nonceUrl->hash();

        $this->assertEquals($expected, $actual);
    }

    public function testNonceUrlCreationWithoutUrl()
    {
        $this->expectException(NonceException::class);

        (new NonceUrl(null, 'make-post-url=100', 'create_url'))->get();
    }

    public function testNonceUrlCreationWithMalformedUrl()
    {
        $this->expectException(NonceException::class);

        (new NonceUrl(null, 'make-post-url=100', 'create_url'))->url('notreallyanurl')->get();
    }

    public function testNonceFieldCreation()
    {
        $nonceField = (new nonceField(null, 'update-post=150', 'post_id'))->echo(false);

        $actual = $nonceField->get();
        $expected = '<input type="hidden" id="' . $nonceField->name() . '" name="';
        $expected .= $nonceField->name() . '" value="' . $nonceField->hash() . '" />';

        $this->assertEquals($expected, $actual);
    }
}
