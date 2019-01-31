<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Tests\Properties\Partials;

use DrdPlus\Tests\Properties\AbstractStoredPropertyTest;

abstract class AbstractFloatPropertyTest extends AbstractStoredPropertyTest
{
    protected function getValuesForTest(): array
    {
        return [0.01, 123.456];
    }

    /**
     * @test
     */
    public function Has_modifying_methods_return_value_annotated()
    {
        $reflectionClass = new \ReflectionClass(self::getSutClass());
        $classBasename = str_replace($reflectionClass->getNamespaceName() . '\\', '', $reflectionClass->getName());
        self::assertContains(<<<ANNOTATION
 * @method static {$classBasename} getIt(float | NumberInterface \$value)
ANNOTATION
            , $reflectionClass->getDocComment());
    }
}