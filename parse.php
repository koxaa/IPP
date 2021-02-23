<?php
ini_set('display_errors', 'stderr'); // Pro výpis varování na standardní chybový výstup
global $argv;


/******* Hlavní scénář *******/
check_options();
check_syntax();


/********* Funkce *********/
function check_syntax() {

    $new_line = fgets(STDIN);
    var_dump($new_line);
    
}

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
    
    } elseif (array_key_exists("stats", $options)) {

        if (!$options["stats"]) {
            fwrite(STDERR,"Missing an argument for parament --stats\n");
            exit(10);
        }
        // TODO
        echo $options["stats"],"\n";
    }
}

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