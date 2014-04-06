<?php session_start();

function load_entry_content($entry_name){
  //  $escaped_entry_name = htmlspecialchars(pg_escape_string($entry_name));
    $escaped_entry_name = $entry_name;

    $_GLOBAL['current_entry'] = $escaped_entry_name;

    if ($_SESSION['LOGGED_IN']){
        $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
        $pwd = htmlspecialchars(pg_escape_string($_SESSION['PASSWORD']));

        $db_name = 'wikidb'; 
        $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
        if ($con)
        { 
	    $result = pg_prepare($con, "get_content", 'SELECT * FROM Entries WHERE entryname = $1');
	    $result = pg_execute($con, "get_content", array($escaped_entry_name));

            $row = pg_fetch_array($result);
	    $content = htmlspecialchars($row[1]);

	    // find refs to other content and replace them with a link
	    preg_match_all('#\[\[(.+?)\]\s\[(.+?)\]\]#', $content, $matches);

	    $names = $matches[1];
	    $clickables = $matches[2];

	    $no_click = $content;

	    foreach ($names as $i => $n){
		//check if name in database
		$result = pg_prepare($con, "check_content", 'SELECT count(1) FROM Entries WHERE entryname = $1');
		$result = pg_execute($con, "check_content", array($n));
		$exists = pg_fetch_array($result)[0];
		

		//replace position in content with clickable
		if ($exists == 1){
                	$clickable = "<a href=\"#\" onClick=\"get_entry_data('" . addslashes($n) . "');\" >" .  $clickables[$i] . " </a>";
			$content = preg_replace("#\[\[$n\]\s\[$clickables[$i]\]\]#", $clickable, $content);
		}
	    } 
	    echo json_encode(array("content"=>$content, "no_click"=>$no_click));	
	    

            pg_free_result($result);
            pg_close($con);
        }
    }
}
load_entry_content($_POST['name']);
?>
