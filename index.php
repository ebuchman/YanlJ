<?php session_start(); if (!isset($_SESSION['LOGGED_IN'])) $_SESSION['LOGGED_IN'] = 0;?>

<!DOCTYPE html>
<html>
<head>
<title>YanlJ, a non-linear JanlY.</title>

<link rel="stylesheet" type="text/css" href="style/style.css">

<script type="text/javascript" src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML"> </script>
<script type="text/x-mathjax-config"> MathJax.Hub.Config({ tex2jax: {inlineMath: [['$','$'], ['\\(','\\)']], processEscapes: true, ignoreClass: "tex2jax_ignore"}}); </script>

<?php // use php to load javascripts
$handle = opendir("js/"); 
while (($file = readdir($handle)) !== false) {
  echo "<script type='text/javascript' src='js/$file?271'></script>";
} closedir($handle);
?>

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


<?php  // include php functions, check https
    include("wiki.php");
    https(); 
?>

<div id="headster">
	<h1>YanlJ, a non-linear JanlY</h1>

	<h3 id="quote">Are you a general purpose computer looking for a particular purpose? Consider pursuing the general purpose, where progress towards particular purposes can be made without any particular purpose.</h3>
</div>

<?php check_login(); check_logout(); if($_POST['login_entry']){present_login();} ?>

<?php  if (!htmlspecialchars($_SESSION['LOGGED_IN'])) { $_SESSION['USR_NAME'] = 'Guest';} ?>

<!--div id="real_page"-->
<?php // left_scroll has a greeting, a search box, and the list of recent posts ?>
<div class="left_scroll">

	<?php echo "<h3 id='hello'>Hello, " .  htmlspecialchars($_SESSION['USR_NAME']) . ".  Enjoy using YanlJ</h3>" ; ?>

	<div id="search" class="content_unit">
        <form>
		  <input id="search_box" type="text" name="db_search" onkeyup="displaySearchResults(this.value)">	
		  <div id="search_results"></div>
		</form>
	</div>

	<div id="recent_posts" class="content_unit">
		<?php load_recent_posts(); ?>
	</div>
</div>

<?php // right scroll has some basic instructions ?>
<div class="right_scroll">
    <h3>YanlJ is a bubbly wiki platform where you can offload and interconnect your thoughts</h3>
    <p> You can link to other entries using [[other_entry_name] [this]] </p>
    <p class="tex2jax_ignore"> And you can write latex in line using \( 3^4 \) and out of line using \[ 3^4 \]</p>
</div>

<?php //workflow has "add_new_entry" and the scroll of content bubbles that are opened ?>
<div id="workflow">
    <?php //if logged in, present new_entry button
        if (htmlspecialchars($_SESSION['LOGGED_IN'])) { 
    ?>
	<div id="new_entry" class="content_unit">
		<div>
			<input id="new_entry_button" type="submit" name="new_entry" value="Add New Entry" onclick="load_new_entry_form()">
		</div>

		<div class="new_entry_div">
			<div class="entry">
				<p id="new_entry_form_internals">
				</p>
			</div>
		</div>
	</div>
    <?php } 
        // create a content_bubble prototype to be copied every time a new bubble is loaded 
    ?>
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

</div><!-- closes the page container -->

<div id="logout_button">
<?php if (htmlspecialchars($_SESSION['LOGGED_IN'])){ ?>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" ><p>	
    <input type="submit" name="exit" value="Logout">
  </form>
<?php } else {?>
  <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" ><p>	
    <input type="submit" name="login_entry" value="Login">
  </form>
<?php } ?>
</div>
<div id="logos">
  <a href="https://github.com/ebuchman/Yanlj"><img src="style/github.jpg" alt="We're on github" width="40" height="40"></a>
</div>

</div>
</body>
</html>
