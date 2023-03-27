<?php
namespace GDO\DogTick\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\DogTick\DOG_Tick;
use GDO\UI\GDT_Confirm;

/**
 * Reset the game.
 *
 * @author gizmore
 */
final class Reset extends DOG_Command
{

	public $priority = 100;

	public function getCLITrigger(): string
	{
		return 'cc.reset';
	}

	public function getPermission(): ?string
	{
		return 'admin';
	}

	public function gdoParameters(): array
	{
		return [
			GDT_Confirm::make('confirm'),
		];
	}

	public function dogExecute(DOG_Message $message, $confirmed)
	{
		DOG_Tick::table()->truncate();
		$message->rply('msg_tick_reset');
	}

}
