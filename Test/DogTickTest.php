<?php
namespace GDO\DogTick\Test;

use GDO\Dog\DogTestCase;
use function PHPUnit\Framework\assertMatchesRegularExpression;

final class DogTickTest extends DogTestCase
{
    private $ticked;
    
    public function testTick()
    {
        $this->ticked = $this->createUser('Ticked');
        $this->userGizmore();
        $response = $this->command('tick Ticked');
        assertMatchesRegularExpression('/ticked 1/', $response, 'Test if tick was succesful');
    }
    
    public function testScore()
    {
        $response = $this->command('tickscore');
        assertMatchesRegularExpression('/ticked 1/', $response, 'Test if tick was succesful');
    }

}
