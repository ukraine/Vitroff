var count = 1

function MM_findObj(n, d)	{ //v4.0
	var p,i,x;	if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
	d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
	if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
	for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
	if(!x && document.getElementById) x=document.getElementById(n); return x;
}
	
function MM_changeProp(objName,x,theProp,theValue)	{ //v3.0
	var obj = MM_findObj(objName);
	if (obj && (theProp.indexOf("style.")==-1 || obj.style)) eval("obj."+theProp+"='"+theValue+"'");
}

// Открытие урла в новом окне
function MM_openBrWindow(theURL,features,scroll) { //v2.0
  window.open(theURL,'add','toolbar=no,location=no,status=no,menubar=no,scrollbars='+scroll+',resizable=no,'+features);
}

function id(x) { return document.getElementById(x); }

function ReloadParentCloseThis() {
	window.opener.location.realod();
	window.close();
}

// Чтение куки по названию
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

// Подтверждение удаления
function confirmDelete()	{
	return confirm('Your are about to permanently delete this item. Delete it?');
}

// Выполнение запроса AJAX
function RunAJAX(url) {

	url=url+"&sid="+Math.random()

	// alert(url)

	xmlHttp.onreadystatechange=stateChanged
	xmlHttp.open("POST",url,true)
	xmlHttp.send()

}

function attachFile() {
	document.write("<div id=\"uploadsourceform\"><input type=\"file\" name=\"uploadfile[1][2]\"></div>");
}

function saveasfile(id) {

	// 31.10.2006 added table var in order to change values in different sections (tables)
	
	xmlHttp=GetXmlHttpObject()

	if (xmlHttp==null)
	{
	alert ("Browser does not support HTTP Request")
	return
	}
	
	RunAJAX("/mad/ajax.php?action=createfile&id="+id)
	var filename = id + "-source.doc"
	var InsertFile = "<div id='fileid" + id +"'><a href='/getfile.php?file=" + filename + "'>" + filename + "</a> &nbsp; &#151; &nbsp; <span onclick=\"unlinkfile('" + filename + "','" + id + "','fileid " + id + " ')\" class=\"redlink\">delete</span></div>"
	document.getElementById("sourcefilesblock").innerHTML = document.getElementById("sourcefilesblock").innerHTML+InsertFile;
	document.forms[0].source_text.value = ""
}


// Подтверждение и последующее удаление файла, прикрепленного к заказу на перевод
function unlinkfile(filename, fileid, showuploadform)
{

	// 31.10.2006 added table var in order to change values in different sections (tables)
	
	xmlHttp=GetXmlHttpObject()

	confirmDelete();

	if (xmlHttp==null)
	{
	alert ("Browser does not support HTTP Request")
	return
	}
	
	RunAJAX("/mad/ajax.php?action=unlinkfile&filename="+filename+"&fileid="+fileid)

	document.getElementById("fileid"+fileid).style.display = "none";

} 

// Свойство объекта изменено. AJAX
function stateChanged() 
{ 

	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	   { 
		var id = readCookie('id')
		var option = readCookie('option')
		document.getElementById('loading').innerHTML=xmlHttp.responseText
	   } 
}

function showText(id) {

	document.getElementById(id).style.display='inline';
	document.getElementById(id+'_a').style.display='none';

}

function ChangeHiddenFieldValue(fieldon) {

	if (document.getElementById('label'+fieldon).value == 0)
	{
		document.getElementById('label'+fieldon).value = 1
	}
		else document.getElementById('label'+fieldon).value = 0

}

function GetXmlHttpObject()
{ 
	var objXMLHttp=null
	if (window.XMLHttpRequest)
	  {
	  objXMLHttp=new XMLHttpRequest()
	  }
	else if (window.ActiveXObject)
	  {
	  objXMLHttp=new ActiveXObject("Microsoft.XMLHTTP")
	  }
	return objXMLHttp
} 

// Загрузка картинок в шапку
//preload images:

var imgpath = '/mad/img/icons/';
var offimage = '_off'

a1 = new Image(41,31);
a1.src = imgpath + "payments_off.gif";
a2 = new Image(41,31);
a2.src = imgpath + "payments.gif";

a3 = new Image(41,31);
a3.src = imgpath + "rates_off.gif";
a4 = new Image(41,31);
a4.src = imgpath + "rates.gif";

a5 = new Image(41,31);
a5.src = imgpath + "templates_off.gif";
a6 = new Image(41,31);
a6.src = imgpath + "templates.gif";

a7 = new Image(41,31);
a7.src = imgpath + "workflow_off.gif";
a8 = new Image(41,31);
a8.src = imgpath + "workflow.gif";

a9 = new Image(41,31);
a9.src = imgpath + "newsletter_off.gif";
a10 = new Image(41,31);
a10.src = imgpath + "newsletter.gif";


