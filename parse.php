<?php
/**
 * IPP Project 2020/21
 * Parsing code in IPPcode21. Create XML file.
 * @author Kostiantyn Krukhmalov
*/

include 'parse_lib/scanner.php';
ini_set('display_errors', 'stderr'); // Pro výpis varování na standardní chybový výstup
check_options();

const ERROR_MISS_PARAM = 10;
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


check_options();
$line = new lineClass;
if ($line->elements == false) { // test for empty file
    exit(ERROR_SYNTAX_LEX);
} else if ( ($line->cnt() != 1) or (strcmp($line->elements[0], ".IPPcode21") != 0) ) { // test for bad header or its missing
    exit(ERROR_HEADER);
}

while (($line->nextLine())) {
    
    if (!$line->searcOpCode()) {
        exit(ERROR_OPCODE);
    }

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
        }
        echo $options["stats"],"\n";
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