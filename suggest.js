function getRObject() {

if (window.XMLHttpRequest) {
		return new XMLHttpRequest();
	} else if(window.ActiveXObject) {
		return new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		alert("Your Browser Sucks!\nIt's about time to upgrade don't you think?");
	}
}

var suggObject = getRObject();

function suggest(block) {

 if(suggObject.readyState == 4 || suggObject.readyState == 0) {
   var str = escape(document.getElementById(block+'_class').value);
   suggObject.open("GET", 'sugg.php?block='+block+'&str='+str, true);
   suggObject.onreadystatechange= handleSuggest;
   suggObject.send(null);
 }

}

function handleSuggest() {
  var str = suggObject.responseText.split("\n");
  var sugg = document.getElementById(str[0]+'_suggest');
  if (suggObject.readyState == 4 ) {
    if (sugg) {sugg.innerHTML = '';}
    for(i=1; i < str.length - 1; i++) {
      var item ='<div onclick="javascript:setClass('+"'"+str[0]+' - '+str[i]+"'"+');" class="suggest_link">'+ str[i] + '</div>';
      sugg.innerHTML += item;
    }
if(sugg) { sugg.style.visibility="visible"; }
  }
}

function setClass(theClass) {
  var str = theClass.split(' - ');
  document.getElementById(str[0]+'_class').value = str[1];
  document.getElementById(str[0]+'_teacher').value = str[2];
  document.getElementById(str[0]+'_suggest').innerHTML = '';
  document.getElementById(str[0]+'_suggest').style.visibility='hidden';
}

function blur(block) {
  document.getElementById(block+'_suggest').style.visibility='hidden';
}
