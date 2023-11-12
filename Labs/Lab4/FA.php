<?php

require_once("Transition.php");

class FA
{
    private string $filename;
    private array $states = [];
    private array $alphabet = [];
    private array $transitions = [];
    private string $initial_state = "";
    private array $output_states = [];

    public function __construct($filename)
    {
        $this->filename = $filename;
        try {
            $this->init();
        } catch (Exception $e) {
            echo "Error when initializing FA: " . $e->getMessage();
        }
    }

    private function init()
    {
        $regex = "/^([a-z_]*)=/";
        $file_content = file_get_contents($this->filename);
        $lines = explode("\n", $file_content);

        foreach ($lines as $line) {
            if (preg_match($regex, $line, $matches)) {
                $keyword = $matches[1];
                $value = substr($line, strpos($line, "=") + 1);
                switch ($keyword) {
                    case "states":
                        $this->states = $this->parseList($value);
                        break;
                    case "alphabet":
                        $this->alphabet = $this->parseList($value);
                        break;
                    case "out_states":
                        $this->output_states = $this->parseList($value);
                        break;
                    case "initial_state":
                        $this->initial_state = trim($value);
                        break;
                    case "transitions":
                        $this->transitions = $this->parseTransitions($value);
                        break;
                    default:
                        throw new Exception("Invalid line in file");
                }
            }
        }
    }

    private function parseList($string)
    {
        $string = trim($string, "{} \t\n\r\0\x0B");
        return array_map('trim', explode(',', $string));
    }

    private function parseTransitions($string)
    {
        $transitions = [];
        $transList = explode(';', trim($string, "{} \t\n\r\0\x0B"));
        foreach ($transList as $trans) {
            $parts = explode(',', trim($trans, "() \t\n\r\0\x0B"));
            $transitions[] = new Transition(trim($parts[0]), trim($parts[1]), trim($parts[2]));
        }
        return $transitions;
    }

    public function printStates()
    {
        $this->printListOfString("states", $this->states);
    }

    public function printAlphabet()
    {
        $this->printListOfString("alphabet", $this->alphabet);
    }

    public function printOutputStates()
    {
        $this->printListOfString("out_states", $this->output_states);
    }

    public function printInitialState()
    {
        echo "initial_state = " . $this->initial_state . "\n";
    }

    public function printTransitions()
    {
        echo "transitions = {";
        $lastIndex = count($this->transitions) - 1;
        foreach ($this->transitions as $i => $transition) {
            if ($i != $lastIndex) {
                echo "(" . $transition->getFrom() . ", " . $transition->getTo() . ", " . $transition->getLabel() . "); ";
            } else {
                echo "(" . $transition->getFrom() . ", " . $transition->getTo() . ", " . $transition->getLabel() . ")";
            }
        }
        echo "}\n";
    }

    private function printListOfString($listName, $list)
    {
        echo $listName . " = {" . implode(', ', $list) . "}\n";
    }

    public function checkAccepted($word)
    {
        $wordAsArray = str_split($word);
        $currentState = $this->initial_state;
        $idx = 0;
        foreach ($wordAsArray as $c) {
            $found = false;
            if ($idx >= count($this->transitions)) {
                return false;
            }
            $transition = $this->transitions[$idx];
            if ($transition->getFrom() == $currentState && $transition->getLabel() == $c) {
                $currentState = $transition->getTo();
                ++$idx;
                $found = true;
            }
            if (!$found) {
                return false;
            }
        }
        return in_array($currentState, $this->output_states);
    }

    public function getNextAccepted($word)
    {
        $currentState = $this->initial_state;
        $acceptedWord = "";
        $idx = 0;
        foreach (str_split($word) as $c) {
            $newState = null;
            if ($idx >= count($this->transitions)) {
                return $acceptedWord;
            }
            $transition = $this->transitions[$idx];
            if ($transition->getFrom() == $currentState && $transition->getLabel() == $c) {
                $newState = $transition->getTo();
                ++$idx;
                $acceptedWord .= $c;
            }
            if ($newState == null) {
                if (!in_array($currentState, $this->output_states)) {
                    return null;
                } else {
                    return $acceptedWord;
                }
            }
            $currentState = $newState;
        }
    }
}