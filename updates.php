<ul class="mapNavbar">
	<li><h4><a href="#11-16" title="11/16 - First Source Release, Node.Js?">11/16</a></h4></li>
	<li><h4><a href="#11-15" title="11/15 - Bug Fixes as well as starting interface fixes">11/15</a></h4></li>
	<li><h4><a href="#11-13" title="11/13 - Map Editor Live with terrain options as well as all of the units and tile improvements for the Beta">11/13</a></h4></li>
	<li><h4><a href="#11-12" title="11/12 - 20 minutes of code writing and 4 hours of road graphics, but I have roads.">11/12</a></h4></li>
</ul>


<div id="11-16">
	<p> It's messy for now, but here it is as a few people have been asking for it...
	<br />
	<a href="updates/rvb0.5-11-16.zip">Alpha 0.5 release source</a>  w/ (limited) documentation (3.8mb)
	
	<p>Also, I'm looking into the integration of Node.Js for the multiplayer experience concurrently as I work on the interface, so updates will be slow.. if anyone has experience with
	this, I would truly appreciate their help.</p>
	
	<p>Updates will be slow as I clean up code and functions for efficiency over the next few days...</p>
	
	<p>As always, feel free to email reedjoshuam [at] gmail</p>
</div>


<div id="11-15">
	<p>Marathon 2 day session - I fixed a number of bugs and have started on the interface, which will be a more flexible, dynamic load fixed / hidable / dockable bottom bar.</p>

	<p><strong>Fixed:</strong>
		<ul>
			<li> - map slider bugs</li>
			<li> - window resizing to accomodate at least most desktop/laptop browser screens.. doesn't accommodate mobile and most tablets yet.</li>
			<li> - function bugs in the 'load map'
		</ul>
	</p>
	<p><strong>Added:</strong>
	 <ul>
		<li> - Mouse over cursors for the image you're editing</li>
		<li> - a "Quick start" option as well as the map generator</li>
		<li> - Right click to delete and cancel selected placement items </li>
		<li> - Stupid little simple concept graphics </li>
	</p>
	<p><strong>Next:</strong>
		<ul>	
			<li> - The function to update the roads to connect occasionally bugs out and I can't figure out why.</li>
			<li> - The navigator drag-box is still out..</li>
			<li> - Interface updates for cleanliness and displaying tile information</li>
			<li> - A peak at my development timeline coming up </li>
		</ul>
	</p>
	<p><strong>Long run:</strong>
		<ul>
			<li> - Custom unit definitions via CSV and form as opposed </li>
			<li> - once the last few bugs are worked out, I'll start defining the 'free play' rules and make the actual game </li>
		</ul>
	</p>
</div>




<div id="11-13">

	<p>Whew! So after last night's long road-making session, today I finished up making the sample graphics for the tile improvements and programmed the map editor, as well as getting
	all the items for the Beta up in the database... now I just have to fix the window bugs (I have to create a page to act as menu loader and introduction for the long term, and I figured I would 
	use that separate page to get the window height and width down to take care of you netbook and smart phone users.)
	</p> 
	<br /><br />
	<p>
	On an intangible level, I made massive steps in getting the code cleaned up, hopefully enough to branch out into a development blog and source releases within the next two weeks.

	</p>
 
</div>		
<div id="11-12">

	<strong>11/12:</strong> Tonight's demo is the poorly named 'Road Mode' demo.  I worked out a few bugs today, but in particular I'm excited to have written out a script
	 to remap roads as they are constructed, something I was always marveled by playing Civilization when I was little.  For this first set, I wanted to 
	 actually create each image individually from different layers to give it a more 'authentic' feel - as roads develop in complexity, the likelihood of them 
	 being uniform tends to decrease. As a result, though (and boo on me for not writing a script to plan this out), there are still a few images missing and a 
	 few bugs in the script... when I do rivers, I'll work it out.
	 <br />
	 <br />
	 In addition, I've fixed the bugs on the color picker, although I am still confronting a bug on the window sizing that will require a work around, so I apologize
	 if my current sizing doesn't work with your screen resolution.  That and the syncing with the navigation window is next on my list...

	 
	 <hr />
 
</div>	