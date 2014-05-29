function load_new_entry_form(){
	var internals = '<input type="submit" name="close_entry" value="x" class="x_button" onclick="document.getElementById(\'new_entry_form_internals\').innerHTML=\'\'">';
	internals += '<h4>Add a new entry below:</h4>';
	internals += 'Entry Name: <input id="new_entry_name_box" type="text" name="entry_name"><p>';
	internals += 'Entry Content: <textarea  id="new_entry_content_box" name="entry_content" rows=5 cols=50></textarea>';
	internals += '<input type="submit" name="new_entry" value="Add Entry" onclick=add_new_entry()>';
	document.getElementById('new_entry_form_internals').innerHTML = internals;
}

function present_signup(){
    var signup_form = document.getElementById("signup_form");
    signup_form.innerHTML = 'Password (again): <input type="password" name="pwd2">'+
              '<p>'+
              'Email: <input type="text" name="email">'+
              '<p>'+
              'Important Question: What time is it in China in five minutes when it\'s one fifty eight in Toronto and day-light savings time is about to set the clock forward?'+
              '<input type="text" name="question">';
    var loginbtn = document.getElementById('login_btn');
    loginbtn.value="Sign Up";
    loginbtn.onclick=function(){register_new_user(document.getElementById('login_form'));}; // ......
    loginbtn.type="button";
    document.getElementById('signup_btn_div').innerHTML = '';

}

