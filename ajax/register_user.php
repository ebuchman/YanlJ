<?php session_start(); include_once('../wiki.php');

function new_user($name, $pwd, $email){
            $usr = htmlspecialchars(pg_escape_string($_SESSION['USR_NAME']));
            //$pwd = htmlspecialchars(pg_escape_string($_SESSION['PASSWORD']));
            //
            if (($con=connect_db('../auth.txt')))
            { 
                // we should do some validating here!  note html cleaning happens on the way out.  otherwise, we couldnt do math...
                if(preg_match('/[^a-z_ \-0-9]/i', $name)){
              	    echo "<div id='bad_name'><h3>Names may only contain alphanumeric characters and the underscore</h3></div>";
                }
                else{
            	    $result = pg_prepare($con, "register_user", 'INSERT INTO users VALUES ($1, $2, $3, $4)');
            	    $result = pg_execute($con, "register_user", array($name, $pwd, '00000000', $email));
		            pg_free_result($result);
		            pg_close($con);
		            echo json_encode(array("name"=>$name, "email"=>$email));
                }
            }	
            else{
                echo json_encode(array("name"=>"oops", "content"=>"fucjed up"));
            }
}

new_user($_POST['name'], $_POST['pwd'], $_POST['email']);

?>
