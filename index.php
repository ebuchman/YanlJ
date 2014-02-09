<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>YanlJ, a non-linear JanlY.</title>
</head>

<body>
<?php if ($_SERVER['HTTPS'] !== 'on') {
    die("Must be a secure connection."); }?>

<?php include("wiki.php"); ?>

<h1>YanlJ, a non-linear JanlY</h1>

<?php check_login(); check_logout(); present_login() ?>

<?php 
    if ($_SESSION['LOGGED_IN']){
        edit_delete();
        load_recent_posts();
        load_new_entry_form();
    }
?>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" style="position:fixed; top:20px; right:100px;"><p>	
<input type="submit" name="exit" value="Logout"></form>

</body>
</html>
