<?php

declare(strict_types=1);

namespace LaravelApp\tests\Feature;

use PHPUnit\Framework\Attributes\Group;
use Tests\TestCase;

class ExampleWithUnexpectedOutputTest extends TestCase
{
    #[Group('unexpected-output')]
    public function test_pass_example()
    {
        echo 'This is an unexpected output';

        $this->assertTrue(true);
    }
}
