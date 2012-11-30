

 
 

 <?php

 
if(!isset($_POST['mapWidth'])) {
 
 $mapWidth=48;
$mapHeight=36;
$cellWidth=32;
$cellHeight=32;
$iterations=12;
$waterLandBalance=-1;
$elevationMod=5;
$landBridgesAdj=10;
$plainsMaxWater=1;
$deserts='on';
$lakesBalance=4;
$varyShadesBy=10;
$tempAdjust=6;
$polarBoundary=0.4;
$forestsThresh=30;

}
else{

/* BEGIN CURRENT FORM VARIABLES */
 
/* Custom Variables */




$cellWidth=intval($_POST['cellWidth']);
$cellHeight=intval($_POST['cellHeight']);
$mapWidth=intval($_POST['mapWidth']);
$mapHeight=intval($_POST['mapHeight']);
$iterations=intval($_POST['iterations']);  // 5-6: all continents, 8: chain continents with seas, 10: 

$totalWidth=$cellWidth*($mapWidth+2);


/* Continent Control functions */
$waterLandBalance=intval($_POST['oceansLand']);
$elevationMod=intval($_POST['elevationMod']);
$landBridgesAdj=(intval($_POST['isthmuses']));

/* Terrain type variables */
$plainsMaxWater=$_POST['plainsWater'];; // water neighbor threshhold for plains/grass
$deserts=$_POST['deserts']; // deserts (no water neighbors) off/on
$lakesBalance=round(intval($_POST['lakesBalance'])/3);  // random lakes off/on
$tempAdjust=(intval($_POST['iceCaps']));
$polarBoundary=(intval($_POST['iceCaps'])/10);
$forestsThresh=(intval($_POST['forestsBalance']));

/* Aesthetic */
$varyShadesBy=(intval($_POST['varyShades']));






}

 ?>
 
<?php include('gameMapGen.styles.php'); ?>

<?php



/* BEGIN CONSTANT VARIABLES - many of these will be applied to variable functions and are cleared to the above section as I'm content to either
manipulate them via the constants themselves, or coefficients applied to multiple constants */

/* Start Set for data range - VERY sensitive, particularly  */

$lowStartElevation=-25;  // -20: -10 produces continents with few mountains, -30 sparse islands with no highlands
$highStartElevation=75;  // 70:  70 produces chains of low rising continents, 

/* TERRAIN DIVISIONS - These will be exported to a range slider in 'advanced' functions as soon as I figure out how to do the complex slider  */
// It may be reasonable to create a min thresh enable / disable to support overlapping terrain with a random choice generator?

$oceanMaxThresh=-20;
$shallowMaxThresh=0; // don't touch this
$grasslandMaxThresh=42;
$plainsMaxThresh=42;
$hillsMaxThresh=60;
$mountainMaxThresh=75;

/* Variables used to deepen oceans - deepenShallows is manipulated by the 'resist land bridges' function -
/* deepen oceans A/B/C target, respectively, ocean tiles surrounded by increasing amounts of water */
$deepenShallows1=15; // 10/5: chains of continents with vast oceans  5/5: many land bridges 15/15 many land bridges but small land asses
$deepenShallows2=5; // 5/15 sparse islands with many land bridges, 15/5: chains of continents with medium oceans

$deepenOceanA=1*$waterLandBalance; // 1 3 5 with similar effects to above, although lowering tends to produce taller mountains as the balance between deep oceans / tall mountains is thrown off in the levelling
$deepenOceanB=3*$waterLandBalance; // keeping an even ratio and raising the numbers pretty evenly raises the water level
$deepenOceanC=5*$waterLandBalance; 

/* Variables used to smooth elevation on coastlines and potentially to prevent land bridges */
$floodCoasts=10+($landBridgesAdj/1.5); // 10 40: flood coasts acts largely as a 'raise water level' as expected.. shallows preserve does tend to be more sensitive and deepen oceans.
$shallowsPreserve=40+$landBridgesAdj;

/* Variables used to raise (and to an extent smooth) mountains */

$sameNeighborsRange=20; // kept at 50: higher values seem to moderately reinforce larger oceans
$mtnPlainsRaise=3*($elevationMod/10); // 4 30 75 - this and below moderate the influence of mountains 'pull'.  thresholds esp. high seem to have little tangible effect except on extremes
$mtnPlainsLowThresh=30; 
$mtnPlainsHighThresh=45;

