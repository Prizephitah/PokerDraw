<?php

namespace Prizephitah\PokerDraw\Tests;

use PHPUnit\Framework\TestCase;
use function Prizephitah\PokerDraw\array_shuffle;

class ArrayShuffleTest extends TestCase
{
    const TestSubject = ['a', 'b', 'c', 'd', 'e', 'f'];

    public function testSameCount() {
        $result = array_shuffle(self::TestSubject);
        self::assertCount(count(self::TestSubject), $result);
    }

    public function testSameContent() {
        $result = array_shuffle(self::TestSubject);
        foreach (self::TestSubject as $value) {
            self::assertNotFalse(array_search($value, $result, true));
        }
    }

    public function testNotEquals() {
        // Run the test 5 times to avoid flukes
        $samePlaces = 0;
        for ($i = 0; $i < 5; $i++) {
            $samePlaces =+ $this->countEqualPlaces();
        }
        self::assertLessThan(5, $samePlaces);
    }

    protected function countEqualPlaces(): int {
        $result = array_shuffle(self::TestSubject);
        $samePlaces = 0;
        foreach ($result as $key => $value) {
            if (self::TestSubject[$key] === $value) {
                $samePlaces++;
            }
        }
        return $samePlaces;
    }

    public function testStandardDeviation() {
        $this->assertEquals(2, $this->getStandardDeviation([2, 4, 4, 4, 5, 5, 7, 9]));
    }

    public function testRandomness() {
        $lengths = [];
        for ($i = 0; $i < 1000; $i++) {
            $result = array_shuffle($this->getLongTestSubject());
            $lengths[] = $this->getCompressedStringLength($result);
        }

        // How many bytes from mean does it usually differ?
        $standardDeviation = $this->getStandardDeviation($lengths);
        // If compression deviates less then 10 bytes on average for a 1000 shuffles, randomness is evenly distributed;
        $this->assertLessThan(10, $standardDeviation);

        $lengthOrdered = $this->getCompressedStringLength($this->getLongTestSubject());
        $lengthShuffled = $this->getCompressedStringLength(array_shuffle($this->getLongTestSubject()));

        // Assert entropy is at least 9 times higher for a shuffled array, then a sorted
        $this->assertGreaterThan($lengthOrdered, $lengthShuffled);
        $this->assertGreaterThan(9, $lengthShuffled / $lengthOrdered);;
    }

    protected function getLongTestSubject(): array {
        $data = [];
        foreach (range('a', 'j') as $character) {
            $data = array_merge($data, str_split(str_repeat($character, 100)));
        }
        return $data;
    }

    protected function getCompressedStringLength(array $characters): int {
        $string = implode('', $characters);
        $compressed = gzencode($string);
        return strlen($compressed);
    }

    protected function getStandardDeviation(array $array): float {
        $average = fn(array $values) => array_sum($values) / count($values);
        $mean = $average($array);
        $squareDistances = array_map(
            function ($value) use ($mean) {
                return ($value - $mean) ** 2;
            },
            $array
        );
        $variance = $average($squareDistances);
        return sqrt($variance);
    }
}
