var chatuserset=0;var logoutchat=0;var chatroom=document.getElementById("chatroom")?document.getElementById("chatroom").value:document.getElementById("s_room").innerHTML;var callphp=0;var nrchatusers=1;var setchat="chatfiles/setchat.php";var getchat=function(){return"chattxt/"+chatroom+".txt"};var ajxsend=0;var chatbeep=1;var playbeep=2;var beepfile="beep1.wav";function GetCookie(e){var a="0";var f=" "+document.cookie+";";var c=" "+e+"=";var b=f.indexOf(c);if(b!=-1){b+=c.length;var d=f.indexOf(";",b);a=unescape(f.substring(b,d))}return a}function delCookie(a){var c=3*24*60*60*1000;var b=new Date();b.setTime(b.getTime()-c);document.cookie=a+"=deletes; expires="+b.toGMTString();if(document.getElementById("name_code")){document.getElementById("name_code").style.display="block"}if(document.getElementById("chatadd")){document.getElementById("chatadd").style.display="none"}logoutchat=1;chatuserset=0}var cookie_namec=(document.getElementById("chatuser")&&document.getElementById("chatuser").value.length>1)?"0":GetCookie("name_c");if(cookie_namec!="0"&&document.getElementById("name_code")){callphp=0;document.getElementById("name_code").style.display="none";document.getElementById("chatadd").style.display="block";document.getElementById("chatuser").value=cookie_namec;logoutchat=0;chatuserset=1}var cookie_roomc=GetCookie("room_c");if(cookie_roomc!="0"){var chatrooms=document.getElementById("chatrooms").getElementsByTagName("span");for(var i=0;i<chatrooms.length;i++){chatrooms[i].removeAttribute("id");if(chatrooms[i].innerHTML==cookie_roomc){chatrooms[i].setAttribute("id","s_room")}}if(document.getElementById("chatroom")){document.getElementById("chatroom").value=cookie_roomc}chatroom=cookie_roomc}var cookie_beepc=GetCookie("beep_c");if(cookie_beepc!=="0"&&document.getElementById("playbeep")){playbeep=cookie_beepc;document.getElementById("playbeep").src="chatex/playbeep"+playbeep+".png"}function playBeep(b){if(b.match(/\<q\>([^\<]+)\<\/q\>/)){var a=b.match(/\<q\>([^\<]+)\<\/q\>/)[1];if(a!=chatbeep){chatbeep=a;document.getElementById("chatbeep").innerHTML='<audio autoplay="autoplay" src="chatex/'+beepfile+'" type="audio/wav"><embed src="chatex/'+beepfile+'" hidden="true" autostart="true" loop="false" /></audio>'}}}function setPlayBeep(e){playbeep=(playbeep==1)?2:1;e.src="chatex/playbeep"+playbeep+".png";var d="beep_c";var b=playbeep;var c=48*60*60*1000;var a=new Date();a.setTime(a.getTime()+c);document.cookie=d+"="+escape(b)+"; expires="+a.toGMTString()}function checkNameC(d){var e=0;if(document.getElementById("chatusersli")){var c=document.getElementById("chatusersli").getElementsByTagName("li");var b=c.length;for(var a=0;a<b;a++){if(c[a].innerHTML.match(/[^\<]+/)==d){e=1;break}}}return e}function setChatRoom(f){var d=document.getElementById("chatrooms").getElementsByTagName("span");for(var c=0;c<d.length;c++){d[c].removeAttribute("id")}if(document.getElementById("chatroom")){document.getElementById("chatroom").value=f.innerHTML}chatroom=f.innerHTML;f.setAttribute("id","s_room");document.getElementById("chats").innerHTML=texts.loadroom;callphp=0;var g="room_c";var e=f.innerHTML;var a=7*24*60*60*1000;var b=new Date();b.setTime(b.getTime()+a);document.cookie=g+"="+escape(e)+"; expires="+b.toGMTString()}function getNrChatUsers(){if(document.getElementById("chatusersli")){return document.getElementById("chatusersli").getElementsByTagName("li").length}else{return 1}}function setNameC(c){var a=c.chatuser.value;if(a.length<2||a.length>12){alert(texts.err_name);c.chatuser.focus();return false}else{if(c.cod.value.length<4||c.cod.value!=document.getElementById("code_ch").innerHTML){alert(texts.err_vcode);document.getElementById("code_ch").style.color="red";c.cod.focus();c.cod.select();return false}else{if(checkNameC(a)==1){alert(a+texts.err_nameused);c.chatuser.select()}else{var f="name_c";var d=a;var e=24*60*60*1000;var b=new Date();b.setTime(b.getTime()+e);document.cookie=f+"="+escape(d)+"; expires="+b.toGMTString();document.getElementById("name_code").style.display="none";document.getElementById("chatadd").style.display="block";c.cod.value="";logoutchat=0;return chatuserset=1}}}}function enterChat(){logoutchat=0;chatuserset=1;document.getElementById("name_code").style.display="none";document.getElementById("chatadd").style.display="block";callphp=0}function addChatS(c){if(chatuserset==1){var a=c.adchat.value.length;if(a<2||a>200){alert(texts.err_textchat);c.adchat.focus()}else{var b="adchat="+c.adchat.value+"&chatuser="+c.chatuser.value;ajxsend=1;ajaxF(setchat,b);c.adchat.value=""}}else{setNameC(c)}return false}function setUrl(b){var a=window.prompt(texts.addurl);if(a.match(/^(www.){0,1}([a-zA-z0-9_,+ -]+[.]+)/)){addChatBIU("[url="+a+"]","[/url]",b)}else{alert(texts.err_addurl)}}function addChatBIU(start,end,zona){var adchat=document.getElementById(zona);var IE=
/*@cc_on!@*/
false;if(IE){adchat.value=adchat.value+start+end;var pos=adchat.value.length-end.length;range=adchat.createTextRange();range.collapse(true);range.moveEnd("character",pos);range.moveStart("character",pos);range.select()}else{if(adchat.selectionStart||adchat.selectionStart=="0"){var startPos=adchat.selectionStart;var endPos=adchat.selectionEnd;adchat.value=adchat.value.substring(0,startPos)+start+adchat.value.substring(startPos,endPos)+end+adchat.value.substring(endPos,adchat.value.length);adchat.setSelectionRange((endPos+start.length),(endPos+start.length));adchat.focus()}}}function addSmile(a,b){var c=document.getElementById(b);c.value+=a;c.focus()}function ajaxRequest(){var a=["Msxml2.XMLHTTP","Microsoft.XMLHTTP"];if(window.ActiveXObject){for(var b=0;b<a.length;b++){try{return new ActiveXObject(a[b])}catch(c){}}}else{if(window.XMLHttpRequest){return new XMLHttpRequest()}else{return false}}}var scrol0=-1;var i_scrol=0;var mypostrequest=new ajaxRequest();function ajaxF(b,c){var d=1;c+="&chatroom="+chatroom;mypostrequest.open("POST",b,true);mypostrequest.setRequestHeader("Content-type","application/x-www-form-urlencoded");mypostrequest.send(c);mypostrequest.onreadystatechange=a;function a(){var e=document.getElementById("chats").scrollTop;if(mypostrequest.readyState==4){if(mypostrequest.status==200&&document.getElementById("chats")&&mypostrequest.responseText.indexOf('id="chats"')!=-1){var h=mypostrequest.responseText;if(logoutchat===1){var g=new RegExp("<li>"+document.getElementById("chatuser").value+"<span>([^<]*)</span></li>","i");if(h.match(g)){h=h.replace(g,"")}}document.getElementById("chatwindow").innerHTML=h;var j=document.getElementById("chats");var f=Math.max(j.scrollHeight,j.clientHeight);if(e!=0&&e<scrol0){j.scrollTop=e}else{j.scrollTop=j.scrollHeight;i_scrol=0}if(i_scrol==0){scrol0=document.getElementById("chats").scrollTop;i_scrol=1}if(playbeep==2){playBeep(h)}}}d=0}}function apelAjax(){callphp-=1.5;if(callphp<=0){callphp=2.8+(getNrChatUsers()*0.3);var a=setchat}else{var a=getchat()}if(callphp>10){callphp=10}var b=(chatuserset==1)?(document.getElementById("chatuser").value):"";if(ajxsend===0){ajaxF(a,"chatuser="+b)}else{ajxsend=0}setTimeout("apelAjax()",1900)}apelAjax();