<?php 

function search_db($keystrokes, $con){
    $escaped_keystrokes = htmlspecialchars(pg_escape_string($keystrokes));

    $result = pg_prepare($con, "search", 'SELECT entry_name FROM Entries WHERE entry_name LIKE ($1)');
    $result = pg_execute($con, "search", array($escaped_keystrokes."%"));
           
    echo "<ul class=\"search_list\">";
    for($i=0; ($row=@pg_fetch_result($result, $i, 'entry_name')); $i++){
        $row = htmlspecialchars($row);
        echo "<li><a href=\"#/\" onClick=\"get_entry_data('" . addslashes($row) . "');\" >" .  $row . " </a> </li>";
    }
    echo "<ul>";

    pg_free_result($result);
		
}

search_db($_POST['keystrokes'], $con);


?>
