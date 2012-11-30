 <?php

 if(isset($_POST['windowWidth'])){
 $windowWidth=$_POST['windowWidth'];
 $windowHeight=$_POST['windowHeight'];
 
 }
 
 
if(!isset($_POST['mapWidth'])) {
 
$mapWidth=48;
$mapHeight=36;
$cellWidth=32;
$cellHeight=32;
$iterations=12;
$waterLandBalance=-1;
$elevationMod=5;
$landbridgesAdj=10;
$plainsMaxWater=1;
$deserts=on;
$lakesBalance=4;
$varyShadesBy=10;
$tempAdjust=6;
$polarBoundary=0.4;
$forestsThresh=30;

}else{

/* BEGIN CURRENT FORM VARIABLES */
 
/* Custom Variables */




$cellWidth=intval($_POST['cellWidth']);
$cellHeight=intval($_POST['cellHeight']);
$mapWidth=intval($_POST['mapWidth']);
$mapHeight=intval($_POST['mapHeight']);
$iterations=intval($_POST['iterations']);  // 5-6: all continents, 8: chain continents with seas, 10: 

$totalWidth=($cellWidth+2)*$mapWidth;


/* Continent Control functions */
$waterLandBalance=intval($_POST['oceansLand']);
$elevationMod=intval($_POST['elevationMod']);
$landbridgesAdj=(intval($_POST['isthmuses']));

/* Terrain type variables */
$plainsMaxWater=$_POST['plainsWater'];; // water neighbor threshhold for plains/grass
$deserts=$_POST['deserts']; // deserts (no water neighbors) off/on
$lakesBalance=round(intval($_POST['lakesBalance'])/3);  // random lakes off/on
$tempAdjust=(intval($_POST['iceCaps']));
$polarBoundary=(intval($_POST['iceCaps'])/10);
$forestsThresh=(intval($_POST['forestsBalance']));

/* Aesthetic */
// $varyShadesBy=(intval($_POST['varyShades']));






}

 ?>
 

<style type="text/css">

.tileIndex{display:table-cell;background-color:white;border:1px dotted #ddd;overflow:hidden;margin:0px;padding:0px;width:<?php echo round($cellWidth-2); ?>px;height:<?php echo $cellHeight; ?>px;font-size:8px;line-height:12px;float:left;}

#Map_window table{empty-cells: show;}
 #board{empty-cells: show;width:<?php echo(($mapWidth)*($cellWidth+2));?>px;height:<?php echo(($mapHeight+2)*$cellHeight);?>px;display:table;font-size:0.4em;margin:0px auto;position:relative;}
 
 .board_row{display:table-row;padding:0;margin:0;z-index:2;position:relative;height:<?php echo $cellHeight-2; ?>px;clear:both;}
 .tile{display:table-cell;text-align:center;width:<?php echo $cellWidth-2; ?>px;height:<?php echo $cellHeight; ?>px;border-radius:2px;border:1px;margin:0px;padding:0px;z-index:2;float:left;overflow:hidden;position:relative;}

			
		<?php 
		if(isset($_POST['oceanColor'])) {
		$oceanColor=stripslashes($_POST['oceanColor']);
		$shallowColor=stripslashes($_POST['shallowColor']);
		$grasslandColor= stripslashes($_POST['grasslandColor']);
		$plainsColor= stripslashes($_POST['plainsColor']);
		$desertColor= stripslashes($_POST['desertColor']);
		$hillsColor= stripslashes($_POST['hillsColor']);
		$mountainColor=stripslashes($_POST['mountainColor']);
		$iceColor=  stripslashes($_POST['snowColor']);
		}
		else{
		
		$oceanColor="#4859a3";
		$shallowColor="#616fbf";
		$grasslandColor="#50d941";
		$plainsColor="#c6e86f";
		$desertColor="#f8ffb5";
		$hillsColor="#a7ab63";
		$mountainColor="#6a6c75";
		$iceColor="#f0f0ff";
		
		}
		
		?>
			

		.ocean{background-color:<?php echo $oceanColor; ?>;}
		.shallow{background-color:<?php echo $shallowColor; ?>;}
		.grassland{background-color:<?php echo $grasslandColor; ?>;}
		.plains{background-color:<?php echo $plainsColor;?>;}
		.desert{background-color:<?php echo $desertColor;?>;}
		.hills{background-color:<?php echo $hillsColor;?>;}
		.mountain{background-color:<?php echo $mountainColor;?>;}
		.ice{background-color:<?php echo $iceColor;?>;}	
			
		
		
		<?php $goodyWidth=round(($cellHeight+$cellWidth)/8);?>

		.forest, .oasis, .lake, .volcano, .snowCap, .island{border-radius:3px;margin:auto;z-index:4;position:absolute;margin:0 auto;}
		
		
		.forest{border:2px dotted #3dae31;border-radius:4px;background-color:#181;width:<?php echo $goodyWidth ?>px;height:<?php echo $goodyWidth ?>px;top:33%;left:33%;}
        .oasis{border:1px dotted <?php echo $grasslandColor; ?>;background-color:#66D;width:<?php echo $goodyWidth ?>px;height:<?php echo $goodyWidth ?>px;top:33%;left:33%;}
		.volcano{border:2px dotted #b67171;border-radius:1px;background-color:#fb722e;width:<?php echo $goodyWidth ?>px;height:<?php echo $goodyWidth ?>px;top:33%;left:33%;}
		
		.island{border:2px dotted <?php echo $desertColor;?>;background-color:<?php echo $grasslandColor;?>;width:<?php echo $goodyWidth ?>px;height:<?php echo $goodyWidth ?>px;top:33%;left:33%;}
		
		.snowCap{border:4px dotted #a0a0aa;background-color:<?php echo $iceColor; ?>;width:<?php echo(round($cellWidth*.5));?>px;height:<?php echo(round($cellHeight*.5));?>px;top:10%;left:10%;}
		.lake{background-color:<?php echo $shallowColor;?>;border: 1px dotted <?php echo $desertColor;?>;width:<?php echo ($cellWidth/2); ?>px;height:<?php echo ($cellHeight/2); ?>px;top:25%;left:25%;}



#Navigator {text-align:center;background:url('images/stars.jpg');width:250px;border-radius:8px;padding:0px;margin:0 auto;overflow:hidden;}
#Navigator *{border-radius:0;border-width:0px;;}
#Navigator #board{display:table;width:250px;height:<?php echo ($mapHeight*5);?>px;overflow:auto;margin:0;border-radius:8px;text-align:center;background-color:black;border:2px solid #333;}
#Navigator .board_row{display:table-row;width:<?php echo ($mapWidth*5); ?>px;height:4px;overflow:auto;margin:0 auto;padding:0;position:relative;}
#Navigator .tileIndex{display:none;}
#Navigator .tile, #Navigator.tileIndex{display:table-cell;width:5px;height:5px;margin:0;padding:0;position:relative;}
#Navigator .forest,#Navigator .oasis,#Navigator .lake,#Navigator .iceCap,#Navigator .volcano,#Navigator .island{width:2px;height:2px;margin:auto;z-index:4;position:relative;left:1px;top:1px;}

#navSquare{width:100px;height:100px;position:absolute;border:2px solid #006;z-index:10;margin:0px;padding:0px;}		
		
		
		
</style>

		
		