$mtnHillsRaise=4*($elevationMod/10); // 6/50/80 basically just creates sharper mountain range
$mtnHillsLowThresh=50;
$mtnHillsHighThresh=70;

/* Post Processing to randomize mountain heights - while it's less important functionally to smooth ocean depths, mountains that are
raised too much like oceans are will bring up the rest of terrain.  This should become a variable soon. */

$mtnLowVariance=-15;  // -10 + 10 just creates variations in mountain heights: raising will produce no tangible effect without elevation, lowering may straight up randomly create valleys in mountain ranges
$mtnHighVariance=30;  // 


/* I could get funky with axis tilt later on.. revisit
$glaciersNorth=$_POST['iceCaps']; // 20 1/X of each pole is covered w/ glaciers
$glaciersSouth=$_POST['iceCaps'];
*/


$board=array();

for($y=0;$y<$mapHeight;$y++){
	for($x=0;$x<$mapWidth;$x++){
	
		if (!isset($_POST['blankMap'])){
	
		$a=rand($lowStartElevation,$highStartElevation);
		$b=$x.",".$y;
		$board[$b]=array();
		$board[$b]["elevation"]=intval($a);
		// $board[$b]["type"]="Feature";
		// $board[$b]["properties"]["name"]="tile";
		// $board[$b]["geometry"]["type"]="Polygon";
		// $board[$b]["geometry"]["coordinates"]=array(array($x,$y), array(($x+1),$y), array(($x+1),($y+1)), array($x,($y+1))); 
		$board[$b]["id"]=$x.",".$y;
	
		}else{
		
		$b=$x.",".$y;
		$board[$b]=array();
		$board[$b]["elevation"]=rand(-200,0);
		$board[$b]["id"]=$x.",".$y;
		
		}
	}
}

/* SMOOTHING */

global $neighborVals;

$count=0;

