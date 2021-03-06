<?php


namespace Prizephitah\PokerDraw\Cards;


use function Prizephitah\PokerDraw\array_shuffle;

class Deck implements \Countable
{
    protected array $cards = [];

    public function __construct(array $cards) {
        $this->cards = $cards;
    }

    public function shuffle(): static {
        array_shuffle($this->cards);
        return $this;
    }

    public function add(Card $card): static {
        array_unshift($this->cards, $card);
        return $this;
    }

    public function insert(Card $card): static {
        $place = random_int(0, count($this->cards));
        array_splice($this->cards, $place, 0, [$card]);
        return $this;
    }

    public function bury(Card $card): static {
        $this->cards[] = $card;
        return $this;
    }

    /**
     * Take a card from the top of the deck.
     * @return Card
     * @throws EmptyDeckException
     */
    public function draw(): Card {
        if (empty($this->cards)) {
            throw new EmptyDeckException();
        }
        return array_shift($this->cards);
    }

    /**
     * Take a card from a random place in the deck.
     * @return Card
     * @throws EmptyDeckException
     */
    public function pick(): Card {
        if (empty($this->cards)) {
            throw new EmptyDeckException();
        }
        $key = array_rand($this->cards);
        $card = $this->cards[$key];
        unset($this->cards[$key]);
        return $card;
    }

    /**
     * Take a card from the bottom of the deck.
     * @return Card
     * @throws EmptyDeckException
     */
    public function dig(): Card {
        if (empty($this->cards)) {
            throw new EmptyDeckException();
        }
        return array_pop($this->cards);
    }

    public function count()
    {
        return count($this->cards);
    }

    public function toArray(): array {
        return $this->cards;
    }
}