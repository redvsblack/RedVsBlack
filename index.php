		<?php error_reporting(E_ERROR | E_PARSE); ?>
	<?php if(!(isset($_SESSION["players"]))){session_start();} ?>

    <?php include('create.player.php'); ?>	
	
	
	
<!DOCTYPE html>
<head>
	<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
	<title>Red Vs. Black - a conceptual Board Game framework based on HTML5, CSS3, and the jQuery UI, as well as PhP and MySQL.</title>

	<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
	<style type="text/css">
	body{background-color:black;text-align:center;}
	a{cursor:pointer;}
	</style>
		<script type="text/javascript">
		
		var windowWidth=$(window).width;
			var windowHeight=$(window).height;
		
			
		
		$(document).ready(function(){
		
		$('.introImage').css('maxWidth', function(){windowWidth+"px";});
		$('.introImage').css('maxHeight',  function(){windowHeight+"px";});
		
		
		});
			
		
			
			
			
		</script>
	
		<!-- <script src="/socket.io/socket.io.js"></script> //-->
		
		<script src="http://code.jquery.com/jquery-1.8.2.js"></script>
		<script src="scripts/jquery-ui-1.9.1.custom/js/jquery-ui-1.9.1.custom.js"></script>
		<script src="scripts/jquery.contextmenu.js"></script>
	

	<!--	<script src="scripts/nodeScript.js"></script> //-->
	
	
		<!--<script type="text/javascript" src="scripts/jquery.overscroll.js"></script> //-->
		<!--<script type="text/javascript" src="scripts/mapgen.js"></script>
		//-->
		<link rel="stylesheet" media="screen" type="text/css" href="colorpicker/css/colorpicker.css" />
		<script type="text/javascript" src="colorpicker/js/colorpicker.js"></script>
		
		<link type="text/css" rel="stylesheet" href="css/ui-darkness/jquery-ui-1.9.1.custom.css" />
		

		<link type="text/css" rel="stylesheet" href="css/game.css" /> <!-- OVERWRITES MUCH OF THE ABOVE FILE //-->
	<link type="text/css" rel="stylesheet" href="assets/css/mpStyles.css" />
		<?php include('gameMapGen.styles.php'); ?>
	
	
	<script>
	
	
	
	
	</script>
	
		<script type="text/javascript">  // MAP GEN SCRIPTS


/* A lot of this code is sub-optimal as it was ported from my separate Map Generator when I started anew... I'm in the process of integrating it into the main code pattern,
   and eventually the code will be re-segmented back out into functions.  But for now ... */
var mapData="";

