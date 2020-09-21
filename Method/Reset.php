<?php
namespace GDO\DogTick\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogTick\DOG_Tick;
use GDO\UI\GDT_Confirm;

/**
 * Reset the game.
 * @author gizmore
 */
final class Reset extends DOG_Command
{
    public $priority = 100;
    public $group = 'Corona';
    public $trigger = 'tick_reset';
    
    public function getPermission() { return 'admin'; }
    
    public function gdoParameters()
    {
        return array(
            GDT_Confirm::make('confirm'),
        );
    }
    
    public function dogExecute(DOG_Message $message, $confirmed)
    {
        DOG_Tick::table()->truncate();
        $message->rply('msg_tick_reset');
    }
    
}
