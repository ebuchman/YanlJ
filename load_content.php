<?php session_start();

function load_entry_content($entry_name){
    $escaped_entry_name = htmlspecialchars(pg_escape_string($entry_name));
    $_GLOBAL['current_entry'] = $escaped_entry_name;

    if ($_SESSION['LOGGED_IN']){
        $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
        $pwd = htmlspecialchars(pg_escape_string($_SESSION['PASSWORD']));
        $db_name = 'wikidb'; 

        $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
        if ($con)
        { 
            $sql = "SELECT * FROM Entries WHERE entryname = '$escaped_entry_name'";
            $result = pg_query($con, $sql);
            while($row = pg_fetch_array($result))
                echo htmlspecialchars($row[1]);
            pg_free_result($result);
            pg_close($con);
        }
    }
}
load_entry_content(htmlspecialchars($_POST['name']));
?>
