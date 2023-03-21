<?php
namespace GDO\DogTick;

use GDO\Core\GDO_Module;
use GDO\Core\GDT_UInt;

/**
 * A corona game. Tick others to infect them. Only works if you are yourself infected.
 *
 * @version 6.10.1
 * @since 6.10.0
 * @author gizmore
 */
final class Module_DogTick extends GDO_Module
{

	public function onLoadLanguage(): void { $this->loadLanguage('lang/tick'); }

	public function getDependencies(): array
	{
		return [
			'Country',
			'Dog',
			'DogIRC',
		];
	}

	public function getClasses(): array
	{
		return [
			DOG_Tick::class,
		];
	}

	public function getConfig(): array
	{
		return [
			GDT_UInt::make('tick_min_score')->notNull()->initial('1'),
			GDT_UInt::make('tick_max_score')->notNull()->initial('4'),
			GDT_TickVariant::make('tick'),
		];
	}

}
