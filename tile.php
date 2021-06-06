<?php


class Tile
{
    private $player;
    private $low;
    private $high;

    public function getPlayer()
    {
        return $this->player;
    }
    public function setPlayer($p)
    {
        $this->player = $p;
    }

    public function getLow()
    {
        return $this->low;
    }
    public function setLow($l)
    {
        $this->low = $l;
    }

    public function getHigh()
    {
        return $this->high;
    }
    public function setHigh($h)
    {
        $this->high = $h;
    }
}