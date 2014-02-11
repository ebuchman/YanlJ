<?php session_start(); 
if (!isset($_SESSION['LOGGED_IN'])) $_SESSION['LOGGED_IN'] = 0;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>YanlJ, a non-linear JanlY.</title>

<script type="text/x-mathjax-config">
//MathJax.Hub.Config({ tex2jax: {inlineMath: [['$','$'], ['\\(','\\)'], processEscapes: true}});
</script>

<script type="text/javascript"
src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>

<script type="text/javascript">

function get_entry_data(name){
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
            var content = xmlhttp.responseText;
            document.getElementById('entry_content_box').innerHTML = content;

            MathJax.Hub.Typeset('entry_content_box');

            // edit and delete links, built dynamically
            var delete_form = document.getElementById('delete_entry_form');
            var delete_link = document.getElementById('delete_link');
            var delete_name_input = document.getElementById('delete_name_input');
            var edit_link = document.getElementById('edit_link');
        
            delete_link.href="javascript:;"; 
            delete_name_input.value=name;
            delete_link.onclick = function(){delete_form.submit();};
            delete_link.innerHTML="delete";

            edit_link.href="#";
            edit_link.onclick= function(){edit_entry_data(name, content);};
            edit_link.innerHTML="edit";

           document.getElementById('content_header').innerHTML = "<h4>"+name+"</h4>";
        }
    }
    xmlhttp.open("POST", "load_content.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("name="+name);
    return false;
}

function edit_entry_data(name, content){
        document.getElementById('new_entry_name_box').value = name;
        document.getElementById('new_entry_content_box').value = content;
}
</script>

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

<div id="edit_delete_links" style="position:fixed; top:500px; left:300px;">
    <form id="delete_entry_form" style="display:inline-block;"  action="" method="post" >
        <a id="delete_link"></a> 
        <input id="delete_name_input" type="hidden" name="entry_to_delete">
    </form>
    <a id="edit_link"></a>
</div>
<div id="big_content_box" style="position:fixed; top:400px; left:300px;">
    <div id="content_header"></div>
    <div id="entry_content_box"></div>
</div>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" style="position:fixed; top:20px; right:100px;"><p>	
<input type="submit" name="exit" value="Logout"></form>

</body>
</html>
