<?php


class game
{
/**
 *
 * pseudo code the steps of the game
 *
*/

    const MAXPLAYER = 2;
    const MAXTILES = 7;
    private $playOn;

    public function startGame() {
        $this->playOn = true;
        $this->deal();
        $this->selectStartTile();
        $this->playGame();
    }

    public function deal(){
        for ($player = 0; $player < $this::MAXPLAYER; $player++){
            for ($card = 0; $card < $this::MAXTILES; $card++){
                $this->pickRandomTile($player);
            }
        }

    }
    private function selectStartTile(){
        $this->pickRandomTile("table");
    }
    private function playGame(){
        $player = 0;

        while ($this->playOn){
            // player tries to play a tile, draws a tile if fails to play one.
            $this->playTile($player);
            $this->nextPlayer($player);
            $this->checkPlayOn($player);
            $this->generateOutput();
        }
    }
    private function pickRandomTile($p) {
        // add a random tile to player $p
        // probably easiest to consider all 28 tiles as an array with value and holder of the tile.
        // i.e. tiles[$i] = [ lowest face, highest face, player (or stack, or table)]
    }

}