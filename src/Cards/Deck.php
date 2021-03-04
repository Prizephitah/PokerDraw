<?php


namespace Prizephitah\PokerDraw\Cards;


class Deck
{
    protected array $cards = [];

    public function __construct(array $cards) {
        $this->cards = $cards;
    }

    public function shuffle(): static {
        shuffle($this->cards);
        return $this;
    }

    public function add(Card $card): static {
        array_unshift($this->cards, $card);
        return $this;
    }

    public function draw(): Card {
        if (empty($this->cards)) {
            // TODO Throw EmptyDeck
        }
        return array_shift($this->cards);
    }
}