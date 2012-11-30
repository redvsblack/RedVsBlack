<?php




class Improvement
	{
	public $improvementCode;
	
		public function __construct($player, $x, $y, $info){
		
			$improvementCount=count($_SESSION["players"][$player]["Improvements"]);
			
			$this->code=$info["Improvement_Code"];
			$this->name=$info["Improvement_Name"];
			$this->isLinked=$info["Linked"];
			
			$this->playerName=$player;
			$this->graphic=$info["Graphic_URL"];
			$this->uniqueCode=$this->playerName.$improvementCount;
			$this->lat=$x;
			$this->long=$y;
			

			$improvementCount++;
			}
	
		private function UpOrDowngrade(){
			$this->lastWas=$this->code;
		   // COPY ALL ATTRIBUTES
		   // CREATE NEW UNIT
		   // PASTE ATTRIBUTES
		   // DESTROY THIS UNIT
		}
		

	}


	
	






?>

