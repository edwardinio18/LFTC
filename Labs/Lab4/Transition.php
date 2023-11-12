<?php

class Transition
{
    private $from;
    private $to;
    private $label;

    public function __construct($from, $to, $label)
    {
        $this->from = $from;
        $this->to = $to;
        $this->label = $label;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setFrom($from)
    {
        $this->from = $from;
    }

    public function setTo($to)
    {
        $this->to = $to;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }
}