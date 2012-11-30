<?php




class Settlement
	{
	public $settlementCode;
	
		public function __construct($player, $x, $y, $info){
		
			$settlementCount=count($_SESSION["players"][$player]["Settlements"]);
			
			$this->code=$info["Settlement_Code"];
			$this->name=$info["Settlement_Name"];
			$this->isLinked=$info["Linked"];
			
			$this->playerName=$player;
			$this->graphic=$info["Graphic_URL"];
			$this->uniqueCode=$this->playerName.$settlementCount;
			$this->lat=$x;
			$this->long=$y;
			

			$settlementCount++;
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

