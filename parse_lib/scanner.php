<?php
/**
 * IPP Project 2020/2021
 * Parsing code in IPPcode21. Represent IPPcode21 in XML
 * 
 * @file scanner.php
 * @brief Implementation of classes and methods for work with string from input.
 * @author Kostiantyn Krukhmalov
*/

class Line {
	
	var $elements;

	function __construct(){
		$this->elements = getLine();
	}

	function nextLine(){
		$this->elements = getLine();
		return ($this->elements != false) ? true : false;
	}
	
	function cnt(){
		return count($this->elements);
	}
}


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

	// cut comment,move string to start of line, replace two or more spaces by one, remove spaces in the end of line
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