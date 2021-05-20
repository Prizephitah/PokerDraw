<?php
declare(strict_types=1);


namespace Prizephitah\PokerDraw\Game;


use Prizephitah\PokerDraw\Cards\Card;
use Prizephitah\PokerDraw\Cards\Deck;
use Prizephitah\PokerDraw\Cards\EmptyDeckException;
use Prizephitah\PokerDraw\Cards\Hand;

class Game
{
    protected Deck $deck;
    protected Contestant $a;
    protected int $chipCountA;
    protected Contestant $b;
    protected int $chipCountB;

    /** @var Card[] */
    protected array $cardsA = [];

    /** @var Card[] */
    protected array $cardsB = [];

    public function __construct(Deck $deck, Contestant $a, Contestant $b, int $chipCount) {
        $this->deck = $deck;
        $this->a = $a;
        $this->b = $b;
        $this->chipCountA = $chipCount;
        $this->chipCountB = $chipCount;
        $this->a->sponsor($chipCount);
        $this->b->sponsor($chipCount);
    }

    public function run() {
        $this->cardsA = $this->draw(5);
        $this->cardsB = $this->draw(5);
        $discardedA = [];
        $discardedB = [];
        $additionA = $this->cardsA;
        $additionB = $this->cardsB;
        for ($i = 0; $i < 2; $i++) {
            $discardedA = $this->a->exchange($additionA, count($discardedB));
            $this->cardsA = $this->validateDiscarded($discardedA, $this->cardsA);
            $additionA = $this->draw(count($discardedA));
            $this->cardsA = [...$this->cardsA, ...$additionA];

            $discardedB = $this->b->exchange($additionB, count($discardedA));
            $this->cardsB = $this->validateDiscarded($discardedB, $this->cardsB);
            $additionB = $this->draw(count($discardedB));
            $this->cardsB = [...$this->cardsB, ...$additionB];
        }
        $betA = $this->validateBet($this->a->bet(count($discardedB)), $this->chipCountA);
        $betB = $this->validateBet($this->b->bet(count($discardedA)), $this->chipCountB);
        $win = round(($betA + $betB)/2);
        $handA = new Hand($this->cardsA);
        $handB = new Hand($this->cardsB);
        if ($handA->isBetterThan($handB)) {
            $this->a->win($betA + $win);
            $this->chipCountA += $win;
            $this->b->lose($betB + $win);
            $this->chipCountB -= ($betB + $win);
        } else if ($handB->isBetterThan($handA)) {
            $this->b->win($betB + $win);
            $this->chipCountB += $win;
            $this->a->lose($betA + $win);
            $this->chipCountA -= ($betA + $win);
        } else {
            $this->a->tie($betA);
            $this->b->tie($betB);
        }
    }

    /**
     * @param int $amount
     * @return Card[]
     * @throws EmptyDeckException
     */
    protected function draw(int $amount): array {
        $result = [];
        for ($i = 0; $i < $amount; $i++) {
            $result[] = $this->deck->draw();
        }
        return $result;
    }

    /**
     * @param Card[] $cards
     * @param Card[] $hand
     * @return Card[]
     * @throws InvalidDiscardException
     */
    protected function validateDiscarded(array $cards, array $hand): array {
        foreach ($cards as $card) {
            $cardExists = false;
            foreach ($hand as $key => $handCard) {
                if (!$handCard->equals($card)) {
                    continue;
                }
                $cardExists = true;
                unset($hand[$key]);
                break;
            }
            if (!$cardExists) {
                throw InvalidDiscardException::cardNotInHand($card, $hand);
            }
        }
        return $hand;
    }

    /**
     * @param int $bet
     * @param int $chipCount
     * @return int
     * @throws InvalidBetException
     */
    protected function validateBet(int $bet, int $chipCount): int {
        if ($bet < 1) {
            throw InvalidBetException::tooLow($bet);
        }
        if ($bet > $chipCount) {
            throw InvalidBetException::tooHigh($bet, $chipCount);
        }
        return $bet;
    }
}