<?php

namespace Nonces\Tests;

use PHPUnit\Framework\TestCase as PhpUnitTestCase;

class TestCase extends PhpUnitTestCase
{
    public function setUp()
    {
        @session_start();
        parent::setUp();
    }
}
