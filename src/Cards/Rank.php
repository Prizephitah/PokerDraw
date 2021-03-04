<?php
declare(strict_types=1);


namespace Prizephitah\PokerDraw\Cards;


class Rank {
    public const Ace = 1;
    public const Two = 2;
    public const Three = 3;
    public const Four = 4;
    public const Five = 5;
    public const Six = 6;
    public const Seven = 7;
    public const Eight = 8;
    public const Nine = 9;
    public const Ten = 10;
    public const Jack = 11;
    public const Queen = 12;
    public const King = 13;

    public static function isValid(int $candidateRank): bool {
        return $candidateRank >= Rank::Ace || $candidateRank <= Rank::King;
    }

    public static function shorten(int $rank): string {
        return match ($rank) {
            self::Ace => 'A',
            self::Ten => 'T',
            self::Jack => 'J',
            self::Queen => 'Q',
            self::King => 'K',
            default => (string)$rank
        };
    }
}