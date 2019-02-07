<?php
declare(strict_types=1);

namespace DrdPlus\Tests\AttackSkeleton;

use DeviceDetector\Parser\Bot;
use DrdPlus\Armourer\Armourer;
use DrdPlus\AttackSkeleton\ArmamentsUsabilityMessages;
use DrdPlus\AttackSkeleton\AttackServicesContainer;
use DrdPlus\AttackSkeleton\CurrentArmaments;
use DrdPlus\AttackSkeleton\CurrentArmamentsValues;
use DrdPlus\AttackSkeleton\CurrentProperties;
use DrdPlus\AttackSkeleton\CustomArmamentsRegistrar;
use DrdPlus\AttackSkeleton\PossibleArmaments;
use DrdPlus\CalculatorSkeleton\CurrentValues;
use DrdPlus\CalculatorSkeleton\Memory;
use DrdPlus\Codes\ItemHoldingCode;
use DrdPlus\Properties\Base\Agility;
use DrdPlus\Properties\Base\Charisma;
use DrdPlus\Properties\Base\Intelligence;
use DrdPlus\Properties\Base\Knack;
use DrdPlus\Properties\Base\Strength;
use DrdPlus\Properties\Base\Will;
use DrdPlus\Properties\Body\HeightInCm;
use DrdPlus\Properties\Body\Size;
use DrdPlus\RulesSkeleton\Configuration;
use DrdPlus\RulesSkeleton\HtmlHelper;
use DrdPlus\RulesSkeleton\ServicesContainer;
use DrdPlus\Tests\CalculatorSkeleton\Partials\AbstractCalculatorContentTest;
use Mockery\MockInterface;

/**
 * @method AttackServicesContainer getServicesContainer
 */
abstract class AbstractAttackTest extends AbstractCalculatorContentTest
{

    /**
     * @param CurrentArmamentsValues $currentArmamentsValues
     * @return CustomArmamentsRegistrar|MockInterface
     */
    protected function createCustomArmamentsRegistrar(CurrentArmamentsValues $currentArmamentsValues): CustomArmamentsRegistrar
    {
        $currentArmamentsRegistrar = $this->mockery(CustomArmamentsRegistrar::class);
        $currentArmamentsRegistrar->shouldReceive('registerCustomArmaments')
            ->with($currentArmamentsValues);

        return $currentArmamentsRegistrar;
    }

    /**
     * @return CurrentArmamentsValues|MockInterface
     */
    protected function createCurrentArmamentValues(): CurrentArmamentsValues
    {
        return $this->mockery(CurrentArmamentsValues::class);
    }

    /**
     * @return PossibleArmaments|MockInterface
     */
    protected function createPossibleArmaments(): PossibleArmaments
    {
        return $this->mockery(PossibleArmaments::class);
    }

    /**
     * @return ArmamentsUsabilityMessages|MockInterface
     */
    protected function createArmamentsUsabilityMessages(): ArmamentsUsabilityMessages
    {
        return $this->mockery(ArmamentsUsabilityMessages::class);
    }

    /**
     * @return ArmamentsUsabilityMessages|MockInterface
     */
    protected function createEmptyArmamentsUsabilityMessages(): ArmamentsUsabilityMessages
    {
        return new ArmamentsUsabilityMessages($this->createAllPossibleArmaments());
    }

    protected function createAllPossibleArmaments(): PossibleArmaments
    {
        return new PossibleArmaments(
            Armourer::getIt(),
            $this->createMaximalCurrentProperties(),
            ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS),
            ItemHoldingCode::getIt(ItemHoldingCode::TWO_HANDS)
        );
    }

    /**
     * @param int $maximum = 999
     * @return CurrentProperties|MockInterface
     */
    protected function createMaximalCurrentProperties(int $maximum = 999): CurrentProperties
    {
        $currentProperties = $this->mockery(CurrentProperties::class);
        $currentProperties->shouldReceive('getCurrentStrength')
            ->andReturn(Strength::getIt($maximum));
        $currentProperties->shouldReceive('getCurrentAgility')
            ->andReturn(Agility::getIt($maximum));
        $currentProperties->shouldReceive('getCurrentKnack')
            ->andReturn(Knack::getIt($maximum));
        $currentProperties->shouldReceive('getCurrentWill')
            ->andReturn(Will::getIt($maximum));
        $currentProperties->shouldReceive('getCurrentIntelligence')
            ->andReturn(Intelligence::getIt($maximum));
        $currentProperties->shouldReceive('getCurrentCharisma')
            ->andReturn(Charisma::getIt($maximum));
        $currentProperties->shouldReceive('getCurrentSize')
            ->andReturn(Size::getIt($maximum));
        $currentProperties->shouldReceive('getCurrentHeightInCm')
            ->andReturn(HeightInCm::getIt($maximum));
        return $currentProperties;
    }

    protected function createDefaultCurrentArmaments(): CurrentArmaments
    {
        return new CurrentArmaments(
            new CurrentProperties(new CurrentValues([], $this->createMemory())),
            $currentArmamentValues = $this->createEmptyCurrentArmamentValues(),
            Armourer::getIt(),
            $this->createCustomArmamentsRegistrar($currentArmamentValues)
        );
    }

    /**
     * @return CurrentArmamentsValues|MockInterface
     */
    protected function createEmptyCurrentArmamentValues(): CurrentArmamentsValues
    {
        return new CurrentArmamentsValues($this->createEmptyCurrentValues());
    }

    /**
     * @return CurrentValues|MockInterface
     */
    protected function createEmptyCurrentValues(): CurrentValues
    {
        $currentValues = $this->mockery(CurrentValues::class);
        $currentValues->shouldReceive('getCurrentValue')
            ->with($this->type('string'))
            ->andReturnNull();
        $currentValues->shouldReceive('getSelectedValue')
            ->with($this->type('string'))
            ->andReturnNull();
        return $currentValues;
    }

    /**
     * @return Memory|MockInterface
     */
    protected function createMemory(): Memory
    {
        $memory = $this->mockery(Memory::class);
        $memory->shouldReceive('getValue')
            ->andReturnNull();

        return $memory;
    }

    protected function getAttackServicesContainer(): AttackServicesContainer
    {
        static $attackServicesContainer;
        if ($attackServicesContainer === null) {
            $attackServicesContainer = $this->createAttackServicesContainer();
        }
        return $attackServicesContainer;
    }

    protected function createAttackServicesContainer(): AttackServicesContainer
    {
        return new AttackServicesContainer($this->getConfiguration(), $this->getHtmlHelper());
    }

    /**
     * @param Configuration|AttackServicesContainer|null $configuration
     * @param HtmlHelper|null $htmlHelper
     * @return ServicesContainer|AttackServicesContainer
     */
    protected function createServicesContainer(
        Configuration $configuration = null,
        HtmlHelper $htmlHelper = null
    ): ServicesContainer
    {
        return new AttackServicesContainer(
            $configuration ?? $this->getConfiguration(),
            $htmlHelper ?? $this->createHtmlHelper($this->getDirs())
        );
    }

    protected function getBot(): Bot
    {
        static $bot;
        if ($bot === null) {
            $bot = new Bot();
        }
        return $bot;
    }
}