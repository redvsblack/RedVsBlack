<div id="toolbar">





<button type="button"  onclick="clearImprovements()">Clear Improvements</button>

</div>

<?php 


$improvementCount=0;

foreach ($_SESSION['allImprovements'] as $improvement){

?>
	<div class="itemSummary" code='<?php echo($improvement[Improvement_Code]); ?>'>
		<div class="titleHolder"><h4><?php echo($improvement[Improvement_Name]); ?></h4></div>
	

		<div class='sidebarItem'>
			<img class='buildImage' id='improvement <?php echo($improvement[Improvement_Code]);?>' src='<?php echo(trim($improvement[Graphic_URL]));?>' alt='No Image Yet' />
			
		</div>
	
		<div style="clear:both;height:0px;"></div>
	</div>
	
<?php

}


?>
<div style="clear:both;"></div>
<script>


</script>