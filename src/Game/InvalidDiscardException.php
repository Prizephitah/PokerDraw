<?php
declare(strict_types=1);


namespace Prizephitah\PokerDraw\Game;


use Prizephitah\PokerDraw\Cards\Card;
use Prizephitah\PokerDraw\PokerDrawException;

class InvalidDiscardException extends PokerDrawException
{
    public static function cardNotInHand(Card $card, array $hand) {
        return new static('Card '.(string)$card.' not in hand ['.implode(', ', $hand).']');
    }
}