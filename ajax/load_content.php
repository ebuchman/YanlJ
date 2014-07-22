<?php session_start(); include_once('wiki.php');


function insert_links($content, $con){
    preg_match_all('#\[\[(.+?)\]\s\[(.+?)\]\]#', $content, $matches);

    $names = $matches[1];
    $clickables = $matches[2];

    foreach ($names as $i => $n){
	//check if name in database
	$result = pg_prepare($con, "check_content", 'SELECT count(1) FROM Entries WHERE entry_name = $1');
	$result = pg_execute($con, "check_content", array($n));
	$exists = pg_fetch_array($result)[0];
	
	pg_free_result($result);

	//replace position in content with clickable
	if ($exists == 1){
	    $clickable = "<a href=\"#/\" onClick=\"get_entry_data('" . addslashes($n) . "');\" >" .  $clickables[$i] . " </a>";
	    $content = preg_replace("#\[\[$n\]\s\[$clickables[$i]\]\]#", $clickable, $content);
	} else {
	    $unclickable = $clickables[$i];
	    $content = preg_replace("#\[\[$n\]\s\[$clickables[$i]\]\]#", $unclickable, $content);
	}
    } 
    return $content;
}

// convert database entry to bubble (ie display links, replace new lines)
function db2bubble($content, $no_click, $owner, $con){
    $content = insert_links($content, $con);
    $content = str_replace("\n", "<br/>", $content);
    echo json_encode(array("content"=>$content, "no_click"=>$no_click, "owner"=>$owner));	
}

function load_entry_content($entry_name){
  //  $escaped_entry_name = htmlspecialchars(pg_escape_string($entry_name));
    $escaped_entry_name = $entry_name;

    $_GLOBAL['current_entry'] = $escaped_entry_name;

    if (1 || $_SESSION['LOGGED_IN']){
        $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
        //$pwd = htmlspecialchars(pg_escape_string($_SESSION['PASSWORD']));
        if (($con=connect_db('../auth.txt')))
        { 
	    //load_prepared_statements($con);
            $result = pg_prepare($con, "get_content", 'SELECT * FROM Entries WHERE entry_name = $1');
            $result = pg_execute($con, "get_content", array($escaped_entry_name));

            $row = pg_fetch_array($result);
            pg_free_result($result);
            
            // output ! Data is html cleaned as soon as it comes out of the database
            // the only HTML/script that should make it to the page is what follows, 
            // where entries are linked to eachother
            // however, we also return the entries without html protection, so the real text can be loaded into the edit space (which isn't rendered as html :D).
            $content = htmlspecialchars($row[1]);
            $no_click = $row[1];
            $owner= $row[2] == $usr;
            
            // find refs to other content and replace them with a link
	    db2bubble($content, $no_click, $owner, $con);
            pg_close($con);
        }
    }
}
load_entry_content($_POST['name']);

?>
