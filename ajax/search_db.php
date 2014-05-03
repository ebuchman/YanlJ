<?php session_start();


function search_db($keystrokes){
    $escaped_keystrokes = htmlspecialchars(pg_escape_string($keystrokes));

    if ($_SESSION['LOGGED_IN']){
        $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
        $pwd = htmlspecialchars(pg_escape_string($_SESSION['PASSWORD']));

        $db_name = 'wikidb'; 
        $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
        if ($con)
        { 
	    $result = pg_prepare($con, "search", 'SELECT entryname FROM Entries WHERE entryname LIKE ($1)');
	    $result = pg_execute($con, "search", array($escaped_keystrokes."%"));
   	    	   
	    echo "<ul class=\"search_list\">";
 	    for($i=0; ($row=pg_fetch_result($result, $i, 'entryname')); $i++){
		$row = htmlspecialchars($row);
                echo "<li><a href=\"#/\" onClick=\"get_entry_data('" . addslashes($row) . "');\" >" .  $row . " </a> </li>";
	    }
	    echo "<ul>";

            pg_free_result($result);
            pg_close($con);
		
        }
    }
}

search_db($_POST['keystrokes']);


?>
