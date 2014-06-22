# Tarot

[![Build Status](https://travis-ci.org/DestinyLab/tarot.svg?branch=master)](https://travis-ci.org/DestinyLab/tarot)
Tarot is the tool of Tarot Decks.

## Requirement

 - PHP >=5.4

## Installing via Composer

The recommended way to install Tarot is through Composer.

```bash
# Install Composer
curl -sS https://getcomposer.org/installer | php
```

Next, update your project's composer.json file to include Tarot:

```json
{
    "require": {
        "destinylab/tarot": "dev-master"
    }
}
```

## Usage

```php
<?php

require_once 'vendor/autoload.php';

// get the Rider-Waite deck
$deck = new \DestinyLab\TarotDeck('Rider-Waite');
var_dump($deck->getCards());
var_dump($deck->getSupportGroups());
var_dump($deck->getCardsByGroups(['Swords']));

// get the TarotDraw object
$draw = new \DestinyLab\TarotDraw($deck);

// only use 'Major Arcana', has reversed, need shuffle, and draw 3
$draw->number(3)
    ->includes(['Major'])
    ->reversed(true)
    ->shuffle(true);

var_dump($draw->draw());

// If you use spreads, you can pass the options in construct
$options = [
    'include' => ['Major'],
    'number' => 7,
    'reversed' => true,
    'shuffle' => true,
];

$draw = new \DestinyLab\TarotDraw($deck, $options);
var_dump($draw->draw());
```

## Configuration

Adds the search paths of Tarot Deck File:

```php
<?php

$deck = new \DestinyLab\TarotDeck('My-deck', ['path/to/dir/', 'path/to/another/dir/']);
```

## Custom Your Tarot Decks

```json
{
    "cards": [
        {
            "id": 0,
            "name": "The Fool"
        },
        {
            "id": 1,
            "name": "The Magician"
        },
        ...
        {
            "id": 77,
            "name": "King of Pentacles"
        }
    ],
    "groups": {
        "group1": [
            0,
            1,
            2
        ],
        "group2": [
            22,
            23,
            24
        ]
    }
}
```

## License

MIT