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
            //MathJax.Hub.Queue(["Typeset",MathJax.Hub,"entry_content_box"]); 
            MathJax.Hub.Typeset('entry_content_box');

            // prevent escaping backslashes (for mathjax) when content gets sent to edit_entry_data
            //  but this is a bug if the text itself has a backslash - makes it uneditable!!!
            content = content.replace(/\\/g, '\\\\');

            //edit and delete links, as html in a js var. Use innerHTML.
            var eddel = '<form id="delete_entry" style="display:inline-block;" action="" method="post" > \
            <a href="javascript:;" onClick="document.getElementById(\'delete_entry\').submit();">delete</a>  \
            <input type="hidden" name="entry_to_delete" value="' + name + '" > \
        </form>  &nbsp&nbsp \
        <a href="#" id="edit_link" onClick="edit_entry_data(\'' + name + '\', \'' + content + '\');">edit</a></div> ';
            
           document.getElementById('edit_delete_links').innerHTML = eddel;   
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

<div id="edit_delete_links" style="position:fixed; top:500px; left:300px;"></div>
<div id="big_content_box" style="position:fixed; top:400px; left:300px;">
<div id="content_header"></div>
<div id="entry_content_box"></div>
</div>

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" style="position:fixed; top:20px; right:100px;"><p>	
<input type="submit" name="exit" value="Logout"></form>

</body>
</html>
