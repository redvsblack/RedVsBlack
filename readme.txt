==RED VS BLACK - BOARD AND STRATEGY GAME FRAMEWORK RELEASE NOTES==

The goal of this project is to create a lightweight board-based gaming framework that allows for integration of different 
traditional and non-traditional ruleset, as an experiment with the capabilities of jQuery HTML5/CSS3 web standards.   

I am an amateur (hack) programmer, so I apologize where documentation is unclear, but I will do my best to improve it over time.

Further releases throughout the next month will include improved interfaces, rule sets, and when the system is stable, I will start 
introducing simple games (checkers, go) as well as developing my own unique rule / scenario sets based on my reverence for the full
spectrum of board and strategy cames from Checkers and Chess through the Civilization series that I have followed since my youth.


======PLUGINS UTILIZED ======
- jQuery and jQuery UI (heavily) - jquery.com, jquery.ui.com
- jQuery custom context menu plugin - http://medialize.github.com/jQuery-contextMenu/docs.html
- jQuery color picker: www.eyecon.ro/colorpicker
 
All Map generations and algorithms are my own, all code is my own besides obvious exceptions pasted and edited from Stackflow, 
PhP documentation, etc.




======LATEST RELEASE - ALPHA 0.5======
11-16

Features in place:
- Map Generator with slideable specs (defaults on load are represented by the 'quick start' button)
- Map Editor via select and placement:  
   - Click to select and place
   - Right click to open context menus to delete
   - Future versions will include selection of multiple items on the tile for editing and separate deletion
- Navigator menu which currently is not functioning properly but I have left it up to show a picture of the map generated
- Update tabs which will reflect a more narrative version of development

- Plans for the week of 11-16 to 11-23:
   - Code optimization now that module integration is complete
   - Development of a bottom bar information and editing interface 