function resetMapGen(){   // Sets up Map Generator for use from code.. left general for dynamic input from PhP down the line?

	  $( ".mapSlider" ).each(function() {
				// read initial values from markup and remove that
				
				var value=$(this).attr('value');
				var value = parseInt(value);
				var minimum = parseInt($(this).attr('min'));
				var maximum = parseInt($(this).attr('max'));
				// window.alert($.type(minimum));
				$(this).attr('realvalue', function(){$(this).html()});
				
				$( this ).empty().slider({
					animate: "fast",
					value: value,
					range: "min",
					animate: true,
					orientation: "horizontal",
					min: minimum,
					max: maximum,
					slide: function( event, ui ) {
						
						$(this).siblings(".value").text(ui.value);
					},
					create: function(event, ui){
						$(this).siblings(".value").text(value);
						$(this).siblings("input").val(value);
					},
					stop: function( event, ui) {
						$(this).siblings("input").val(ui.value);
						}
					
				});
				
			});
	}
		
		function resetDefaults(){   // Resets changes you may have made
			$( ".mapSlider" ).each(function() {
				var value=$(this).attr('realvalue');
				
				$(this).slider({value:value});
				$(this).siblings(".value").text(value);
				$(this).siblings("input").val(value);
				});
			}
		
		
		
		function setUpNav(){  // this is currently failing and needs to be fixed soon
			
			
			var tempHTML=$("#Navigator #Map_window").html();
			$("#Navigator").empty();
			$("#Navigator").html(tempHTML);
			
			 $("#Navigator .tile").each(function(){$(this).removeAttr('style');});
			 
			 $("#Navigator").height('<?php echo(5*intval($mapHeight)); ?>px');
			 $("#Navigator #board").width('<?php echo (5*intval($mapWidth+2));?>');
			 $("#Navigator .tile").height('5px');
		    $("#Navigator .board_row").height('5px');
			$("#Navigator").prepend("<div id='navSquare'></div>");
			$("#Navigator .tile").each(function(){$(this).removeAttr('title');});
			
			$("#navSquare").draggable({
				containment: "#Navigator",
				drag: function( event, ui) {
						$("#Map_window").scrollTop((windowHeight/<?php echo ($mapHeight*5);?>)*(ui.position.top)); // This needs to be fixed with respect to window ratio
						$("#Map_window").scrollLeft((windowWidth/250)*(ui.position.left));
						}
				});
			}
		
		// makes map either on load or on edits - for now duplicated with main doc.
		

		function generateMap(){   // This actually calls the map generation
			
			$( "#Main_window, #Navigator").empty();
			$( "#Main_window").html("<h2 style='color:white;'>Loading: This may take up to 10 or 15 seconds depending on size.</h1>");
			var tempMap=$("#mapGenerator").serialize();
			
		    
			
			
			
			// $.post("gameMapGen.styles.php", tempMap, function(data) { $("#Main_window").prepend( data );}); 
			 $.post("redvblack.php", tempMap, function(data) { $("#Main_window, #Navigator").empty().append( data );}); 
			
			
			$(".introImage").hide();
			resizeMap();
			// resetMapGen();
			setUpNav();
			setupBoard();
			updateAllRoads();
			
			
			
			
		}
		
		// I think this is functionally redundant but I'll keep it around as a reminder
		function toggleToolBar(){
			$(this).slideToggle('slow', function(){ });
	
		}		
	 
	
	 
	// DOCUMENT RELATED FUNCTIONS TO BE INTEGRATED INTO MAIN FUNCTION PATTERN
	
