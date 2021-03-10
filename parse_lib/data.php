<?php
/**
 * IPP Project 2020/2021
 * Parsing code in IPPcode21. Represent IPPcode21 in XML
 * 
 * @file data.php
 * @brief Some data and error codes
 * @author Kostiantyn Krukhmalov
*/

const ERROR_PARAM = 10;
const ERROR_INPUT_FILE = 11;
const ERROR_OUTPUT_FILE = 12;
const ERROR_INTERNAL = 99;
const ERROR_HEADER = 21;
const ERROR_OPCODE = 22;
const ERROR_SYNTAX_LEX = 23;

$opCodes = array(1 => "move", "createframe","pushframe",
"popframe","defvar","call","return",
"pushs","pops","add","sub","mul",
"idiv","lt","gt","eq","and", "or",
"not","int2char","stri2int","read",
"write","concat","strlen","getchar",
"setchar","type","label", "jump",
"jumpifeq","jumpifneq","exit","dprint","break");

?> 