for($iterationNum=0;$iterationNum<$iterations;$iterationNum++){
	foreach (array_keys($board) as $a){ 
		if (!isset($_POST['blankMap'])){			
				$currentElevation=$board[$a]["elevation"];
				
				$xy=explode(",", $a);
				$x=$xy[0];
				$y=$xy[1];
			
			$neighborVals=array(     // just selects one square in every direction.. this function should be generalized
				
				$board[($x-1).",".($y-1)]["elevation"],
					$board[($x-1).",".($y)]["elevation"],
					$board[($x-1).",".($y+1)]["elevation"],
					$board[($x).",".($y-1)]["elevation"],
					$board[($x).",".($y)]["elevation"],
					$board[($x).",".($y+1)]["elevation"],
					$board[($x+1).",".($y-1)]["elevation"],
					$board[($x+1).",".($y)]["elevation"],
					$board[($x+1).",".($y+1)]["elevation"]);
					
				
				$neighborVals=array_filter($neighborVals);
				
		/* groups water tiles */		
				$sameNeighbors=1;
				foreach($neighborVals as $tempval){
					if (($tempval>($currentElevation-$sameNeighborsRange))&&($tempval<$currentElevation)){$sameNeighbors++;}
				}

		// preliminary deepening of water if there is deeper water around
				if (($currentElevation<$shallowMaxThresh)&&($sameNeighbors>1)){$currentElevation=$currentElevation-(2*$sameNeighbors);}
				if (($currentElevation<$shallowMaxThresh)&&($sameNeighbors>2)){$currentElevation=$currentElevation-$deepenShallows1;}
				
		/* levels up highlands to mountains */
				$highNeighbors=0;  // (# of mountain neighbors including self)
				foreach($neighborVals as $tempval){
					if ($tempval>=50){$highNeighbors++;}
				}

		// if surrounded by mountains, raises height
				if (($currentElevation>$mtnPlainsLowThresh)&&($currentElevation<$mtnPlainsHighThresh)&&($highNeighbors>1)){$currentElevation=$currentElevation+$mtnPlainsRaise;}
				if (($currentElevation>$mtnHillsLowThresh)&&($currentElevation<$mtnHillsHighThresh)&&($highNeighbors>3)){$currentElevation=$currentElevation+$mtnHillsRaise;}

		/* counts surrounding water */
				$waterNeighbors=0;
				foreach($neighborVals as $tempval){
					if ($tempval<0){$waterNeighbors++;}
				}	

		// exponentially decreases depth of water in oceans / isolates lakes
				if (($currentElevation<$shallowMaxThresh)&&($waterNeighbors>=1)){$currentElevation=($currentElevation-$deepenShallows2);}
				if (($currentElevation<-$oceanMaxThresh)&&($waterNeighbors>1)){$currentElevation=($currentElevation-$deepenOceanA);}
				if (($currentElevation<-$oceanMaxThresh)&&($waterNeighbors>3)){$currentElevation=($currentElevation-$deepenOceanB);}
				if (($currentElevation<-$oceanMaxThresh)&&($waterNeighbors>5)){$currentElevation=($currentElevation-$deepenOceanC);}

		// helps deepen shallow water : basically counteracts averaging with below
				if (($currentElevation<5)&&($currentElevation>=-25)&&($waterNeighbors>2)){$currentElevation=($currentElevation-$floodCoasts);}


		/* THIS SECTION DEALS WITH LESSENING EFFECT OF NEIGHBORS TO PRESERVE ITEMS, RATHER THAN CHANGE THEM */		
				
				$neighborVals=(array_sum($neighborVals)/count($neighborVals));   

		/* preserves shorelines */
				if (($currentElevation<$shallowMaxThresh)&&($currentElevation>-$oceanMaxThresh)&&($waterNeighbors>1)){$neighborVals=$neighborVals-$shallowsPreserve;}

		/* preserves highlands */
				if ($currentElevation > 40) {$neighborVals=$neighborVals;} // preserve hills, should be hillsMinThresh
				if ($currentElevation > 65){$neighborVals=$neighborVals;} //preserve mtn
				
				if  (($currentElevation>65)&&($sameNeighbors>2)){$neighborVals=$neighborVals;} //preserve ranges

		/* processes - coefficients larger decreases effect of surrounding territory */
				$tileWeight=(2*$currentElevation);
				$currentElevation=(($tileWeight+$neighborVals)/3);  // at this point I got tired of writing "current" apparently
				// $c=$avgs[$count];
				
				$currentElevation=round($currentElevation);
				$currentElevation=intval($currentElevation);

				$board[$a]["elevation"]=$currentElevation;
				
				
		}	
	
				
				/* BEGIN POST PROCESSING/SMOOTHING*/
				
				$aye=$iterationNum;
				if($aye==($iterations-1)){ 
				
					$waterNeighbors=0;
					$landNeighbors=0;
					$isDesert=false;
					$isPlains=false;
					$isLake=false;
					$isPolar=false;

					$neighborVals=array(     // just selects one square in every direction
				
					$board[($x-1).",".($y-1)]["elevation"],
					$board[($x-1).",".($y)]["elevation"],
					$board[($x-1).",".($y+1)]["elevation"],
					$board[($x).",".($y-1)]["elevation"],
					$board[($x).",".($y)]["elevation"],
					$board[($x).",".($y+1)]["elevation"],
					$board[($x+1).",".($y-1)]["elevation"],
					$board[($x+1).",".($y)]["elevation"],
					$board[($x+1).",".($y+1)]["elevation"]
					);
					
					// to introduce subtle world tweaking.. these variables inactive for now 
					$seaLevelRaise=10;
					$stretchWorld=1;
					
					
					
					// $currentElevation=array_sum($neighborVals)/count($neighborVals);
					$currentElevation=round($currentElevation);
					$currentElevation=$stretchWorld*$currentElevation+$seaLevelRaise;
					
					$neighborVals=array_filter($neighborVals);
					
					foreach($neighborVals as $tempval){
						if ($tempval<0){$waterNeighbors++;}
						if ($tempval>0){$landNeighbors++;}
					}
				
					if (($currentElevation>=0)&&($currentElevation<40)&&($waterNeighbors<=$plainsMaxWater)){ // if limited water, prepare to make plains
							
							$isPlains=true;  // or designates as plain
							
							if($currentElevation>20){$plainsVar=rand(-5,10);$currentElevation=$currentElevation+$plainsVar;} //mixes up plain height
						}
					

					$board[$a]["elevation"]=$currentElevation; // necessary so that desert doesn't read water
				
					 $twoSquareNeighbors=array(
					 $board[($x-1).",".($y-1)]["elevation"],
					$board[($x-1).",".($y)]["elevation"],
					$board[($x-1).",".($y+1)]["elevation"],
					$board[($x).",".($y-1)]["elevation"],
					$board[($x).",".($y)]["elevation"],
					$board[($x).",".($y+1)]["elevation"],
					$board[($x+1).",".($y-1)]["elevation"],
					$board[($x+1).",".($y)]["elevation"],
					$board[($x+1).",".($y+1)]["elevation"],
					
					$board[($x-2).",".($y-2)]["elevation"],
					$board[($x-2).",".($y-1)]["elevation"],
					$board[($x-2).",".($y)]["elevation"],
					$board[($x-2).",".($y+1)]["elevation"],
					$board[($x-2).",".($y+2)]["elevation"],
				
					$board[($x+2).",".($y-2)]["elevation"],
					$board[($x+2).",".($y-1)]["elevation"],
					$board[($x+2).",".($y)]["elevation"],
					$board[($x+2).",".($y+1)]["elevation"],
					$board[($x+2).",".($y+2)]["elevation"],
					
					 $board[($x-1).",".($y-2)]["elevation"],
					$board[($x).",".($y-2)]["elevation"],
					$board[($x+1).",".($y-2)]["elevation"],
					
					 $board[($x-1).",".($y+2)]["elevation"],
					$board[($x).",".($y+2)]["elevation"],
					$board[($x+1).",".($y+2)]["elevation"]);
					

					$twoSquareNeighbors=array_filter($twoSquareNeighbors);
					

					$waterNeighbors2=0;
					$landNeighbors2=0;
					foreach($twoSquareNeighbors as $twoVal){
						if ($twoVal<0){$waterNeighbors2++;}
						if ($twoVal>=0){$landNeighbors2++;}
					}
					if ($landNeighbors2==0){$landNeighbors2=1;}
					if ($landNeighbors==0){$landNeighbors=1;}
					
					
					if (($currentElevation>0)&&($currentElevation<12)&&(($waterNeighbors/$landNeighbors)<1.1*(($waterNeighbors2/$landNeighbors2)))){$currentElevation=$currentElevation-(2*$floodCoasts);} // lowers itshmuses? 1.1-1.5
					// if (($c<-20)&&($landNeighbors>=3)){$c=rand(-$oceanMaxThresh,0);} // guarantees coast
					// if (($c<10)&&($landNeighbors>=3)){$c=$c+5;} // raises coasts a bit
					
					
					if($currentElevation>$grasslandMaxThresh){$mtnvar=rand($mtnLowVariance,$mtnHighVariance);$currentElevation=$currentElevation+$mtnvar;} // mixes up mountain height
					
		
				$board[$a]["elevation"]=$currentElevation;
				//print gettype($c);
				
				
				// MAKES ICE CAPS : SIMPLE VERSION
					$currentLat=intval($y);  // get latitude
					
						$equatorLine=(0.5)*$mapHeight;
						if ($currentLat>$equatorLine){ // remembering that 0 is at top
							$polarDistance=$mapHeight-$currentLat;
						}else $polarDistance = $currentLat;
						
						$snowProb=(-(pow($polarDistance,2.2)/$polarBoundary)+100);  // decreases exponentially, if -(X^2/A)+100>0.
						$snowCo=rand(1, $polarBoundary); // 
						$snowCoB=(rand(3,6)/10); // decreasing lower bound improves chances of regular terrain in higher level
						if ($snowProb>($snowCoB*($mapHeight/$snowCo*($polarDistance/1.5)))){$d.=" ice";$isPolar=true;}
								
				
				// MAKES DESERTS
				
				$d="";
				
				if (($currentElevation>=0)&&($currentElevation<$grasslandMaxThresh)&&($deserts=="on")&&($isPolar==false)){ // if limited water, prepare to make desert
						
						if($waterNeighbors2<=0){$isDesert=true;
						$waterNeighbors2++;
						}
						$desertTemp=rand(0, $currentElevation);
						if ($desertTemp>(1.5*$tempAdjust*$waterNeighbors2)){$isDesert=true;}
						

					}
				
				
				// CLASS DEFINITIONS - "GOODY" DEFINITIONS SHOULD BE INTEGRATED AFTERWARDS
				if ($currentElevation<$oceanMaxThresh){$d.=" ocean";}
				if (($currentElevation>=$oceanMaxThresh)&&($currentElevation<=$shallowMaxThresh)){$d.=" shallow";}
				if (($currentElevation>0)&&($currentElevation<$grasslandMaxThresh)){
						if ($isPlains==true){$d.=" plains";
						}else{$d.=" grassland";}
				}
				if ($isDesert){$d="desert";	}  // OVERRIDES GRASSLANDS AND PLAINS FOR DESERT
					
				if(($currentElevation>=$grasslandMaxThresh)&&($currentElevation<$hillsMaxThresh)){$d.=" hills";}
				if($currentElevation>=$hillsMaxThresh){$d.=" mountain";}
				
				
				if ($isPolar){$d="ice";} // OVERRIDES ANYTHING FOR POLAR REGION
				
				
				if (isset($_POST['blankMap'])){$d=" ocean";} // This is hacked all day long. Along with the beginning of the function. Oh well.
				
				$board[$a]["class"]=$d;		
				
				
				
				
				
				
				
				
				
				
				$count++;
				
		
		// }
		
		
			} // End if is last time
		unset($avgs);
		unset($neighborVals);
		} // End each board key
	} // End iterations
 // end blank map conditional
