<?php
declare(strict_types=1);/** be strict for parameter types, https://www.quora.com/Are-strict_types-in-PHP-7-not-a-bad-idea */
namespace DrdPlus\Properties\Derived\Partials;

use DrdPlus\Properties\Derived\DerivedProperty;
use DrdPlus\Properties\Partials\WithHistoryTrait;
use Granam\Integer\IntegerInterface;
use Granam\Integer\Tools\ToInteger;
use Granam\Strict\Object\StrictObject;

/** @noinspection SingletonFactoryPatternViolationInspection */

/**
 * Unlike \DrdPlus\Properties\AbstractDerivedProperty this is not an enum because is not intended to be persisted (is
 * calculated / derived always)
 */
abstract class AbstractDerivedProperty extends StrictObject implements DerivedProperty
{
    use WithHistoryTrait;

    /**
     * @var int
     */
    protected $value;

    /**
     * @param int $value
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    protected function __construct($value)
    {
        $this->value = ToInteger::toInteger($value);
        $this->noticeChange();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getValue();
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int|IntegerInterface $value
     * @return AbstractDerivedProperty
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function add($value): AbstractDerivedProperty
    {
        $increased = clone $this; // clones history as well
        $increased->value += ToInteger::toInteger($value);
        $increased->noticeChange();

        return $increased;
    }

    /**
     * @param int|IntegerInterface $value
     * @return AbstractDerivedProperty
     * @throws \Granam\Integer\Tools\Exceptions\WrongParameterType
     * @throws \Granam\Integer\Tools\Exceptions\ValueLostOnCast
     */
    public function sub($value): AbstractDerivedProperty
    {
        $decreased = clone $this; // clones history as well
        $decreased->value -= ToInteger::toInteger($value);
        $decreased->noticeChange();

        return $decreased;
    }
}