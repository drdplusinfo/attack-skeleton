<?php
declare(strict_types=1);

namespace DrdPlus\AttackSkeleton;

use Granam\Strict\Object\StrictObject;

class FrontendHelper extends StrictObject
{
    /**
     * @param array $additionalParameters
     * @return string
     */
    public function getLocalUrlWithQuery(array $additionalParameters = []): string
    {
        /** @var array $parameters */
        $parameters = $_GET;
        if ($additionalParameters) {
            foreach ($additionalParameters as $name => $value) {
                $parameters[$name] = $value;
            }
        }
        $queryParts = [];
        foreach ($parameters as $name => $value) {
            if (\is_array($value)) {
                /** @var array $value */
                foreach ($value as $index => $item) {
                    $queryParts[] = \urlencode("{$name}[{$index}]") . '=' . \urlencode((string)$item);
                }
            } else {
                $queryParts[] = \urlencode((string)$name) . '=' . \urlencode((string)$value);
            }
        }
        $query = '';
        if ($queryParts) {
            $query = '?' . \implode('&', $queryParts);
        }

        return $query;
    }

    public function formatInteger(int $integer): string
    {
        return $integer >= 0
            ? ('+' . $integer)
            : (string)$integer;
    }

    public function getLocalUrlToAction(string $action): string
    {
        return $this->getLocalUrlWithQuery([AttackRequest::ACTION => $action]);
    }

    public function getLocalUrlToCancelAction(): string
    {
        return $this->getLocalUrlToAction('');
    }
}