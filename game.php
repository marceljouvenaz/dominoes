<?php

include 'tile.php';

class Game
{
    /**
     * Set constants
    */
    const MAX_PLAYER = 2;
    const STARTING_NUMBER_OF_TILES = 7;
    const MAX_DOTS = 6;
    const TILES_IN_GAME = 28; // = MAX_DOTS*(MAX_DOTS + 1)/2
    const STACK = 11;
    const BOARD = 12;

   /**
    * Declare global variables
    * would have preferred private variables with getters and setters,
    * but it didn't seem worth the hassle in this little code
   */
    private $playOn;
    public $tiles=[];
    public $gameBoard = "";
    public $availableTiles;
    public $activePlayer;

    /**
     * main function, public
     */
    public function startGame() {
        $this->activePlayer = 0;
        $this->availableTiles = self::TILES_IN_GAME;

        $this->playOn = true;
        $this->generateTiles();
        $this->deal();
        $this->selectStartTile();
        $this->playGame();
    }

    /**
     * functions called by startGame(), all private
     */
    public function generateTiles()
    {
        // make TILES_IN_GAME tiles to play with
        for ($h = 0; $h <= self::MAX_DOTS; $h++){
            for($l = 0; $l <= $h; $l++){
                $tile = new Tile();
                $tile->setPlayer(self::STACK);
                $tile->setHigh($h);
                $tile->setLow($l);
                $this->tiles[] = $tile;
            }
        }
    }
    public function deal(){
        for ($this->activePlayer = 0; $this->activePlayer < self::MAX_PLAYER; $this->activePlayer++){
            for ($tileInHand = 0; $tileInHand < self::STARTING_NUMBER_OF_TILES; $tileInHand++){
                $this->pickRandomTile();
            }
        }
        $this->activePlayer = 0;
    }
    private function selectStartTile(){
       /**
        * too close to functional programming,
        * no output so have to create an interim variable.
        * could have created a global variable active tile?
        */
       $random = rand(0,27);
       // $this->gameBoard = [$this->tiles[$random]->getLow(), $this->tiles[$random]->getHigh()];
       $this->gameBoard = "[" . $this->tiles[$random]->getLow() . ":" . $this->tiles[$random]->getHigh() . "]";
       $this->tiles[$random]->setPlayer(self::BOARD);
       print("initial tile is: <br>");
       print($this->gameBoard . "<br>" );
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

    /**
     * functions called by private functions
     */
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
        $this->activePlayer =  ($this->activePlayer + 1) % self::MAX_PLAYER;
    }
    private function cannotPlay(): bool {
        /**
         * Either play a tile or report that you can't play a tile
         */

        foreach ($this->tiles as $tile){
            if($tile->getPlayer() == $this->activePlayer ){

                if($tile->getLow() == substr($this->gameBoard,1,1)){
                    $this->gameBoard = "[" . $tile->getHigh() . ":" . $tile->getLow() . "] , " . $this->gameBoard;
                    $tile->setPlayer(self::BOARD);
                    // if number of remaining tiles for activePlayer == 0, activePlayer wins.
                    return false; }
                if($tile->getHigh() == substr($this->gameBoard,1,1)){
                    $this->gameBoard = "[" . $tile->getLow() . ":" . $tile->getHigh() . "] , " . $this->gameBoard;
                    $tile->setPlayer(self::BOARD);
                    return false; }
                if($tile->getLow() == substr($this->gameBoard,-2,1)){
                    $this->gameBoard = $this->gameBoard . " , [" . $tile->getLow() . ":" . $tile->getHigh() . "]";
                    $tile->setPlayer(self::BOARD);
                    return false; }
                if($tile->getLow() == substr($this->gameBoard,-2,1)){
                    $this->gameBoard = $this->gameBoard . " , [" . $tile->getHigh() . ":" . $tile->getLow() . "]";
                    $tile->setPlayer(self::BOARD);
                    return false; }
            }
        }
        return true;
    }
    private function generateOutput()
    {
        print("Current player is " . $this->activePlayer . "<br>");
        print("Line of domino tiles is: <br>");
        print($this->gameBoard);
        print("<br>");
    }

}