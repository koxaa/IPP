<?php

const XML_INDENT = 1;
const XML_INDENT_STRING = '   ';

class Writer extends XMLWriter{
	
	static private $instNum = 0;

	function __construct()
	{
		$this->openMemory();
		$this->setIndent(XML_INDENT);
		$this->setIndentString(XML_INDENT_STRING);
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
		
		self::$instNum++;
		$this->startElement('instruction');

		$this->startAttribute('opcode');
		$this->text(strtoupper($inst->opCode));
		$this->endAttribute();

		$this->startAttribute('order');
		$this->text(self::$instNum);
		$this->endAttribute();

		for ($i=0; $i < count($inst->operands); $i++) { 
			$this->startElement('arg'.strval($i+1));
			$this->startAttribute('type');
			$opType = $inst->operandType($i);
			$this->text($opType);
			$this->startElement("text");
			
			switch ($opType) {
				case 'string':
					$this->text(substr($inst->operands[$i], 7));
					break;
				case 'bool':
					$this->text(substr($inst->operands[$i], 5));
					break;
				case 'int': case 'nil':
					$this->text(substr($inst->operands[$i], 4));
					break;
				case 'label': case 'type': case 'var':
					$this->text($inst->operands[$i]);
					break;
				default:
					break;
			}

			$this->endElement();

			$this->endElement();
		}
		
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