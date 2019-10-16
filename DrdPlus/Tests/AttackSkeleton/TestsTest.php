<?php declare(strict_types=1);

namespace DrdPlus\Tests\AttackSkeleton;

use DrdPlus\Tests\AttackSkeleton\Partials\AttackCalculatorTestTrait;

class TestsTest extends \DrdPlus\Tests\CalculatorSkeleton\TestsTest
{
    use AttackCalculatorTestTrait;

    /**
     * @test
     * @throws \ReflectionException
     */
    public function All_rules_skeleton_tests_are_used(): void
    {
        $reflectionClass = new \ReflectionClass(parent::class);
        $calculatorSkeletonDir = dirname($reflectionClass->getFileName());
        foreach ($this->getClassesFromDir($calculatorSkeletonDir) as $calculatorSkeletonTestClass) {
            if (is_a($calculatorSkeletonTestClass, \Throwable::class, true)) {
                continue;
            }
            $calculatorSkeletonTestClassReflection = new \ReflectionClass($calculatorSkeletonTestClass);
            if ($calculatorSkeletonTestClassReflection->isAbstract()
                || $calculatorSkeletonTestClassReflection->isInterface()
                || $calculatorSkeletonTestClassReflection->isTrait()
            ) {
                continue;
            }
            $expectedAttackTestClass = str_replace('\\CalculatorSkeleton', '\\AttackSkeleton', $calculatorSkeletonTestClass);
            self::assertTrue(
                class_exists($expectedAttackTestClass),
                "Missing test class {$expectedAttackTestClass} adopted from rules skeleton test class {$calculatorSkeletonTestClass}"
            );
            self::assertTrue(
                is_a($expectedAttackTestClass, $calculatorSkeletonTestClass, true),
                "$expectedAttackTestClass should be a child of $calculatorSkeletonTestClass"
            );

            $attackTestClassReflection = new \ReflectionClass($expectedAttackTestClass);
            self::assertContains(
                AttackCalculatorTestTrait::class,
                $attackTestClassReflection->getTraitNames(),
                sprintf("Adopted test '%s' should has attack trait '%s'", $expectedAttackTestClass, AttackCalculatorTestTrait::class)
            );
        }
    }
}