/* DEFINE BORDER CLASSES */

$terrainColors=array();
	
	$terrainColors[" ocean"]=$oceanColor;
	$terrainColors[" shallow"]=$shallowColor;
	$terrainColors[" grassland"]=$grasslandColor;
	$terrainColors[" plains"]=$plainsColor;
	$terrainColors["desert"]=$desertColor;
	$terrainColors[" hills"]=$hillsColor;
	$terrainColors[" mountain"]=$mountainColor;
	$terrainColors["ice"]=$iceColor;


foreach (array_keys($board) as $a){ 
		
			$xy=explode(",", $a);
			$x=$xy[0];
			$y=$xy[1];

	
				$left=$board[($x-1).",".($y)]["class"];
				$top=$board[($x).",".($y-1)]["class"];
				$bottom=$board[($x).",".($y+1)]["class"];
				$right=$board[($x+1).",".($y)]["class"];
				
				// THIS CAN BE MODIFIED NEXT INTO TRANSITIONAL TERRAIN GRAPHICS AND MASKS, PERHAPS EVEN SVG DRAWING FOR FULL CUSTOMIZABILITY
				
				
				$board[$a]["borderString"]="border-top-color:".$terrainColors[$top].";border-right-color:".$terrainColors[$right].";border-bottom-color:".$terrainColors[$bottom].";border-left-color:".$terrainColors[$left].";";
				
				
	unset($neighborColors);			
	}	

	unset($terrainColors);
	

