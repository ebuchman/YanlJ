<?php

include_once('wiki.php');

$path = 'ajax/';
if (isset($_POST['form'])){
    // if logged in...
    session_start(); 
    $con=connect_db();
	switch ($_POST['form']){
	  case 'load_content':
	    include $path."load_content.php";
	    break;
	  case 'edit_entry_content':
	    include $path."edit_entry_content.php";
	    break;
	  case 'replace_entry':
	    include $path."replace_entry.php";
	    break;
	  case 'search_db':
	    include $path."search_db.php";
	    break;
	  case 'delete_entry':
	    include $path."delete_entry.php";
	    break;
	  case 'register_user':
	    include $path."register_user.php";
	    break;
	  default:
	    include 'index.inc';
	    break;
	}
    pg_close($con);
}
else {
	include 'index.inc';
}



?>
