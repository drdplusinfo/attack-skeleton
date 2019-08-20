<?php declare(strict_types=1);

namespace DrdPlus\Tests\AttackSkeleton;

use DrdPlus\CalculatorSkeleton\CalculatorApplication;
use DrdPlus\CalculatorSkeleton\Memory;
use Mockery\MockInterface;

/**
 * @backupGlobals enabled
 */
class CalculatorApplicationTest extends \DrdPlus\Tests\CalculatorSkeleton\CalculatorApplicationTest
{
    use Partials\AttackCalculatorTestTrait;

    protected static function getSutClass(string $sutTestClass = null, string $regexp = '~\\\Tests(.+)Test$~'): string
    {
        return defined('DRD_PLUS_APPLICATION_CLASS')
            ? DRD_PLUS_APPLICATION_CLASS
            : CalculatorApplication::class;
    }

    /**
     * @return Memory|MockInterface
     */
    protected function createMemoryForHistoryDeletion(): Memory
    {
        $memory = parent::createMemoryForHistoryDeletion();
        $memory->shouldReceive('getValue')
            ->andReturnNull();
        return $memory;
    }

}