//image swapping function:
function hiLite(imgDocID, imgObjName, comment) {
	document.images[imgDocID].src = eval(imgObjName + ".src");
	window.status = comment; return true;
}
//end hiding -->

// Подсчет слов II
function countwordsandchars(){

	if (document.forms[0].source_text.value.length!=0) {

		var y=document.forms[0].source_text.value;
		var r = 0;
		a=y.replace(/\s/g,' ');
		a=a.split(' ');
		for (z=0; z<a.length; z++) {if (a[z].length > 0) r++;}
		document.forms[0].wordcount.value=r;
		document.forms[0].characters.value=document.forms[0].source_text.value.length;
	}

	else alert("Nothing to count");

}

// 04.10.2007
// Включение и отключение видимости блока
function toggle_visibility(id) {

	var e = document.getElementById(id);

	var text = document.getElementById('openview')

	if(e != null) {
		if (e.style.display == 'none') {
			e.style.display = 'block';
			text.innerHTML = 'Hide';
			}
		else {
			e.style.display = 'none';
			text.innerHTML = 'Show';
		}
	}

}


function toggleVisibilityV2(id) {
	var e = document.getElementById(id);
	if(e.style.display == 'block')
		e.style.display = 'none';
	else e.style.display = 'block';
}


function ShowUploadForm(where,filegroupid) {

	count++
	document.getElementById(where).innerHTML = document.getElementById(where).innerHTML+"<br><input type='file' name='uploadfile[" + filegroupid +"][" + count + "]'>"

}

/* 13.03.2010 */

function switchTab(paneId) {

	// Шаг 1 - ставим общее кол-во закладок, если больше - увеличиваем на единицу, меньше - соотв. уменьшаем
	var panes = ["0","1","2","3"];

	for(n in panes) {

		if (n == paneId) { 
			document.getElementById("pane"+panes[n]).style.display = "block"; 
			document.getElementById("tab"+panes[n]).className = "activeTab"
		}

		else { 
			document.getElementById("pane"+panes[n]).style.display = "none"; 
			document.getElementById("tab"+panes[n]).className = "none";
		}
	}
     
}

function changeFormAction(actionURL,fieldname) {
	
	document.forms[0].action = actionURL
	document.getElementById("labelid").name = fieldname

}

function CalculatePrice(priceperword,Div) {

var input = document.getElementById("label"+Div);

if (input.value.indexOf(',') > -1) {
	input.value = input.value.replace(/,/g, '.');
	}

	numberofWords = document.forms[0].wordcount.value;
	amount = input.value * numberofWords;
	id("innerHTML"+Div).style.display = 'inline'
	id("innerHTML"+Div).innerHTML = priceperword + " per word x " + numberofWords + " words = " + Math.round(amount);

}

function FixDotComa (Div) {

var input = document.getElementById("label"+Div);

if (input.value.indexOf(',') > -1) {
	input.value = input.value.replace(/,/g, '.');
	}

}

function CalculateDiscountedPrice(percent, Div) {

var input = document.getElementById("label"+Div);

if (input.value.indexOf(',') > -1) {
	input.value = input.value.replace(/,/g, '.');
	}

/*	numberofWords = document.forms[0].wordcount.value; */
	estimatedprice = document.forms[0].estimatedprice.value;
	amount = estimatedprice - (estimatedprice * (percent/100));
	id("innerHTML"+Div).style.display = 'inline'
	id("innerHTML"+Div).innerHTML = percent + "% discount means " + Math.round(amount);

}

function setValue(key,val) {

	id("label"+key).focus();
	id("label"+key).value=val; return false;

}

var hintcontainer = null;

function hint(obj, txt) {

	if (hintcontainer==null) {
		hintcontainer = document.createElement("div");  
		hintcontainer.className="hintstyle";  
		document.body.appendChild(hintcontainer);  
	}

	obj.onmouseout = hidehint;  
	obj.onmousemove=movehint;  
	hintcontainer.innerHTML=txt;  
}
function movehint(e) {  

	if (!e) e = event; //line for IE compatibility  
	hintcontainer.style.top =  (e.clientY+document.documentElement.scrollTop+2)+"px";  
	hintcontainer.style.left = (e.clientX+document.documentElement.scrollLeft+10)+"px"; 
	hintcontainer.style.display="";  
}

function hidehint() {  
	hintcontainer.style.display="none";  
}

function getHours(totalAmount,defaultPricePerHour) {

	totalHours = totalAmount/defaultPricePerHour;
	dailyHours = Math.round(totalHours/22);

	document.forms[0].numberOfHours.value		= totalHours
	document.forms[0].numberOfHoursDaily.value	= dailyHours

}