<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\Calculators;

use DrdPlus\AttackSkeleton\AttackForCalculator;
use DrdPlus\AttackSkeleton\Controller;
use Granam\Tests\Tools\TestWithMockery;

class ControllerTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_get_attack_object(): void
    {
        $controller = new Controller(
            'https://example.com',
            __CLASS__, /* as cookie postfix */
            __DIR__ . '/../../..',
            __DIR__ . '/../../../vendor'
        );
        self::assertInstanceOf(AttackForCalculator::class, $controller->getAttack());
    }
}