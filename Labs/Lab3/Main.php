<?php

require_once("SymbolTable.php");
require_once("Scanner.php");

/**
 * Class Main
 * A class to demonstrate the usage of SymbolTable.
 */
class Main
{
    /**
     * Run the main application.
     */
    public static function run(): void
    {
        $scanner = new Scanner();
        $p1 = "p2.txt";
        $scanner->scan($p1);
    }
}

Main::run();