<?php
declare(strict_types=1);


namespace Prizephitah\PokerDraw\Game;


use Prizephitah\PokerDraw\Cards\Card;

interface Contestant
{
    /**
     * Get the name of the contestant.
     * @return string
     */
    public function getName(): string;

    public function sponsor(int $amount): void;

    /**
     * @param Card[] $cards New cards to hold.
     * @param int|null $opponentDiscards The number of cards the opponent discarded last round, null if not disclosed.
     * @return Card[] Discarded cards
     */
    public function exchange(array $cards, int $opponentDiscards = null): array;

    /**
     * @param int $opponentDiscards The number of cards the opponent discarded last round
     * @return int The bet.
     */
    public function bet(int $opponentDiscards): int;

    public function win(int $amount): void;

    public function lose(int $amount): void;

    public function tie(int $amount): void;
}