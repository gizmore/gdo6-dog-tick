<?php
namespace GDO\DogTick;

use GDO\Core\GDO;
use GDO\Core\GDT_AutoInc;
use GDO\Core\GDT_CreatedAt;
use GDO\Date\Time;
use GDO\Dog\DOG_User;
use GDO\Dog\GDT_DogUser;

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
		return self::blank([
			'tick_by' => $by->getID(),
			'tick_to' => $to->getID(),
		])->insert();
	}

	public static function isInfected(DOG_User $user)
	{
		return !!self::tickFor($user);
	}

	###############
	### Getters ###
	###############

	public static function tickFor(DOG_User $user)
	{
		return self::table()->getBy('tick_to', $user->getID());
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
		return self::table()->countWhere();
	}

	public static function bestPlayer()
	{
		$row = self::table()->select('tick_by, COUNT(*) c')->
		group('tick_by')->
		order('c DESC')->
		first()->exec()->fetchRow();
		return $row ? DOG_User::findById($row[0]) : null;
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
