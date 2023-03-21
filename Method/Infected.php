<?php
namespace GDO\DogTick\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\Dog\GDT_DogUser;
use GDO\DogTick\DOG_Tick;

/**
 * Check if a user is infected.
 *
 * @author gizmore
 */
final class Infected extends DOG_Command
{

	public function getCLITrigger()
	{
		return 'cc.ticked';
	}

	public function gdoParameters(): array
	{
		return [
			GDT_DogUser::make('user')->thyself(),
		];
	}

	public function dogExecute(DOG_Message $message, DOG_User $user = null)
	{
		$user = $user === null ? $message->user : $user;

		if ($tick = DOG_Tick::tickFor($user))
		{
			return $message->rply('msg_dog_infected', [$user->displayFullName(), $tick->displayDate(), $tick->getTicker()->displayFullName()]);
		}
		else
		{
			return $message->rply('msg_dog_not_infected', [$user->displayFullName()]);
		}
	}

}
