<?php

namespace DestinyLab;

trait TarotTrait
{
    /**
     * @param $cards
     * @param $cardIds
     * @return array
     */
    protected function filterCards($cards, $cardIds)
    {
        return array_filter(
            $cards,
            function ($card) use ($cardIds) {
                return in_array($card['id'], $cardIds);
            }
        );
    }
}
