<?php session_start(); include_once('../wiki.php');

function new_user($name, $pwd, $email){
            if (($con=connect_db('../auth.txt')))
            { 
                // we should do some validating here!  note html cleaning happens on the way out.  otherwise, we couldnt do math...
                if(preg_match('/[^a-z_ \-0-9]/i', $name)){
              	    echo "<div id='bad_name'><h3>Names may only contain alphanumeric characters and the underscore</h3></div>";
                }
                else{
		    $salt = bin2hex(openssl_random_pseudo_bytes(32));
		    $salted = $pwd.$salt;
		    // note it will generate a salt for us as well and store it within the result. 
		    $pwd = password_hash($salted, PASSWORD_DEFAULT);
            	    $result = pg_prepare($con, "register_user", 'INSERT INTO users VALUES ($1, $2, $3, $4, $5, $6)');
            	    $result = pg_execute($con, "register_user", array($name, $pwd, $salt, $email, null, null));
		    pg_free_result($result);
		    pg_close($con);
		    echo json_encode(array("name"=>$name, "email"=>$email, "salted"=>$salVted));
                }
            }	
            else{
                echo json_encode(array("name"=>"oops", "content"=>"fucjed up"));
            }
}

new_user($_POST['name'], $_POST['pwd'], $_POST['email']);

?>
