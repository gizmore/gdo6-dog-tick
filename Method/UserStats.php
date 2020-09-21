<?php
namespace GDO\DogTick\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\Dog\GDT_DogUser;
use GDO\DogTick\DOG_Tick;

/**
 * Show stats for a user.
 * @author gizmore
 *
 */
final class UserStats extends DOG_Command
{
    public $priority = 70;
    public $group = 'Corona';
    public $trigger = 'tickstats_for';
    
    public function gdoParameters()
    {
        return array(
            GDT_DogUser::make('user')->thyself(),
        );
    }
    
    public function dogExecute(DOG_Message $message, DOG_User $user=null)
    {
        $user = $user === null ? $message->user : $user;
        
        $infected = DOG_Tick::isInfected($user) ? $message->t('has_corona') : $message->t('no_corona'); 
        $numTicks = DOG_Tick::numTicks($user);
        $numTicked = DOG_Tick::numTicked($user);
        
        if ($numTicks == 0)
        {
            return $message->rply('msg_dog_tickstats_user_nope', [$user->displayFullName(), $numTicked, $infected]);
        }
        else
        {
            return $message->rply('msg_dog_tickstats_user', [$user->displayFullName(), $numTicks, $numTicked, $infected]);
        }
    }
    
}
