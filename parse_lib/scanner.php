<?php

class lineClass {
	var $elements;
	function __construct()
	{
		$this->elements = scanLine();
	}

	function nextLine(){
		return $this->elements = scanLine();
	}
	
	function cnt(){
		return count($this->elements);
	}

	function searcOpCode(){
		global $opCodes;
		return array_search( strtolower($this->elements[0]) , $opCodes);
	}
	
	function dump() {
		var_dump($this->elements);
	}
}


/**
 * Gets nem line, normilize it, and returns as array.
 *
 * @return string[]|false
 * array of strings, false if eol
 */
function scanLine() {

	$result = false;
	$line = fgets(STDIN);

	// Test for comment, empty line or EOF
	while($line != false) {

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

	// line normalizing
	if ($line != false) {
		$line = preg_replace("/#.*/", PHP_EOL,$line);
		$line = preg_replace("/^\s\s*/", "" , $line);
		$line = preg_replace("/\s\s*$/", null, $line);
		$line = preg_replace("/\s\s*/", " ", $line);
		$result = explode(" ", $line);
	}

	return $result;
}


?>