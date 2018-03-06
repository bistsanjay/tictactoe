<?php

class TictactoeClass implements MoveInterface {
//class TictactoeClass {
private $numMoveLeft = 9;	
public $currentMovePos='';
public $errorCode ='0'; // No Error
public $errorDescription ='OK'; // No Error
public $currentMovePos1;



function __construct(){
	
}

/**
It will initialize the board as "_"
So in complete life cycle of the game.
It will get called once.
**/	
function inicializeBoard(){
	$arrBoard = array();
	for($i=0;$i<=2;$i++){
		for($j=0;$j<=2;$j++){
			$arrBoard[$i][$j]="_";
		}
	}
	$_SESSION['arrBoard'] = $arrBoard;
	return $_SESSION['arrBoard'];
}

/*
it will update the board state by making move in board
*/
function makeMove($boardState,$playerUnit='X'){
	$arrPos = explode("x",$this->currentMovePos);
	$boardState[$arrPos[0]][$arrPos[1]] = $playerUnit;
	$_SESSION['arrBoard'] = $boardState;
	return array($arrPos[0],$arrPos[1],$playerUnit);
}

/*
It will return the current state of game boad
*/
function getCurrentState(){
	return $_SESSION['arrBoard'];
}

/*
This function will return how many move left in the board out of 9 moves
*/
function numMovesLeft($boardState){
	$num = 0;
	for($i=0;$i<3;$i++){
		for($j=0;$j<3;$j++){
			if($boardState[$i][$j]=="_"){
				$num++;	
			}
		}
	}
	return $num;
}	
	
/*
It will return wining player or 0 is game is still on
*/
function isWin($boardState){
	//IF Row get completed with same Set
	for($i=0;$i<3;$i++){
		if ($boardState[$i][0]==$boardState[$i][1] &&	$boardState[$i][1]==$boardState[$i][2] && $boardState[$i][0]!=="_")
		{
			return $boardState[$i][0];
		}		
	}
	//IF columns get completed with same Set	
	for($j=0;$j<3;$j++){
		if ($boardState[0][$j]==$boardState[1][$j] &&	$boardState[1][$j]==$boardState[2][$j] && $boardState[1][$j]!=="_")
		{
			return $boardState[0][$j];
		}
	}
	//IF diagonal get completed with same set		
	if ($boardState[0][0]==$boardState[1][1] && $boardState[1][1]==$boardState[2][2] && $boardState[1][1]!=="_"){
			return $boardState[0][0];
	}
	//IF diagonal get completed with same set		
	if($boardState[0][2]==$boardState[1][1] && $boardState[1][1]==$boardState[2][0] && $boardState[2][0]!=="_"){
			return $boardState[1][1];
	}
	return 0;
}



function findBestMovePosition($boardState,$playerUnit){
	
	$emptyBlocks=0;
	$arrRemaingPostions = array();
	$arrMyPositions["top_left_diagonal"] = array("0x0","1x1","2x2");
	$arrMyPositions["top_right_diagonal"] = array("0x2","1x1","2x0");
	$arrOpponentPositions["top_left_diagonal"] = array("0x0","1x1","2x2");
	$arrOpponentPositions["top_right_diagonal"] = array("0x2","1x1","2x0");

	$arrMyPositions["row"] = array();
	$arrMyPositions["col"] = array();
	$arrOpponentPositions["row"] = array();
	$arrOpponentPositions["col"] = array();
	
	
	for($i=0;$i<3;$i++){
		for($j=0;$j<3;$j++){
			if($boardState[$i][$j]=="_"){
				$arrRemaingPostions[$emptyBlocks] = $i."x".$j;
				$emptyBlocks++;
			}
			else if($boardState[$i][$j] == OPPONENT) {
				
				@$arrOpponentPositions['row'][$i] ++;	
				@$arrOpponentPositions['col'][$j] ++;	
				// check if it is about to form diagonal for opponent
				if($i==$j){
					unset($arrOpponentPositions['top_left_diagonal'][$i]);
					if($i==1){
						unset($arrOpponentPositions['top_right_diagonal'][$i]);
					}
				}
				if(($i==0 && $j==2) || ($i==2 && $j==0)){
					unset($arrOpponentPositions['top_right_diagonal'][$i]);
				}
				
			}
			else {
				// check if it is about to form diagonal for BOT
				if($i==$j){
					unset($arrMyPositions['top_left_diagonal'][$i]);
					if($i==1){
						unset($arrMyPositions['top_right_diagonal'][$i]);
					}
				}
				if(($i==0 && $j==2) || ($i==2 && $j==0)){
					unset($arrMyPositions['top_right_diagonal'][$i]);
				}
				@$arrMyPositions['row'][$i]++;
				@$arrMyPositions['col'][$j]++;	
			}
		}
	}
	
	//Best move
	if($emptyBlocks==8 && ($boardState[0][0]==OPPONENT || $boardState[0][2]==OPPONENT || $boardState[2][0]==OPPONENT || $boardState[2][2]==OPPONENT)){
		return "1x1";
	}
	
	if($emptyBlocks==8 && ($boardState[1][1]==OPPONENT || $boardState[0][1]==OPPONENT || $boardState[1][0]==OPPONENT  )){
		return "0x0";
	}
	
	if(count($arrMyPositions['top_left_diagonal'])==1){
		 $pos = $this->getDiagonalWinPos($arrMyPositions['top_left_diagonal'],$boardState);
		 if($pos!='0'){
			 return $pos;
		 }
	}
	
	if(count($arrMyPositions['top_right_diagonal'])==1){
		 $pos = $this->getDiagonalWinPos($arrMyPositions['top_right_diagonal'],$boardState);
		 if($pos!='0'){
			 return $pos;
		 }
	}
	
	if(count($arrOpponentPositions['top_left_diagonal'])==1){
		 $pos = $this->getDiagonalWinPos($arrOpponentPositions['top_left_diagonal'],$boardState);
		 if($pos!='0'){
			 return $pos;
		 }
	}
	if(count($arrOpponentPositions['top_right_diagonal'])==1){
		 $pos = $this->getDiagonalWinPos($arrOpponentPositions['top_right_diagonal'],$boardState);
		 if($pos!='0'){
			 return $pos;
		 }
	}
	
	// Play for win	
	foreach($arrMyPositions['row'] as $rowNum=>$myCount){
				// Here is point where I/Bot  can win
				if($myCount==2){
					for($i=0;$i<3;$i++){
						if($boardState[$rowNum][$i]=="_"){
							return $rowNum."x".$i;
						}
					}
					// check for diagonal win
				} 
	}	
	foreach($arrMyPositions['col'] as $colNum=>$myCount){
		   // Here is point where Me can win
			if($myCount==2){
				for($i=0;$i<3;$i++){
					if($boardState[$i][$colNum]=="_"){
						return $i."x".$colNum;
					}
				}
			}
		}	
	
	  // Play for drow	
	   foreach($arrOpponentPositions['row'] as $rowNum=>$opCount){
			// Here is point where opponent can win
			if($opCount==2){
				for($i=0;$i<3;$i++){
					if($boardState[$rowNum][$i]=="_"){
						return $rowNum."x".$i;
					}
				}
			}
		}
		
		foreach($arrOpponentPositions['col'] as $colNum=>$opCount){
			// Here is point where opponent can win
			if($opCount==2){
				for($i=0;$i<3;$i++){
					if($boardState[$i][$colNum]=="_"){
						return $i."x".$colNum;
					}
				}
			}
		}
		if($boardState[1][1]=="_" && $emptyBlocks==7){
			return "1x1";
		}
		else {
			return array_pop($arrRemaingPostions);
		}
}


function getDiagonalWinPos($arrDiag,$boardState){
		$key = $arrDiag[array_keys($arrDiag)[0]];
		$arrKeys = explode("x",$key);
		$key0 = $arrKeys[0];
		$key1 = $arrKeys[1];
		if($boardState[$key0][$key1]=="_"){
			return $key;
		}
		else {
			return 0;
		}
	
}

function setComputerFirstMove(){
	$arrCompFirstMove = array("0x0","0x2","2x0","2x2");
	$this->currentMovePos = $arrCompFirstMove[rand(0,3)];
	$this->makeMove($this->getCurrentState(),"O");
	return $arrResp = array("status"=>0,"pos"=>$this->currentMovePos,"numMovesLeft"=>8);
	
	
}
/*
This function will validate move.

*/
function validateMove(){
	if(empty($this->currentMovePos)){
		$this->errorCode=1;
		$this->errorDescription="Error: Invalid or empty position for move";
	}
	else {
		
		$arrSplit = str_split($this->currentMovePos);
		// Position values should be in 0x0 format, Range form 0x0 to 2x2
		if(($arrSplit[0]>=0 && $arrSplit[0]<=2)  && ($arrSplit[1]=='x') && ($arrSplit[2]>=0 && $arrSplit[2]<=2) ){
			// Check if current position is already filled
			$boardState =$this->getCurrentState();
				if($boardState[$arrSplit[0]][$arrSplit[2]]!="_"){
				$this->errorCode=2;
				$this->errorDescription="Error: Selected block or move is already occupied";
			}
			else {
				$this->errorCode=0;
				$this->errorDescription="OK";
			}
		}
		else {
			$this->errorCode=3;
			$this->errorDescription="Error: Move position should be between range form 0x0 to 2x2";
		}
		
	}
}
	
}
?>