
	 /* UNIT CREATION AND EDITING FUNCTIONS */
		
	 
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
			{'Delete':function(menuItem,menu) {$(this).remove();updateAllRoads();} },
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
					// $('.ui-selected').removeClass('ui-selected');
				}
			 );
					
			// currentUnit="";
			// currentItem="";
				
		}
			
		function buildSettlement(tempCode, thisTile){
		
		thisTile.children('.improvement:not(.roads)').remove();
		
		var thisX=parseInt(thisTile.attr('x'));
		var thisY=parseInt(thisTile.attr('y'));
		
			var pieceColor=$('input[name=playerColor]:checked', '#playerColor').val()
			$.post(
				"create.settlement.php", 
				 {settlementCode:tempCode, pieceColor:pieceColor, thisX:thisX, thisY:thisY}, 
				 function(data){
					
					var thisLocation=getNeighborItems( thisTile, 1, 'roads');
					$('#Map_window .tile[x='+thisX+'][y='+thisY+']').append(data);
					$('#Map_window .tile[x='+thisX+'][y='+thisY+']').append("<img class='roads' src='improvements/"+thisLocation+".png' />"); // BUILDS A ROAD UNDERNEATH THE SETTLEMENT
					$('.settlement').contextMenu(settlementMenu,{delay:500, autoHide:false, theme:'vista', className:'settlementInfo'})
					// $('.ui-selected').removeClass('ui-selected');
					updateRoads(thisTile);
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
						
						$('#Map_window .tile[x='+thisX+'][y='+thisY+']').append(data);
						
						updateRoads(thisTile);
						$('.improvement').contextMenu(improvementMenu,{delay:500, autoHide:false, theme:'vista', className:'improvementInfo'})
						// $('.ui-selected').removeClass('ui-selected');
					}
				);
				// currentItem="";
				// currentImprovement="";
			}
		}
		

   /* TERRAIN MAPPING FUNCTIONS */	


	
