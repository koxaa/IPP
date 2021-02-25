<?php

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

/** I would like to try this */

// global $opCodes;

// class line {

// 	var $elements;
// 	var $newElements;
// 	static $comments;

// 	function __construct() {
// 		$this->elements = array();
// 	}

// 	function add_ellement ($newElements) {
// 		$this->elements = array_push($this->ellements, $newElements);
// 	}

// 	function next_line() {
// 		$line = fgets(STDIN);
// 		while(true) {
// 			if ( preg_match("/^\s*#/",$line) ) {
// 				$line = fgets(STDIN);
// 			} elseif (preg_match("/^\s*$/",$line)) {
// 				$line = fgets(STDIN);
// 			}
// 		}
// 	}
	
// }
?>