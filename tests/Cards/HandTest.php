<?php

namespace Prizephitah\PokerDraw\Tests\Cards;

use Prizephitah\PokerDraw\Cards\Card;
use Prizephitah\PokerDraw\Cards\Hand;
use PHPUnit\Framework\TestCase;
use Prizephitah\PokerDraw\Cards\InvalidHandException;
use Prizephitah\PokerDraw\Cards\Rank;
use Prizephitah\PokerDraw\Cards\Suit;

class HandTest extends TestCase
{
    public function testTooFew() {
        self::expectException(InvalidHandException::class);
        $cards = [
            new Card(Suit::Club, Rank::Ace), new Card(Suit::Heart, Rank::Ace),
            new Card(Suit::Diamond, Rank::Ace), new Card(Suit::Spade, Rank::Ace)
        ];
        new Hand($cards);
    }

    public function testTooMany() {
        self::expectException(InvalidHandException::class);
        $cards = [];
        foreach (range(1, 6) as $rank) {
            $cards[] = new Card(Suit::Club, $rank);
        }
        new Hand($cards);
    }

    public function testToString() {
        $cards = [];
        foreach (range(1, 5) as $rank) {
            $cards[] = new Card(Suit::Heart, $rank);
        }
        $hand = new Hand($cards);
        self::assertEquals('[2♥, 3♥, 4♥, 5♥, A♥]', $hand->__toString());
    }

    public function testIsStraight()
    {
        $cards = [];
        foreach (range(1, 5) as $rank) {
            $cards[] = new Card(Suit::Club, $rank);
        }
        $hand = new Hand($cards);
        self::assertTrue($hand->isStraight());

        $cards = [];
        foreach (range(2, 6) as $rank) {
            $cards[] = new Card(Suit::Club, $rank);
        }
        $hand = new Hand($cards);
        self::assertTrue($hand->isStraight());

        $cards = [];
        foreach (range(1, 5) as $rank) {
            $cards[] = new Card(Suit::Club, $rank * 2);
        }
        $hand = new Hand($cards);
        self::assertFalse($hand->isStraight());

        $cards = [];
        foreach (range(10, 13) as $rank) {
            $cards[] = new Card(Suit::Club, $rank);
        }
        $cards[] = new Card(Suit::Club, Rank::Ace);
        $hand = new Hand($cards);
        self::assertTrue($hand->isStraight(), 'Failed recognizing a straight with an Ace on top.');
    }

    public function testIsFlush()
    {
        $cards = [];
        foreach (range(1, 5) as $rank) {
            $cards[] = new Card(Suit::Club, $rank);
        }
        $hand = new Hand($cards);
        self::assertTrue($hand->isFlush());

        $cards = [];
        foreach (range(1, 4) as $rank) {
            $cards[] = new Card(Suit::Club, $rank);
        }
        $cards[] = new Card(Suit::Spade, Rank::Ace);
        $hand = new Hand($cards);
        self::assertFalse($hand->isFlush());
    }

    public function testRoyalFlush()
    {
        $cards = [];
        foreach (range(10, 13) as $rank) {
            $cards[] = new Card(Suit::Heart, $rank);
        }
        $cards[] = new Card(Suit::Heart, Rank::Ace);
        $hand = new Hand($cards);
        self::assertTrue($hand->isRoyalFlush());

        $cards = [];
        foreach (range(9, 13) as $rank) {
            $cards[] = new Card(Suit::Heart, $rank);
        }
        $hand = new Hand($cards);
        self::assertFalse($hand->isRoyalFlush());

        $cards = [];
        foreach (range(11, 13) as $rank) {
            $cards[] = new Card(Suit::Heart, $rank);
        }
        $cards[] = new Card(Suit::Heart, Rank::Ace);
        $cards[] = new Card(Suit::Heart, Rank::Nine);
        $hand = new Hand($cards);
        self::assertFalse($hand->isRoyalFlush());
    }

