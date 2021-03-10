<?php
/**
 * IPP Project 2020/2021
 * Parsing code in IPPcode21. Represent IPPcode21 in XML
 * 
 * @file parse.php
 * @brief Main file of script.
 * @author Kostiantyn Krukhmalov
*/

include 'parse_lib/scanner.php';
include 'parse_lib/data.php';
include 'parse_lib/instruction.php';
include 'parse_lib/writer.php';
ini_set('display_errors', 'stderr');


check_options();

$line = new Line;
$w = new Writer;
$w->start();

// test for empty file
if ($line->elements == false) {
    exit(ERROR_SYNTAX_LEX);
}

// test for bad header or its missing
if ( ($line->cnt() != 1) or (strcmp($line->elements[0], ".IPPcode21") != 0) ) {
    exit(ERROR_HEADER);
}

/******* Parsing ********/
while ($line->nextLine()) {
    $inst = new Instruction($line);
    switch ($inst->opCode) {

        // no operands
        case 'createframe': case 'pushframe': case 'popframe': case 'return': case 'break':
            exit_if_false($inst->checkOpCnt(0), ERROR_SYNTAX_LEX);
            break;
        
        // <var>
        case 'defvar': case 'pops':
            exit_if_false($inst->checkOpCnt(1), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkVar(0), ERROR_SYNTAX_LEX);
            break;
         
        // <symb>
        case 'pushs': case 'write': case 'exit': case 'dprint':
            exit_if_false($inst->checkOpCnt(1), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkSymb(0),ERROR_SYNTAX_LEX);
            break;

        // <label>
        case 'call': case 'label': case 'jump':
            exit_if_false($inst->checkOpCnt(1), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkLabel(),ERROR_SYNTAX_LEX);
            break;

        // <var> <symb>
        case 'move': case 'int2char': case 'strlen': case'type':
            exit_if_false($inst->checkOpCnt(2), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkVar(0), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkSymb(1), ERROR_SYNTAX_LEX);
            break;

        case 'not':
            exit_if_false($inst->checkOpCnt(2), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkVar(0), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkSymb(1), ERROR_SYNTAX_LEX);
            break;
        
        // <var> <symb> <symb>
        case 'add': case 'sub': case 'mul': case 'idiv':
        case 'lt': case 'gt': case 'eq': case 'and': case 'or': case 'str2int':
        case 'concat': case 'getchar': case 'setchar':
            exit_if_false($inst->checkOpCnt(3),ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkVar(0), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkSymb(1), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkSymb(2), ERROR_SYNTAX_LEX);
            break;

        // <var> <type>
        case 'read':
            exit_if_false($inst->checkOpCnt(2),ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkVar(0), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkType(1), ERROR_SYNTAX_LEX);
            break;

        // <label> <symb> <symb>
        case 'jumpifeq': case 'jumpifneq':
            exit_if_false($inst->checkOpCnt(3),ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkLabel(), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkSymb(1), ERROR_SYNTAX_LEX);
            exit_if_false($inst->checkSymb(2), ERROR_SYNTAX_LEX);
            break;

        default:
            exit(ERROR_OPCODE);
            break;
    }

    $w->inst($inst);
    unset($inst);
}
$w->finish();
$w->writeOut();


function check_options() {

    global $argc;
    $options = getopt("",["help"]);

    if ($argc == 1) {
        return 0;
    } elseif (array_key_exists("help", $options)) {

        if ($argc != 2) {
            exit(ERROR_PARAM);
        } else {
            print_help_msg();
            exit(0);
        }
    }
}


function exit_if_false(bool $bool, int $errCode){
    if($bool == false){
        exit($errCode);
    }
}

function print_help_msg() { //TODO #3    
    echo "Usage: ",basename(__FILE__)," [--help]\n";
    echo "    Skript typu filtr (parse.php v jazyce PHP 7.4)\n";
    echo "      načte ze standardního vstupu zdrojový kód v IPP-code21,\n";
    echo "      zkontroluje lexikální a syntaktickou správnost kódu\n";
    echo "      a vypíše na standardní výstup XML reprezentaci programu.\n";
    echo "\n";
    echo "  --help  Vypise tuto napovědu na standartní výstup\n";
}
?>