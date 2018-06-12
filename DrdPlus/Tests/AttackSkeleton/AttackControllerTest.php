<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\AttackSkeleton;

use PHPUnit\Framework\TestCase;

class AttackControllerTest extends TestCase
{
    /**
     * @test
     */
    public function I_am_already_tested(): void
    {
        self::assertTrue(true, 'Everything is already covered by ' . CalculatorControllerTest::class);
    }
}