<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class UnitTest extends TestCase
{
    public function testDemo(): void
    {
        $demo = new Demo();
        $demo->setDemo('demo');

        $this->assertTrue($demo->getDemo() === 'demo');
    }
}
