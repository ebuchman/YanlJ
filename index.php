<?php session_start(); 
if (!isset($_SESSION['LOGGED_IN'])) $_SESSION['LOGGED_IN'] = 0;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
<title>YanlJ, a non-linear JanlY.</title>
<script type="text/x-mathjax-config">
/*
MathJax.Hub.Config({
      tex2jax: {
              inlineMath: [['$','$'], ['\\(','\\)']],
                      processEscapes: true
                        }
      });
 */
</script>
<script type="text/javascript"
src="https://c328740.ssl.cf1.rackcdn.com/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML">
</script>

<script type="text/javascript">

// when an entry name is clicked on, a content box should appear with the information 
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

// when the edit link is clicked, the content box should become a textbox, but getting this to work is a nightmare (consensus: use jQuery...)
function edit_entry_data_(name){
        box = document.getElementById('entry_content_box'); //.innerHTML = "fuck";
//        var edit_box_form = document.createElement('form');
  //      edit_box_form.action="
        var edit_box = document.createElement('textarea');
        edit_box.style.position = "fixed";
        edit_box.style.top = "400px";
        edit_box.style.left = "300px";
        edit_box.id = 'edit_box';
        edit_box.value = box.innerHTML;
        document.body.appendChild(edit_box);
        edit_box.rows = 5; edit_box.columns=50;
        var edit_link  = document.getElementById('edit_link');
        //edit_box.onchange=push_entry_changes();
        /*var done_form= "<form id=\"update_entry\" action=\"\" method=\"post\"> \ 
            <a href=\"#\" onClick=\"document.getElementById('update_entry').submit();\">done</a>"; \ 
            <input type=\"hidden\" name=\"new_content\" value=\" 
         */
        var done_link = "<a href\"#\" onClick=\"push_entry_changes()\">done</a>";
        edit_link.innerHTML = done_link;
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
        edit_delete();
        load_recent_posts();
        load_new_entry_form();
    }
?>

<div id="edit_delete_links" style="position:fixed; top:500px; left:300px;"></div>
<div id="big_content_box" style="position:fixed; top:400px; left:300px;">
<div id="content_header"></div>
<div id="entry_content_box"></div>
</div>

<!--<input type="input" id="fucker">-->

<form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  method="post" style="position:fixed; top:20px; right:100px;"><p>	
<input type="submit" name="exit" value="Logout"></form>

</body>
</html>