    public function testIsStraightFlush()
    {
        $cards = [];
        foreach (range(1, 5) as $rank) {
            $cards[] = new Card(Suit::Club, $rank);
        }
        $hand = new Hand($cards);
        self::assertTrue($hand->isStraightFlush());

        $cards = [];
        foreach (range(1, 5) as $rank) {
            $cards[] = new Card(Suit::Club, $rank * 2);
        }
        $hand = new Hand($cards);
        self::assertFalse($hand->isStraightFlush());
    }

    public function testFourOfAKind()
    {
        $cards = [];
        for ($i = 0; $i < 4; $i++) {
            $cards[] = new Card(Suit::List[$i], Rank::Ace);
        }
        $cards[] = new Card(Suit::Heart, Rank::Two);
        $hand = new Hand($cards);
        self::assertTrue($hand->isFourOfAKind());

        $cards = [];
        for ($i = 0; $i < 3; $i++) {
            $cards[] = new Card(Suit::List[$i], Rank::Ace);
        }
        $cards[] = new Card(Suit::Heart, Rank::Two);
        $cards[] = new Card(Suit::Heart, Rank::Three);
        $hand = new Hand($cards);
        self::assertFalse($hand->isFourOfAKind());
    }

    public function testThreeOfAKind()
    {
        $cards = [];
        for ($i = 0; $i < 3; $i++) {
            $cards[] = new Card(Suit::List[$i], Rank::Ace);
        }
        $cards[] = new Card(Suit::Heart, Rank::Two);
        $cards[] = new Card(Suit::Heart, Rank::Three);
        $hand = new Hand($cards);
        self::assertTrue($hand->isThreeOfAKind());

        $cards = [];
        for ($i = 0; $i < 2; $i++) {
            $cards[] = new Card(Suit::List[$i], Rank::Ace);
        }
        $cards[] = new Card(Suit::Heart, Rank::Two);
        $cards[] = new Card(Suit::Heart, Rank::Three);
        $cards[] = new Card(Suit::Heart, Rank::Four);
        $hand = new Hand($cards);
        self::assertFalse($hand->isThreeOfAKind());
    }

    public function testTwoOfAKind()
    {
        $cards = [];
        for ($i = 2; $i < 5; $i++) {
            $cards[] = new Card(Suit::Heart, $i);
        }
        $cards[] = new Card(Suit::Heart, Rank::Ace);
        $cards[] = new Card(Suit::Club, Rank::Ace);
        $hand = new Hand($cards);
        self::assertTrue($hand->isPair());

        $cards = [];
        for ($i = 1; $i < 6; $i++) {
            $cards[] = new Card(Suit::Heart, $i);
        }
        $hand = new Hand($cards);
        self::assertFalse($hand->isThreeOfAKind());
    }

    public function testFullHouse()
    {
        $cards = [];
        foreach ([Suit::Heart, Suit::Club, Suit::Spade] as $suit) {
            $cards[] = new Card($suit, Rank::Ace);
        }
        foreach ([Suit::Heart, Suit::Club] as $suit) {
            $cards[] = new Card($suit, Rank::King);
        }
        $hand = new Hand($cards);
        self::assertTrue($hand->isFullHouse());

        $cards = [];
        foreach ([Suit::Heart, Suit::Club] as $suit) {
            $cards[] = new Card($suit, Rank::Ace);
        }
        foreach ([Suit::Heart, Suit::Club] as $suit) {
            $cards[] = new Card($suit, Rank::King);
        }
        $cards[] = new Card(Suit::Heart, Rank::Queen);
        $hand = new Hand($cards);
        self::assertFalse($hand->isFullHouse());
    }

    public function testTwoPairs()
    {
        $cards = [];
        foreach ([Suit::Heart, Suit::Club] as $suit) {
            $cards[] = new Card($suit, Rank::Ace);
            $cards[] = new Card($suit, Rank::King);
        }
        $cards[] = new Card(Suit::Heart, Rank::Queen);
        $hand = new Hand($cards);
        self::assertTrue($hand->isTwoPairs());

        $cards = [];
        foreach (range(1, 4) as $rank) {
            $cards[] = new Card(Suit::Heart, $rank);
        }
        $cards[] = new Card(Suit::Club, Rank::Ace);
        $hand = new Hand($cards);
        self::assertFalse($hand->isTwoPairs());

        $cards = [];
        foreach ([Suit::Heart, Suit::Club, Suit::Spade] as $suit) {
            $cards[] = new Card($suit, Rank::Ace);
        }
        $cards[] = new Card(Suit::Heart, Rank::Two);
        $cards[] = new Card(Suit::Heart, Rank::Two);
        $hand = new Hand($cards);
        self::assertFalse($hand->isTwoPairs());
    }