?>

<script>
	$('#Map_window #board').width('<?php echo $totalWidth?>px');
	$('#Map_window #board .tile, #Map_window #board .tileIndex').height('<?php echo $cellHeight; ?>px');
	$('#Map_window #board_row').height('<?php echo $cellHeight-2; ?>px');
	$('#Map_window #board .tile, #Map_window #board .tileIndex').width('<?php echo ($cellWidth-2); ?>px');
	$('.ocean').css('background-color', '<?php echo $oceanColor; ?>');
	$('.shallow').css('background-color', '<?php echo $shallowColor; ?>');
	$('.lake').css('background-color', '<?php echo $shallowColor; ?>');
	$('.grassland').css('background-color', '<?php echo $grasslandColor; ?>');
	$('.plains').css('background-color', '<?php echo $plainsColor; ?>');
	$('.desert').css('background-color', '<?php echo $desertColor; ?>');
	$('.hills').css('background-color', '<?php echo $hillsColor; ?>');
	$('.mountain').css('background-color', '<?php echo $mountainColor; ?>');
	$('.ice').css('background-color', '<?php echo $iceColor; ?>');
</script>
	

<div id="Map_window">	
	<?php
	

/* DISPLAY OUTPUT */
set_time_limit(5);
 

 echo "<div id='board'>";
echo "<div class='board_row board_row_first '><div class='tileIndex'> </div>";
	for($x=1;$x<=$mapWidth;$x++){
	echo ("<div class='tileIndex'>".$x."</div>");
	} 
echo "<div class='tileIndex'> </div></div><div class='board_row'><div class='tileIndex'>1</div>";
$rowNum=1;
$colNum=1;




