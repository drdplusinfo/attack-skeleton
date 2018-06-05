<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\AttackSkeleton;

use DrdPlus\CalculatorSkeleton\Controller;

class CalculatorSkeletonExceptionsHierarchyTest extends \DrdPlus\Tests\CalculatorSkeleton\CalculatorSkeletonExceptionsHierarchyTest
{
    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getTestedNamespace(): string
    {
        return (new \ReflectionClass(Controller::class))->getNamespaceName();
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getRootNamespace(): string
    {
        return $this->getTestedNamespace();
    }

}