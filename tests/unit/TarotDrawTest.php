<?php

use DestinyLab\TarotDeck;
use DestinyLab\TarotDraw;

class TarotDrawTest extends \Codeception\TestCase\Test
{
    /**
     * @var DestinyLab\TarotDeck
     */
    private $tarotDeck;

    /**
     * @var DestinyLab\TarotDraw
     */
    private $tarotDraw;

    protected function _before()
    {
        $this->tarotDeck = new TarotDeck('custom', [codecept_root_dir().'resources/']);
        $this->tarotDraw = new TarotDraw($this->tarotDeck);
    }

    public function testConstruct()
    {
        $this->assertInstanceOf("DestinyLab\\TarotDraw", $this->tarotDraw);
    }

    public function testConstructWithOptions()
    {
        $options = [
            'number' => 5
        ];
        $tarotDraw = new TarotDraw($this->tarotDeck, $options);
        $this->assertEquals(5, $tarotDraw->config()['number']);
    }

    public function testNumber()
    {
        $this->tarotDraw->number(10);
        $this->assertEquals(10, $this->tarotDraw->config()['number']);
    }

    public function testReversed()
    {
        $this->tarotDraw->reversed(false);
        $this->assertFalse($this->tarotDraw->config()['reversed']);
    }

    public function testShuffle()
    {
        $this->tarotDraw->shuffle(false);
        $this->assertFalse($this->tarotDraw->config()['shuffle']);
    }

    public function testIncludes()
    {
        $this->tarotDraw->includes(['Group1']);
        $expected = [1, 2, 3, 4, 5];
        $this->assertEquals($expected, $this->tarotDraw->config()['include']);
    }

    public function testExcludes()
    {
        $this->tarotDraw->excludes(['Group2']);
        $expected = [6, 7, 8, 9, 10];
        $this->assertEquals($expected, $this->tarotDraw->config()['exclude']);
    }

    /**
     * @expectedException DestinyLab\TarotException
     */
    public function testExcludesWithWrongGroup()
    {
        $this->tarotDraw->excludes(['Group3']);
    }

    public function testGetCards()
    {
        $this->assertInternalType('array', $this->tarotDraw->getCards());
    }

    public function testReset()
    {
        $this->tarotDraw->reset();
        $expected = [
            'number' => 0,
            'reversed' => true,
            'shuffle' => true,
            'include' => [],
            'exclude' => [],
        ];
        $this->assertContains($expected, $this->tarotDraw->config());
    }

    public function testDraw()
    {
        $options = [
            'exclude' => ['Group1'],
            'number' => 5,
        ];
        $tarotDraw = new TarotDraw($this->tarotDeck, $options);
        $this->assertCount(5, $tarotDraw->draw());
    }
}