foreach($board as $a){



		$c=$a["elevation"];
		
		// print $c. " "; // print gettype($c);
		
		
	
			if ($colNum==($mapWidth+1)){echo "<div class='tileIndex'>".$rowNum."</div>";$rowNum++;echo"</div><div class='board_row'><div class='tileIndex' >$rowNum</div>";$colNum=1;}
		
				$coords=($rowNum).", ".$colNum;
				$thisY=$rowNum;
				$thisX=$colNum;
				
			/* $op=rand((120-($varyShadesBy/1.8)), 120);  // just randomizes the opacity for style
			$op=$op-(abs((.5*$mapHeight)-$rowNum)); */
			$op=rand(80,100);
			$op=$op/100;
			if ($op>1){$op=1;}
			
			$elevation=$a["elevation"]*100;
			
			/* use this later to define borders

			$neighborVals=array(     // just selects one square in every direction
				$board[($x-1).",".($y)]["class"],  // left
				$board[($x).",".($y+1)]["class"],  // top
				$board[($x).",".($y-1)]["class"],  // bottom
				$board[($x+1).",".($y)]["class"],  // right
			);
			
				$neighborVals=array_filter($neighborVals);
			*/
			
			$goody="";
			$goodyText="";
			// FIRST GOODY FUNCTION - ADDS FORESTS 
			$treeLine=$polarBoundary*1000;
			$terrainType=$a["class"];
			
			if (($elevation>0)&&($elevation<($hillsMaxThresh*1000))&&($terrainType!="ice")&&($terrainType!="shallow")&&($terrainType!="desert")&&(empty($goody))){
				
				if ($a["class"]=="plains"){$forestsThresh-=($tempAdjust/2);} // This is Nifty
				$isForest=rand(0, 100);
				
				if ($isForest > (100-$forestsThresh)){
				
				$goody.="<div class='forest t1'> </div>";
	            $goodyText.=", forest";
				}
			 }
			
			// SECOND GOODY FUNCTION - ADDS OASES
			$isOasis=false;
			if (($a["class"]=="desert")&&(empty($goody))){
			$oasisTemp=rand(0,100);
			if ($oasisTemp<($lakesBalance/2)){
				$isOasis=true;
				$goody.="<div class='oasis t1'> </div>";
	            $goodyText.=", oasis";
				
				}
			}
			
			// THIRD GOODY FUNCTION - ADD LAKES
			
			$isLake=false;
			if(($elevation>1000)&&($elevation<$hillsMaxThresh*100)/*&&($waterNeighbors<=1)*/&&($terrainType!="ice")&&($terrainType!="desert")&&(empty($goody))){
				$lake=rand(0,100);
				if ($lake>(100-$lakesBalance)){
							$isLake=true;
							$goody.="<div class='lake t1'></div>";
							$goodyText.=", lake";
							} // adds random little lake 1/10 of time
				}
		
			// FOURTH GOODY FUNCTION - ADD VOLCANOES
			
			$isVolcano=false;
			if(($a["class"]=="mountain")&&(empty($goody))){
				$eruption=rand(0,100);
				if ($eruption>85){
							$isVolcano=true;
							$goody.="<div class='volcano t1'></div>";
							$goodyText.=", volcano";
							} 
			}
			
			
			// FIFTH GOODY FUNCTION - MAKE ICECAPS
			$isSnowcap=false;
			if(($elevation>6000)&&($isVolcano==false)&&(empty($goody))){
				$snowCap=rand(0,100);
				if ($snowCap>((100-$a["elevation"])+(($tempAdjust/6)))){
						
							$goody.="<div class='snowCap t1'></div>";
							$goodyText.=", snowCap";
							} 
			}
			
			// FIFTH GOODY FUNCTION - MAKE ISLANDS
			
			$isIsland=false;
			if(($elevation<-1000)&&($terrainType!="ice")&&(empty($goody))){
				$island=rand(0,100);
				if ($island>(-1*$elevation)/25){
						
							$goody.="<div class=' island t1'></div>";
							$goodyText.=", island";
							} 
			}
			
			
			if (!$goody){$goody="&nbsp;";} // makes sure cells aren't empty for sizing;
			
			
			///*title='".$coords." - ".$a["class"]." ".$goodyText." - ".$elevation." Feet'*/
			
			echo "<div class='tile ".$a["class"]."' x=".$thisX." y=".$thisY."  style='opacity:".$op.";".$a["borderString"]."'>".$goody." </div>\n";
			$colNum++;
			$goody="";
			// <div class='unitCount'>".$thisX.",".$thisY."</div>
}		
echo "<div class='tileIndex'>".$rowNum."</div></div>\n";
echo "<div class='board_row board_row_first '><div class='tileIndex'> </div>";
	for($x=1;$x<=$mapWidth;$x++){
	echo ("<div class='tileIndex'>".$x."</div>");
	} 
echo "<div class='tileIndex'> </div></div>";
echo "</div>";



unset($board);




?>
</div>

