<?php

global $opCodes;

class line {

	var $elements;
	var $newElements;

	function __construct() {
		$this->elements = array();
	}

	function add_ellement ($newElements) {
		$this->elements = array_push($this->ellements, $newElements);
	}

	function next_line() {
		$line = fgets(STDIN);
		while(true) {
			if ( preg_match("/^\s*#/",$line) ) {
				$line = fgets(STDIN);
			} elseif (preg_match("/^\s*$/",$line)) {
				$line = fgets(STDIN);
			}
		}
	}
	
}

function scanLine() {

	$line = fgets(STDIN);
	$result = array("opCode" => false,"op1" => false, "op2" => false, "op3" => false);

	//Check if new line is comment or empty. Jump over this.
	while(true) {

		// Commented line
		if ( preg_match("/^\s*#/",$line) ) {
			$line = fgets(STDIN);
			continue;
		// Empty line
		} elseif (preg_match("/^\s*$/",$line)) {
			$line = fgets(STDIN);
			continue;
		} else {
			break;
		}
	}

	preg_replace("/.*\s\s*/", " ", $line);
	$exploded = explode(" ",$line); // TODO #8
	var_dump($line);
	var_dump($exploded);
	
}

?>