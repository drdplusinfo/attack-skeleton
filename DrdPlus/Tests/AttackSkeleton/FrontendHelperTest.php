<?php
declare(strict_types=1);

namespace DrdPlus\Tests\AttackSkeleton;

use DrdPlus\AttackSkeleton\FrontendHelper;
use DrdPlus\Tests\RulesSkeleton\Partials\AbstractContentTest;

class FrontendHelperTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_get_local_url_with_scalar_but_non_string_additional_parameters(): void
    {
        $frontendHelper = new FrontendHelper();
        $encodedBrackets0 = \urlencode('[0]');
        $encodedBrackets1 = \urlencode('[1]');
        self::assertSame(
            '?just+SOME+boolean+PrOpErTy=1&some+number=123'
            . "&just+an+array+with+non-string+content$encodedBrackets0=-5"
            . "&just+an+array+with+non-string+content$encodedBrackets1=",
            $frontendHelper->getLocalUrlWithQuery([
                'just SOME boolean PrOpErTy' => true,
                'some number' => 123,
                'just an array with non-string content' => [
                    -5,
                    false,
                ],
            ])
        );
    }
}