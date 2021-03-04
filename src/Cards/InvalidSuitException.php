<?php


namespace Prizephitah\PokerDraw\Cards;


class InvalidSuitException extends CardException
{
    public static function forSuit(string $suit): InvalidSuitException {
        return new InvalidSuitException('"'.$suit.'" is not a valid suit!. Must be one of '.implode(', ', Suit::List).'.');
    }
}