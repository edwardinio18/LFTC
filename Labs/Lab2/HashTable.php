<?php

class HashTable
{
    private array $hashTable;
    private int $capacity;

    public function __construct(int $capacity)
    {
        $this->capacity = $capacity;
        $this->hashTable = array_fill(0, $capacity, array());
    }

    public function getCapacity(): int
    {
        return $this->capacity;
    }

    private function hash(int $key): int
    {
        return $key % $this->capacity;
    }

    private function hashString(string $key): int
    {
        $hash = 193;
        for ($i = 0; $i < strlen($key); $i++) {
            $hash = (($hash << 2) + $hash) + ord($key[$i]);
        }
        return abs($hash) % $this->capacity;
    }

    public function contains(int|string $key): bool
    {
        $hashValue = $this->getHashValue($key);
        return in_array($key, $this->hashTable[$hashValue]);
    }

    public function getHashValue(int|string $key): int
    {
        $hashValue = -1;
        if (is_int($key)) {
            $hashValue = $this->hash($key);
        } elseif (is_string($key)) {
            $hashValue = $this->hashString($key);
        }
        return $hashValue;
    }

    public function add(int|string $key, int $value): void
    {
        $hashValue = $this->getHashValue($key);
        if ($hashValue != -1 && !$this->contains($key)) {
            $this->hashTable[$hashValue][] = array($key, $value);
        }
    }

    public function getPosition(int|string $key): int
    {
        $hashValue = $this->getHashValue($key);

        if ($hashValue != -1) {
            for ($i = 0; $i < count($this->hashTable[$hashValue]); $i++) {
                if ($this->hashTable[$hashValue][$i][0] == $key) {
                    return $this->hashTable[$hashValue][$i][1];
                }
            }
        }

        return -1;
    }

    public function getHashTable(): array
    {
        return $this->hashTable;
    }
}