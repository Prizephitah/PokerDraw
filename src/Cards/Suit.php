<?php
declare(strict_types=1);


namespace Prizephitah\PokerDraw\Cards;


class Suit {
	public const Club = 'club';
	public const Diamond = 'diamond';
	public const Heart = 'heart';
	public const Spade = 'spade';

	public const List = [self::Club, self::Diamond, self::Heart, self::Spade];

	public static function isValid(string $candidateSuit): bool {
        return in_array($candidateSuit, self::List);
    }

    public static function shorten(string $suit): string {
	    return match ($suit) {
	        self::Club => '♣',
            self::Diamond => '♦',
            self::Heart => '♥',
            self::Spade => '♠'
        };
    }
}