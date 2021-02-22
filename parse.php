<?php
ini_set('display_errors', 'stderr'); 
while ($f = fgets(STDIN)) {
    echo "line: $f";
}
?>