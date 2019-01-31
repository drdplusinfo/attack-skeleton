<?php
declare(strict_types=1);

namespace DrdPlus\AttackSkeleton\Web;

trait ArmamentUsabilityTrait
{

    protected function getDisabled(bool $canUseIt): string
    {
        return $canUseIt
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