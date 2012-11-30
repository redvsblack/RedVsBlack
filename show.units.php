
<div id="toolbar">
<button type="button"  onclick="clearUnits()">Clear Units</button>
</div>

<?php 

foreach ($_SESSION['allUnits'] as $unit){

?>
	<div class="itemSummary" code='<?php echo($unit[Unit_Code]);?>'>
		<div class="titleHolder"><h4><?php echo($unit[Unit_Name]); ?></h4></div>
	
		<div class='sidebarItem'>
			<img class='buildImage' id='unit <?php echo($unit[Unit_Code]);?>'  src='pieces/<?php echo(trim($unit[Graphic_URL]));?>' alt='No Image Yet' />
			
		</div>
		
	
		
	</div>
	

<?php

}


?>
<div style="clear:both;"></div>
