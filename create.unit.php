<?php
session_start();

include('define.units.php');

$unitCode=$_POST['unitCode'];
$pieceColor=$_POST['pieceColor'];
$thisX=$_POST['thisX'];
$thisY=$_POST['thisY'];

$tempInfo=$_SESSION['allUnits'][$unitCode];

 $thisUnit=new Unit($pieceColor, $thisX, $thisY, $tempInfo);
   $players[$pieceColor]["Units"][]=$thisUnit;
 

  $graphic=trim($thisUnit->graphic);
  $unitText="<img name='".$thisUnit->name."' class='".$pieceColor." ".$thisUnit->code." unit' id='".$thisUnit->uniqueCode."' src='pieces/".$graphic."' />";
	
 echo($unitText);
?>