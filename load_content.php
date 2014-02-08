<?php session_start();

include("wiki.php");

function load_entry_content($entry_name){
    if ($_SESSION['LOGGED_IN']){
        $usr = $_SESSION['USR_NAME'];
        $pwd = $_SESSION['PASSWORD'];
        $db_name = 'wikidb'; 


        $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
        if ($con)
        { 
            echo "<h4>" . $entry_name . "</h4>";
            echo "<p>";
            $sql = "SELECT * FROM Entries WHERE entryname = '$entry_name'";
            $result = pg_query($con, $sql);
            while($row = pg_fetch_array($result))
                echo $row[1];

            pg_free_result($result);
            pg_close($con);

            delete_entry($entry_name);


        }
    }
}
load_entry_content($_POST['name']);

?>
