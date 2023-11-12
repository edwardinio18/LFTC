<?php

require_once("FA.php");

function printMenu()
{
    echo "\nMenu:\n";
    echo "1. Print states\n";
    echo "2. Print alphabet\n";
    echo "3. Print output states\n";
    echo "4. Print initial state\n";
    echo "5. Print transitions\n";
    echo "6. Check word\n";
    echo "7. Get matching substring\n";
    echo "0. Exit\n\n";
}

// The main program logic
$fa = new FA("fa.in");

while (true) {
    printMenu();
    $option = readline("Enter an option: ");
    if ($option === "0") {
        break;  // Exit the loop and end the program
    } elseif ($option === "1") {
        $fa->printStates();
    } elseif ($option === "2") {
        $fa->printAlphabet();
    } elseif ($option === "3") {
        $fa->printOutputStates();
    } elseif ($option === "4") {
        $fa->printInitialState();
    } elseif ($option === "5") {
        $fa->printTransitions();
    } elseif ($option === "6") {
        while (true) {
            $word = readline("Enter a word (or type 'exit' to return to the main menu): ");
            if (strtolower($word) === 'exit') {
                break;
            }
            $accepted = $fa->checkAccepted($word);
            if ($accepted) {
                echo "The word '{$word}' is accepted.\n";
            } else {
                echo "The word '{$word}' is NOT accepted.\n";
            }
        }
    } elseif ($option === "7") {
        while (true) {
            $word = readline("Enter a word (or type 'exit' to return to the main menu): ");
            if (strtolower($word) === 'exit') {
                break;
            }
            $longest_accepted = $fa->getNextAccepted($word);
            if ($longest_accepted) {
                echo "The longest accepted substring is '{$longest_accepted}'.\n";
            } else {
                echo "No accepted substring found.\n";
            }
        }
    } else {
        echo "Invalid option. Please try again.\n";
    }
}

echo "\nProgram ended.\n";