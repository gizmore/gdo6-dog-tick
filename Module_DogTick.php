<?php
declare(strict_types=1);
namespace GDO\DogTick;

use GDO\Core\Event\Entry;
use GDO\Core\Event\Table;
use GDO\Core\GDO_Module;
use GDO\Core\GDT_Checkbox;
use GDO\Core\GDT_Float;
use GDO\Core\GDT_UInt;
use GDO\Date\GDT_Duration;
use GDO\Date\Time;
use GDO\Util\FileUtil;

/**
 * A corona game. Tick others to infect them. Only works if you are yourself infected.
 *
 * @version 7.0.3
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
			GDT_UInt::make('tick_min_score')->notNull()->initial('2'),
			GDT_UInt::make('tick_max_score')->notNull()->initial('10'),
			GDT_Float::make('tick_mutations')->notNull()->initial('0.25'),
			GDT_Duration::make('tick_infect_timer')->notNull()->initial('1h'),
		];
	}
	public function cfgMinScore(): int { return $this->getConfigValue('tick_min_score'); }

	public function cfgMaxScore(): int { return $this->getConfigValue('tick_max_score'); }

	public function cfgMutations(): float { return $this->getConfigValue('tick_mutations'); }

	public function cfgInitInfect(): float { return $this->getConfigValue('tick_infect_timer'); }

	public function coronaConfig(): array
	{
		static $config;
		if (!$config)
		{
			if (FileUtil::isFile($this->filePath('config.php')))
			{
				$config = require $this->filePath('config.php');
			}
			else
			{
				$config = require $this->filePath('config.example.php');
			}
		}
		return $config;
	}

	public function coronaConfigVariant(string $name): array
	{
		$conf = $this->coronaConfig();
		return $conf[$name];
	}

	public function onModuleInit(): void
	{
		Table::timer(Entry::timer([self::class, 'timer'], null,Time::ONE_SECOND));
	}

	public static function timer(): void
	{
		echo "here";
	}

}
