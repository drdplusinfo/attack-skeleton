<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\Calculators;

use DrdPlus\Calculators\AttackSkeleton\Controller;
use Granam\Tests\Tools\TestWithMockery;

class ControllerTest extends TestWithMockery
{
    /**
     * @test
     * @runInSeparateProcess because of cookies
     */
    public function I_can_use_it(): void
    {
        $controller = new Controller(static::class);
        self::assertTrue($controller->shouldRemember(), 'Memory should be remembered by default');
    }
}