<?php
declare(strict_types=1);
/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */

namespace DrdPlus\Tests\AttackSkeleton;

use DrdPlus\AttackSkeleton\AttackController;
use DrdPlus\Tests\FrontendSkeleton\AbstractContentTest;

class AttackControllerTest extends AbstractContentTest
{
    /**
     * @test
     */
    public function I_can_get_local_url_with_scalar_but_non_string_additional_parameters(): void
    {
        $controller = new AttackController(
            $this->createHtmlHelper(),
            'https://example.com',
            'foo',
            $this->getDocumentRoot(),
            $this->getVendorRoot()
        );
        $encodedBrackets0 = \urlencode('[0]');
        $encodedBrackets1 = \urlencode('[1]');
        self::assertSame(
            '?just+SOME+boolean+PrOpErTy=1&some+number=123'
            . "&just+an+array+with+non-string+content$encodedBrackets0=-5"
            . "&just+an+array+with+non-string+content$encodedBrackets1=",
            $controller->getLocalUrlWithQuery([
                'just SOME boolean PrOpErTy' => true,
                'some number' => 123,
                'just an array with non-string content' => [
                    -5,
                    false
                ],
            ])
        );
    }
}