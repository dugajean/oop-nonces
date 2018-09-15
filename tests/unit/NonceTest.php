<?php

namespace Nonces\Tests\Unit;

use Nonces\Nonce;
use PHPUnit\Framework\TestCase;

class NonceTest extends TestCase
{
    /**
     * @var Nonce
     */
    private $firstMock, $secondMock;

    public function setUp()
    {
        @session_start();
        parent::setUp();

        $this->firstMock = $this->getMockForAbstractClass(Nonce::class, [null, 'delete-post=15', 'post_id'])->create();
        $this->secondMock = $this->getMockForAbstractClass(Nonce::class, [$this->firstMock->hash()]);
    }

    public function test_nonce_verification()
    {
        $nonce = $this->firstMock;

        $result = Nonce::verify($nonce);

        $this->assertGreaterThan(0, $result);
    }

    public function test_nonce_verification_with_different_data()
    {
        $result = Nonce::verify($this->secondMock);

        $this->assertFalse($result);
    }
}
