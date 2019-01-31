<?php
declare(strict_types=1); // on PHP 7+ are standard PHP methods strict to types of given parameters

namespace DrdPlus\Tests\Tables\Measurements\Tools;

use DrdPlus\Tables\Measurements\Tools\DummyEvaluator;
use Granam\Tests\Tools\TestWithMockery;

class DummyEvaluatorTest extends TestWithMockery
{

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Measurements\Exceptions\UnsupportedMethodCalled
     */
    public function I_can_not_use_it_for_evaluation()
    {
        $evaluator = new DummyEvaluator();
        $evaluator->evaluate(123);
    }
}