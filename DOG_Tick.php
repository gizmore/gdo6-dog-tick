<?php
declare(strict_types=1);
namespace GDO\DogTick;

use GDO\Core\GDO;
use GDO\Core\GDT;
use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_Checkbox;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_UInt;
use GDO\Date\Time;
use GDO\Dog\DOG_User;
use GDO\Dog\GDT_DogUser;
use GDO\Util\Arrays;
use GDO\Util\Random;

/**
 * Corona case table.
 *
 * @version 7.0.3
 * @author gizmore
 * @see GDO
 */
final class DOG_Tick extends GDO
{

	/**
	 * Insert tick event.
	 */
	public static function tick(DOG_User $to, DOG_User $by): self
	{
		$variants = self::getVariantsFor($by, true);
		$variant = Random::mrandomItemCallback($variants, function (string $v) {
			return Module_DogTick::instance()->coronaConfigVariant($v)[1];
		});
		$mutated = '0';
		$variant = self::mutate($variant, $mutated);
		return self::blank([
			'tick_by' => $by->getID(),
			'tick_to' => $to->getID(),
			'tick_type' => $variant,
			'tick_score' => self::getRandomDamage($variant),
			'tick_mutated' => $mutated,
		])->insert();
	}

	private static function mutate(string $variant, string &$mutated): string
	{
		$module = Module_DogTick::instance();
		$config = $module->coronaConfigVariant($variant);
		if (Random::mchance($config[3]))
		{
			$mutated = GDT::ONE;
			return self::getRandomVariant();
		}
		return $variant;
	}


	public static function isInfected(DOG_User $user): bool
	{
		return !!self::tickFor($user);
	}


	public static function tickFor(DOG_User $user, DOG_User $by=null): ?static
	{
		$query = self::table()->select()->where("tick_to={$user->getID()}");
		if ($by)
		{
			$byVariants = self::getVariantsFor($by);
			$query->where(sprintf('tick_type IN ("%s")', implode('","', $byVariants)));
		}
		return $query->exec()->fetchObject();
	}

	public static function numTicks(DOG_User $user): int
	{
		return self::table()->countWhere("tick_by={$user->getID()}");
	}

	public static function numTicked(DOG_User $user): int
	{
		return self::table()->countWhere("tick_to={$user->getID()}");
	}

	public static function totalVictims(): int
	{
		return (int) self::table()->select('COUNT(distinct(tick_to))')->exec()->fetchVar();
	}

	public static function bestPlayers(int $page=1): array
	{
		return
			self::table()->select('tick_by, COUNT(DISTINCT(tick_to)) c, SUM(tick_score) l')->
		group('tick_by')->
		order('l DESC')->
		limit(10, ($page-1)*10)->
		exec()->fetchAllObjects();
	}

	/**
	 * @return string[]
	 */
	private static function getVariantsFor(DOG_User $user, bool $random=false): array
	{
		$variants = self::table()->select('DISTINCT(tick_type)')->
			where("tick_to={$user->getID()}")->
			orderRand()->exec()->fetchColumn();
		return $variants ?: ($random ? [self::getRandomVariant()] : []);
	}

	private static function getRandomVariant(): string
	{
		$conf = Module_DogTick::instance()->coronaConfig();
		return Random::mrandomItemCallback($conf, function(array $item): float {
			return $item[1];
		})[0];
	}

	private static function getRandomDamage(string $variant): int
	{
		$module = Module_DogTick::instance();
		$variant = $module->coronaConfigVariant($variant);
		$min = $module->cfgMinScore();
		$max = $module->cfgMaxScore();
		$dmg = Random::mrand($min, $max);
		return (int) min([max([$min, (int)round($dmg * $variant[2]+.25)]), $max]);
	}

	public static function displayVariantsFor(?DOG_User $user): string
	{
		$variants = self::getVariantsFor($user);
		return Arrays::implodeHuman($variants);
	}

	public static function bestPlayer(): DOG_User
	{
		return self::table()->select('dog_tick.*, SUM(tick_score) AS lvl')->group('tick_by')->
		order('lvl')->
		exec()->fetchObject()->getTicker();

	}

	###########
	### GDO ###
	###########


	public function gdoCached(): bool { return false; }


	public function gdoColumns(): array
	{
		return [
			GDT_AutoInc::make('tick_id'),
			GDT_DogUser::make('tick_by')->notNull(),
			GDT_DogUser::make('tick_to')->notNull(),
			GDT_TickVariant::make('tick_type')->notNull(),
			GDT_UInt::make('tick_score')->notNull(),
			GDT_Checkbox::make('tick_mutated')->notNull(),
			GDT_CreatedAt::make('tick_at'),
		];
	}

	public function getTicker(): DOG_User { return $this->gdoValue('tick_by'); }

	public function getTickerID(): string { return $this->gdoVar('tick_by'); }

	public function getVictim(): DOG_User { return $this->gdoValue('tick_to'); }

	public function getVictimID(): string { return $this->gdoVar('tick_to'); }

	public function getTickDate(): string { return $this->gdoVar('tick_at'); }

	public function displayDate(): string { return Time::displayDate($this->gdoVar('tick_at')); }

	public function getVariantName(): string { return $this->gdoVar('tick_type'); }

	public function isMutated(): string { return $this->gdoVar('tick_mutated'); }

	public function displayMutated(): string
	{
		return $this->isMutated() ? t('mutated') : t('not_mutated');
	}

	public function getScore(): string { return $this->gdoVar('tick_score'); }

}
