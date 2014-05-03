<?php

// page content functions

function load_recent_posts(){
    if (htmlspecialchars($_SESSION['LOGGED_IN'])){
        $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
        $pwd = htmlspecialchars(pg_escape_string($_SESSION['PASSWORD']));

        $db_name = 'wikidb'; 

        $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
        if ($con)
        { 
            //add_new_entry($con);

            $sql = "SELECT * FROM Entries";
            $result = pg_query($con, $sql);	?>

            <?php while ($row = pg_fetch_array($result)) { 
                $this_name = htmlspecialchars($row[0]);
		echo "<div class=\"post\">";
                echo "<p><a href=\"#/\" id=\"" . $this_name . "_entry_link\" onclick=\"get_entry_data('" . addslashes($this_name) . "');\" >" .  $this_name . " </a> </p>";
		echo "</div>";
             }

            pg_free_result($result);
            pg_close($con);
        }
    }
}

function load_new_entry_form(){?>

        <div class="new_entry_div">
		<div class="entry">
			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post"><p>
			<h4>Add a new entry below:</h4>
			Entry Name: <input id="new_entry_name_box" type="text" name="entry_name"><p>
			Entry Content: <textarea  id="new_entry_content_box" name="entry_content" rows=5 cols=50></textarea><p>
			<input type="submit" name="new_entry" value="Add Entry"></form>
		</div>
        </div>
<?php }

// database functions

function add_new_entry($con){
    if (isset($_POST['new_entry'])){

        if ($con)
        { 
            $name = $_POST['entry_name'];
            $content = $_POST['entry_content'];

	    // we should do some validating here!  note html cleaning happens on the way out.  otherwise, we couldnt do math...
	    if(preg_match('/[^a-z_\-0-9]/i', $name)){
		echo "<div id='bad_name'><h3>Names may only contain alphanumeric characters and the underscore</h3></div>";
	    }
	
	    else{
		    $result = pg_prepare($con, "check_entry", 'SELECT * FROM Entries WHERE entryname = $1');
		    $result = pg_execute($con, "check_entry", array($name));

		    if ($result){

			// can we update entries instead of replacing them?

			// note this prepared statement is already built in another function!
			$result = pg_prepare($con, "del_entry", 'DELETE FROM Entries WHERE entryname=$1');
			$result = pg_execute($con, "del_entry", array($name));

		    }

		    $result = pg_prepare($con, "add_entry", 'INSERT INTO Entries VALUES ($1, $2)');
		    $result = pg_execute($con, "add_entry", array($name, $content));
	    }
        }	
    }
}


function delete_entry(){
    if (isset($_POST['entry_to_delete'])){
        if (htmlspecialchars($_SESSION['LOGGED_IN'])){
            $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
            $pwd = htmlspecialchars(pg_escape_string($_SESSION['PASSWORD']));
            $db_name = 'wikidb'; 

            $entry_to_delete = $_POST['entry_to_delete'];

            $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
            if ($con)
            { 
		$result = pg_prepare($con, "del_entry", 'DELETE FROM Entries WHERE entryname=$1');
		$result = pg_execute($con, "del_entry", array($entry_to_delete));

                pg_free_result($result);
                pg_close($con);
            }
        }
    }
}

// authorization functions

function check_login(){
    if (isset($_POST['login'])){
      $usr = htmlspecialchars(pg_escape_string($_POST['usr']));
      $pwd = htmlspecialchars(pg_escape_string($_POST['pwd']));

      // some shitty validation
      if (count($usr) > 10 or count($pwd) > 15)
	echo "Incorrect Credentials";
      else{
	      $db_name = 'wikidb'; 
	      // use the @ to suppress php/psql error message
	      $con = @pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
	     
	      if ($con){
	       $_SESSION['LOGGED_IN'] = 1;
	       
	       // this is a bad idea... pwd is too open...
	       $_SESSION['USR_NAME'] = $usr;
	       $_SESSION['PASSWORD'] = $pwd;
	      }
	      else echo "Incorrect Credentials.";
	    }
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
    if(!htmlspecialchars($_SESSION['LOGGED_IN'])) : ?>
	<div class="loginbox content_unit">
		<h3>Welcome <?php echo htmlspecialchars($_SERVER['REMOTE_ADDR']) ?>. Please log in to use the wiki</h3>

		<?php // Note: $_SERVER['PHP_SELF'] comes from the url and is an attack vector.  Must be escaped.  Or, just use $_SERVER['SCRIPT_NAME'] ?>
		<form action="<?php htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>" method="post">
		  Username: <input type="text" name="usr">
		  <p>
		  Password: <input type="password" name="pwd">
		  <p>
		  <input type="submit" name="login" value="Login">
		</form> 
	</div>
    <?php else :
 	$a = 3;
	//        echo "<h3 id='hello'>Hello, " .  htmlspecialchars($_SESSION['USR_NAME']) . ".  Enjoy using YanlJ</h3>" ;
    endif; 
}

function https(){ 
 if ($_SERVER['HTTPS'] != 'on') {
    echo "<h2 class='https'>YanlJ is only accessible over a secure connection.  Try <a href=https://".htmlspecialchars($_SERVER['HTTP_HOST']).">here</a></h2>" ;
    die();
 }
     
}


?>
