<?php




class Unit 
	{
	public $unitCode;
	
		public function __construct($player, $x, $y, $info){
		
			$unitCount=count($_SESSION["players"][$player]["Units"]);
			
			
			$this->playerName=$player;
			$this->uniqueCode=$this->playerName.$unitCount;
			$this->code=$info["Unit_Code"];
			$this->name=$info["Unit_Name"];
			// ALLOW FOR UNIQUE NAMING OF UNIT
			$this->graphic=$info["Graphic_URL"];
			$this->battleType=$info["MovementType"];
			$this->movementType=$info["MovementSubType"];
			$this->attack=$info["Attack"];
			$this->defense=$info["Defense"];
			// THIS ENDS ALL STATIC ITEMS
			
			$this->HP=$info["HP"];
			$this->XP=$info["XP"];
			$this->currentSquare=$x.",".$y;
			
			$this->isNoble=$info["isNoble"];
		
			
			// ALLOW FOR UNIQUE NAMING OF UNIT
			
			// THIS ENDS ALL VARIABLE ITEMS 
			
			
			// THESE ARE ALL THE METHODS THAT WILL BE UNIQUE TO THE UNIT OR CALELD OFTEN

			$unitCount++;
			}
	
		public function healSelf(){
		   $this->status="healing self";
		}

		

		public function fortifying(){
		   $this->status="fortified";
		}

		public function attack(){
		   $this->status="attacked";
		}
		
		
		public function clearStatus(){
		   $this->status="";
		}
		
		public function moveUnit(){
		   $this->status="hasMoved";
		   
		}
		
		public function refresh(){
		// ADD NEW TURN FUNCTIONS TO READ LAST TURN'S STATUS
		
		}
		
		
		private function UpOrDowngrade(){
			$this->lastWas=$this->unitCode;
		   // COPY ALL ATTRIBUTES
		   // CREATE NEW UNIT
		   // PASTE ATTRIBUTES
		   // DESTROY THIS UNIT
		}
		
		
		
		
		
		
	}


	class Medic extends Unit{
	
	public function healOther($unitCode){
		   $this->status="healing ".$unitCode;
		}
	
	}
	
	class Laborer extends Unit{
		public function workTile($tileNum){
	
	
		}
	}
	
	class Ranged extends Unit{
		public function rangedAttack(){
		
		
		}	
	
	}
	
	
	class Commander extends Unit{
	
	
	}







?>