</script>	
	
	
	
	
	
	<script> /* SIDEBAR FUNCTIONS */
			
		var oldTile;
		var currentUnit="";
		var currentSettlement=""; 
		var currentImprovement="";
		var currentItem="";
		var currentTerrain;
		var currentTerrainLevel;
		var terrainID;

	 
		function clearSelects(){
			$('.ui-selected').removeClass('ui-selected');
			$('#mapCursor').removeClass();
			$('#mapCursor').empty();
			$('#Map_window').css('cursor', 'pointer');
			
		}	
			
		/* LOADS CODE IN FOR ITEM CREATION */
		 
		function setupSelects(){
		$("#mapCursor").removeClass();
		$("#mapCursor").html($(".ui-selected").children('.sidebarItem').html());
		var thisColor=$("input[name=playerColor]:radio").attr('value');
		$("#mapCursor").addClass(thisColor);
		}
		 
		function unitSelect(event, ui){
			setupSelects();
			currentItem="Unit";
			currentUnit=$(".ui-selected").attr('code');	
			
			}
			
		function settlementSelect(event, ui){
			setupSelects();
			currentItem="Settlement";
			currentSettlement=$(".ui-selected").attr('code');	
			
			
			}
			
		function roadSelect(event,ui){
		currentImprovement="Road";
		$("#mapCursor").html($(".ui-selected").children('.sidebarItem').html());
		
		}	
			
			
		function improvementSelect(event, ui){
			setupSelects();
			currentItem="Improvement";
			currentImprovement=$(".ui-selected").attr('code');
			if (currentImprovement=="1"){$("#mapCursor").removeClass();}
			
		}
		
		function terrainSelect(event, ui){
			$("#mapCursor").removeClass();
			currentItem="Terrain";
			currentTerrain=$(".ui-selected").attr('id');
			currentTerrainLevel=$(".ui-selected").attr('level');
			$("#mapCursor").html('&nbsp;');
			 terrainID=$(".ui-selected").attr('id');
			
			$("#mapCursor").addClass(terrainID);

		}	
		
		
		function changeTerrain(terrainType, thisTile, terrainLevel){
			
			// this function will require a lot of diversification and access through the database to do properly.
			
			
		<?php 
			$terrainMain="";
			$terrainAddon="";
			foreach($_SESSION['Terrain'] as $terrainType){
				 $type=intval($terrainType['Modifier']);
				 
					if ($type==0){
						$terrainMain.=current($terrainType)." ";
					}
					if ($type==1){
						$terrainAddon.=$terrainType["Terrain_Code"]." ";
					}
				
			}
			
		?>
		

		if (terrainLevel==0){
		 $(thisTile).removeClass('<?php echo $terrainMain;?>').addClass(terrainType);
		 $(thisTile).css('background-color', '');// Hack #1 until next stage where loads are independent
		//  $(thisTile).css('border-color', ''); // Hack #2 until terrain editing is revamped
		// $(thisTile).css('border-color', 'transparent'); // Hack #3.. this will be a pain in the butt to get working properly since neighboring tiles will still have borders.
		}
		
		if (terrainLevel==1){
		$(thisTile).children('.t1').remove();
		$(thisTile).append("<div class='"+terrainType+"'></div>");
		
		}
		
		var thisX=parseInt($(thisTile).attr('x'));
		var thisY=parseInt($(thisTile).attr('y'));
		
		
		// Change This tile's border will not work until I either define explicitly background colors, or work something else out.
		// Ultimately it feels like, without calling PhP, this will require an array, and selecting an appropriate terrain type to match with. 
		
		// $(thisTile).css('border-left-color', function(){$('#Map_window .tile[x='+(thisX-1)+'][y='+thisY+']').css('background-color');});	// Left
		// $(thisTile).css('border-right-color', function(){$('#Map_window .tile[x='+(thisX+1)+'][y='+thisY+']').css('background-color');});	// Right
		// $(thisTile).css('border-top-color', function(){$('#Map_window .tile[x='+thisX+'][y='+(thisY-1)+']').css('background-color');});		// Top
		// $(thisTile).css('border-bottom-color', function(){$('#Map_window .tile[x='+thisX+'][y='+(thisY+1)+']').css('background-color');});	// Bottom
		
		
		// Change Other Tiles' borders
		$('#Map_window .tile[x='+(thisX-1)+'][y='+thisY+']').css('border-right', function(){$(thisTile).css('background-color');});		// Left
		$('#Map_window .tile[x='+(thisX+1)+'][y='+thisY+']').css('border-left', function(){$(thisTile).css('background-color');});		// Right
		$('#Map_window .tile[x='+thisX+'][y='+(thisY-1)+']').css('border-bottom', function(){$(thisTile).css('background-color');});		// Top
		$('#Map_window .tile[x='+thisX+'][y='+(thisY+1)+']').css('border-top', function(){$(thisTile).css('background-color');});		// Bottom
		

		}
		
		
		
      
		
		
		function makeCursor(x){  // DOESN'T WORK
			document.write("<style type='text/css'>#Map_window{cursor:url("+x+"),'pointer',auto;}</style>");
			 $("#Map_window").css("cursor", "url("+x+")"); 	
			}
			
		function clearUnits(){
			$(".unit").each(function(){$(this).remove();});
			$(".tile").each(function(){updateUnitTotals($(this));}); 
		}
		
		function clearSettlements(){
			$(".settlement").each(function(){$(this).remove();});
			 
		}
		
		function clearImprovements(){
			$(".improvement").each(function(){$(this).remove();});
			 
		}
		
			
		
		function newMap(){
			
		    
			
			$("#Main_window, #Navigator").empty().load('redvblack.php');
			
		//	$.post("redvblack.php", tempMap, function(data) { $("#Map_window").empty().append( data );tempData=data;}); 
			
			resizeMap();
			
			setUpNav();
			// resetMapGen();
			$(".introImage").hide();
		}
		
		function saveMap(){
			if(typeof(Storage)!=="undefined")
			  {
				var thisData=$("#Map_window").html();
				 
				localStorage.mapState=thisData;
				
			  }
			else
			  {
			 window.alert("Bah!");
			  }
			
		}

		
		function loadMap(){    
			
			
			 $.post("redvblack.php", {savedMap:localStorage.mapState}, function(data) { $("#Map_window, #Navigator").empty().append( data );}); 
			$("#Map_window").empty();
			
			$(".introImage").hide();
			
		}
	
		function resizeMap(){
				var sOff=$("#Sidebar").offset();
		
			tempWidth=$("#board").width();
			tempHeight=$("#board").height();
			$("#board").width("0px");
			$("#board").height("0px");
			
			var windowHeight = $(window).height();
			var windowWidth = $(window).width();
			$("#Map_window").height(windowHeight);
			$("#Map_window").width(windowWidth);
			
			$("#board").width(tempWidth);
			$("#board").height(tempHeight);
				$("#Sidebar").offset(sOff);
			
		}
		
		$(window).resize(function() {
			resizeMap();
		});
		
		
		
		
	
    </script>
	
	<script>
	$(document).ready(
		function(){
		
			/* LOAD VISUAL LAYOUT ESP SIDEBAR */	
		 $("#editorOptions" ).tabs();
		$("#updates").tabs();		
		$("#mapGenerator" ).tabs();
		$("#Sidebar_Content" ).accordion({active:false, collapsible:true, heightStyle: "content" }); 
		$("#Sidebar").draggable({handle: ".dragHandle"});
		// $("#Main_window").draggable({handle:".dragHandle"});
		$("#Map_window").resizable();
		$( document ).tooltip({show: {delay:250}, tooltipClass: "tooltip" });  // POTENTIAL JAMMER
		
		
		
		
		resetMapGen();	
		
		/* SETS UP INFO HIDING ON UNIT SELECTOR */
			
			$(".showInfo").click(function(){$(this).parents().siblings('.stats').show();});
			$(".stats").click(function(){$(this).hide();});
		
			$("#editorOptions .selectorBox").selectable({filter: ".itemSummary", start:clearSelects, unselected: function(event,ui){$("#mapCursor").empty();}});
			
			$("#Units_selector").selectable( "option", "selected", unitSelect);
			$("#Settlement_selector").selectable( "option", "selected", settlementSelect);
			$("#Improvement_selector").selectable( "option", "selected", improvementSelect);
			$("#Terrain_selector").selectable( "option", "selected", terrainSelect);
			
			
			/*
			$("#Units_selector").selectable({create:clearSelects,selected:unitSelect});
			$("#Cities_selector").selectable({create:clearSelects,selected:settlementSelect});
			$("#Improvement_selector").selectable({create:clearSelects,selected:improvementSelect});
			$("#Terrain_selector").selectable({create:clearSelects,selected:terrainSelect});
			*/
				
		/* SETS RED AND BLACK DEPENDING ON PLAYER */
			$("input[name=playerColor]:radio").change(
				function(){
					var thisColor;
					if ($("#redButton").attr("checked")){
						tabsColor="#933";
						itemSumColor="#933";
					}
					else{ 
						tabsColor="#666";
						itemSumColor="#666";
					}
					// $(".ui-tabs .ui-tabs-nav li").css('background-color', tabsColor);
					
					var state = true;
					if(state){
					$(".itemSummary").animate({backgroundColor: itemSumColor}, 800);
					}
					state=!state;
					
					var thisColor=$(this).attr('value');
					$("#mapCursor").removeClass();
					$("#mapCursor").addClass(thisColor);
					
				}
			);	
		
		 $('.terrainPicker').each(
			function(){
				var thisBG=$(this).attr('value');
				$(this).css('background-color', thisBG);
			});
		
		  $('.terrainPicker').ColorPicker({
				
				onSubmit: function(hsb, hex, rgb, el) {
				$(el).val("#"+hex);
				$(el).attr("value", "#"+hex);
				$(el).css('background-color', "#"+hex);
				$(el).ColorPickerHide();
				
			},
				onBeforeShow: function () {
				$(this).ColorPickerSetColor(this.value);
			}
		})
		.bind('keyup', function(){
			$(this).ColorPickerSetColor(this.value);
			
		});
		
		
		
		});
	
	</script>
	<script type="text/javascript">

	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-8287142-17']);
	  _gaq.push(['_trackPageview']);

	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();

	</script>
	
	</head>

	
	

