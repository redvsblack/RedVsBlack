<div id="toolbar">





<button type="button"  onclick="clearSettlements()">Clear Settlements</button>

</div>

<?php 

$settlementCount=0;

foreach ($_SESSION['allSettlements'] as $settlement){

?>
	<div class="itemSummary" code='<?php echo($settlement[Settlement_Code]); ?>'>
		<div class="titleHolder"><h4><?php echo($settlement[Settlement_Name]); ?></h4></div>
	

		<div class='sidebarItem'>
			<img class='buildImage' id='settlement <?php echo($settlement[Settlement_Code]);?>'  src='<?php echo(trim($settlement[Graphic_URL]));?>' alt='No Image Yet' />
			
		</div>
	
		<div style="clear:both;height:0px;"></div>
	</div>
	
<?php

}


?>
<div style="clear:both;"></div>
<script>


</script>