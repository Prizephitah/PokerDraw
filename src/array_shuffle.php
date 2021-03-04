<?php
declare(strict_types=1);


namespace Prizephitah\PokerDraw;

function array_shuffle(array $array): array {
    $currentIndex = count($array) - 1;
    $tempValue = null;
    $randomIndex = null;

    while (0 !== $currentIndex) {
        $randomIndex = random_int(0, $currentIndex);

        $tempValue = $array[$currentIndex];
        $array[$currentIndex] = $array[$randomIndex];
        $array[$randomIndex] = $tempValue;

        $currentIndex--;
    }

    return $array;
}