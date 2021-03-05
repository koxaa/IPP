<?php
/**
 * IPP Project 2020/2021
 * Parsing code in IPPcode21. Create XML file.
 * 
 * @file scanner.php
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
            ifFalseExit($inst->checkOpCnt(0), ERROR_SYNTAX_LEX);
            break;
        
        // <var>
        case 'defvar': case 'pops':
            ifFalseExit($inst->checkOpCnt(1), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkVar(0), ERROR_SYNTAX_LEX);
            break;
         
        // <symb>
        case 'pushs': case 'write': case 'exit': case 'dprint':
            ifFalseExit($inst->checkOpCnt(1), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkSymb(0),ERROR_SYNTAX_LEX);
            break;

        // <label>
        case 'call': case 'label': case 'jump':
            ifFalseExit($inst->checkOpCnt(1), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkLabel(),ERROR_SYNTAX_LEX);
            break;

        // <var> <symb>
        case 'move': case 'int2char': case 'strlen': case'type':
            ifFalseExit($inst->checkOpCnt(2), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkVar(0), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkSymb(1), ERROR_SYNTAX_LEX);
            break;

        case 'not':
            ifFalseExit($inst->checkOpCnt(2), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkVar(0), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkSymb(1), ERROR_SYNTAX_LEX);
            break;
        
        // <var> <symb> <symb>
        case 'add': case 'sub': case 'mul': case 'idiv':
        case 'lt': case 'gt': case 'eq': case 'and': case 'or': case 'str2int':
        case 'concat': case 'getchar': case 'setchar':
            ifFalseExit($inst->checkOpCnt(3),ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkVar(0), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkSymb(1), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkSymb(2), ERROR_SYNTAX_LEX);
            break;

        // <var> <type>
        case 'read':
            ifFalseExit($inst->checkOpCnt(2),ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkVar(0), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkType(1), ERROR_SYNTAX_LEX);
            break;

        // <label> <symb> <symb>
        case 'jumpifeq': case 'jumpifneq':
            ifFalseExit($inst->checkOpCnt(3),ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkLabel(), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkSymb(1), ERROR_SYNTAX_LEX);
            ifFalseExit($inst->checkSymb(2), ERROR_SYNTAX_LEX);
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



/**
 * Checking options.
 * Desides how script will be running.
 *
 * @return void
 */
function check_options() {

    global $argc;
    $options = getopt("",["help","stats::"]);

    if ($argc == 1) {
        return 0;
    } elseif (array_key_exists("help", $options)) {

        if ($argc != 2) {
            exit(10);
        } else {
            print_help_msg();
            exit(0);
        }

    } elseif (array_key_exists("stats", $options)) { // todo #7

        if (!$options["stats"]) {
            fwrite(STDERR,"Missing an argument for parament --stats\n");
            exit(10);
        } else {
            echo $options["stats"],"\n";
        }
    }
}


function ifFalseExit(bool $bool, int $errCode){
    if($bool == false){
        exit($errCode);
    }
}


/**
 * Printing out help message
 */
function print_help_msg() { //TODO #3    
    echo "Usage: ",basename(__FILE__)," [--help] [--stats=<filename> [--loc] [--comments] [--labels] [--jumps] ]\n";
    echo "    Skript typu filtr (parse.php v jazyce PHP 7.4)\n";
    echo "      načte ze standardního vstupu zdrojový kód v IPP-code21,\n";
    echo "      zkontroluje lexikální a syntaktickou správnost kódu\n";
    echo "      a vypíše na standardní výstup XML reprezentaci programu.\n";
    echo "\n";
    echo "  --loc   vypíše do statistik počet řádků s instrukcemi (nepočítají se\n";
    echo "          prázdné řádky ani řádky obsahující pouze komentář ani úvodní řádek)\n";
    echo "  --stats pro sběr skupin statistik.\n";
}

?>