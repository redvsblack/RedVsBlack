<?php
session_start();

include('define.improvements.php');
 
$improvementCode=strval($_POST['improvementCode']);
$pieceColor=$_POST['pieceColor'];
$thisX=$_POST['thisX'];
$thisY=$_POST['thisY'];

$tempInfo=$_SESSION['allImprovements']["".$improvementCode.""];

 $thisImprovement=new Improvement($pieceColor, $thisX, $thisY, $tempInfo);
 
   $players[$pieceColor]["Improvements"][]=$thisImprovement;
 
 if($thisImprovement->isLinked==true){$pieceColor="";}  // This will fail eventually

  $graphic=trim($thisImprovement->graphic);
  $improvementText="<img name='".$thisImprovement->name."' class='".$pieceColor." ".$thisImprovement->code." ".$thisImprovement->name." ".$thisImprovement->isLinked." improvement' id='".$thisImprovement->uniqueCode."' src='".$graphic."' />";
	
 echo($improvementText);
?>