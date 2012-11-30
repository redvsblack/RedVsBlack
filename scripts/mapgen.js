
/* A lot of this code is sub-optimal as it was ported from my separate Map Generator when I started anew... I'm in the process of integrating it into the main code pattern,
   and eventually the code will be re-segmented back out into functions.  But for now ... */



	 
	 
	// DOCUMENT RELATED FUNCTIONS TO BE INTEGRATED INTO MAIN FUNCTION PATTERN
	 $(document).ready(
		function(){
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
		
			 resetMapGen();
		
		
		
		$('#mapDragBar').dblclick(function() {toggleToolBar(); }); 
		

	});
	
		