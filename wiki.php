<?php

// page content functions

function load_recent_posts(){
    if (1 || htmlspecialchars($_SESSION['LOGGED_IN'])){
        $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
        //$pwd = htmlspecialchars(pg_escape_string($_SESSION['PASSWORD']));
    
        $db_name = 'wikidb'; 

        //$con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
        if (($con = connect_db('auth.txt')))
        { 
            $sql = "SELECT * FROM Entries";
            $result = pg_query($con, $sql);
            while ($row = pg_fetch_array($result)) { 
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

// database functions

function add_new_entry($con){
    if (isset($_POST['new_entry'])){
        if ($con)
        { 
            $name = $_POST['entry_name'];
            $content = $_POST['entry_content'];

	    // we should do some validating here!  note html cleaning happens on the way out.  otherwise, we couldnt do math...
	    if(preg_match('/[^a-z_\-0-9]/i', $name))
		echo "<div id='bad_name'><h3>Names may only contain alphanumeric characters and the underscore</h3></div>";
	    else{
		    $result = pg_prepare($con, "check_entry", 'SELECT * FROM Entries WHERE entry_name = $1');
		    $result = pg_execute($con, "check_entry", array($name));
		    if ($result){
			// can we update entries instead of replacing them?
			// note this prepared statement is already built in another function!
			$result = pg_prepare($con, "del_entry", 'DELETE FROM Entries WHERE entry_name=$1');
			$result = pg_execute($con, "del_entry", array($name));
		    }
		    $result = pg_prepare($con, "add_entry", 'INSERT INTO Entries VALUES ($1, $2)');
		    $result = pg_execute($con, "add_entry", array($name, $content));
	    }
        }	
    }
}

// authorization functions

function connect_db($path){
  $db_name = 'wikidb'; 
  // use the @ to suppress php/psql error message
  $lines = file($path);
  $master_usr = $lines[0];
  $master_pwd = $lines[1];

  $con = @pg_connect("host=localhost dbname=wikidb user=$master_usr password=$master_pwd");
  return $con;
}

function check_login(){
    if (isset($_POST['login'])){
      $usr = htmlspecialchars(pg_escape_string($_POST['usr']));
      $pwd = htmlspecialchars(pg_escape_string($_POST['pwd']));

      // some shitty validation
      if (count($usr) > 10 or count($pwd) > 15)
	echo "Incorrect Credentials";
      else{
	     
	      if (($con = connect_db('auth.txt'))){
            	    $result = pg_prepare($con, "check_login", 'SELECT * FROM users WHERE name = $1');
            	    $result = pg_execute($con, "check_login", array($usr));

            	    if ($result){
			$user_data = pg_fetch_all($result)[0];
			pg_free_result($result);
			pg_close($con);
			if ($user_data != NULL){
				$stored_pwd = $user_data['pwdhash'];
				$nonce = $user_data['salt'];
				if ($stored_pwd == password_hash($pwd.$nonce, PASSWORD_DEFAULT)){
				       $_SESSION['LOGGED_IN'] = 1;
				       
				       $_SESSION['USR_NAME'] = $usr;
				}
				else echo "Incorrect Credentials.";
			}
			else echo "Incorrect Credentials.";
            	    }
	      }
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
    // Note: $_SERVER['PHP_SELF'] comes from the url and is an attack vector.  Must be escaped.  Or, just use $_SERVER['SCRIPT_NAME']
    if(!htmlspecialchars($_SESSION['LOGGED_IN'])) : ?>
	<div class="loginbox content_unit">
		<h3>Welcome <?php echo htmlspecialchars($_SERVER['REMOTE_ADDR']) ?>. Please log in to use the wiki</h3>

		<form id="login_form" action="<?php htmlspecialchars($_SERVER['SCRIPT_NAME']); ?>" method="post">
		  Username: <input type="text" name="usr">
		  <p>
		  Password: <input type="password" name="pwd">
		  <p>
          <div id="signup_form">
          </div>
		  <input id="login_btn" type="submit" name="login" value="Login">
		</form> 
        <div id="signup_btn_div">
            <input id="signup_btn" type="button" name="signup" value="Sign Up" onclick="present_signup();" >
        </div>
	</div>
    <?php
    endif; 
}

function https(){ 
 if ($_SERVER['HTTPS'] != 'on') {
    echo "<h2 class='https'>YanlJ is only accessible over a secure connection.  Try <a href=https://".htmlspecialchars($_SERVER['HTTP_HOST']).">here</a></h2>" ;
    die();
 }
}

?>
