<?php session_start();

function delete_entry($name){
        if ($_SESSION['LOGGED_IN']){
            $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
            $pwd = htmlspecialchars(pg_escape_string($_SESSION['PASSWORD']));

            $db_name = 'wikidb'; 
            $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");

            if ($con)
            { 
            	    $result = pg_prepare($con, "check_entry", 'SELECT * FROM Entries WHERE entryname = $1');
            	    $result = pg_execute($con, "check_entry", array($name));

            	    if ($result){
            		$result = pg_prepare($con, "del_entry", 'DELETE FROM Entries WHERE entryname=$1');
            		$result = pg_execute($con, "del_entry", array($name));
            	    }
		pg_free_result($result);
		pg_close($con);
            }	
        }
}
delete_entry($_POST['name']);
?>
