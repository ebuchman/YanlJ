<?php
function load_recent_posts(){
    if (htmlspecialchars($_SESSION['LOGGED_IN'])){
        $usr = pg_escape_string($_SESSION['USR_NAME']);
        $pwd = pg_escape_string($_SESSION['PASSWORD']);
        $db_name = 'wikidb'; 

        $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
        if ($con)
        { 
            add_new_entry($con);
            $sql = "SELECT * FROM Entries";
            $result = pg_query($con, $sql);
            ?>
            <div style="position:fixed; top:100px; left:50px;">
            <?php while ($row = pg_fetch_array($result)) { 
                $this_name = $row[0];
                echo "<p><a href=\"#\" onClick=\"get_entry_data('" . $this_name . "');\" >" .  $this_name . " </a> </p>";
             }

            pg_free_result($result);
            pg_close($con);
        }
    }
}


function load_new_entry_form(){?>

        <div>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" style="position:fixed; top:100px; left:300px;"><p>
        <h4>Add a new entry below:</h4>
        Entry Name: <input id="new_entry_name_box" type="text" name="entry_name"><p>
        Entry Content: <textarea  id="new_entry_content_box" name="entry_content" rows=5 cols=50></textarea><p>
        <input type="submit" name="new_entry" value="Add Entry"></form>
        </div>
<?php }


function add_new_entry($con){
    if (isset($_POST['new_entry'])){

        if ($con)
        { 
            $name = htmlspecialchars($_POST['entry_name']);
            $content = htmlspecialchars($_POST['entry_content']);
            if (pg_query($con, "SELECT * FROM Entries WHERE entryname = '$name'")){
                /* couldnt get the UPDATE working...
                echo $content;
                $sql = "UPDATE Entries SET entry='$content' WHERE entryname='$name'";
                $result = pg_query($con, $sql);
                echo pg_fetch_row($result);
                 */
                $sql="DELETE FROM Entries WHERE entryname= '$name'";
                $result = pg_query($con, $sql);
            }
            //else{

                $sql = "INSERT INTO Entries VALUES ('$name', '$content')";
                $result = pg_query($con, $sql);
            //}
        }	
    }
}


function edit_delete(){
    if (isset($_POST['entry_to_delete'])){
        if (htmlspecialchars($_SESSION['LOGGED_IN'])){
            $usr = pg_escape_string($_SESSION['USR_NAME']);
            $pwd = pg_escape_string($_SESSION['PASSWORD']);
            $db_name = 'wikidb'; 

            $entry_to_delete = pg_escape_string($_POST['entry_to_delete']);

            $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
            if ($con)
            { 
                $sql = "DELETE FROM Entries WHERE entryname='". $entry_to_delete ."';";
                $result = pg_query($con, $sql);

                pg_free_result($result);
                pg_close($con);
            }
        }
    }
}

// authorization functions

function check_login(){
    if (isset($_POST['login'])){
      $usr = pg_escape_string($_POST['usr']);
      $pwd = pg_escape_string($_POST['pwd']);
      // is this a bad idea?
      $_SESSION['USR_NAME'] = $usr;
      $_SESSION['PASSWORD'] = $pwd;

      $db_name = 'wikidb'; 
      // use the @ to suppress php/psql error message
      $con =@pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
     
      if ($con)
       $_SESSION['LOGGED_IN'] = 1;
      else echo "Incorrect Credentials.";
    }
}
function check_logout(){
    if (isset($_POST['exit'])){ 	
       $_SESSION['LOGGED_IN'] = 0;
       $_SESSION['current_entry'] = 0;
       session_destroy();
    }
    if (!isset($_SESSION['USR_NAME']))
      $_SESSION['USR_NAME']=NULL;

    if (!isset($_SESSION['PASSWORD']))
      $_SESSION['PASSWORD']=NULL;
    }

function present_login(){
    if(!$_SESSION['LOGGED_IN']) : ?>
        <h3>Welcome <?php echo htmlspecialchars($_SERVER['REMOTE_ADDR']) ?>. Please log in to use the wiki</h3>

        <?php // Note: $_SERVER['PHP_SELF'] comes from the url and is an attack vector.  Must be escaped.  Or, just use $_SERVER['SCRIPT_NAME'] ?>
        <form action="<?php htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>" method="post">
          Username: <input type="text" name="usr">
          <p>
          Password: <input type="password" name="pwd">
          <p>
          <input type="submit" name="login" value="Login">
        </form> 
    <?php else :
        echo "<h3 style='position:fixed; top:50px; left:100; padding:10px;'>Hello, " .  htmlspecialchars($_SESSION['USR_NAME']) . ".  Enjoy using YanlJ</h3>" ;
    endif; 
}

function https(){ 
 if (!isset($_SERVER['HTTPS'])) {
    echo "<h2>YanlJ is only accessible over a secure connection.  Try <a href=https://".htmlspecialchars($_SERVER['HTTP_HOST']).">here</a></h2>" ;die(); }
     
}

?>
