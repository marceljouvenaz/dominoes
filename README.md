# dominoes 

This is a simple test to build a game without a framework. 

# steps
- main class runs game  
- game.php holds most of the game logic  
- tile.php holds the tile class  
- since moving $gameBoard into a string from an array, we  can make the tiles simple strings as well, making the entire tile class obsolete.  

# thoughts:
- I want to make variables private, with getters and setters. Requires a refactor because activePlayer is used to control a loop in deal() and I have used a -- on availableTiles. Both are easy, but it would take more time than this is supposed to take.   
- Game is very slow when all tiles are taken from the stash, probably partly due to the pickRandomTile() method which can be problematic when there are few tiles left. I suspect there is also a problem with garbage collection as the first run is usually a lot faster than the later ones.  
- Faster way of dealing is possible, but would take more time than planned. (count number of tiles avaible, generate random number below it, count throug available tiles to get the random available tile.)  
- 
