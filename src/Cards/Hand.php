<?php


namespace Prizephitah\PokerDraw\Cards;


class Hand
{
    public const RankRoyalFlush = 9;
    public const RankStraightFlush = 8;
    public const RankFourOfAKind = 7;
    public const RankFullHouse = 6;
    public const RankFlush = 5;
    public const RankStraight = 4;
    public const RankThreeOfAKind = 3;
    public const RankTwoPairs = 2;
    public const RankPairs = 1;
    public const RankHighCard = 0;

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
        return $this->isNormalStraight();
    }

    protected function isNormalStraight(): bool {
        $start = $this->cards[0]->getRank();
        for ($i = 1; $i < 5; $i++) {
            if ($this->cards[$i]->getRank() !== $start + $i &&
                !($i === 4 && $this->cards[$i]->getRank() === Rank::Ace && $this->cards[3]->getRank() === Rank::King) &&
                !($i === 4 && $this->cards[$i]->getRank() === Rank::Ace && $this->cards[0]->getRank() === Rank::Two)
            ) {
                return false;
            }
        }
        return true;
    }

    protected function isRoyalStraight(): bool {
        if ($this->cards[4]->getRank() !== Rank::Ace || $this->cards[3]->getRank() !== Rank::King) {
            return false;
        }
        return $this->isStraight();
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

    public function isTwoPairs(): bool {
        $counts = array_fill_keys(range(Rank::Ace, Rank::King), 0);
        foreach ($this->cards as $card) {
            $counts[$card->getRank()]++;
        }
        $counts = array_filter($counts);
        return max($counts) === 2 && min($counts) === 1 && count($counts) === 3;
    }

    public function getHighCard(int $offset = 0): Card {
        return $this->cards[4 - $offset];
    }

    public function getHandRank(): int {
        if ($this->isRoyalFlush()) {
            return self::RankRoyalFlush;
        }
        if ($this->isStraightFlush()) {
            return self::RankStraightFlush;
        }
        if ($this->isFourOfAKind()) {
            return self::RankFourOfAKind;
        }
        if ($this->isFullHouse()) {
            return self::RankFullHouse;
        }
        if ($this->isFlush()) {
            return self::RankFlush;
        }
        if ($this->isStraight()) {
            return self::RankStraight;
        }
        if ($this->isThreeOfAKind()) {
            return self::RankThreeOfAKind;
        }
        if ($this->isTwoPairs()) {
            return self::RankTwoPairs;
        }
        if ($this->isPair()) {
            return self::RankPairs;
        }
        return self::RankHighCard;
    }

    public function isBetterThan(Hand $candidate): bool {
        $handComparison = $this->getHandRank() <=> $candidate->getHandRank();
        if ($handComparison === -1) {
            return false;
        }
        if ($handComparison === 1) {
            return true;
        }

        for ($i = 0; $i < 5; $i++) {
            if ($this->getHighCard($i)->getRank() > $candidate->getHighCard($i)->getRank()
                && $candidate->getHighCard($i)->getRank() !== Rank::Ace) {
                return true;
            }
            if ($this->getHighCard($i)->getRank() === Rank::Ace && $candidate->getHighCard($i)->getRank() !== Rank::Ace) {
                return true;
            }
        }
        return false;
    }
}