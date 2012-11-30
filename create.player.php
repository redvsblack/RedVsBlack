<?php
$_SESSION["players"]=array("Red", "Black");


foreach ($_SESSION["players"] as $player){

$_SESSION["players"][$player]=array();
$_SESSION["players"][$player]["Units"]=array();
$_SESSION["players"][$player]["Settlements"]=array();
$_SESSION["players"][$player]["Improvements"]=array();
$_SESSION["players"][$player]["Resources"]=array("Gold", "Food");

}

include('load.units.php');
include('define.units.php');
include('load.settlements.php');
include('load.improvements.php');
include('load.terrain.php');





?>