/* THIS FUNCTION IS USABLE FOR MANY APPLICATIONS - IT DETECTS CHILDREN OF A PARTICULAR TYPE IN NEIGHBORING SQUARES.  IT IS USED CURRENTLY FOR ROADS BUT CAN ALSO BE USED FOR RIVERS, DETECTING ENEMY UNITS, ETC */	
	
	function getNeighborItems(thisTileB, tilesAway, item){
		
				var a;var b;
					var thisX=parseInt(thisTileB.attr('x'));
					var thisY=parseInt(thisTileB.attr('y'));
					var neighborItems=""+item+"-";
					var currentTile;
					var thisIndex=0;
					var results2;	

					for (a=(thisY-tilesAway);a<=(thisY+tilesAway);a++){
						for (b=(thisX-tilesAway);b<=(thisX+tilesAway);b++){
							
							var currentTile=$('#Map_window .tile[x='+b+'][y='+a+']');
							
							if (currentTile.has('.'+item).length!=0){
								if (thisIndex!=4){neighborItems+=thisIndex;results2+=b+','+a+','+thisIndex+' | ';}
								
								}
								thisIndex++;
							
						}
					}
					neighborItems = neighborItems.replace("4","");
					
					totalItems=neighborItems;
					neighborItems=0;
				//	$("#info1").html(results2);
					return(totalItems);
					
						
			}

	/* THESE SCRIPTS UPDATE ROADS TO CONNECT AND CAN OBVIOUSLY ALSO BE USED FOR RIVERS AND MAYBE IN SOME WAY WITH TERRAIN BORDERS */
		
		function updateRoads(tile){
			
			$('#info1, #info2').empty();
			var tempX = parseInt(tile.attr('x'));
			var tempY = parseInt(tile.attr('y'));
			var thisLocationB;
			var results="";
			
			for(var countX=(tempX-1);countX<=(tempX+1);countX++){
				for(var countY=(tempY-1);countY<=(tempY+1);countY++){
					$('#Map_window .tile[x='+countX+'][y='+countY+']').has('.roads').each(
						function(){
							// if (($(this).attr('x')!=x)&&($(this).attr('y')!=y)){
								 thisLocationB=getNeighborItems( $(this), 1, "roads");
								 results+=countX+','+countY+','+thisLocationB+' | ';
								 // window.alert(thisLocationB);
								$(this).children('.roads').attr('src', 'improvements/'+thisLocationB+'.png');
								thisLocationB="";
							// }
						});
				}
			}
				
			// $('#info2').html(results);	
		}
		
		
		function updateAllRoads(){
				$('#Map_window .tile').has('.roads').each(
						function(){
							// if (($(this).attr('x')!=x)&&($(this).attr('y')!=y)){
								var thisLocationB=getNeighborItems( $(this), 1, 'roads');
								// window.alert(thisLocationB);
								$(this).children('.roads').attr('src', 'improvements/'+thisLocationB+'.png');
							// }
						});
		
		}
		
		function buildRoad(thisTile, thisScope){
			$('#mapCursor').empty();
			$('#mapCursor').html($('.ui-selected').children('.sidebarItem').html());
			if (thisScope=="pieces"){
			
				if (thisTile.children('.roads').length==0){
					var thisLocation=getNeighborItems( thisTile, 1, 'roads');
					
					thisTile.append("<img class='improvement roads' src='improvements/"+thisLocation+".png' />");
					thisTile.children('.roads').contextMenu(improvementMenu,{delay:500, autoHide:false, theme:'vista', className:'improvementInfo'})
				}
				
				var thisX = thisTile.attr('x');
				var thisY = thisTile.attr('y');
				
				 updateRoads(thisTile);
				//  updateAllRoads();
			}
		}
	
	
	
 /* FUNCTIONS RELATED TO BOARD SET-UP AND ALLOWING PIECES TO BE MOVABLE - SPECIFICALLY ON RELOAD */	
	

	 /* MAKES PIECES MOVABLE */
	
	
		function makeUnitsDraggable(){  /* SEPARATED FOR CONVENIENCE IN LATER DECLARATIONS - SHOULD BE MADE TO TARGET INDIVIDUALS ON CREATION TO SAVE TIME */
			$('.tile .unit').draggable({
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
			
			$('#Map_window .tile').droppable({
				addClasses : false,
				scope: 'pieces',
				snap: 'tile',
				drop: pieceDrop,
				tolerance: 'intersect',
				hoverClass: 'selectedTile'
				});
		
			$('#Map_window .ocean, #Map_window .shallow').each(function(){$(this).droppable({disabled:true});$(this).droppable('option', 'scope', 'water');});
			$('#Map_window .ocean').has('.island').each(function(){$(this).droppable({disabled:false});$(this).droppable('option', 'scope', 'pieces');});
			$('#Map_window .shallow').has('.island').each(function(){$(this).droppable({disabled:false});$(this).droppable('option', 'scope', 'pieces');});
			$('#Map_window .mountain, #Map_window .ice').has('.volcano').each(function(){$(this).droppable({disabled:true});$(this).droppable('option', 'scope', 'volcano');});
		
			$('.settlement').contextMenu(settlementMenu,{delay:500, autoHide:false, theme:'vista', className:'unitInfo'});
		     $('.improvement').contextMenu(improvementMenu,{delay:500, autoHide:false, theme:'vista', className:'unitInfo'});

			$('#Map_window .tile').click(function(){
		       
			//	$("#info1").html(currentTerrain);
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
	
		
		
	
 /* LOAD UP FUNCTIONS */
		
	$(document).ready(function(){
		
		
		/* FROM MAP GEN */
		$(document).bind('mousemove', function(e){
			$('#mapCursor').css({
			left:  (e.pageX-16),
			top:   (e.pageY-16)
			});
		});		
		 $('#mapCursor').hide();
		 
		  $('#Map_window').hover(function(){$('#mapCursor').show();}, function(){$('#mapCursor').hide();});
		 
		// $('#Map_window').hover(function(){$("#mapCursor").show();$(this).css('cursor', 'none');}, function(){$("#mapCursor").hide();$(this).css('cursor', 'pointer');});
		 
			
		

		$('#mapDragBar').dblclick(function() {toggleToolBar(); }); 
		
		
		
		$('#Map_window').bind('contextmenu', function(e) {
			return false;
		});
		$(document).mousedown(function(e){ 
			if( e.button == 2 ) { 
				clearSelects(); 
			} 
			return true; 
		});
		
			/* SET UP NAVIGATOR WINDOW.. BUGGY */
			
			
	
			 /* APPLY EARLIER FUNCTIONS - SETTING UP BOARD FOR PLAY */
			
			setupBoard();
			setUpNav();
			// startGame();  // Will create fog of war, etc in regular gameplay mode
			// loadTurnEvents();     // start loading up turn events and information
			// playTurn(player);    // this will be a large function for the main release
			// etc
			

			}
		);
	
	