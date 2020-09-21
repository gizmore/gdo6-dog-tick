<?php
namespace GDO\DogTick\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogTick\DOG_Tick;
use GDO\Form\GDT_Confirmation;

final class Reset extends DOG_Command
{
    public $priority = 100;
    public $group = 'Corona';
    public $trigger = 'tick_reset';
    
    public function getPermission() { return 'admin'; }
    
    public function gdoParameters()
    {
        return array(
            GDT_Confirmation::make('confirm')->phrase(),
        );
    }
    
    public function dogExecute(DOG_Message $message)
    {
        $user = $message->user;
        if (!DOG_Tick::isInfected($user))
        {
            DOG_Tick::tick($user, $user);
            $message->rply('msg_dog_tick_init_succeeded');
        }
        else
        {
            $message->rply('err_dog_tick_init_failed');
        }
    }
    
    
}

