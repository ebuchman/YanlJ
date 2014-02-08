<script type="text/javascript">
function get_entry_data(name){
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            document.getElementById('entry_content_box').innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST", "load_content.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("name="+name);
    return false;
}
</script>

<div id="entry_content_box" style="position:fixed; top:400px; left:300px;">


</div>


<?php

function load_recent_posts(){
    if ($_SESSION['LOGGED_IN']){
        $usr = $_SESSION['USR_NAME'];
        $pwd = $_SESSION['PASSWORD'];
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

/*
                <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post">	
                <input type="hidden" name="entry" value="<?php echo $row[0]; ?>"><p>
                <input type="submit" name="<?php echo $row[0]; ?>" value="<?php echo $row[0]; ?>"></form> <?php 
 */



function load_new_entry_form(){?>

        <div>
        <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" style="position:fixed; top:100px; left:300px;"><p>
        <h4>Add a new entry below:</h4>
        Entry Name: <input type="text" name="entry_name"><p>
        Entry Content: <textarea  name="entry_content" rows=5 cols=50></textarea><p>
        <input type="submit" name="new_entry" value="Add Entry"></form>
        </div>
<?php }


function add_new_entry($con){
    if ($_POST['new_entry']){
        if ($con)
        { 
            $name = htmlspecialchars($_POST['entry_name']);
            $content = htmlspecialchars($_POST['entry_content']);
            $sql = "INSERT INTO Entries VALUES ('$name', '$content')";
            $result = pg_query($con, $sql);
        }	
    }
}

function delete_entry($entry_name){
            echo "<form id=\"delete_entry\" action=\"\" method=\"post\" >
               <a href=\"javascript:;\" onClick=\"document.getElementById('delete_entry').submit();\">delete</a>
               <input type=\"hidden\" name=\"entry_to_delete\" value=\"".$entry_name."\">
                </form>";
}

function edit_delete(){
    if ($_POST['entry_to_delete']){
        if ($_SESSION['LOGGED_IN']){
            $usr = $_SESSION['USR_NAME'];
            $pwd = $_SESSION['PASSWORD'];
            $db_name = 'wikidb'; 

            $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
            if ($con)
            { 
                $sql = "DELETE FROM Entries WHERE entryname='".$_POST['entry_to_delete']."';";
                $result = pg_query($con, $sql);

                pg_free_result($result);
                pg_close($con);
            }
        }



    }
}


?>

<?php 

// authorization functions

function check_login(){
    if ($_POST['login']){
      $usr = pg_escape_string($_POST['usr']);
      $pwd = pg_escape_string($_POST['pwd']);
      // is this a bad idea?
      $_SESSION['USR_NAME'] = $usr;
      $_SESSION['PASSWORD'] = $pwd;

      $db_name = 'wikidb'; 
      $con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");

      if ($con)
       $_SESSION['LOGGED_IN'] = 1;
    }
}
function check_logout(){
    if ($_POST['exit']){ 	
       $_SESSION['LOGGED_IN'] = 0;
       session_destroy();
    }
    if (!isset($_SESSION['USR_NAME']))
      $_SESSION['USR_NAME']=NULL;

    if (!isset($_SESSION['PASSWORD']))
      $_SESSION['PASSWORD']=NULL;
    }

function present_login(){
    if(!$_SESSION['LOGGED_IN']) : ?>
        <h3>Please log in to use the wiki</h3>

        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
          Username: <input type="text" name="usr">
          <p>
          Password: <input type="password" name="pwd">
          <p>
          <input type="submit" name="login" value="Login">
        </form> 
    <?php else :
        echo "<h3 style='position:fixed; top:50px; left:100; padding:10px;'>Hello, " .  $_SESSION['USR_NAME'] . ".  Enjoy using YanlJ</h3>" ;
    endif; 
}
?>




