<?php

include 'tile.php';

class Game
{
    // Set constants
    const MAX_PLAYER = 2;
    const STARTING_NUMBER_OF_TILES = 7;
    const STACK = 11;
    const BOARD = 12;

    //Declare global variables
    private $playOn;
    public $tiles=[];
    public $gameBoard = array();
    public $availableTiles;
    public $activePlayer;

    //main function, public
    public function startGame() {
        $this->activePlayer = 0;
        $this->availableTiles = 28;

        $this->playOn = true;
        $this->generateTiles();
        $this->deal();
        $this->selectStartTile();
        $this->playGame();
    }

    //functions called by startGame(), all private
    public function generateTiles()
    {
        // make 28 tiles to play with
        for ($h = 0; $h < 7; $h++){
            for($l = 0; $l <= $h; $l++){
                $tile = new Tile();
                $tile->setPlayer($this::STACK);
                $tile->setHigh($h);
                $tile->setLow($l);
                $this->tiles[] = $tile;
            }
        }
        //print_r($this->tiles);
    }
    public function deal(){
        for ($this->activePlayer = 0; $this->activePlayer < $this::MAX_PLAYER; $this->activePlayer++){
            for ($tileInHand = 0; $tileInHand < $this::STARTING_NUMBER_OF_TILES; $tileInHand++){
                $this->pickRandomTile();
            }
        }
        $this->activePlayer = 0;

    }
    private function selectStartTile(){
       /**
        * $this->pickRandomTile("table");
        * this doesn't work because there is no output to add to
        */
       $random = rand(0,27);
       $this->gameBoard = [$this->tiles[$random]->getLow(), $this->tiles[$random]->getHigh()];
       $this->tiles[$random]->setPlayer($this::BOARD);
       print("initial tile is: <br>");
       print("[" . $this->gameBoard[0] . ":" . $this->gameBoard[1] . "]". "<br>" );
    }
    private function playGame(){
        while ($this->playOn){
            // player tries to play a tile, draws a tile if fails to play one.
            $this->playTile();
            $this->generateOutput();
            $this->nextPlayer();
        }
        print("game over <br>");
    }

    //functions called by private functions
    private function pickRandomTile() {

        /**
         * random_int is more random, but it can throw exceptions and lesser quality randomness seems acceptable
         *
         * the loop will be slow toward the end,
         * when most tiles have been taken out of the stack, the chance of randomly picking a tile that is still in the stack shrinks
         * I could count the number of tiles left in the stack,
         * set that as the upper limit in rand()
         * and then pick the rand()-th tile that is still in the stack
         *
         * That just seems a lot of code for little gain.
         */

        do {
            $randomTileNumber = rand(0,27);
        } while ($this->tiles[$randomTileNumber]->getPlayer() !== $this::STACK);
        //print("player " . $this->activePlayer . " drew tile <br>");
        $this->tiles[$randomTileNumber]->setPlayer($this->activePlayer);
        $this->availableTiles--;
    }
    private function playTile(){
        while($this->cannotPlay()){
            if($this->availableTiles == 0){
                print("player ". $this->activePlayer . " needs to draw a tile to continue but there are no tiles left in the stack");
                $this->playOn = false;
                break;
            }else{
                $this->pickRandomTile();
                print("player " . $this->activePlayer . " drew a tile <br>");
            }
        }
        /**
         * This would be faster:
         * Don't loop over all tiles but only over newly picked tile after finding that player can't play a tile.
         * That would require a return value from pickRandomTile($p)
         * And I don't want to refactor that quite yet.
         */
    }
    private function nextPlayer() {
        $this->activePlayer =  ($this->activePlayer + 1) % $this::MAX_PLAYER;
    }
    private function cannotPlay(): bool {
        /**
         * Either play a tile or report that you can't play a tile
         */

        foreach ($this->tiles as $tile){
            if($tile->getPlayer() == $this->activePlayer ){

                if($tile->getLow() == $this->gameBoard[0]){
                    array_unshift($this->gameBoard, $tile->getHigh(), $tile->getLow());
                    $tile->setPlayer($this::BOARD);
                    // if number of remaining tiles for activePlayer == 0, activePlayer wins.
                    return false; }
                if($tile->getHigh() == $this->gameBoard[0]){
                    array_unshift($this->gameBoard, $tile->getLow(), $tile->getHigh());
                    $tile->setPlayer($this::BOARD);
                    return false; }
                if($tile->getLow() == $this->gameBoard[count($this->gameBoard)-1]){
                    /**
                     * probably faster to do:
                     * $this->gameBoard = $tile->getLow();
                     * $this->gameBoard = $tile->getHigh();
                     */
                    array_push($this->gameBoard, $tile->getLow(), $tile->getHigh());
                    $tile->setPlayer($this::BOARD);
                    return false; }
                if($tile->getHigh() == $this->gameBoard[count($this->gameBoard)-1]){
                    array_push($this->gameBoard, $tile->getHigh(), $tile->getLow());
                    $tile->setPlayer($this::BOARD);
                    return false; }
            }
        }
        return true;
    }
    private function generateOutput()
    {
        print("Current player is " . $this->activePlayer . "<br>");
        print("Line of domino tiles is: <br>");
        for ($i = 0; $i < count($this->gameBoard); $i++){
            if ($i % 2 == 0){
                print("[ " . $this->gameBoard[$i] . " ,");
            }else{
                print($this->gameBoard[$i] .  "] , ");
            }
        }
        print("<br>");
    }

}