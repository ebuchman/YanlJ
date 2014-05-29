
// inplace is either nothing or is the element corresponding to the bubble to change
function get_entry_data(name, inplace){
    inplace = typeof inplace !== 'undefined' ? inplace : 0
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
		_get_entry_data(xmlhttp, name, inplace);
        }
    }
    xmlhttp.open("POST", "ajax/load_content.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("name="+encodeURIComponent(name));
    return false;
}

function edit_entry_data(name, content, element){
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
		_edit_entry_data(xmlhttp, name, content, element);
        }
    }
    xmlhttp.open("POST", "ajax/load_content.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("name="+encodeURIComponent(name));
    return false;
}

// called when clicking `done` after editing
function replace_entry_data(name, content, element){
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    var new_title = element.getElementsByClassName('edit_title')[0].value;
    var new_content = element.getElementsByClassName('edit_entry_content')[0].value;

    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
		_replace_entry_data(xmlhttp, name, content, element, new_title, new_content);
        }
    }
    var query_string = "old_title="+encodeURIComponent(name)+"&new_title="+encodeURIComponent(new_title)+"&content="+encodeURIComponent(new_content);
    xmlhttp.open("POST", "ajax/replace_entry.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Content-length", query_string.length); 
    xmlhttp.send(query_string);
    return false;
}

function displaySearchResults(keystrokes){
    if (keystrokes.length == 0)
    { document.getElementById('search_results').innerHTML = ""; return;}

    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
	    document.getElementById('search_results').innerHTML = xmlhttp.responseText;
        }
    }
    xmlhttp.open("POST", "ajax/search_db.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("keystrokes="+keystrokes);
}

function add_new_entry(){
    var name = document.getElementById('new_entry_name_box').value;
    var content = document.getElementById('new_entry_content_box').value;

    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
		_add_new_entry(xmlhttp, name, content);
        }
    }
    var query_string = "old_title="+encodeURIComponent('')+"&new_title="+encodeURIComponent(name)+"&content="+encodeURIComponent(content);
    xmlhttp.open("POST", "ajax/replace_entry.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Content-length", query_string.length); 
    xmlhttp.send(query_string);
    return false;
}


function delete_entry(name){
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
	  _delete_entry(xmlhttp, name);
        }
    }
    xmlhttp.open("POST", "ajax/delete_entry.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("name="+encodeURIComponent(name));
    return false;
}


function register_new_user(login_form){
    var name = login_form.usr.value;
    var pwd = login_form.pwd.value;
    var pwd2 = login_form.pwd2.value;
    var email = login_form.email.value;

    // validate....

    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange=function(){
    if (xmlhttp.readyState==4 && xmlhttp.status==200){
	  _register_new_user(xmlhttp, login_form);
        }
    }
    post_data = "name="+encodeURIComponent(name)+"&pwd="+encodeURIComponent(pwd)+"&email="+encodeURIComponent(email);
    xmlhttp.open("POST", "ajax/register_user.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(post_data);
    return false;
}
