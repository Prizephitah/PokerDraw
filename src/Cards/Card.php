<?php
declare(strict_types=1);


namespace Prizephitah\PokerDraw\Cards;


class Card
{
    protected string $suit;
    protected int $rank;

    /**
     * @param string $suit
     * @param int $rank
     * @throws CardException
     */
    public function __construct(string $suit, int $rank) {
        $this->setSuit($suit);
        $this->setRank($rank);
    }

    /**
     * @return string
     */
    public function getSuit(): string
    {
        return $this->suit;
    }

    /**
     * @param string $suit
     * @return static
     * @throws InvalidSuitException
     */
    public function setSuit(string $suit): static
    {
        if (!Suit::isValid($suit)) {
            throw InvalidSuitException::forSuit($suit);
        }
        $this->suit = $suit;
        return $this;
    }

    /**
     * @return int
     */
    public function getRank(): int
    {
        return $this->rank;
    }

    /**
     * @param int $rank
     * @return static
     * @throws InvalidRankException
     */
    public function setRank(int $rank): static
    {
        if (!Rank::isValid($rank)) {
            throw InvalidRankException::forRank($rank);
        }
        $this->rank = $rank;
        return $this;
    }

    public function __toString(): string {
        return Rank::shorten($this->getRank()).Suit::shorten($this->getSuit());
    }

    public function equals($candidate): bool {
        if (!($candidate instanceof Card)) {
            return false;
        }
        if ($candidate->getRank() !== $this->getRank()
            || $candidate->getSuit() !== $this->getSuit()) {
            return false;
        }
        return true;
    }

    /**
     * Returns -1, 0 or 1 when the first card is respectively less then, equal to or, greater than the second card.
     * @param Card $a
     * @param Card $b
     * @return int
     */
    public static function compare(Card $a, Card $b): int {
        $aRank = $a->getRank() === Rank::Ace ? 14 : $a->getRank();
        $bRank = $b->getRank() === Rank::Ace ? 14 : $b->getRank();
        $rank = $aRank <=> $bRank;
        if ($rank !== 0) {
            return $rank;
        }
        return array_search($a->getSuit(), Suit::List) <=> array_search($b->getSuit(), Suit::List);
    }
}