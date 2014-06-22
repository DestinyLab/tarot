<?php

/**
 * This file is part of DestinyLab.
 */

namespace DestinyLab;

/**
 * TarotDraw
 *
 * @package DestinyLab
 * @author  Lance He <indigofeather@gmail.com>
 */
class TarotDraw
{
    use TarotTrait;

    /**
     * @var TarotDeck
     */
    protected $deck;
    protected $cards = [];
    protected $number = 0;
    protected $reversed = true;
    protected $shuffle = true;
    protected $include = [];
    protected $exclude = [];

    /**
     * @param TarotDeck $deck
     * @param array     $options
     */
    public function __construct(TarotDeck $deck, array $options = [])
    {
        $this->deck = $deck;
        isset($options['number']) and $this->number($options['number']);
        isset($options['reversed']) and $this->reversed($options['reversed']);
        isset($options['shuffle']) and $this->shuffle($options['shuffle']);
        isset($options['include']) and $this->includes($options['include']);
        isset($options['exclude']) and $this->excludes($options['exclude']);
    }

    /**
     * @param $number
     * @return $this
     */
    public function number($number)
    {
        $this->number = (int) $number;

        return $this;
    }

    /**
     * @param $hasReversed
     * @return $this
     */
    public function reversed($hasReversed)
    {
        $this->reversed = (bool) $hasReversed;

        return $this;
    }

    /**
     * @param $needShuffle
     * @return $this
     */
    public function shuffle($needShuffle)
    {
        $this->shuffle = (bool) $needShuffle;

        return $this;
    }

    /**
     * @param array $cardIdsOrGroups
     * @return $this
     */
    public function includes(array $cardIdsOrGroups)
    {
        $this->include = array_merge($this->include, $this->process($cardIdsOrGroups));

        return $this;
    }

    /**
     * @param array $cardIdsOrGroups
     * @return array
     * @throws TarotException
     */
    protected function process(array $cardIdsOrGroups)
    {
        $cardIds = [];
        foreach ($cardIdsOrGroups as $val) {
            $isGroup = $this->deck->validGroup($val);
            if (! $isGroup and ! is_int($val)) {
                throw new TarotException("[{$val}] is not group or cardId!");
            }

            $isGroup and $cardIds = array_merge($cardIds, $this->deck->getGroup($val));
            is_int($val) and $cardIds[] = $val;
        }

        return $cardIds;
    }

    /**
     * @param array $cardIdsOrGroups
     * @return $this
     */
    public function excludes(array $cardIdsOrGroups)
    {
        $this->exclude = array_merge($this->exclude, $this->process($cardIdsOrGroups));

        return $this;
    }

    /**
     * @param int $number
     * @return array
     */
    public function draw($number = 0)
    {
        $cardIds = array_diff($this->include, $this->exclude);
        $this->cards = ! $cardIds ? $this->getCards() : $this->filterCards($this->getCards(), $cardIds);
        $number === 0 and $number = $this->number;
        $this->shuffle and shuffle($this->cards);

        if ($this->reversed) {
            array_walk(
                $this->cards,
                function (&$card) {
                    $card['reversed'] = (bool) mt_rand(0, 1);
                }
            );
        }

        return array_slice($this->cards, 0, $number);
    }

    /**
     * @return array
     */
    public function getCards()
    {
        return $this->deck->getCards();
    }

    /**
     * @return $this
     */
    public function reset()
    {
        $this->cards    = [];
        $this->number   = 0;
        $this->reversed = true;
        $this->shuffle  = true;
        $this->include  = [];
        $this->exclude  = [];

        return $this;
    }

    /**
     * @return array
     */
    public function config()
    {
        return [
            'cards' => $this->cards,
            'number' => $this->number,
            'reversed' => $this->reversed,
            'shuffle' => $this->shuffle,
            'include' => $this->include,
            'exclude' => $this->exclude,
        ];
    }
}
