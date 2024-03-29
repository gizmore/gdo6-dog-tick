<?php
declare(strict_types=1);
namespace GDO\DogTick\lang;
return [
	'dog_help_tick_init' => 'Create an initial infection to yourself.',
	'err_dog_tick_init_failed' => 'You were already infected.',
	'msg_dog_tick_init_succeeded' => 'You got the initial infection.',

	'dog_help_tick' => 'Infect a user with the corona disease.',
	'err_dog_no_disease' => 'You do not have the corona yet.',
	'err_dog_already_ticked' => '%s got already infected by %s on %s.',
	'mutated' => 'It mutated!',
	'not_mutated' => 'It looks normal.',
	'msg_dog_ticked' => 'You ticked %s for %s points. They now have corona variant %s - %s. You have infected %s persons!',
	'msg_dog_you_are_infected' => '%s has infected you with %s corona! You are his victim number %s. You can infect others with the "tick" command.',

	'dog_help_ticked' => 'Check if a user is infected.',
	'msg_dog_not_infected' => '%s is not infected yet.',
	'msg_dog_infected' => '%s is a poor soul who got infected on %s by %s on purpose!',

	'dog_help_tickstats' => 'Show statistics for the corona game.',
	'msg_dog_tickstats_total' => 'In this corona game, %s people have been infected. The best player, %s, has infected %s people.',
	'msg_dog_tickstats_top10' => '%s Corona players, page %s/%s: %s.',
	'msg_dog_tickstats_victims' => '%s latest Corona victims, page %s/%s: %s.',

	'dog_help_tickstats_user' => 'Show statistics for a user of the corona game.',
	'msg_dog_tickstats_user_nope' => '%s has not infected anyone and got ticked %s time(s). %s',
	'msg_dog_tickstats_user' => '%s has infected %s people and got ticked %s time(s). %s',
	'has_corona' => 'They have corona variants %s!',
	'no_corona' => 'They don\'t have any corona.',

	'dog_help_tick_reset' => 'Reset the game completely. All progress will be lost.',
	'msg_tick_reset' => 'The game has been reset.',
];
