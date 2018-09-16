<?php

declare(strict_types=1);

namespace Nonces\Tests\Unit;

use Nonces\Nonce;
use Nonces\Types\NonceUrl;
use Nonces\Types\NonceField;
use PHPUnit\Framework\TestCase;

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

    public function testNonceFieldCreation()
    {
        $nonceField = (new nonceField(null, 'update-post=150', 'post_id'))->echo(false);

        $actual = $nonceField->get();
        $expected = '<input type="hidden" id="' . $nonceField->name() . '" name="';
        $expected .= $nonceField->name() . '" value="' . $nonceField->hash() . '" />';

        $this->assertEquals($expected, $actual);
    }
}
