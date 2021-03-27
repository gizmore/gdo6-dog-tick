<?php
namespace GDO\DogTick;

use GDO\Core\GDO_Module;

/**
 * A corona game. Tick others to infect them. Only works if you are yourself infected.
 * @author gizmore
 * @version 6.10.1
 * @since 6.10.0
 */
final class Module_DogTick extends GDO_Module
{
    public function onLoadLanguage() { return $this->loadLanguage('lang/tick'); }
    
    public function getDependencies() { return ['Dog', 'DogIRC']; }
    
    public function getClasses()
    {
        return [
            DOG_Tick::class,
        ];
    }
    
}
