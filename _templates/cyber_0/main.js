if (navigator.userAgent.match(/iPhone|iPad|iPod/i)) userOs="iOS";
else if (navigator.userAgent.match(/Android/i)) userOs="Android";
else if (navigator.userAgent.match(/Windows Phone/i)) userOs="Windows Phone";
else  userOs="PC";

if (userOs=="iOS" || userOs=="Android" || userOs=="Windows Phone") {
	var viewPortTag=document.createElement('meta');
	viewPortTag.id="viewport";
	viewPortTag.name = "viewport";
	viewPortTag.content = "width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;";
	document.getElementsByTagName('head')[0].appendChild(viewPortTag);
}



var userOs;
var openedPage;
document.addEventListener("DOMContentLoaded", function() {resizeBody();});


// _____Изменение ширины окна___
var clWidth;
window.onresize = function(){resizeBody();}
function resizeBody(){
	clWidth=document.documentElement.clientWidth;
	//_______Меняем ширину основного тела________
	if (clWidth>800) {
		document.body.style.margin="20px 0px 20px 0px";
		clWidth=800;
	}
	else {
		document.body.style.margin="0px 0px 0px 0px";
	}
	document.getElementById("bodymain").style.width=clWidth-2;

	//Меняем ширину и отступ имени в шапке________
	var nameWigh=clWidth-100;
	document.getElementById("cyberLightName").style.width=(nameWigh*0.8);
	document.getElementById("cyberLightName").style.margin="0px "+((nameWigh*0.2)/2)+"px";

	if (openedPage=='main' || openedPage=='main1'){
		ResizeGroup();
		ResizeButs();
	}
}


// _____Открывание/закрывание меню___
var x=0;
var menuOpend="0";
var menu_width = 130;
function butOpenMenu() {
	if (menuOpend=="0") {
		openMenu();
	}
	else {
		closeMenu();
	}
}
function openMenu(){
	x=x+20;
	if (x>menu_width) x=menu_width;
	document.getElementById("bodymain").style.margin="0 0 0 " + x;
	document.getElementById("bodymenu").style.margin="0 0 0 " + (x-menu_width);
	if (x<menu_width) setTimeout(arguments.callee, 1);
	else {
		document.getElementById("bodybody").style.opacity="0.30";
		//document.getElementById("bodyfoot").style.opacity="0.30";
		document.getElementById("bodymain").onclick=function () {closeMenu();}
		menuOpend="1";
	}
}
function closeMenu(){
	x=x-20;
	if (x<0) x=0;
	document.getElementById("bodymain").style.margin="0 0 0 " + x;
	document.getElementById("bodymenu").style.margin="0 0 0 " + (x-menu_width);
	if (x>0) setTimeout(arguments.callee, 1);
	else {
		document.getElementById("bodybody").style.opacity="1";
		//document.getElementById("bodyfoot").style.opacity="1";
		document.getElementById("bodymain").onclick="";
		menuOpend="0";
	}
}


// _____Открывание/закрывание дополнительного меню___
function OpenDopScreen(e){
	var s=document.getElementById('dopScreen');
	var but=document.getElementById('openDop');
	if (s.style.display=='table'){s.style.display='none';but.innerHTML='Ещё..';}
	else {s.style.display='table';but.innerHTML='Убрать';}
}


// _____Движения мышкой и TOUCH______ MAIN
var moveOn=0;
var clickEnable=1;
var sdvig=0;
var posX=0;
var otstup=0;

function AddEvents(){
	if (userOs=="iOS"){
		document.getElementById('DevMenu').addEventListener('touchstart', function(e) {TouchStarted(e,'DevMenu','DevMenuSlider');}, false);
		document.body.addEventListener('touchend', TouchEnded, false);
		document.body.addEventListener('touchmove', SdigDiv, false);
	}
//	else if (userOs=="Android") {
//	}
	else {
		document.getElementById('DevMenu').onmousedown = function(e) {TouchStarted(e,'DevMenu','DevMenuSlider');}
		document.body.onmouseup = function(e) {TouchEnded(e);}
		document.body.onmousemove = function(e) {SdigDiv (e);}
	}

}

var sdvigSumm;
var mnDiv;
var slideDiv;
function TouchStarted(e,c,d){
//alert(e.type+" "+c);
	moveOn=1;
	clickEnable=1;
	posX=e.pageX;
	sdvigSumm=0;

	mnDiv=c;
	slideDiv=d;
}
function SdigDiv (e){
	if (moveOn==1){
		sdvig=e.pageX-posX;	/*Определение сдвига мыши*/
		sdvigSumm=sdvigSumm+sdvig;
		if (sdvigSumm<-10||sdvigSumm>10) clickEnable=0;
		sdvig=sdvig+otstup;	/*Корекция на бывший отступ*/
		document.getElementById(slideDiv).style.margin="0px 0px 0px "+sdvig+"px";
	}
}
function TouchEnded(e){
	CheckOtstup();
	moveOn=0;
}

function CheckOtstup(){
	var dmWidth = parseInt(document.getElementById('DevMenu').style.width);
	var dmsWidth = parseInt(document.getElementById('DevMenuSlider').style.width);

	var maxSdvig = dmWidth-dmsWidth-15;
	if (dmsWidth<dmWidth)maxSdvig = -2;

var sdvigCorrect;
	if (sdvig>-40) sdvigCorrect=0;
	else if (sdvig<maxSdvig+40) sdvigCorrect=maxSdvig;
	else sdvigCorrect=(parseInt((sdvig+38)/76)-1)*76;

	if (sdvig>0){
		sdvig=sdvig-2;
	}
	else if (sdvig<maxSdvig){
		sdvig=sdvig+2;
	}
	else if (sdvig!=sdvigCorrect){
		if (sdvig>sdvigCorrect) sdvig--;
		else sdvig++;
	}

	document.getElementById('DevMenuSlider').style.margin="0px 0px 0px "+sdvig+"px";
	if (maxSdvig>sdvig || sdvig>0 || sdvig!=sdvigCorrect) setTimeout(arguments.callee, 1);
	else {otstup=sdvig;}
}
