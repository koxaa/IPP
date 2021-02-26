<?php

/**
 * Represents a line
 * 
 * Line is represented as array of strings
 */
class Line {
	
	var $elements;

	function __construct(){
		$this->elements = getLine();
	}

	/**
	 * Gets next line.
	 *
	 * @return boolean
	 */
	function nextLine(){
		$this->elements = getLine();
		return ($this->elements != false) ? true : false;
	}
	
	/**
	 * Returns a count of elements in line.
	 *
	 * @return int
	 */
	function cnt(){
		return count($this->elements);
	}

	/**
	 * Searchs match with operation code.
	 * 
	 * @return int
	 * index in $opCodes
	 */
	function searcOpCode(){
		global $opCodes;
		return array_search( strtolower($this->elements[0]) , $opCodes);
	}
	
	/**
	 * Write out Content of line.
	 */
	function dump() {
		var_dump($this->elements);
	}
}


/**
 * Gets new line, normilize it, and returns as array.
 *
 * @return string[]|false
 * array of strings, false if eol
 */
function getLine() {

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