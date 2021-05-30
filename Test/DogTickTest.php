<?php
namespace GDO\DogTick\Test;

use function PHPUnit\Framework\assertMatchesRegularExpression;
use GDO\DogIRC\IRCTestCase;
use function PHPUnit\Framework\assertTrue;
use GDO\DogTick\DOG_Tick;

final class DogTickTest extends IRCTestCase
{
    private $ticked;
    private $room;
    
    public function testInit()
    {
        $this->room = $this->getDogRoom();
        $this->ticked = $this->createUser('Ticked');
        $this->user($this->userGizmore2());
        $this->ircPrivmsgRoom('corona.init.');
        assertTrue(DOG_Tick::isInfected($this->doguser), 'Check if first infection works.');
    }
    
    public function testTick()
    {
        $response = $this->ircPrivmsgRoom('corona.tick Tick');
        assertTrue(strpos($response, 'You ticked Τicked') !== false, 'Test if tick was succesful');
    }
    
    public function testScore()
    {
        $response = $this->ircPrivmsgRoom('corona.stats.');
        assertTrue(strpos($response, 'ticked 1 time') !== false, 'Test if tickstats do work');
        assertTrue(strpos($response, 'infected 2') !== false, 'Test if tickstats do work correctly');
    }

}
