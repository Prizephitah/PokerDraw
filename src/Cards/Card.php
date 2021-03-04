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
}