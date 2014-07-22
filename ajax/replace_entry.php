<?php 

function add_entry($old_title, $new_title, $content, $con){
    $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
    // we should do some validating here!  note html cleaning happens on the way out.  otherwise, we couldnt do math...
    if(preg_match('/[^a-z_ \-0-9]/i', $new_title)){
        echo "<div id='bad_name'><h3>Names may only contain alphanumeric characters and the underscore</h3></div>";
    }
    else{
        $result = pg_prepare($con, "check_entry", 'SELECT * FROM Entries WHERE entry_name = $1');
        $result = pg_execute($con, "check_entry", array($old_title));

        if ($result){
        // can we update entries instead of replacing them?
            $result = pg_prepare($con, "del_entry", 'DELETE FROM Entries WHERE entry_name=$1');
            $result = pg_execute($con, "del_entry", array($old_title));
            pg_free_result($result);
        }

        $result = pg_prepare($con, "add_entry", 'INSERT INTO entries VALUES ($1, $2, $3)');
        $result = pg_execute($con, "add_entry", array($new_title, $content, $usr));
        pg_free_result($result);
        echo json_encode(array("name"=>$new_title, "content"=>$content));
    }
}

add_entry($_POST['old_title'], $_POST['new_title'], $_POST['content'], $con);
?>
