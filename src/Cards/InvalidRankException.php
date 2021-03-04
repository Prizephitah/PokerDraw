<?php


namespace Prizephitah\PokerDraw\Cards;


class InvalidRankException extends CardException
{
    public static function forRank(int $rank): InvalidRankException {
        return new InvalidRankException('"'.$rank.'" is not a valid rank. Must be within '.Rank::Ace.' to '.Rank::King.'.');
    }
}