/*									O N L O A D									*/
var siteDiv, menu_holderDiv, menuDiv, bodyDiv;
window.addEventListener('DOMContentLoaded', function() {
	siteDiv=document.getElementById("site");
	menu_holderDiv=document.getElementById("menu_holder");
	menuDiv=document.getElementById("menu");
	bodyDiv=document.getElementById("body");

	menu_holderDiv.style.display="block";
	menuDiv.style.width=menuWidth+'px';
	bodyDiv.style.display="block";

	/*			Enable animation			
	setTimeout(function () {
		document.getElementById("menu").style.transition='0.1s';
		document.getElementById("body").style.transition='0.1s';
	}, 1);*/

	/*	Open/Close */
	siteWdth = siteDiv.clientWidth;
	if (siteWdth<=560) MenuClose ();
	else MenuOpen ();

	window.addEventListener('resize', function() {Resize ();});
	Resize ();
});


/*									O N R E S I Z E									*/
var menuOpened=0, menuWidth=160;
var siteWdth, siteWdthLast=0, siteWdthType='big';
function Resize () {
	siteWdth = siteDiv.clientWidth;

	/*			If width is changed			*/
	if (siteWdth!=siteWdthLast){
		/*			Close			*/
		if (siteWdth<=560 && siteWdthLast>560) {//
			MenuClose ();
			//console.log('close');
		}
		/*			Open			*/
		else if (siteWdth>560 && siteWdthLast<=560) {
			MenuOpen ();
			//console.log('open');
		}

		siteWdthLast=siteWdth;
		//console.log(siteWdth);
	}
	
}


/*									M E N U   O P E N / C L O S E									*/
function MenuOpen () {
	menuDiv.style.marginLeft='0px';
	bodyDiv.style.marginLeft=menuWidth+'px';
	if (siteWdth<560) bodyDiv.style.width='100%';
	else bodyDiv.style.width='calc(100% - '+(menuWidth+1)+'px)';
	menu_holderDiv.style.display="block";
	menuOpened=1;
	// body height
	document.getElementById('body').style.minHeight=document.getElementById('menu_holder').clientHeight + 20;
	// myhome
	//if (document.location.href.split('/')[3]=='myhome') ResizeButs();
}
function MenuClose () {
	menuDiv.style.marginLeft='-'+menuWidth+'px';
	bodyDiv.style.marginLeft='1px';
	bodyDiv.style.width='calc(100% - 2px)';
	menu_holderDiv.style.display="none";
	menuOpened=0;
	// myhome
	//if (document.location.href.split('/')[3]=='myhome') ResizeButs();
}
function MenuOnClick(){
	siteWdth = siteDiv.clientWidth;
	if (menuOpened==1) MenuClose ();
	else MenuOpen ();
	setTimeout("window.dispatchEvent(new Event('resize'));",100);
}





/*									B L O K   O P E N / C L O S E									*/
function ChangeTextCode (bloackId,butId) {
	var text_div=document.getElementById(bloackId);
	var but_div=document.getElementById(butId);
	if (text_div.style.display=='none') {
		text_div.style.display='block';
		but_div.innerHTML='Скрыть код...';
	}
	else {
		text_div.style.display='none';
		but_div.innerHTML='Показать код...';
	}
}

