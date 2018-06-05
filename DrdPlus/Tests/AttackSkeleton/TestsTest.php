<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\AttackSkeleton;

class TestsTest extends \DrdPlus\Tests\CalculatorSkeleton\TestsTest
{

    protected function getParentTestsReferentialClass(): string
    {
        return \DrdPlus\Tests\CalculatorSkeleton\TestsTest::class;
    }
}