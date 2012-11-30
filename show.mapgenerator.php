
		<div class="mapDragBar" title="">
				<p>Map Generator</p>
				
		</div>
		<div id="mapCreator" class="content">
			
			<div class="buttonBar">
				<button type=""  id="submitButton" onclick="generateMap();">Create</button>	
				<button type=""  id="resetButton" onclick="resetDefaults();">Reset</button>
				
			</div>
			<form id="mapGenerator" method="post" class="content">
				<ul class="mapNavbar" style="background:none;">
					<li><a href="#mapSize" title="Size">Size</a></li>
					<li><a href="#terrainLayout" title="World">Geography</a></li>
					<li><a href="#terrainDisplay">Colors</a></li>
				</ul>
				

				<div class="box" id="mapSize">
					<h2>Map dimensions</h2>
					<p>Smaller maps will be less diverse and particularly with less water due to the clustering algorithms of the RNG.</p>
					<p>Map Width (Tiles):<span class="mapSlider" id="mapWidth" min="1" max="125" value="48">48</span> <br /><span class="value"></span><input type="hidden" name="mapWidth" /></p>
					<p>Map Height (Tiles):<span class="mapSlider" id="mapHeight" min="1" max="125" value="36">36</span><br /><span class="value"></span><input type="hidden" name="mapHeight" /></p>
					<p>Tile Width (px):<span class="mapSlider" id="cellWidth" min="16" max="40" value="32">32</span> <br /><span class="value"></span><input type="hidden" name="cellWidth" /></p>
					<p>Tile Height (px):<span class="mapSlider" id="cellHeight" min="16" max="40" value="32">32</span> <br /><span class="value"></span><input type="hidden" name="cellHeight" /></p>
					<p title="(All Oceans)">Generate Blank Map: <input type="checkbox" name="blankMap"  /></p>
				
				</div>		
				
				
				<div class="box" id="terrainLayout" >
					<h2>Geography Options</h2>
					<p>Terrain is currently limited to Ocean, Shallows, Low (Grassland / Plains / Desert), Hills, Mountains, and Snowcap Mountains.</p>
									
					<p title="Currently the map clusters to deepen oceans and raise mountains so more iterations means, generally, more water and mountains..  A range of 1-18 is allowed although 6-12 is recommended.">Iterations:</h3><span class="mapSlider" id="iterations" min="1" max="18" value="11"></span> <br /><span class="value"></span><input type="hidden" name="iterations" /></p>
				
					<p title="More Continents / Oceans, working in tandem with the next Item">Land -> Water<span class="mapSlider" id="oceansLand" min="-9" max="6" value="1">1</span><br /><span class="value" style="visibilty:hidden;"></span><input type="hidden" name="oceansLand" /></p>
					<p title="More Continents / Oceans, working in tandem with the previous Item... this one is currently pretty sensitive, if you want any reasonable sort of mountains, choose values between 5 and 30.">Low -> High Land<span class="mapSlider" id="elevationMod" min="-75" max="75" value="15">15</span><br /><span class="value" style="visibilty:hidden;"></span><input type="hidden" name="elevationMod" /></p>
					<p title="This algorithm is admittedly rough right now and hard to explain, but the version right now takes an exponential function to measure the probability at a given latitude that there will be ice.  The variable represents a denominator in the equation, effecting both the slope and the extent of ice.  For Mountains, it just slightly increases the odds that mountains will have snow.">Hotter -> Colder<span class="mapSlider" id="iceCaps" min="1" max="100" value="10">10</span><br /> <span class="value" style="visibilty:hidden;"></span><input type="hidden" name="iceCaps" /></p>
					<p title="The current algorithms tend to stubbornly favor land bridges, particularly on lower #'s of iterations... this helps fight that."> Raise -> Lower Land Bridges<span class="mapSlider" id="isthmuses" min="-50" max="50" value="20">20</span><br /> <span class="value" style="visibilty:hidden;"></span><input type="hidden" name="isthmuses" /></p>
					<p title="Less / More Lakes">Less -> More Lakes<span class="mapSlider" id="lakesBalance" min="0" max="100" value="10">10</span><br /><span class="value" style="visibilty:hidden;"></span><input type="hidden" name="lakesBalance" /></p>
						<p title="Less / More Forest">Less -> More Forests<span class="mapSlider" id="forestsBalance" min="0" max="100" value="20">20</span><br /><span class="value" style="visibilty:hidden;"></span><input type="hidden" name="forestsBalance" /></p>
					<p title="Any Grasslands with less water than this # becomes a Plains">Water Threshhold for Grassland/Plains <span class="mapSlider" id="plains" min="1" max="5" value="1">1</span><br /><span class="value"></span><input type="hidden" name="plainsWater" /></p>
					<p title="any Plains with NO water becomes a desert if this is enabled">Enable / Disable deserts  <input type="checkbox" name="deserts" checked="yes" /></p>
				</div>
				
				<div class="box" id="terrainDisplay">
					<h2>Terrain Colors</h2>
					<p>Selector is bugging a little bit but the Hex input and general functionality works.</p>
					
					<ul id="terrainTypes" class="box">
						<li class="terrainType">Ocean: <input class="terrainPicker" name="oceanColor" type="text"  size="8" value="#4859A3" /> </li>
						<li class="terrainType">Shallow: <input class="terrainPicker" name="shallowColor"  type="text" size="8" value="#616fbf" /> </li>
						<li class="terrainType">Grassland: <input class="terrainPicker" name="grasslandColor"  type="text" size="8" value="#50d941" /></li>
						<li class="terrainType">Plains: <input class="terrainPicker" name="plainsColor"  type="text" size="8" value="#c6e86f" /></li>
						<li class="terrainType">Desert: <input class="terrainPicker" name="desertColor"  type="text" size="8" value="#f8ffb5" /></li>
						<li class="terrainType">Hills: <input class="terrainPicker" name="hillsColor" type="text" size="8" value="#a7ab63" /></li>
						<li class="terrainType">Mountain: <input class="terrainPicker" name="mountainColor"  type="text" size="8" value="#6a6c75" /></li>
						<li class="terrainType">Snow and Ice: <input class="terrainPicker" name="snowColor"  type="text" size="8" value="#f0f0ff"></li>
					</ul>
					
					<br />
					
				</div>	
				

				
			<br />
			</form>
		</div>

	
			
					
					
			
			
			
			
			
			