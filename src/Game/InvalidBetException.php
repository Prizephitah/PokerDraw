<?php


namespace Prizephitah\PokerDraw\Game;


class InvalidBetException extends \Prizephitah\PokerDraw\PokerDrawException
{
    public static function tooLow(int $bet): static {
        return new static('Minimum bet is 1. Can\'t bet '.$bet);
    }

    public static function tooHigh(int $bet, int $chipCount): static {
        return new static('Chip count is '.$chipCount.'. Can\'t bet '.$bet);
    }
}