
		var docHeight=$("window").height();
		if (docHeight<800){
			$("#Map_window").height('500px');
			$("#Map_window").width('700px');
		}
	
		
		
		
		<meta charset="UTF-8">
	
	
	
	 /* UNIT CREATION AND EDITING FUNCTIONS */
		var oldTile;
		var currentUnit="";
		var currentSettlement=""; 
		var currentImprovement="";
		var currentItem="";
	 
	 
		function clearSelects(){
			$('.ui-selected').removeClass('ui-selected');
		}
	 
	    var unitMenu = [ 
			{'Delete':function(menuItem,menu) {thisSquare=$(this).parent();$(this).remove();updateUnitTotals(thisSquare);} },
			$.contextMenu.separator, 
			{'Option 2':function(menuItem,menu) { alert("You clicked Option 2!"); } } 
		]; 
		
		var settlementMenu = [ 
			{'Delete':function(menuItem,menu) {$(this).remove();updateRoads($(this).parent());} },
			$.contextMenu.separator, 
			{'Option 2':function(menuItem,menu) { alert("You clicked Option 2!"); } } 
		]; 
		
		var improvementMenu = [ 
			{'Delete':function(menuItem,menu) {$(this).remove();} },
			$.contextMenu.separator, 
			{'Option 2':function(menuItem,menu) { alert("You clicked Option 2!"); } } 
		]; 
		
		var terrainMenu = [ 
			{'Change Terrain Type - not yet active':function(menuItem,menu) {$(this).remove();} },
			$.contextMenu.separator, 
			{'Option 2':function(menuItem,menu) { alert("You clicked Option 2!"); } } 
		]; 
		
		
	 
		function updateUnitTotals(x){
			var units=$(x).children('.unit').length;
			
			var unitCount=$(x).children('.unitCount');
			
			if (units>1){
				if (unitCount.length==0){$(x).append("<div class='unitCount'>"+units+"</div>");
				}else{unitCount.empty().append(units);}
			}
			if (units<=1){unitCount.remove();}
		}
	 
		
		
		
		

		
	function pieceDrop(event, ui){    /* THIS IS PARTIALLY DEPRECATED UNLESS FOR SOME REASON I DECIDE TO KEEP THE CLICK AND DRAG OPTIONS FOR CREATION... DRAGGABLE IS BECOMING CUMBERSOME*/
		
			/* if (ui.draggable.attr('class')=='unitImage'){
				buildUnit(ui.draggable.attr('id'), $(this).attr('x'), $(this).attr('y'));
				ui.helper.position( { of: $(this), my: 'center', at: 'center' } );
			
			}else if (ui.draggable.attr('class')=='improvementImage'){
				buildSettlement(ui.draggable.attr('id'), $(this).attr('x'), $(this).attr('y'));
				ui.helper.position( { of: $(this), my: 'center', at: 'center' } );
			
			}else { */
				$(this).append($(ui.draggable));
				ui.draggable.position( { of: $(this), my: 'center', at: 'center' } ); 
				updateUnitTotals(oldTile);
			// }
				
			var snd = new Audio("sounds/checkersdrop.wav"); // buffers automatically when created
			snd.play();
					
			updateUnitTotals($(this));
			 // TEMP HACK UNTIL CITIES DROPPABLE
		}


		

 /* SETS UP BUILDING OPTIONS */

/* LOADS CODE IN FOR ITEM CREATION */
		 
		function unitSelect(event, ui){
			currentItem="Unit";
			currentUnit=$(".ui-selected").attr('code');	
			}
			
		function settlementSelect(event, ui){
			currentItem="Settlement";
			currentSettlement=$(".ui-selected").attr('code');	
			}
			
		function improvementSelect(event, ui){
			currentItem="Improvement";
			currentImprovement=$(".ui-selected").attr('code');

		}
		
		function terrainSelect(event, ui){
			currentItem="Terrain";
			currentTerrain=$(".ui-selected").attr('id');
			currentTerrainLevel=$(".ui-selected").attr('level');

		}
		
	
