

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