<body>
<div id="cursors" style="position:fixed;"></div>
<div id="Alert_Window"></div>
<div id="mapCursor"></div>
	<div id="container">
		
		<div id="Main_window">
			<a><img src="images/conceptart.jpg" class="introImage" alt="Red Vs. Black" style="margin:12px auto;" title="And in case you're wondering, the background image is the canopy
of a 6 hour trail in New Zealand I once walked.  I definitely have the rights to that picture :)"			/></a>
			<div id="Map_window">
			</div>
		</div>
		<div id="Sidebar">
				
			<div class="dragHandle"><hr /></div>
			<div class="buttonBar" title="Currently only supports one save state... use the Map Generator to customize your own map">
				<button type=""  id="startButton" onclick="newMap();" >Quick Start</button>
				<button type=""  id="saveButton" onclick="saveMap();">Save</button>
				<button type=""  id="loadButton" onclick="loadMap();">Load</button>
			</div>
					
			<div id="Sidebar_Content">
				<div class="mapDragBar">
					<p>Navigator</p>
				</div>
				<div id="Navigator">
					Start a New Map via 'Quick Start' or 'Generate' to enable the viewport.		
					
				</div>
				<?php include('show.mapgenerator.php'); ?>
						
				<div class="mapDragBar" title="<p>Select an item and click to place it - Right click to delete.  Water without islands and volcanoes are off-limits for land units etc... BTW this interface is temporary, I promise. </p>">
					<p>Map Editor</p>
				</div>
						
				<div id="Selector_window" class="content">
					<div class="buttonBar">
						<form id="playerColor">
							<input type="radio" id="redButton" name="playerColor" value="Red" title="#c33;" checked>Red |
							<input type="radio" id="blackButton" name="playerColor" value="Black" title="#333;">Black
						</form>
							
					</div>	
					<div id="editorOptions">
						<ul id="Selectors" class="mapNavbar">
							<li><h4><a href="#Units_selector" title="Units">Unit</a></h4></li>
							<li><h4><a href="#Settlement_selector" title="Settlements">City</a></h4></li>
							<li><h4><a href="#Improvement_selector" title="Roads, workshops, etc">Improve</a></h4></li>
							<li><h4><a href="#Terrain_selector" title="Terrain Types and Subtypes.. VERY Beta and to be developed in sequence with the next few phases">Terrain</a></h4></li>
						</ul>
						<div id="Units_selector" class="selectorBox">
							<?php include('show.units.php'); ?>
						</div>
						<div id="Settlement_selector" class="selectorBox">
							<?php include('show.settlements.php'); ?>
						</div>
						<div id="Improvement_selector" class="selectorBox">
							<?php include('show.improvements.php'); ?>
						</div>
						<div id="Terrain_selector" class="selectorBox">
							<?php include('show.terrain.php'); ?>
							Terrain Options Coming soon and to include any or all of:
							<ul>
								<li>Brush Sizes</li>
								<li>Paint Mode, Raise / Lower Mode</li>
								<li>Raise / Lower Sea Level</li>
								<li>Saving / Uploading Map Files</li>
								<li>Custom textures and images</li>
								<li>Manual elevation threshhold editor</li>
								<li>Add Forests, Volcanoes, and other goodies</li>
								<li>Draggable Terrain</li>
							</ul>
						</div>		
					</div>
				</div>
				<div class="mapDragBar" title="<p>Until I slow down enough to get a proper development blog going...</p>">
					<p>Updates</p>
				</div>
				<div id="updates">
					<?php include('updates.php'); ?>
				</div>
					
				<div class="dragHandle"><hr /></div>
			</div>	
		</div>	
	</div>
	<div id="infoBar">
	
		<div id="info1">
		</div>
		<div id="info2">
		</div>
	</div>
</body>
</html>		