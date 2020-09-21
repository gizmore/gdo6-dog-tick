<?php
namespace GDO\DogTick;

use GDO\Core\GDO_Module;

/**
 * A corono game. Tick others to infect them. Only works if you are yourself infected.
 * @author gizmore
 */
final class Module_DogTick extends GDO_Module
{
    public function onLoadLanguage() { return $this->loadLanguage('lang/tick'); }
    
    public function getClasses()
    {
        return array(
            'GDO\\DogTick\\DOG_Tick',
        );
    }
    
}
