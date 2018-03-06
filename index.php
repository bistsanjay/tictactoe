<?php
include  "config.php";

$param = array();
$paramAllowedTypes = array();
/** Validate or sanitize  the Request **/
$paramKeys = array("type","pos","player");
$paramAllowedTypes = array("makemove","refresh");
$paramAllowedPos = array("0x0","0x1","0x2","1x0","1x1","1x2","2x0","2x1","2x2");

foreach($_REQUEST as $reqKey => $reqValues) {
	if(in_array(strtolower($reqKey),$paramKeys)){
		$reqKey = strtolower($reqKey);
		$param[$reqKey] = $reqValues;
	}
	else {
		unset($_REQUEST[$reqKey]);
	}	
}
$isInvalidRequest = 0;
if(!isset($param['type'])){
  $arrResp['errorCode'] = 8;
  $arrResp['errorDescription'] = "Error: Request Parameter `type` is not found in request";  
  $isInvalidRequest = 1;
}
else if(!in_array(strtolower($param['type']),$paramAllowedTypes)){
  $arrResp['errorCode'] = 6;
  $arrResp['errorDescription'] = "Error: Invalid value for `type` , it should be either makeMove or refresh.";  
  $isInvalidRequest = 1;
 			
}

if($isInvalidRequest==1){
  echo json_encode($arrResp);
  exit();
}
/** Request Validation end here **/
/*********************************/

$objTicTacToe = new TictactoeClass();
if(!isset($_SESSION['arrBoard'])){
	$objTicTacToe->inicializeBoard();
}


$type = isset($param['type'])?$param['type']:'';
switch($type){
	case 'refresh':
		$boardState = $objTicTacToe->inicializeBoard();
		if($param['player']=="computer"){
			$arrResp = $objTicTacToe->setComputerFirstMove();	
			$arrResp["currentState"]=$objTicTacToe->getCurrentState();
			
		}
		else {
			$arrResp = array("status"=>0,
							 "numMovesLeft"=>9,
							 "currentState"=>$boardState);
		}
		$arrResp["errorCode"]=0;
		$arrResp["errorDescription"]="OK";
		
	break;
	case 'makeMove':
	
		$isWin =0;
		//ob_start();
		echo "&nbsp;";
		
		$objTicTacToe->currentMovePos = $param['pos'];
		$boardState = $objTicTacToe->getCurrentState();
		$numMovesLeft = $objTicTacToe->numMovesLeft($boardState);
		
		$objTicTacToe->validateMove();
		
		$botsRecentMove = array();
		if($objTicTacToe->errorCode==0){ // No Error in request
			/** It is Opponent Move**/
			$objTicTacToe->makeMove($boardState,"X");
			$boardState = $objTicTacToe->getCurrentState();
			$isWin = $objTicTacToe->isWin($boardState );
			$numMovesLeft = $objTicTacToe->numMovesLeft($boardState);
			
			/** It is Computer's Move**/
			
			if($isWin==0 && $numMovesLeft>0){
				$playerUnit="O";
				$objTicTacToe->currentMovePos = $objTicTacToe->findBestMovePosition($boardState,$playerUnit);
				/** It is Required Result**/
				$botsRecentMove = $objTicTacToe->makeMove($boardState,"O");
				
			}
		}
		$boardState = $objTicTacToe->getCurrentState();
		$isWin = $objTicTacToe->isWin($boardState);
		$numMovesLeft = $objTicTacToe->numMovesLeft($boardState);
	
		
		
		$arrResp = array("status"=>$isWin,
						"pos"=>$objTicTacToe->currentMovePos,
						"numMovesLeft"=>$numMovesLeft,
						"currentState"=>$boardState,
						"errorCode"=>$objTicTacToe->errorCode,
						"errorDescription"=>$objTicTacToe->errorDescription,
						"botsRecentMove"=>$botsRecentMove
						);
	break;
}
echo json_encode($arrResp);
?>