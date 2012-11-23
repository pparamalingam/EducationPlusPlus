<?php
	class PointEarningScenario {
		private $name;
		private $pointValue;
		private $description;
		private $requirementSet;
		private $expiryDate;
		private $deletedByProf;

		// CONSTRUCTOR
		function __construct( $name, $pointValue, $description, $requirementSet, $expiryDate, $deletedByProf ) {
			//print "In constructor\n";
			$this->name = $name;
			$this->pointValue = $pointValue;
			$this->description = $description;
			$this->requirementSet = $requirementSet;
			$this->expiryDate = $expiryDate;
			$this->deletedByProf = $deletedByProf;
		}

		// DESTRUCTOR
		function __destruct() {
		   //print "Destroying " . $this->name . "\n";
		}
	   
		// GETTER
		public function __get($property) {
			if (property_exists($this, $property)) {
				return $this->$property;
			}
		}

		// SETTER
		public function __set($property, $value) {
			if (property_exists($this, $property)) {
				$this->$property = $value;
			}
			return $this;
		}
		
		// TOSTRING
		public function __toString() {
			$stringToReturn = $this->name . "<br/>" . $this->pointValue . " Point Value<br/>Expires on " . /*$this->expiryDate .*/ "<br/>";
			for ($i=0; $i<count($this->requirementSet); $i++){
				$stringToReturn = $stringToReturn . $this->requirementSet[$i] . "<br/>";
			}
			return $stringToReturn;
		}
	}
	
	class Requirement {
		private $activity;
		private $condition;				//This is a string. See Rules for Condition below
		private $percentToAchieve;
		
		/* 	Rules for Condition:
			"1"    | If Activity has been Completed ($percentToAchieve will be ignored)
			">"    | If Activity's Grade (in percent) >= $percentToAchieve
			">="   | If Activity's Grade (in percent) > $percentToAchieve
			"="    | If Activity's Grade (in percent) is exactly $percentToAchieve
		*/
		
		// CONSTRUCTOR
		function __construct( $activity, $condition, $percentToAchieve ) {
		//print "In constructor\n";
			$this->activity = $activity;
			$this->condition = $condition;
			$this->percentToAchieve = $percentToAchieve;
		}

		// DESTRUCTOR
		function __destruct() {
			//print "Destroying";
		}
		
		// TOSTRING
		public function __toString() {
			//FIX TO ACCOMODATE OTHER CONDITIONS
			$stringToReturn = "Get more than " . $this->percentToAchieve . " on " . $this->activity;
			return $stringToReturn;
		}
	}
	
	class Activity {
		private $name;
		private $weight;
		
		// CONSTRUCTOR
		function __construct( $name, $weight ) {
			//print "In constructor\n";
			$this->name = $name;
			$this->weight = $weight;
		}

		// DESTRUCTOR
		function __destruct() {
			//print "Destroying " . $this->name . "\n";
		}
		
	   	// TOSTRING
		public function __toString() {
			$stringToReturn = $this->name;
			return $stringToReturn;
		}
	}
?>