<?php


namespace Prizephitah\PokerDraw\Cards;


class Hand
{
    /** @var Card[] */
    protected array $cards;

    /**
     * @param Card[] $cards
     */
    public function __construct(array $cards) {
        if (count($cards) !== 5) {
            throw InvalidHandException::forCards($cards);
        }
        $this->cards = $this->sort($cards);
    }

    public function __toString(): string
    {
        return '['.implode(', ', $this->cards).']';
    }

    protected function sort(array $cards): array {
        usort($cards, [Card::class, 'compare']);
        return $cards;
    }

    public function isFlush(): bool {
        $suit = $this->cards[0]->getSuit();
        foreach ($this->cards as $card) {
            if ($card->getSuit() !== $suit) {
                return false;
            }
        }
        return true;
    }

    public function isStraight(): bool {
        return $this->isNormalStraight() || $this->isRoyalStraight();
    }

    protected function isNormalStraight(): bool {
        $start = $this->cards[0]->getRank();
        for ($i = 1; $i < 5; $i++) {
            if ($this->cards[$i]->getRank() !== $start + $i) {
                return false;
            }
        }
        return true;
    }

    protected function isRoyalStraight(): bool {
        if ($this->cards[0]->getRank() !== Rank::Ace) {
            return false;
        }
        for ($i = 1; $i < 5; $i++) {
            if ($this->cards[$i]->getRank() !== 9 + $i) {
                return false;
            }
        }
        return true;
    }

    public function isRoyalFlush(): bool {
        return $this->isFlush() && $this->isRoyalStraight();
    }

    public function isStraightFlush(): bool {
        return $this->isStraight() && $this->isFlush();
    }

    protected function getMostOfSameKind(): int {
        $counts = array_fill_keys(range(1, 13), 0);
        foreach ($this->cards as $card) {
            $counts[$card->getRank()]++;
        }
        return max($counts);
    }

    public function isFourOfAKind(): bool {
        return $this->getMostOfSameKind() === 4;
    }

    public function isThreeOfAKind(): bool {
        return $this->getMostOfSameKind() === 3;
    }

    public function isPair(): bool {
        return $this->getMostOfSameKind() === 2;
    }

    public function isFullHouse(): bool {
        $counts = array_fill_keys(range(Rank::Ace, Rank::King), 0);
        foreach ($this->cards as $card) {
            $counts[$card->getRank()]++;
        }
        $counts = array_filter($counts);
        return max($counts) === 3 && min($counts) === 2;
    }
}