/* BUILD ITEMS - THESE CAN PROBABLY BE GENERALIZED ALTHOUGH MAYBE NOT OPTIMAL */
		function buildUnit(code, thisTile){
		
		
		var thisX=parseInt(thisTile.attr('x'));
		var thisY=parseInt(thisTile.attr('y'));
		
			var pieceColor=$('input[name=playerColor]:checked', '#playerColor').val()
			$.post(
				"create.unit.php", 
				{unitCode:code, pieceColor:pieceColor, thisX:thisX, thisY:thisY}, 
				function(data){	
					var newTile=$("#Map_window .tile[x="+thisX+"][y="+thisY+"]");
					newTile.append(data);
					updateUnitTotals(newTile);
					makeUnitsDraggable();
					$('.ui-selected').removeClass('ui-selected');
				}
			 );
					
			// currentUnit="";
			// currentItem="";
				
		}
			
		function buildSettlement(tempCode, thisTile){
		
		thisTile.children('.improvement:not(.roads), .settlement').remove();
		
		var thisX=parseInt(thisTile.attr('x'));
		var thisY=parseInt(thisTile.attr('y'));
		
			var pieceColor=$('input[name=playerColor]:checked', '#playerColor').val()
			$.post(
				"create.settlement.php", 
				 {settlementCode:tempCode, pieceColor:pieceColor, thisX:thisX, thisY:thisY}, 
				 function(data){
					
					$("#Map_window .tile[x="+thisX+"][y="+thisY+"]").append(data);
					$("#Map_window .tile[x="+thisX+"][y="+thisY+"]").append("<img class='roads' src='improvements/"+thisLocation+".png' />"); // BUILDS A ROAD UNDERNEATH THE SETTLEMENT
					$(".settlement").contextMenu(settlementMenu,{delay:500, autoHide:false, theme:'vista', className:'settlementInfo'})
					$('.ui-selected').removeClass('ui-selected');
				}
			);
			// currentItem="";
			// currentSettlement="";
		}
	
	
		function buildImprovement(tempCode, thisTile){
			
			
			
			if (tempCode=="1"){buildRoad(thisTile, "pieces");}
			else{
				thisTile.children('.improvement:not(.roads), .settlement').remove();
			
				var pieceColor=$('input[name=playerColor]:checked', '#playerColor').val()
				
				var thisX=parseInt(thisTile.attr('x'));
				var thisY=parseInt(thisTile.attr('y'));
				
				$.post(
					"create.improvement.php", 
					 {improvementCode:tempCode, pieceColor:pieceColor, thisX:thisX, thisY:thisY}, 
					 function(data){
						
						$("#Map_window .tile[x="+thisX+"][y="+thisY+"]").append(data);
						
						updateRoads(thisTile);
						$(".improvement").contextMenu(improvementMenu,{delay:500, autoHide:false, theme:'vista', className:'improvementInfo'})
						$('.ui-selected').removeClass('ui-selected');
					}
				);
				// currentItem="";
				// currentImprovement="";
			}
		}
		
		function changeTerrain(terrainType, thisTile, terrainLevel){
			
			// this function will require a lot of diversification and access through the database to do properly.
			
			
		<?php 
			$terrainMain="";
			$terrainAddon="";
			foreach($_SESSION['Terrain'] as $terrainType){
				$type=intval($terrainType['Modifier']);
				
					if ($type==0){
						$terrainMain.=$terrainType["Terrain_Code"]." ";
					}
					if ($type==1){
						$terrainAddon.=$terrainType["Terrain_Code"]." ";
					}
				
			}
		?>
		
		//	window.alert(terrainType);
		if (terrainLevel=="0"){
		 $(thisTile).removeClass('<?php echo $terrainMain;?>').addClass(terrainType);
		 $(thisTile).css('background-color', '');// Hack #1 until next stage where loads are independent
		//  $(thisTile).css('border-color', ''); // Hack #2 until terrain editing is revamped
		// $(thisTile).css('border-color', 'transparent'); // Hack #3.. this will be a pain in the butt to get working properly since neighboring tiles will still have borders.
		}
		
		if (terrainLevel=="1"){
		$(thisTile).children('.t1').remove();
		$(thisTile).append("<div class='"+terrainType+"'></div>");
		
		}
		
		var thisX=parseInt($(thisTile).attr('x'));
		var thisY=parseInt($(thisTile).attr('y'));
		
		
		// Change This tile's border will not work until I either define explicitly background colors, or work something else out.
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

	
   /* TERRAIN MAPPING FUNCTIONS */	


	
/* THIS FUNCTION IS USABLE FOR MANY APPLICATIONS - IT DETECTS CHILDREN OF A PARTICULAR TYPE IN NEIGHBORING SQUARES.  IT IS USED CURRENTLY FOR ROADS BUT CAN ALSO BE USED FOR RIVERS, DETECTING ENEMY UNITS, ETC */	
	
	function getNeighborItems(thisTileB, tilesAway, item){
		
				var a;var b;
					var x=parseInt(thisTileB.attr('x'));
					var y=parseInt(thisTileB.attr('y'));
					var neighborItems=""+item+"-";
					var currentTile;
					var thisIndex=0;
							

					for (a=(y-tilesAway);a<=(y+tilesAway);a++){
						for (b=(x-tilesAway);b<=(x+tilesAway);b++){
							if (b!=a){
							var currentTile=$("#Map_window .tile[x="+b+"][y="+a+"]");
							
							if (currentTile.children('.'+item).length>0){
								neighborItems+=thisIndex;
								
								}
								thisIndex++;
							}
						}
					}
					neighborItems = neighborItems.replace('4','');
					totalItems=neighborItems;
					neighborItems=0;
					return(totalItems);
							
						
			}

	/* THESE SCRIPTS UPDATE ROADS TO CONNECT AND CAN OBVIOUSLY ALSO BE USED FOR RIVERS AND MAYBE IN SOME WAY WITH TERRAIN BORDERS */
		
		function updateRoads(tile){
			
			var tempX = parseInt(tile.attr('x'));
			var tempY = parseInt(tile.attr('y'));
			
			
			for(var countX=(tempX-1);countX<=(tempX+1);countX++){
				for(var countY=(tempY-1);countY<=(tempY+1);countY++){
					$("#Map_window .tile[x="+countX+"][y="+countY+"]").has('.roads').each(
						function(){
							// if (($(this).attr('x')!=x)&&($(this).attr('y')!=y)){
								var thisLocationB=getNeighborItems( $(this), 1, 'roads');
								// window.alert(thisLocationB);
								$(this).children(".roads").attr('src', 'improvements/'+thisLocationB+'.png');
							// }
						});
				}
			}
				
				
		}
		
		
		function updateAllRoads(){
				$("#Map_window .tile").has('.roads').each(
						function(){
							// if (($(this).attr('x')!=x)&&($(this).attr('y')!=y)){
								var thisLocationB=getNeighborItems( $(this), 1, 'roads');
								// window.alert(thisLocationB);
								$(this).children(".roads").attr('src', 'improvements/'+thisLocationB+'.png');
							// }
						});
		
		}
		
		function buildRoad(thisTile, thisScope){
			
			
			if (thisScope=="pieces"){
			
				if (thisTile.children(".roads").length==0){
					var thisLocation=getNeighborItems( thisTile, 1, 'roads');
					
					thisTile.append("<img class='improvement roads' src='improvements/"+thisLocation+".png' />");
					thisTile.children(".roads").contextMenu(improvementMenu,{delay:500, autoHide:false, theme:'vista', className:'improvementInfo'})
				}
				
				var thisX = thisTile.attr('x');
				var thisY = thisTile.attr('y');
				
				// updateRoads(thisTile);
				 updateAllRoads();
			}
		}
	
	
	
 /* FUNCTIONS RELATED TO BOARD SET-UP AND ALLOWING PIECES TO BE MOVABLE - SPECIFICALLY ON RELOAD */	
	

	 /* MAKES PIECES MOVABLE */
	
	
		function makeUnitsDraggable(){  /* SEPARATED FOR CONVENIENCE IN LATER DECLARATIONS - SHOULD BE MADE TO TARGET INDIVIDUALS ON CREATION TO SAVE TIME */
			$(".tile .unit").draggable({
				addClasses: false,
				appendTo: "window",
				create: function(){$(this).contextMenu(unitMenu,{delay:500, autoHide:false, theme:'vista', className:'unitInfo'})}, 
				helper: ".dragger",
				refreshPositions: false,
				revert: "invalid",
				scope: 'pieces',
				snap: '.tile',
				start: function(){oldTile=$(this).parent();},
				zIndex:200
							
			});		
		}
	
	
	
	
		function setupBoard(){
			
			makeUnitsDraggable();
			
			/* FUNCTIONS RELATED TO CLICK-AND-DRAG CREATION.. CURRENTLY DEPRECATED AND PARTIALLY INVALIDATED 
			
			$(".unitImage").draggable({
				addClasses: false,
				appendTo: "window",
				refreshPositions: false,
				helper: "clone",
				revert: true,
				scope: 'pieces',
				snap: '.tile',
				zIndex:50
			});

			$(".settlementImage").draggable({
				addClasses: false,
				appendTo: "window",
				refreshPositions: false,
				helper: "clone",
				revert: true,
				scope: 'pieces',
				snap: '.tile',
				zIndex:50
			});		


			*/	
			
			$("#Map_window .tile").droppable({
				addClasses : false,
				scope: 'pieces',
				snap: 'tile',
				drop: pieceDrop,
				tolerance: 'intersect',
				hoverClass: 'selectedTile'
				});
		
			$("#Map_window .ocean, #Map_window .shallow").each(function(){$(this).droppable({disabled:true});$(this).droppable('option', 'scope', 'water');});
			$("#Map_window .ocean").has(".island").each(function(){$(this).droppable({disabled:false});$(this).droppable('option', 'scope', 'pieces');});
			$("#Map_window .shallow").has(".island").each(function(){$(this).droppable({disabled:false});$(this).droppable('option', 'scope', 'pieces');});
			$("#Map_window .mountain, #Map_window .ice").has(".volcano").each(function(){$(this).droppable({disabled:true});$(this).droppable('option', 'scope', 'volcano');});
		
			$(".settlement").contextMenu(settlementMenu,{delay:500, autoHide:false, theme:'vista', className:'unitInfo'});
		     $(".improvement").contextMenu(improvementMenu,{delay:500, autoHide:false, theme:'vista', className:'unitInfo'});

			$("#Map_window .tile").click(function(){
		       
				
				thisScope=$(this).droppable('option', 'scope');
				// if ($(this).children('.roads').length==0){buildRoad($(this), thisScope);}
				updateRoads($(this));  // preventative until road segment sorted out.
				
				if(currentItem=="Terrain"){changeTerrain(currentTerrain, $(this), currentTerrainLevel);}
				
				if (thisScope=="pieces"){
				    if(currentItem=="Unit"){buildUnit(currentUnit, $(this));}
				    if(currentItem=="Settlement"){buildSettlement(currentSettlement, $(this));}
					if(currentItem=="Improvement"){buildImprovement(currentImprovement, $(this));}
					
					
					
					// if(currentItem=="Linked_Improvement"){buildRoad($(this));}  // This will generalize with other linked improvement
					
				}
			});
		
			

		}
	
 /* MAP EDITOR FUNCTIONS */
			
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
		
		function saveMap(){
			if(typeof(Storage)!=="undefined")
			  {
				localStorage.mapState=$("#Map_window").html();
				
			  }
			else
			  {
			 window.alert("Bah!");
			  }
	
		}
			
		function loadMap(){
				
			$("#Map_window").empty();
			$("#Map_window").html(localStorage.mapState);
			setupBoard();
			updateAllRoads();
		}
	
 