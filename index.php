<?php session_start(); if (!isset($_SESSION['LOGGED_IN'])) $_SESSION['LOGGED_IN'] = 0;?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>YanlJ, a non-linear JanlY.</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src=scripts.js></script>
<script type="text/javascript" src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> </script>
<script type="text/x-mathjax-config"> //MathJax.Hub.Config({ tex2jax: {inlineMath: [['$','$'], ['\\(','\\)'], processEscapes: true}}); </script>
<noscript>
	<style type="text/css">
		.pagecontainer{display:none;}
	</style>
	<h1> It appears javascript is disabled!</h1>
 	<h1>YanlJ uses asynchronous javascript requests to load content from the server without re-loading the page.</h1>
	<h1>YanlJ does not use any third-party javascript libraries.</h1>
	<h1>If you would like to access YanlJ, please enable javascript</h1>
</noscript>
</head>

<body>
<div class="pagecontainer">

<?php include("wiki.php"); ?>
<?php https(); ?>
<h1>YanlJ, a non-linear JanlY</h1>
<?php check_login(); check_logout(); present_login() ?>

<?php if (htmlspecialchars($_SESSION['LOGGED_IN'])) { ?>

<div id="recent_posts" class="content_unit">
	<?php 	
		delete_entry();
		load_recent_posts();
	?>
</div>

<div id="workflow">
	<div id="new_entry" class="content_unit">
		<?php load_new_entry_form(); ?>
	</div>

	<div id="entry_div_box_proto">
		<div class="entry">
			<input type="submit" name="close_entry" value="x" class="x_button" onClick="close_bubble(this.parentNode.parentNode.id)">
			<div class="big_content_box">
			    <div class="content_header"></div>
			    <div class="entry_content_box"></div>
			</div>
			<p>
			<div class="edit_delete_links" style="position:static">
			    <form class="delete_entry_form" style="display:inline-block;"  action="" method="post" >
				<a class="delete_link"></a> 
				<input class="delete_name_input" type="hidden" name="entry_to_delete">
			    </form>
			    <a class="edit_link"></a>
			</div>
		</div>
	</div>


</div>
<?php } ?>

<form id="logout_button" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" ><p>	
<input type="submit" name="exit" value="Logout">
</form>

</div>
</body>
</html>
