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
ini_set('display_errors', 'stderr'); // Pro výpis varování na standardní chybový výstup



/**** Main script ****/

check_options();

$line = new Line;

if ($line->elements == false) { // test for empty file
    exit(ERROR_SYNTAX_LEX);
} else if ( ($line->cnt() != 1) or (strcmp($line->elements[0], ".IPPcode21") != 0) ) { // test for bad header or its missing
    exit(ERROR_HEADER);
}
echo "Here is good: 28\n";
$iterations = 1;
// SYNTAX CHECKING
while ($line->nextLine()) {

    if ($line->searcOpCode() == false) {
        exit(ERROR_OPCODE);
    }
    
    $inst = new Instruction($line);
    var_dump($inst);
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
            echo "NO IN SWITCH\n";
            exit(ERROR_OPCODE);
            break;
    }
    $iterations++;
    echo $iterations, "\n";
    unset($inst);
}


/**
 * Represents a instruction.
 * 
 * Instruction is OPCODE and 
 */
class Instruction {

    var $opCode;
    var $operands = array();
    
    function __construct(Line $line){
       $this->opCode = $line->elements[0];
       for ($i=1; $i < $line->cnt(); $i++) { 
           array_push($this->operands, $line->elements[$i]);
       }
       $this->opCodeToLower();
    }

    function opCodeToLower() {
        $this->opCode = strtolower($this->opCode);
    }

    function operandsCnt() {
        return count($this->operands);
    }

    function checkOpCnt($count) {
        return ($this->operandsCnt() == $count) ? true : false;
    }

    function checkVar(int $opnum) {
        if ( preg_match("/^(LF|GF|TF)@[A-Za-z_\-$&%*!?][0-9A-Za-z_\-$&%*!?]*$/", $this->operands[$opnum]) == 1 ) {
            return true;
        } else {
            return false;
        }
    }

    function checkSymb(int $opnum){
        if ( $this->checkVar($opnum) == true ) {
            return true;
        } elseif ( preg_match("/^int@-{0,1}[0-9]*$/",$this->operands[$opnum] == 1)) {
            return true;
        } elseif ( preg_match("/^bool@(true|false)$/",$this->operands[$opnum] == 1)) {
            return true;
        } elseif ( preg_match("/string@(\\[0-9]{3}|[^#\\\s])*/",$this->operands[$opnum])) {
            return true;
        } elseif ( preg_match("/^nil@nil$/", $this->operands[$opnum]) == 1) {
            return true;
        } else {
            return false;
        }
    }

    function checkLabel(int $opnum = 0) {
        if (preg_match("/[A-Za-z_\-$&%*!?][0-9A-Za-z_\-$&%*!?]*/", $this->operands[$opnum]) == 1) {
            return true;
        } else {
            return false;
        }
    }

    function checkType(int $opnum) {
        if (preg_match("/^(int|string|bool)$/",$this->operands[$opnum])) {
            return true;
        } else {
            return false;
        }
    }

    // function isIntType (int $opnum) {
    //     if ( preg_match("/int@-{0,1}[0-9]*/",$this->operands[$opnum]) == 1) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    // function isBoolType (int $opnum) {
    //     if ( preg_match("/bool@(true|false)/",$this->operands[$opnum] == 1)) {
    //         # code...
    //     }
    // }
}



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