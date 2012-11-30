
<?php 


foreach ($_SESSION['Terrain'] as $terrain){

?>
	<div class="itemSummary"  id='<?php echo($terrain["Terrain_Code"]);?>' level=<?php echo($terrain["Modifier"]);?> style='background-color:#<?php echo $terrain["bgColor"];?>' >
		<h4><?php echo($terrain["Terrain_Name"]); ?></h4>
	
		
		<div class='sidebarItem' style='background-color:#<?php echo $terrain["bgColor"];?> '>
			<img class='buildImage' src='<?php echo(trim($terrain["Graphic_URL"]));?>'  />
		</div>
	
		<div style="clear:both;height:0px;"></div>
	</div>
	
<?php

}


?>
<div style="clear:both;"></div>
<script>


</script>