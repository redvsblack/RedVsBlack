<?php error_reporting(E_ERROR | E_PARSE); ?>
<?php if(!(isset($_SESSION["players"]))){session_start();} ?>


<script type="text/javascript" src="scripts/loadscripts.js"></script>
	
	

					<?php
					
						if(isset($_POST['savedMap'])){
							echo("SDFJKDSAFJKLDSFJKASDFSDKL");
							echo $_POST['savedMap'];
							unset($_POST['savedMap']);
							
						}else{
							include('gameMapGen.php');
							} 
					?>

			
				
			
		
	
	
<!-- GRAVEYARD FOLLOWS //-->

<script>

/* $("#Navigator .tile").click(function(){
				$("#Map_window").scrollTop(6.4*$(this).attr('y'));
				$("#Map_window").scrollLeft(6.4*$(this).attr('x'));
				$("#navSquare").css('top', function(){return((10/64)*$("#Map_window").scrollTop()+10);});
				$("#navSquare").css('left', function(){return((10/64)*$("#Map_window").scrollLeft()+10);});
			}); */  
			

			/* $("#Map_window").overscroll();
			 $(".unit").click(
				  function () {
					$("#Map_window").removeOverscroll();
				  }, 
				  function () {
					$("#Map_window").overscroll({cancelOn:'img'});
				  }
			); */
</script>	

<!--<script>   REMNANTS OF ZOOMING FUNCTION
    $(function() {
        $( "#slider" ).slider({
            value:32,
            min: 4,
            max: 64,
			orientation:"vertical",
            step: 2,
            slide: function( event, ui ) {
				var x=ui.value;
               $("Map_window .tile").css({"height":x, "width":x});
			   
            }
        });
       
    });  
    </script>//-->