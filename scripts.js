
function get_entry_data(name){

    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
	
	    var response = JSON.parse(xmlhttp.responseText);
	    var content = response.content;

	    var workflow_div = document.getElementById('workflow');
	    var proto = document.getElementById('entry_div_box_proto').cloneNode(true);

            proto.getElementsByClassName('entry_content_box')[0].innerHTML = content;
		
            MathJax.Hub.Typeset(proto);

            // edit and delete links, built dynamically
            var delete_form = proto.getElementsByClassName('delete_entry_form')[0];
            var delete_link = proto.getElementsByClassName('delete_link')[0];
            var delete_name_input = proto.getElementsByClassName('delete_name_input')[0];
            var edit_link = proto.getElementsByClassName('edit_link')[0];
 
            delete_link.href="javascript:;"; 
            delete_name_input.value=name;
            delete_link.onclick = function(){delete_form.submit();};
            delete_link.innerHTML="delete";

            edit_link.href="#/";
            edit_link.onclick= function(){edit_entry_data(name, content);};
            edit_link.innerHTML="edit";

            proto.getElementsByClassName('content_header')[0].innerHTML = "<h4>"+name+"</h4>";
	    proto.setAttribute("class", "entry_div_box content_unit");
	    proto.setAttribute("id", "entry_div_box".concat(name));

	    workflow_div.appendChild(proto);
        }
    }
    xmlhttp.open("POST", "load_content.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("name="+name);
    return false;
}

function edit_entry_data(name, content){
    if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
    else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");

    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState==4 && xmlhttp.status==200){
	    var response = JSON.parse(xmlhttp.responseText);
	    var content = response.no_click;

            document.getElementById('new_entry_name_box').value = name;
            document.getElementById('new_entry_content_box').value = content;
        }
    }
    xmlhttp.open("POST", "load_content.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send("name="+name);
    return false;
}

function close_bubble(id){
 document.getElementById(id).remove();

}

