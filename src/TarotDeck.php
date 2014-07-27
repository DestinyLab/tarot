<?php

/**
 * This file is part of DestinyLab.
 */

namespace DestinyLab;

use Indigofeather\ResourceLoader\Container;

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
     * @param array  $paths
     * @throws TarotException
     */
    public function __construct($deckName, array $paths = [])
    {
        $container = new Container();
        $container->setDefaultFormat('json')
            ->addPath(__DIR__.'/../resources/');
        $paths and $container->addPaths($paths);
        $data = $container->load($deckName);

        if (! isset($data['cards']) or ! isset($data['groups'])) {
            throw new TarotException('Content errors!');
        }

        $this->cards = $data['cards'];
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
