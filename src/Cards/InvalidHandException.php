<?php


namespace Prizephitah\PokerDraw\Cards;


class InvalidHandException extends CardException
{
    public static function forCards(array $cards): static {
        return new static('['.implode(', ', $cards).'] is not a valid hand. Must be exactly 5 cards.');
    }
}