    public function testHighCard()
    {
        $cards = [];
        foreach (range(9, 13) as $rank) {
            $cards[] = new Card(Suit::Heart, $rank);
        }
        $hand = new Hand($cards);
        self::assertTrue((new Card(Suit::Heart, Rank::King))->equals($hand->getHighCard()));
        self::assertFalse((new Card(Suit::Heart, Rank::Queen))->equals($hand->getHighCard()));

        $cards = [];
        foreach (range(1, 5) as $rank) {
            $cards[] = new Card(Suit::Heart, $rank);
        }
        $hand = new Hand($cards);
        self::assertTrue((new Card(Suit::Heart, Rank::Ace))->equals($hand->getHighCard()));
        self::assertFalse((new Card(Suit::Heart, Rank::King))->equals($hand->getHighCard()));
        self::assertFalse((new Card(Suit::Heart, Rank::Five))->equals($hand->getHighCard()));
    }

    public function testHighCardOffset()
    {
        $cards = [];
        foreach (range(2, 5) as $rank) {
            $cards[] = new Card(Suit::Heart, $rank);
        }
        $cards[] = new Card(Suit::Heart, Rank::Ace);
        $hand = new Hand($cards);
        for ($i = 0; $i < 5; $i++) {
            self::assertTrue($cards[4 - $i]->equals($hand->getHighCard($i)), 'Failed asserting offset '.$i);
        }

        $cards = [];
        foreach (range(10, 13) as $rank) {
            $cards[] = new Card(Suit::Heart, $rank);
        }
        $cards[] = new Card(Suit::Heart, Rank::Ace);
        $hand = new Hand($cards);
        for ($i = 0; $i < 5; $i++) {
            self::assertTrue($cards[4 - $i]->equals($hand->getHighCard($i)), 'Failed asserting offset '.$i);
        }
    }

    public function testRoyalFlushIsBetterThanFlush()
    {
        $aCards = [];
        $bCards = [];
        foreach (range(1, 5) as $rank) {
            $aCards[] = new Card(Suit::Heart, $rank);
            $bCards[] = new Card(Suit::Club, $rank + 1);
        }
        $aHand = new Hand($aCards);
        self::assertEquals(Hand::RankStraightFlush, $aHand->getHandRank());
        $bHand = new Hand($bCards);
        self::assertEquals(Hand::RankStraightFlush, $bHand->getHandRank());
        self::assertTrue($aHand->isBetterThan($bHand));
        self::assertFalse($bHand->isBetterThan($aHand));
    }

    public function testHighStraightFlushisBetterThenLow()
    {
        $aCards = [];
        $bCards = [];
        foreach (range(10, 13) as $rank) {
            $aCards[] = new Card(Suit::Heart, $rank);
            $bCards[] = new Card(Suit::Club, $rank - 1);
        }
        $aCards[] = new Card(Suit::Heart, Rank::Ace);
        $bCards[] = new Card(Suit::Club, Rank::King);
        $aHand = new Hand($aCards);
        $bHand = new Hand($bCards);
        self::assertTrue($aHand->isBetterThan($bHand));
        self::assertFalse($bHand->isBetterThan($aHand));
    }

    public function testEqualHands() {
        $aCards = [];
        $bCards = [];
        foreach (range(1, 5) as $rank) {
            $aCards[] = new Card(Suit::Heart, $rank);
            $bCards[] = new Card(Suit::Club, $rank);
        }
        $aHand = new Hand($aCards);
        $bHand = new Hand($bCards);
        self::assertFalse($aHand->isBetterThan($bHand));
        self::assertFalse($bHand->isBetterThan($aHand));
    }
}
