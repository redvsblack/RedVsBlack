<?php
session_start();

include('define.settlements.php');
 
$settlementCode=$_POST['settlementCode'];
$pieceColor=$_POST['pieceColor'];
$thisX=$_POST['thisX'];
$thisY=$_POST['thisY'];

$tempInfo=$_SESSION['allSettlements']["".$settlementCode.""];

 $thisSettlement=new Settlement($pieceColor, $thisX, $thisY, $tempInfo);
   $players[$pieceColor]["Settlements"][]=$thisSettlement;
 

  $graphic=trim($thisSettlement->graphic);
 
  $settlementText="<img name='".$thisSettlement->name."' class='".$pieceColor." ".$thisSettlement->code." ".$thisSettlement->isLinked." settlement' id='".$thisSettlement->uniqueCode."' src='".$graphic."' />";
	
 echo($settlementText);
?>