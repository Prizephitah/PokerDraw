<?php

namespace Prizephitah\PokerDraw\Tests\Cards;

use Prizephitah\PokerDraw\Cards\Card;
use Prizephitah\PokerDraw\Cards\Deck;
use PHPUnit\Framework\TestCase;
use Prizephitah\PokerDraw\Cards\EmptyDeckException;
use Prizephitah\PokerDraw\Cards\Rank;
use Prizephitah\PokerDraw\Cards\Suit;

class DeckTest extends TestCase
{
    protected array $cards;

    protected function setUp(): void
    {
        $this->cards = [
            new Card(Suit::Club, Rank::Ace),
            new Card(Suit::Diamond, Rank::Two),
            new Card(Suit::Heart, Rank::Three)
        ];
    }

    public function testCount()
    {
        $deck = new Deck($this->cards);
        self::assertSameSize($this->cards, $deck);
    }

    public function testToArray()
    {
        $deck = new Deck($this->cards);
        $cards = $deck->toArray();
        foreach ($this->cards as $key => $card) {
            self::assertTrue($card->equals($cards[$key]));
        }
    }

    public function testAdd()
    {
        $deck = new Deck($this->cards);
        $card = new Card(Suit::Spade, Rank::Four);
        $deck->add($card);
        self::assertCount(count($this->cards) + 1, $deck);
        $cards = $deck->toArray();
        self::assertTrue($card->equals($cards[0]));
    }

    public function testBury()
    {
        $deck = new Deck($this->cards);
        $card = new Card(Suit::Spade, Rank::Four);
        $deck->bury($card);
        self::assertCount(count($this->cards) + 1, $deck);
        $cards = $deck->toArray();
        self::assertTrue($card->equals($cards[3]));
    }

    public function testInsert()
    {
        $deck = new Deck($this->cards);
        $card = new Card(Suit::Spade, Rank::Four);
        $deck->insert($card);
        self::assertCount(count($this->cards) + 1, $deck);
        $cards = $deck->toArray();
        $equals = 0;
        foreach ($cards as $deckCard) {
            if ($card->equals($deckCard)) {
                $equals++;
            }
        }
        self::assertEquals(1, $equals);
    }

    public function testPick()
    {
        $deck = new Deck($this->cards);
        $card = $deck->pick();
        $found = false;
        foreach ($this->cards as $deckCard) {
            if ($card->equals($deckCard)) {
                $found = true;
            }
        }
        self::assertTrue($found);
        self::assertCount(2, $deck);
        self::assertCount(3, $this->cards);
        $deck->pick();
        self::assertCount(1, $deck);
        $deck->pick();
        self::assertCount(0, $deck);
        self::expectException(EmptyDeckException::class);
        $deck->pick();
    }

    public function testDig()
    {
        $deck = new Deck($this->cards);
        $card = $deck->dig();
        self::assertTrue($card->equals($this->cards[2]));
        self::assertCount(2, $deck);
        self::assertCount(3, $this->cards);
        $deck->dig();
        self::assertCount(1, $deck);
        $deck->dig();
        self::assertCount(0, $deck);
        self::expectException(EmptyDeckException::class);
        $deck->dig();
    }

    public function testDraw()
    {
        $deck = new Deck($this->cards);
        $card = $deck->draw();
        self::assertTrue($card->equals($this->cards[0]));
        self::assertCount(2, $deck);
        self::assertCount(3, $this->cards);
        $deck->draw();
        self::assertCount(1, $deck);
        $deck->draw();
        self::assertCount(0, $deck);
        self::expectException(EmptyDeckException::class);
        $deck->draw();
    }

    public function testDrawEmpty() {
        self::expectException(EmptyDeckException::class);
        $deck = new Deck($this->cards);
        $deck->draw();
        $deck->draw();
        $deck->draw();
        $deck->draw();
    }

    public function testStandard() {
        $deck = Deck::getStandard();
        self::assertCount(52, $deck);
        $cards = $deck->toArray();
        for ($i = 0; $i < count($cards); $i++) {
            for ($j = 0; $j < count($cards); $j++) {
                if ($i === $j) {
                    continue;
                }
                self::assertFalse($cards[$i]->equals($cards[$j]));
            }
        }
    }
}
