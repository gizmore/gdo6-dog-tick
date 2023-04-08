<?php
declare(strict_types=1);
namespace GDO\DogTick\Test;

use GDO\Dog\DOG_Room;
use GDO\Dog\DOG_User;
use GDO\DogIRC\Test\IRCTestCase;
use GDO\DogTick\DOG_Tick;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertTrue;

final class DogTickTest extends IRCTestCase
{

	private DOG_User $ticked;

	private ?DOG_Room $room;

	public function testInit()
	{
		$this->room = $this->getDogRoom();
		$this->ticked = $this->createUser('Ticked');
		$this->user($this->userGizmore2());
		$this->ircPrivmsgRoom('cc.init');
		assertTrue(DOG_Tick::isInfected($this->doguser), 'Check if first infection works.');
	}

	public function testTick()
	{
		$response = $this->ircPrivmsgRoom('cc.tick Ticked');
		assertStringContainsString('You ticked Τicked', $response, 'Test if tick was succesful');
	}

	public function testScore(): void
	{
		$response = $this->ircPrivmsgRoom('cc.stats');
		assertTrue(str_contains($response, '2 people'), 'Test if tickstats do work');
		assertTrue(str_contains($response, 'infected 2'), 'Test if tickstats do work correctly');
	}

	public function testUserScore(): void
	{
		$r = $this->ircPrivmsgRoom('cc.user');
		self::assertStringContainsString('infected 2', $r);
	}

}
