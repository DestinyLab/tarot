<?php

use DestinyLab\TarotDeck;

class TarotDeckTest extends \Codeception\TestCase\Test
{
    /**
     * @var DestinyLab\TarotDeck
     */
    private $tarotDeck;
    private $path;

    protected function _before()
    {
        $this->path = codecept_root_dir().'resources/';
        $this->tarotDeck = new TarotDeck('custom', [$this->path]);
    }

    public function testConstruct()
    {
        $this->assertInstanceOf("DestinyLab\\TarotDeck", $this->tarotDeck);
    }

    /**
     * @expectedException DestinyLab\TarotException
     */
    public function testConstructWithNoFile()
    {
        new TarotDeck('noFile');
    }

    /**
     * @expectedException DestinyLab\TarotException
     */
    public function testConstructWithWrongFile()
    {
        new TarotDeck('error', [$this->path]);
    }

    public function testGetCards()
    {
        $this->assertCount(10, $this->tarotDeck->getCards());
    }

    public function testGetCardsByIds()
    {
        $this->assertCount(3, $this->tarotDeck->getCardsByIds([1, 2, 3]));
    }

    public function testGetCardsByGroups()
    {
        $this->assertCount(5, $this->tarotDeck->getCardsByGroups(['Group1']));
    }

    /**
     * @expectedException DestinyLab\TarotException
     */
    public function testGetCardsByWrongGroups()
    {
        $this->tarotDeck->getCardsByGroups(['abc']);
    }

    public function testValidGroup()
    {
        $this->assertTrue($this->tarotDeck->validGroup('Group1'));
    }

    public function testValidWrongGroup()
    {
        $this->assertFalse($this->tarotDeck->validGroup('abc'));
    }

    public function testGetSupportGroups()
    {
        $this->assertCount(2, $this->tarotDeck->getSupportGroups());
    }

    public function testGetGroup()
    {
        $this->assertCount(5, $this->tarotDeck->getGroup('Group2'));
    }

    /**
     * @expectedException DestinyLab\TarotException
     */
    public function testGetWrongGroup()
    {
        $this->tarotDeck->getGroup('abc');
    }
}
