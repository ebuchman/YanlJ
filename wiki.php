<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head><title>YanlJ, a non-linear JanlY.</title></head>
<body>
<h1>Welcome to YanlJ, A Non-Linear JanlY.</h1>

<?php 

if (!isset($_SESSION['USR_NAME']))
  $_SESSION['USR_NAME']=NULL;

if (!isset($_SESSION['PASSWORD']))
  $_SESSION['PASSWORD']=NULL;

?>

<?php
if ($_POST['login'] || $_SESSION['LOGGED_IN'])
{

	if ($_POST['login']){
	  $usr = pg_escape_string($_POST['usr']);
	  $pwd = pg_escape_string($_POST['pwd']);
	  $_SESSION['USR_NAME'] = $usr;
	  $_SESSION['PASSWORD'] = $pwd;
	}
	else{
 	  $usr = $_SESSION['USR_NAME'];
	  $pwd = $_SESSION['PASSWORD'];
	}
	$db_name = 'wikidb'; 


	$con = pg_connect("host=localhost dbname=wikidb user=$usr password=$pwd");
	if ($con)
	{ 
	  	$_SESSION['LOGGED_IN'] = 1;

		$sql = "SELECT * FROM Entries";
		$result = pg_query($con, $sql);

		while ($row = pg_fetch_array($result))
		{?>
			<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post"><p>	
			<input type="hidden" name="entry" value="<?php echo $row[0]; ?>"><p>
			<input type="submit" name="<?php echo $row[0]; ?>" value="<?php echo $row[0]; ?>"></form>

	<?php } 
		if ($_POST['entry'])
		{
			$entry = $_POST['entry'];
			echo $entry;
			echo "<p>";
			$sql = "SELECT * FROM Entries WHERE entryname = '$entry'";
			$result = pg_query($con, $sql);
			while($row = pg_fetch_array($result))
				echo $row[1];
		}

	?>
	<h4>Add a new entry below:</h4>
	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post"><p>	
	Entry Name: <input type="text" name="entry_name"><p>
	Entry Content: <textarea  name="entry_content" rows=5 cols=50></textarea><p>
	<input type="submit" name="new_entry" value="Add Entry"></form>
	<?php

	if ($_POST['new_entry']){
		$name = htmlspecialchars($_POST['entry_name']);
		$content = htmlspecialchars($_POST['entry_content']);
		$sql = "INSERT INTO Entries VALUES ('$name', '$content')";
		$result = pg_query($con, $sql);

	}
		
	pg_free_result($result);
	pg_close($con);


}	
}?>

<?php if(!$_SESSION['LOGGED_IN']) : ?>
	<h3>Please log in to use the wiki</h3>

	<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
  	  Username: <input type="text" name="usr">
	  <p>
	  Password: <input type="password" name="pwd">
	  <p>
	  <input type="submit" name="login" value="Login">
	</form> 
<?php else : ?>
	<?php echo "<h3>Welcome, " .  $_SESSION['USR_NAME'] . ".  Enjoy using YanlJ</h3>" ;?>
<?php endif; ?>


<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post"><p>	
<input type="submit" name="exit" value="Logout"></form>

<?php 
if ($_POST['exit']) session_destroy();?>

