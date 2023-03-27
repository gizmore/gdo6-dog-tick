<?php
namespace GDO\DogTick;

use Crypto\Rand;
use GDO\Core\GDO;
use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_Checkbox;
use GDO\Core\GDT_CreatedAt;
use GDO\Core\GDT_UInt;
use GDO\Date\Time;
use GDO\Dog\DOG_User;
use GDO\Dog\GDT_DogUser;
use GDO\Util\Random;

/**
 * Corona case table.
 *
 * @author gizmore
 * @see GDO
 */
final class DOG_Tick extends GDO
{

	###########
	### GDO ###
	###########
	/**
	 * Insert tick event.
	 *
	 * @param DOG_User $to
	 * @param DOG_User $by
	 *
	 * @return self
	 */
	public static function tick(DOG_User $to, DOG_User $by)
	{
		$variants = self::getVariantsFor($by);

		$tick = self::blank([
			'tick_by' => $by->getID(),
			'tick_to' => $to->getID(),
		]);

		$tick->mutate();

		return $tick->insert();
	}

	public function mutate(): self
	{
		$module = Module_DogTick::instance();
		$chance = $module->cfgMutations();
		Random::mchance($chance);
		return $this;
	}

	private static function getTickVariant(DOG_User $user): string
	{
	}

	public static function isInfected(DOG_User $user)
	{
		return !!self::tickFor($user);
	}

	###############
	### Getters ###
	###############

	public static function tickFor(DOG_User $user, DOG_User $by)
	{
		$byVariants = self::getVariantsFor($by);
		return self::table()->select()->where("tick_to={$user->getID()}")->
			where(sprintf('tick_type IN "%s"', implode('","', $byVariants)))->
			exec()->fetchObject();
	}

	public static function numTicks(DOG_User $user)
	{
		return self::table()->countWhere("tick_by={$user->getID()}");
	}

	public static function numTicked(DOG_User $user)
	{
		return self::table()->countWhere("tick_to={$user->getID()}");
	}

	public static function totalVictims()
	{
		return self::table()->select('distinct("tick_to")')->countWhere();
	}

	public static function bestPlayers(int $page=1): array
	{
		return
			self::table()->select('tick_by, COUNT(DISTINCT(tick_to)) c, SUM(tick_level) l')->
		group('tick_by')->
		order('l DESC')->
		limit(10, ($page-1)*10)->
		exec()->fetchAllObjects();
	}

	private static function getVariantsFor(DOG_User $by): array
	{
		return self::table()->select('tick_type')->
			where('tick_to')->
			exec()->fetchColumn();
	}

	public function gdoCached(): bool { return false; }

	##############
	### Static ###
	##############

	public function gdoColumns(): array
	{
		return [
			GDT_AutoInc::make('tick_id'),
			GDT_DogUser::make('tick_by')->notNull(),
			GDT_DogUser::make('tick_to')->notNull(),
			GDT_TickVariant::make('tick_type')->notNull(),
			GDT_UInt::make('tick_level')->notNull(),
			GDT_Checkbox::make('tick_mutated')->notNull(),
			GDT_CreatedAt::make('tick_at'),
		];
	}

	/**
	 * @return DOG_User
	 */
	public function getTicker() { return $this->gdoValue('tick_by'); }

	public function getTickerID() { return $this->gdoVar('tick_by'); }

	/**
	 * @return DOG_User
	 */
	public function getVictim() { return $this->gdoValue('tick_to'); }

	public function getVictimID() { return $this->gdoVar('tick_to'); }

	public function getTickDate() { return $this->gdoVar('tick_at'); }

	public function displayDate() { return Time::displayDate($this->gdoVar('tick_at')); }

}
