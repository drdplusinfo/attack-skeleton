<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\AttackSkeleton;

use DrdPlus\AttackSkeleton\AttackController;
use Granam\Tests\ExceptionsHierarchy\Exceptions\AbstractExceptionsHierarchyTest;

class AttackSkeletonExceptionsHierarchyTest extends AbstractExceptionsHierarchyTest
{
    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getTestedNamespace(): string
    {
        return (new \ReflectionClass(AttackController::class))->getNamespaceName();
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