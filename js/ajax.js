
// general framework for ajax calls. _callback functions are in callback.js

function new_request_obj(){
    if (window.XMLHttpRequest)
        return new XMLHttpRequest();
    else
        return new ActiveXObject("Microsoft.XMLHTTP");
}

function request_callback(xmlhttp, _func, args){
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
		args.unshift(xmlhttp);
		_func.apply(this, args);
        }
    }
}

function make_request(xmlhttp, method, path, async, params){
    xmlhttp.open(method, path, async);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var s = "", i = 0;
    for (var k in params){
	if (i > 0)
  	  s += "&";
	s += k+"="+encodeURIComponent(params[k]);
	i++;
    }
    //xmlhttp.setRequestHeader("Content-length", s.length); // important?
    xmlhttp.send(s);
}


// inplace is either nothing or is the element corresponding to the bubble to change
function get_entry_data(name, inplace){
    inplace = typeof inplace !== 'undefined' ? inplace : 0

    xmlhttp = new_request_obj();
    request_callback(xmlhttp, _get_entry_data, [name, inplace]);
    make_request(xmlhttp, "POST", "ajax/load_content.php", true, {"name":name});
    return false;
}

function edit_entry_data(name, content, element){
    xmlhttp = new_request_obj();
    request_callback(xmlhttp, _edit_entry_data, [name, content, element]);
    make_request(xmlhttp, "POST", "ajax/load_content.php", true, {"name":name});
    return false;
}

// called when clicking `done` after editing
function replace_entry_data(name, content, element){
    var new_title = element.getElementsByClassName('edit_title')[0].value;
    var new_content = element.getElementsByClassName('edit_entry_content')[0].value;

    xmlhttp = new_request_obj();
    request_callback(xmlhttp, _replace_entry_data, [name, content, element, new_title, new_content]);
    make_request(xmlhttp, "POST", "ajax/replace_entry.php", true, {"old_title":name, "new_title":new_title, "content":new_content});
    return false;
}

function displaySearchResults(keystrokes){
    if (keystrokes.length == 0)
    { document.getElementById('search_results').innerHTML = ""; return;}

    xmlhttp = new_request_obj();

    // make callback even?
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
	    document.getElementById('search_results').innerHTML = xmlhttp.responseText;
        }
    }
    make_request(xmlhttp, "POST", "ajax/search_db.php", true, {"keystrokes":keystrokes});
    return false;
}

function add_new_entry(){
    var name = document.getElementById('new_entry_name_box').value;
    var content = document.getElementById('new_entry_content_box').value;

    xmlhttp = new_request_obj();
    request_callback(xmlhttp, _add_new_entry, [name, content]);
    make_request(xmlhttp, "POST", "ajax/replace_entry.php", true, {"old_title":'', "new_title":name, "content":content});
    return false;
}

function delete_entry(name){
    xmlhttp = new_request_obj();
    request_callback(xmlhttp, _delete_entry, [name]);
    make_request(xmlhttp, "POST", "ajax/delete_entry.php", true, {"name":name});
    return false;
}

function register_new_user(login_form){
    var name = login_form.usr.value;
    var pwd = login_form.pwd.value;
    var pwd2 = login_form.pwd2.value;
    var email = login_form.email.value;

    // validate....

    xmlhttp = new_request_obj();
    request_callback(xmlhttp, _register_new_user, []);
    make_request(xmlhttp, "POST", "ajax/register_user.php", true, {"name":name, "pwd":pwd, "email":email});
    return false;
}
