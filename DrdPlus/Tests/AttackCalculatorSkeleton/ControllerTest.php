<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\Calculators;

use DrdPlus\AttackCalculatorSkeleton\AttackForCalculator;
use DrdPlus\AttackCalculatorSkeleton\Controller;
use Granam\Tests\Tools\TestWithMockery;

class ControllerTest extends TestWithMockery
{
    /**
     * @test
     * @runInSeparateProcess
     */
    public function I_can_get_attack_object(): void
    {
        $controller = new Controller(
            __DIR__ . '/../../..',
            'https://example.com',
            __CLASS__ /* as cookie postfix */
        );
        self::assertInstanceOf(AttackForCalculator::class, $controller->getAttack());
    }
}