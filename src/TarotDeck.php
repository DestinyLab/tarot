<?php

/**
 * This file is part of DestinyLab.
 */

namespace DestinyLab;

use Fuel\FileSystem\File;
use Fuel\FileSystem\Finder;

/**
 * TarotDeck
 *
 * @package DestinyLab
 * @author  Lance He <indigofeather@gmail.com>
 */
class TarotDeck
{
    use TarotTrait;

    protected $cards = [];
    protected $groups = [];

    /**
     * @param string $deckName
     * @param array  $path
     * @throws TarotException
     */
    public function __construct($deckName, array $path = [])
    {
        $defaultPath = [__DIR__.'/../resources/'];
        $path        = $path ? array_merge($path, $defaultPath) : $defaultPath;
        $finder      = new Finder($path, '.json');
        $filePath    = $finder->find($deckName);
        if (! $filePath) {
            throw new TarotException('File is not exist!');
        }

        $file = new File($filePath);
        $data = json_decode($file->getContents(), true);
        if (! isset($data['cards']) or ! isset($data['groups'])) {
            throw new TarotException('Content errors!');
        }

        $this->cards  = $data['cards'];
        $this->groups = $data['groups'];
    }

    /**
     * @return array
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * @param array $cardIds
     * @return array
     */
    public function getCardsByIds(array $cardIds)
    {
        return array_values($this->filterCards($this->cards, $cardIds));
    }

    /**
     * @param array $groups
     * @return array
     * @throws TarotException when group is invalid
     */
    public function getCardsByGroups(array $groups)
    {
        $cardIds = [];
        foreach ($groups as $group) {
            if (! $this->validGroup($group)) {
                throw new TarotException("group [{$group}] is not exist!");
            }

            $cardIds = array_merge($cardIds, $this->groups[$group]);
        }

        $cards = $this->filterCards($this->cards, $cardIds);

        return array_values($cards);
    }

    /**
     * @param $group
     * @return bool
     */
    public function validGroup($group)
    {
        return isset($this->groups[$group]);
    }

    /**
     * @return array
     */
    public function getSupportGroups()
    {
        return array_keys($this->groups);
    }

    /**
     * @param $group
     * @return array
     * @throws TarotException when group is invalid
     */
    public function getGroup($group)
    {
        if (! $this->validGroup($group)) {
            throw new TarotException("group [{$group}] is not exist!");
        }

        return $this->groups[$group];
    }
}
