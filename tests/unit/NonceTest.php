<?php

namespace Nonces\Tests\Unit;

use Nonces\Nonce;
use Nonces\Tests\TestCase;

class NonceTest extends TestCase
{
    /**
     * @var Nonce
     */
    private $firstMock;

    /**
     * @var Nonce
     */
    private $secondMock;

    public function setUp()
    {
        parent::setUp();

        $args = [null, 'delete-post=15', 'post_id'];
        $this->firstMock = $this->getMockForAbstractClass(Nonce::class, $args)->create();
        $this->secondMock = $this->getMockForAbstractClass(Nonce::class, [$this->firstMock->hash()]);
    }

    public function testNonceVerification()
    {
        $nonce = $this->firstMock;

        $this->assertGreaterThan(0, $nonce->verify());
    }

    public function testNonceVerificationWithDifferentData()
    {
        $this->assertFalse($this->secondMock->verify());
    }

    public function testVerificationWithEmptyNonce()
    {
        $nonceMock = $this->getMockForAbstractClass(Nonce::class);

        $this->assertFalse($nonceMock->verify());
    }

    public function testGenerateHashOverriding()
    {
        Nonce::overrideGenerateHash(function($nonce, $tick, $salt) {
            return md5($tick . '|' . $nonce->action() . '|' . session_id() . '|' . $salt);
        });

        $nonceMock = $this->getMockForAbstractClass(Nonce::class, [null, 'delete-post=15', 'post_id'])->create();

        $expectedLength = 32;

        $this->assertGreaterThan(0, $nonceMock->verify());
        $this->assertEquals($expectedLength, strlen($nonceMock->hash()));
    }
}
