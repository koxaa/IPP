<?php

class Writer extends XMLWriter{
	
	static $instNum = 0;

	function __construct()
	{
		$this->openMemory();
		$this->setIndent(1);
		$this->setIndentString(' ');
	}

	function start()
	{
		$this->startDocument('1.0','UTF-8');
		$this->startElement('program');
		$this->startAttribute('language');
		$this->text('IPPcode21');
		$this->endAttribute();
	}

	function inst (Instruction $inst) {
		
		$this->openElementsCNT++;
		$this->startElement('instruction');

		$this->startAttribute('opcode');
		$this->text(strtoupper($inst->opCode));
		$this->endAttribute();

		$this->startAttribute('order');
		$this->text($this->openElementsCNT);
		$this->endAttribute();

		// for ($i = 0;$inst->operands[$i]; $i++ ) {
		// 	$this->startElement('arg' . strval($i + 1));		// zacina od arg1
		// 	$this->startAttribute('type');
		// 	if ($inst->checkConst($i)) {

		// 	} elseif ($inst->checkLabel($i)) {
		// 		$this->text('lable');
		// 	} elseif ($inst->checkVar($i)) {
		// 		$this->text('variable');
		// 	}else {
		// 		# code...
		// 	}

		// 	$this->endAttribute();

		// 	$this->endElement();
		// }
		

		$this->endElement();
	}

	function operand () {

	}

	function writeOut () {
		echo $this->outputMemory();
	}

	function finish (){
		$this->endElement();
		$this->endDocument();
	}
}

?>