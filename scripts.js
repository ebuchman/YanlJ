
function new_bubble(name, content){
    var workflow_div = document.getElementById('workflow');
    var proto = document.getElementById('entry_div_box_proto').cloneNode(true);

    proto.getElementsByClassName('entry_content_box')[0].innerHTML = content;
	
    //MathJax.Hub.Typeset(proto);

    // edit and delete links, built dynamically
    var delete_link = proto.getElementsByClassName('delete_link')[0];
    var edit_link = proto.getElementsByClassName('edit_link')[0];

    delete_link.href="javascript:;"; 
    delete_link.onclick = function(){delete_entry(name);};
    delete_link.innerHTML="delete";

    edit_link.href="#/";
    edit_link.onclick= function(){edit_entry_data(name, content, proto);};
    edit_link.innerHTML="edit";

    proto.getElementsByClassName('content_header')[0].innerHTML = "<h4>"+name+"</h4>";
    proto.setAttribute("class", "entry_div_box content_unit");
    proto.setAttribute("id", "entry_div_box_"+name);

    workflow_div.insertBefore(proto, workflow_div.children[1]);
}

function reload_bubble(name, content, element){
    element.getElementsByClassName('entry_content_box')[0].innerHTML = content;
    element.getElementsByClassName('content_header')[0].innerHTML = "<h4>"+name+"</h4>";
}


// inplace is either nothing or is the element corresponding to the bubble to change
function get_entry_data(name, inplace){
    inplace = typeof inplace !== 'undefined' ? inplace : 0
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
	    var response = JSON.parse(xmlhttp.responseText);
	    var content = response.content;
	    if (inplace == 0)
	    	new_bubble(name, content);
	    else 
		reload_bubble(name, content, inplace)
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
	    var response = JSON.parse(xmlhttp.responseText);
	    var content = response.no_click;
	    
	    element.getElementsByClassName('content_header')[0].innerHTML = '<input class="edit_title" type="text" name="entry_name" value="'+name+'">' ;
	    element.getElementsByClassName('entry_content_box')[0].innerHTML = '<textarea class="edit_entry_content" name="entry_content" rows=5 cols=50>'+content+'</textarea>';

            var done_edit_link = element.getElementsByClassName('done_edit_link')[0];
            done_edit_link.href="#/";
            done_edit_link.onclick= function(){replace_entry_data(name, content, element);};
            done_edit_link.innerHTML="done";
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
	    console.log(xmlhttp.responseText);
	    var response = JSON.parse(xmlhttp.responseText);
	    if (new_title != response.name || new_content != response.content){
		throw "assert failed!";
	    }
	    get_entry_data(new_title, element);
	    element.getElementsByClassName('done_edit_link')[0].innerHTML = '';
	    
	    var entry_link = document.getElementById(name+"_entry_link");
	    console.log(entry_link)
	    entry_link.innerHTML = new_title;
	    entry_link.id = new_title + "_entry_link";
	    entry_link.onclick = function(){get_entry_data(new_title);};
	    console.log(entry_link)
        }
    }
    var query_string = "old_title="+encodeURIComponent(name)+"&new_title="+encodeURIComponent(new_title)+"&content="+encodeURIComponent(new_content);
    xmlhttp.open("POST", "ajax/replace_entry.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.setRequestHeader("Content-length", query_string.length); 
    xmlhttp.send(query_string);
    return false;
}

function close_bubble(id){
 document.getElementById(id).remove();
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

function load_new_entry_form(){
	var internals = '<input type="submit" name="close_entry" value="x" class="x_button" onclick="document.getElementById(\'new_entry_form_internals\').innerHTML=\'\'">';
	internals += '<h4>Add a new entry below:</h4>';
	internals += 'Entry Name: <input id="new_entry_name_box" type="text" name="entry_name"><p>';
	internals += 'Entry Content: <textarea  id="new_entry_content_box" name="entry_content" rows=5 cols=50></textarea>';
	internals += '<input type="submit" name="new_entry" value="Add Entry" onclick=add_new_entry()>';
	document.getElementById('new_entry_form_internals').innerHTML = internals;
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
	    var response = JSON.parse(xmlhttp.responseText);
	    var content = response.content;

	    document.getElementById('new_entry_form_internals').innerHTML='';

	    var new_list_element = document.createElement("DIV");
	    new_list_element.setAttribute('class', 'post');
	    new_list_element.setAttribute('id', name+'_entry_link');
	    var new_link = document.createElement('a');
	    new_link.setAttribute('href', '#');
	    new_link.setAttribute('id', name+'_entry_link');
	    new_link.onclick = function(){get_entry_data(name);};
	    new_link.innerHTML = name;
	    new_list_element.appendChild(new_link);

	    document.getElementById('recent_posts').appendChild(new_list_element);
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
	  var element = document.getElementById(name+'_entry_link');
	  element.outerHTML="";
	  delete element;

	  var element = document.getElementById('entry_div_box_'+name);
	  element.outerHTML="";
	  delete element;
        }
    }
    xmlhttp.open("POST", "ajax/delete_entry.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("name="+encodeURIComponent(name));
    return false;
}
