<?php

namespace Nonces\Tests\Unit;

use Nonces\Nonce;
use PHPUnit\Framework\TestCase;

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
        @session_start();
        parent::setUp();

        $args = [null, 'delete-post=15', 'post_id'];
        $this->firstMock = $this->getMockForAbstractClass(Nonce::class, $args)->create();
        $this->secondMock = $this->getMockForAbstractClass(Nonce::class, [$this->firstMock->hash()]);
    }

    public function testNonceVerification()
    {
        $nonce = $this->firstMock;

        $result = Nonce::verify($nonce);

        $this->assertGreaterThan(0, $result);
    }

    public function testNonceVerificationWithDifferentData()
    {
        $result = Nonce::verify($this->secondMock);

        $this->assertFalse($result);
    }

    public function testVerificationWithEmptyNonce()
    {
        $nonceMock = $this->getMockForAbstractClass(Nonce::class);

        $result = Nonce::verify($nonceMock);

        $this->assertFalse($result);
    }
}
