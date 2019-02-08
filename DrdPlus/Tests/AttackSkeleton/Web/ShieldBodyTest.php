<?php
declare(strict_types=1);

namespace DrdPlus\Tests\AttackSkeleton\Web;

use DrdPlus\Armourer\Armourer;
use DrdPlus\AttackSkeleton\Web\AddCustomArmament\AddCustomShieldBody;
use DrdPlus\AttackSkeleton\Web\ShieldBody;

class ShieldBodyTest extends AbstractArmamentBodyTest
{
    public function provideArmamentBodyAndExpectedContent(): array
    {
        return [
            'all shields possible' => [
                $shieldBody = new ShieldBody(
                    $this->getEmptyCustomArmamentsState(),
                    $this->getDefaultCurrentArmaments(),
                    $this->getEmptyCurrentArmamentValues(),
                    $this->getAllPossibleArmaments(),
                    $this->getEmptyArmamentsUsabilityMessages(),
                    $this->getFrontendHelper(),
                    Armourer::getIt(),
                    new AddCustomShieldBody($this->getFrontendHelper())
                ),
                <<<HTML
<div class="row " id="chooseShield">
  <div class="col">
    <div class="messages">
        
    </div>
    <a title="Přidat vlastní štít" href="?action=add_new_shield" class="button add">+</a>
    <label>
      <select name="shield" title="Štít">
         <option value="without_shield" selected >
  bez štítu +0
</option><option value="buckler"  >
  pěstní štítek +2
</option><option value="small_shield"  >
  malý štít +4
</option><option value="medium_shield"  >
  střední štít +5
</option><option value="heavy_shield"  >
  velký štít +6
</option><option value="pavise"  >
  pavéza +7
</option> 
      </select>
    </label>
  </div>
</div>
HTML
    ,
            ],
        ];
    }
}