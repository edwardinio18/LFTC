<?php

require_once("HashTable.php");

class SymbolTable
{
    private HashTable $hashTable;
    private int $nextCode = 0;

    public function __construct(int $capacity)
    {
        $this->hashTable = new HashTable($capacity);
    }

    public function add(int|string $symbol): void
    {
        if (!$this->hashTable->contains($symbol)) {
            $this->hashTable->add($symbol, $this->nextCode);
            $this->nextCode++;
        }
    }

    public function getPosition(int|string $key): int
    {
        return $this->hashTable->getPosition($key);
    }

    public function __toString(): string
    {
        $result = "\n{";

        for ($i = 0; $i < $this->hashTable->getCapacity(); $i++) {
            $result .= "\n\t$i => [";
            for ($j = 0; $j < count($this->hashTable->getHashTable()[$i]); $j++) {
                $result .= "(" . $this->hashTable->getHashTable()[$i][$j][0] . ", " . $this->hashTable->getHashTable()[$i][$j][1] . ")";
                if ($j < count($this->hashTable->getHashTable()[$i]) - 1) {
                    $result .= ", ";
                }
            }
            $result .= "]";
        }

        $result .= "\n}";

        return $result;
    }
}
