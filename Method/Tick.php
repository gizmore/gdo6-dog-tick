<?php
declare(strict_types=1);
namespace GDO\DogTick\Method;

use GDO\Dog\DOG_Command;
use GDO\Dog\DOG_Message;
use GDO\Dog\DOG_User;
use GDO\Dog\GDT_DogUser;
use GDO\DogTick\DOG_Tick;

/**
 * Infect a user.
 *
 * @author gizmore
 */
final class Tick extends DOG_Command
{

	public int $priority = 40;

	public function getCLITrigger(): string
	{
		return 'cc.tick';
	}

	public function gdoParameters(): array
	{
		return [
			GDT_DogUser::make('user')->online()->notNull(),
		];
	}

	public function getMethodTitle(): string
	{
		return t('dog_help_tick');
	}


	public function dogExecute(DOG_Message $message, DOG_User $user): void
	{
		if (!DOG_Tick::isInfected($message->user))
		{
			$message->rply('err_dog_no_disease');
		}

		elseif ($tick = DOG_Tick::tickFor($user, $message->user))
		{
			$message->rply('err_dog_already_ticked',
				[
					$user->displayFullName(),
					$tick->getTicker()
						->displayFullName(),
					$tick->displayDate(),
				]);
		}

		else
		{
			$tick = DOG_Tick::tick($user, $message->user);
			$message->rply('msg_dog_ticked', [
				$user->displayFullName(),
				$tick->getScore(),
				$tick->getVariantName(),
				$tick->displayMutated(),
				DOG_Tick::numTicks($message->user),
			]);
			$numTicks = DOG_Tick::numTicks($message->user);
			$user->send($message->t('msg_dog_you_are_infected', [
				$message->user->displayFullName(),
				$tick->getVariantName(),
				$numTicks,
			]));
		}
	}

}
