<?php
ini_set('display_errors', 'stderr'); // Pro výpis varování na standardní chybový výstup
global $argv;
/******* Hlavní scénář *******/
check_options();

/********* Funkce *********/

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

function print_help_msg() {
    $path = pathinfo('./');

    echo "Usage: ",$path['filename'],"  ARCHIVE  TESTDIR [TASKNUM]\n";
    echo "    Skript typu filtr (parse.php v jazyce PHP 7.4)\n";
    echo "      načte ze standardního vstupu zdrojový kód v IPP-code21,\n";
    echo "      zkontroluje lexikální a syntaktickou správnost kódu\n";
    echo "      a vypíše na standardní výstup XML reprezentaci programu.\n";
}

?>