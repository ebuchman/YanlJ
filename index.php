<?php session_start(); if (!isset($_SESSION['LOGGED_IN'])) $_SESSION['LOGGED_IN'] = 0;?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>YanlJ, a non-linear JanlY.</title>
<script type="text/javascript" src=scripts.js></script>
<script type="text/javascript" src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> </script>
<script type="text/x-mathjax-config"> //MathJax.Hub.Config({ tex2jax: {inlineMath: [['$','$'], ['\\(','\\)'], processEscapes: true}}); </script>
</head>

<body>
<?php include("wiki.php"); ?>
<?php https(); ?>
<h1>YanlJ, a non-linear JanlY</h1>
<?php check_login(); check_logout(); present_login() ?>

<?php 
    if (htmlspecialchars($_SESSION['LOGGED_IN'])){
        delete_entry();
        load_recent_posts();
        load_new_entry_form();
    }
?>


<div id=content_unit style="position:fixed; top:400px; left:300px;">

	<div id="big_content_box">
	    <div id="content_header"></div>
	    <div id="entry_content_box"></div>
	</div>
	<p>
	<div id="edit_delete_links" style="position:static">
	    <form id="delete_entry_form" style="display:inline-block;"  action="" method="post" >
		<a id="delete_link"></a> 
		<input id="delete_name_input" type="hidden" name="entry_to_delete">
	    </form>
	    <a id="edit_link"></a>
	</div>
</div>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" style="position:fixed; top:20px; right:100px;"><p>	
<input type="submit" name="exit" value="Logout">
</form>

</body>
</html>
