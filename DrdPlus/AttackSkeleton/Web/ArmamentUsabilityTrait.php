<?php
declare(strict_types=1);

namespace DrdPlus\AttackSkeleton\Web;

use Granam\String\StringInterface;

trait ArmamentUsabilityTrait
{
    protected function getSelected(StringInterface $current, StringInterface $selected): string
    {
        return $current->getValue() === $selected->getValue()
            ? 'selected'
            : '';
    }

    protected function getDisabled(bool $canUseIt): string
    {
        return !$canUseIt
            ? 'disabled'
            : '';
    }

    protected function getUsabilityPictogram(bool $canUseIt): string
    {
        return !$canUseIt
            ? '💪 '
            : '';
    }

}