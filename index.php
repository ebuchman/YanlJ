<?php session_start(); if (!isset($_SESSION['LOGGED_IN'])) $_SESSION['LOGGED_IN'] = 0;?>

<!DOCTYPE html>
<html>
<head>
<title>YanlJ, a non-linear JanlY.</title>
<link rel="stylesheet" type="text/css" href="style.css">
<script type="text/javascript" src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> </script>
<script type="text/x-mathjax-config"> //MathJax.Hub.Config({ tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']], processEscapes: true}}); </script>

<?php $handle = opendir("js/");
while (($file = readdir($handle)) !== false) {
  echo "<script type='text/javascript' src='js/$file'></script>";
} closedir($handle);?>
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

<div id="headster">
	<h1>YanlJ, a non-linear JanlY</h1>

	<h3 id="quote">Are you a general purpose computer looking for a particular purpose? Consider pursuing the general purpose, where progress towards particular purposes can be made without any particular purpose.</h3>
</div>

<?php check_login(); check_logout(); if($_POST['login_entry']){present_login();} ?>

<?php  if (!htmlspecialchars($_SESSION['LOGGED_IN'])) { $_SESSION['USR_NAME'] = 'Guest';} ?>

<div id="real_page">
<div class="left_scroll">
	<?php echo "<h3 id='hello'>Hello, " .  htmlspecialchars($_SESSION['USR_NAME']) . ".  Enjoy using YanlJ</h3>" ; ?>
	<div id="search" class="content_unit">
		<form>
		  <input id="search_box" type="text" name="db_search" onkeyup="displaySearchResults(this.value)">	
		  <div id="search_results"></div>
		</form>
	</div>

	<?php if (htmlspecialchars($_SESSION['LOGGED_IN'])) { ?>
	<div>
		<input id="new_entry_button" type="submit" name="new_entry" value="Add New Entry" onclick="load_new_entry_form()">
	</div>
	<?php } ?>

	<div id="recent_posts" class="content_unit">
		<?php load_recent_posts(); ?>
	</div>
</div>

<div id="workflow">
	<?php if (htmlspecialchars($_SESSION['LOGGED_IN'])) { ?>
	<div id="new_entry" class="content_unit">
		<div class="new_entry_div">
			<div class="entry">
				<p id="new_entry_form_internals">
				</p>
			</div>
		</div>
	</div>
	<?php } ?>

	<div id="entry_div_box_proto" draggable="true">
		<div class="entry">
			<input type="submit" name="close_entry" value="x" class="x_button" onClick="close_bubble(this.parentNode.parentNode.id)">
			<div class="big_content_box">
			    <form action="" method="post">
			        <div class="content_header"></div>
			        <div class="entry_content_box"></div>
			    </form>
			</div>
			<p>
			<div class="edit_delete_links" style="position:static">
			    <a class="delete_link"></a> 
			    <a class="edit_link"></a>
			    <a class="done_edit_link"></a>
			</div>
		</div>
	</div>


</div>
</div>

<?php if (htmlspecialchars($_SESSION['LOGGED_IN'])){ ?>
<form id="logout_button" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" ><p>	
<input type="submit" name="exit" value="Logout">
</form>
<?php } else {?>
<form id="logout_button" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" ><p>	
	<input  type="submit" name="login_entry" value="Login">
</form>
<?php } ?>

</div>
</body>
</html>
