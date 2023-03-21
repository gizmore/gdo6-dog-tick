<?php
namespace GDO\DogTick\Test;

use GDO\Dog\DOG_Room;
use GDO\DogIRC\IRCTestCase;
use GDO\DogTick\DOG_Tick;
use GDO\User\GDO_User;
use function PHPUnit\Framework\assertMatchesRegularExpression;
use function PHPUnit\Framework\assertTrue;

final class DogTickTest extends IRCTestCase
{

	private GDO_User $ticked;
	private DOG_Room $room;

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
		assertTrue(strpos($response, 'You ticked Τicked') !== false, 'Test if tick was succesful');
	}

	public function testScore(): void
	{
		$response = $this->ircPrivmsgRoom('cc.stats');
		assertTrue(strpos($response, 'ticked 1 time') !== false, 'Test if tickstats do work');
		assertTrue(strpos($response, 'infected 2') !== false, 'Test if tickstats do work correctly');
	}

	public function testUserScore(): void
	{
		$r = $this->ircPrivmsgRoom('cc.user');
		self::assertStringContainsString('infected 1 ', $r);
	}

}
