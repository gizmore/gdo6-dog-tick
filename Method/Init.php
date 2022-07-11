<?php
namespace GDO\DogTick\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\Dog;
use GDO\DogTick\DOG_Tick;

final class Init extends DOG_Command
{
    public $priority = 10;
    public $group = 'Corona';
    public $trigger = 'init';
    
    public function getPermission() : ?string { return Dog::OWNER; }
    
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

