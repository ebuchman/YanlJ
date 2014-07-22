
function _get_entry_data(xmlhttp, name, inplace){
	var response = JSON.parse(xmlhttp.responseText);
	var content = response.content;

	var owner = response.owner;
	if (inplace == 0)
		new_bubble(name, content, owner);
	else 
		reload_bubble(name, content, inplace)
}

function _edit_entry_data(xmlhttp, name, content, element){
	var response = JSON.parse(xmlhttp.responseText);
	var content = response.no_click;

	element.getElementsByClassName('content_header')[0].innerHTML = '<input class="edit_title" type="text" name="entry_name" value="'+name+'">' ;
	element.getElementsByClassName('entry_content_box')[0].innerHTML = '<textarea class="edit_entry_content" name="entry_content" rows=5 cols=50>'+content+'</textarea>';

	var done_edit_link = element.getElementsByClassName('done_edit_link')[0];
	done_edit_link.href="#/";
	done_edit_link.onclick= function(){
		replace_entry_data(name, content, element);

		var el = element.getElementsByClassName('entry_content_box')[0];
		console.log(el.getElementsByClassName('edit_entry_content')[0].value);
	};
	done_edit_link.innerHTML="done";
}

function _replace_entry_data(xmlhttp, name, content, element, new_title, new_content){
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

function _add_new_entry(xmlhttp, name, content){
	var response = JSON.parse(xmlhttp.responseText);
	var content = response.content;
	console.log(content);
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

function _delete_entry(xmlhttp, name){
	var element = document.getElementById(name+'_entry_link');
	element.outerHTML="";
	delete element;

	var element = document.getElementById('entry_div_box_'+name);
	element.outerHTML="";
	delete element;
}

function _register_new_user(xmlhttp){
	var response = JSON.parse(xmlhttp.responseText);
	console.log(response);
	var loginbtn = document.getElementById('login_btn');
	loginbtn.setAttribute("value", "Login");
	loginbtn.setAttribute("type", "submit");
	loginbtn.onclick="";
	document.getElementById("signup_form").innerHTML="";
	var login_form = document.getElementById('login_form');
	login_form.usr.value = "";
	login_form.pwd.value = "";
}
