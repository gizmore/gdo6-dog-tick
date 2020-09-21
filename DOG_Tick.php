<?php
namespace GDO\DogTick;

use GDO\Core\GDO;
use GDO\DB\GDT_AutoInc;
use GDO\Date\Time;
use GDO\Dog\GDT_DogUser;
use GDO\DB\GDT_CreatedAt;
use GDO\Dog\DOG_User;

/**
 * Corona case table.
 * @author gizmore
 * @see GDO
 */
final class DOG_Tick extends GDO
{
    ###########
    ### GDO ###
    ###########
    public function gdoCached() { return false; }
    
    public function gdoColumns()
    {
        return array(
            GDT_AutoInc::make('tick_id'),
            GDT_DogUser::make('tick_by')->notNull(),
            GDT_DogUser::make('tick_to')->notNull(),
            GDT_CreatedAt::make('tick_at'),
        );
    }
    
    ###############
    ### Getters ###
    ###############
    /**
     * @return DOG_User
     */
    public function getTicker() { return $this->getValue('tick_by'); }
    public function getTickerID() { return $this->getVar('tick_by'); }

    /**
     * @return DOG_User
     */
    public function getVictim() { return $this->getValue('tick_to'); }
    public function getVictimID() { return $this->getVar('tick_to'); }
    
    public function getTickDate() { return $this->getVar('tick_at'); }
    public function displayDate() { return Time::displayDate($this->getVar('tick_at')); }
    
    ##############
    ### Static ###
    ##############
    /**
     * Insert tick event.
     * @param DOG_User $to
     * @param DOG_User $by
     * @return self
     */
    public static function tick(DOG_User $to, DOG_User $by)
    {
        return self::blank(array(
            'tick_by' => $by->getID(),
            'tick_to' => $to->getID(),
        ))->insert();
    }
    
    public static function isInfected(DOG_User $user)
    {
        return !!self::tickFor($user);
    }
    
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
        orderDESC('c')->
        first()->exec()->fetchRow();
        return $row ? DOG_User::findById($row[0]) : null;
    }
    
}
