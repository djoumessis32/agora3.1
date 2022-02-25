/*
 * ONLOAD : INIT  LA PAGE !!			(DEBUG : "console.log(variable);" pour afficher une valeur dans la console du débugger)
 */
$(function(){
	// Fonctions de base
	extendJquery();
	initTooltips();
	checkboxLabel();
	checkboxLabelTriggers();
	// Fonctions avancées
	pageLoadTimestamp=Date.now();										//Timestamp en Millisecondes!
	setObjBlockWidth();													//Définie la largeur des blocks d'objets dans les pages principales
	objectBlock();														//Actions sur les blocks d'objet Click/DoubleClick/etc..
	menuContext();														//Menus contextuels (des objets ou autre)
	pageMenuFloat();													//Menus flottants de gauche
	inputControls();													//Initialise les controles de champs -> Datepickers, FileSize controls, Integer, etc
	fieldsetOptionsNav();												//Navigation par onglet des fieldsets
	lightboxGallery();													//Gallerie d'image via fancybox
	$(".confirmEventLib").effect("pulsate",{times:5},5000);				//"pulsate" sur les confirmations d'événements
	$(document).toastmessage({position:"top-center",stayTime:10000});	//Notifications avec "toastmessage" (cf."displayNotif")
	$("input[type='password']").attr("autocomplete","off");				//Pas d'autocomplete des passwords
});
/*
 * ONLOAD AJAX : Mise à jour partielle de page après requete Ajax (messenger, etc)
 */
$(document).ajaxSuccess(function(){
	extendJquery();
	initTooltips();
	checkboxLabel();
	checkboxLabelTriggers();
});
/*
 * RESIZE DE LA FENETRE : RESIZE LES BLOCKS D'OBJETS
 */
$(window).resize(function(){ setObjBlockWidth(); });



/*
 * ONLOAD : Fonctions étendues à Jquery
 */
function extendJquery()
{
	////	Verifie l'existance d'un element	(ex "existe()")
	$.fn.exist=function(){
		return (this.length>0) ? true : false;
	};
	////	Verifie si l'element n'a pas de valeur ("empty") ...et aussi s'il existe
	$.fn.isEmpty=function(){
		return (this.length==0 || this.val().length==0) ? true : false;
	};
	////	Focus sur un champs
	$.fn.fieldFocus=function(){
		this.css("box-shadow","2px 2px 6px #955").focus();
	};
	////	Affichage/masquage d'element : "surcharge" de fonctions de base de Jquery
	var fadeInBASIC=$.fn.fadeIn;
	var fadeOutBASIC=$.fn.fadeOut;
	var showBASIC=$.fn.show;
	var hideBASIC=$.fn.hide;
	var toggleBASIC=$.fn.toggle;
	var slideToggleBASIC=$.fn.slideToggle;
	var fadeToggleBASIC=$.fn.fadeToggle;
	$.fn.fadeIn=function(){		lightboxHeightResize(); return fadeInBASIC.apply(this,arguments); };
	$.fn.fadeOut=function(){	lightboxHeightResize(); return fadeOutBASIC.apply(this,arguments); };
	$.fn.show=function(){		lightboxHeightResize(); return showBASIC.apply(this,arguments); };
	$.fn.hide=function(){		lightboxHeightResize(); return hideBASIC.apply(this,arguments); };
	$.fn.toggle=function(){		lightboxHeightResize(); return toggleBASIC.apply(this,arguments); };
	$.fn.slideToggle=function(){lightboxHeightResize(); return slideToggleBASIC.apply(this,arguments); };
	$.fn.fadeToggle=function(){ lightboxHeightResize(); return fadeToggleBASIC.apply(this,arguments); };
}

/*
 * ONLOAD : Init les tooltips avec Tooltipster (mettre la class "tooltip" & l'attribut "title" à la balise html)
 */
function initTooltips()
{
	$("[title]").not(".noTooltip,[title='']").tooltipster({
		contentAsHTML:true,
		multiple:true,
		animation:"grow",
		delay:700,
		speed:100,
		theme:"tooltipster-shadow"
	});
}

/*
 * ONLOAD : Stylise les labels rattachés à une checkbox (checked ou pas?)
 * -> la balise <label> doit avoir une propriété "for" correspondant à un "id" de checkbox
 */
function checkboxLabel()
{
	//Checkboxes avec un "id" : stylise leurs labels. Sauf les menu d'affectation ("objectRight[]")	 =>  Attention à la rapidité avec des centaines de boxes !!
	$(":checkbox[id],:radio[id]").not("[name='objectRight[]']").each(function(){
		if(this.checked)		{$("label[for='"+this.id+"']").removeClass("sLink").addClass("sLinkSelect");}
		else if(!this.disabled)	{$("label[for='"+this.id+"']").removeClass("sLinkSelect").addClass("sLink");}
		else					{$("label[for='"+this.id+"']").css("cursor","default");}//désactivé : pas de "cursor:pointer". Mettre en dernier pour + de rapidité!
	});
}
//Click un label rattaché à une checkbox (ou inversement) : change le style
function checkboxLabelTriggers()
{
	$("label, :checkbox[id], :radio[id]").change(function(){
		//"checkboxLabelTriggers()" lancé avec 50ms de décallage => pour pas lancer des dixaines de demandes en même temps si on check/unckeck plusieurs box!
		if(typeof checkboxLabelTriggersTimeOut=="undefined" || (Date.now()-checkboxLabelTriggersTimeOut)>50){
			//dernier lancement de "timeSlotBusy()"
			checkboxLabelTriggersTimeOut=Date.now();
			//Lance avec un décalage : le temps que d'éventuels mutiples checks se fassent
			setTimeout(function(){ checkboxLabel(); },20);
		}
	});
}

/*
 * ONLOAD : Définie la largeur des blocks d'objets dans les pages principales
 */
function setObjBlockWidth()
{
	if($(".objDBlock").exist() && $(".objDBlock").length>0)
	{
		//Marge des objets  &  taille max des objets (avec marge..)  &  largeur disponible
		var objectMargin=parseInt($(".objDBlock").css("margin-right").replace("px",""));
		var objectMinWidth=parseInt($(".objDBlock").css("min-width").replace("px",""));
		var objectMaxOuterWidth=parseInt($(".objDBlock").css("max-width").replace("px","")) + objectMargin;
		var containerWidth=$(".pageFullContent").width();//pas de "innerWidth()" car ajoute le "padding"
		//Pas de scroller de la page => anticipe son apparition (avec l'agrandissement du ".pageMenu" de gauche ou autre)
		if($(document).height()==$(window).height())	{containerWidth=containerWidth-18;}
		//Nb maxi d'objets par ligne
		var lineNbObjects=Math.ceil(containerWidth / objectMaxOuterWidth);
		//Calcul de la largeur des objets
		var objectWidth=null;
		if(containerWidth < (objectMinWidth*2))			{objectWidth=containerWidth;}							//Le conteneur ne peut afficher qu'un objet par ligne : ce dernier occupera toute la largeur du conteneur
		else if($(".objDBlock").length<lineNbObjects)	{objectWidth=objectMaxOuterWidth;}						//Le nb d'objets est insuffisant pour remplir la première ligne => "objectWidth"="objectMaxOuterWidth"
		else											{objectWidth=Math.floor(containerWidth/lineNbObjects);}	//Calcul en fonction de la largeur du conteneur et du nb d'objets par ligne
		//Applique la largeur des blocks (enlève le margin, car pas pris en compte par le "outerWidth")
		$(".objDBlock").outerWidth((objectWidth-objectMargin)+"px");
	}
}

/*
 * ONLOAD : Actions sur les blocks conteneurs des objets (l'id du block doit commencer par "objBlock_")
 */
function objectBlock()
{
	$("[id^='objBlock_']").on("click",function(){
		var blockId="#"+this.id;
		var isDblClick=(typeof timeLastClick!="undefined" && (Date.now()-timeLastClick)<300);
		if(isDblClick==true && $(blockId).attr("data-urlEdit"))	{lightboxOpen($(blockId).attr("data-urlEdit"));}//DblClick : edition de l'objet
		else if(typeof objSelect=="function")					{objSelect(this.id);}							//Click + "objSelect()" : sélectionne l'objet (cf. "VueObjMenuSelection.php")
		else if($(blockId).attr("data-onclickJs")){																//Click + "data-onclickJs" : execute "onclickJs"
			setTimeout(function(onclickJs){
				if(timeLastClick==="undefined" || (Date.now()-timeLastClick)>=300)	{eval(onclickJs);}
			},300, $(blockId).attr("data-onclickJs"));//"Timeout" de 300ms pour de pas interférer avec un dblClick. "data-onclickJs" passé en parametre du "setTimeout"
		}
		timeLastClick=Date.now();//Update "lastClickTime"
	});
}

/*
 * ONLOAD : Gestion des Menus Contextuels
 * -> tous les launcher (icone/texte du menu || block de l'objet) doivent avoir une propriété "for" correspondant à l'ID du Menu Contextuel
 * -> l'icone/texte doit posséder une class "menuContextLauncher" (seul le block de l'objet possède la propriété "for")
 */
function menuContext()
{
	////	Affiche/Masque le menu context
	$(".menuContextLauncher").on("click mouseover mouseout",function(event){  menuContextAction(this,event);  });		//Action sur le launcher icone/texte
	$("[for^='objMenuContext_']").on("contextmenu",function(event){  menuContextAction(this,event);  return false;  });	//Click droit sur le block d'un objet. "return false" car on affiche pas le menu du browser
	////	Click/dblClick sur le menu context ou le launcher (responsive) : pas de probagation sur le block de l'objet (selection du block & co)
	$(".menuContext,.menuContextLauncher").on("click dblclick",function(event){  event.stopPropagation();  });
}
function menuContextAction(target, event)
{
	////	Init le menu :  Identifiant  &&  Ferme les autres menus  &&  hauteur max du menu (en fonction de hauteur de page)
	var menuContextId="#"+$(target).attr("for");
	$(".menuContext").not(menuContextId).css("display","none");
	var footerHeight=$("#pageFooter").outerHeight(true)+10;
	$(menuContextId).css("max-height", Math.ceil($(window).outerHeight(true)-footerHeight)+"px");
	////	Position du menu
	if(event.type=="contextmenu")	{var menuPosX=event.pageX-10;				var menuPosY=event.pageY-10;}//on prend la position de la souris
	else							{var menuPosX=$(target).position().left-3;	var menuPosY=$(target).position().top-3;}//on prends la position du "menuContextLauncher"
	////	Repositionne le menu s'il est au bord droit ou bas de la page
	var borderRightPos=menuPosX + $(menuContextId).width();
	var borderBottomPos=menuPosY + $(menuContextId).height();
	var pageBottomPos=$(window).height()+$(window).scrollTop();
	if(borderRightPos > $(window).width())	{menuPosX = menuPosX - (borderRightPos-$(window).width()) - 30;}
	if(borderBottomPos > pageBottomPos)		{menuPosY = menuPosY - (borderBottomPos-pageBottomPos) - footerHeight;}
	$(menuContextId).css("left", Math.round(menuPosX)+"px");
	$(menuContextId).css("top", Math.round(menuPosY)+"px");
	////	Affiche/Masque le menu
	if(event.type=="mouseout")	{$(menuContextId).css("display","none");}	//"mouseout" : masque le menu
	else						{$(menuContextId).fadeIn(100);}				//"mouseover" ou click : affiche le menu
	if(tactileDevice())  {$(".menuContextClose").css("display","inline");}//tactileDevice() : Affiche "close"
	////	Entrée/Sortie du menu contextuel (pas de "mouseover" ou "mouseout"!)
	$(menuContextId).mouseenter(function(){ $(this).css("display","inline"); }).mouseleave(function(){ $(this).css("display","none"); });
}

/*
 * ONLOAD : Menu de gauche flottant		(ex "menuFlottant()")
 */
function pageMenuFloat()
{
	if($(".pageMenu").exist())
	{
		var pageMenuPos=$(".pageMenu").position();
		$(window).scroll(function(){
			var pageMenuHeight=pageMenuPos.top;//position top du menu
			$(".pageMenu").children().each(function(){ pageMenuHeight+=$(this).outerHeight(); });//hauteur de chaque element
			if(pageMenuHeight < $(window).height())		{$(".pageMenu").css("padding-top",$(window).scrollTop()+"px");}
		});
	}
}

/*
 * ONLOAD : Initialise les controles de champs -> Datepickers, FileSize controls, Integer, etc
 */
function inputControls()
{
	////	Init le Datepicker jquery-UI
	$(".dateInput, .dateBegin, .dateEnd").datepicker({
		dateFormat:"dd/mm/yy",
		firstDay:1,
		showOtherMonths: true,
		selectOtherMonths: true,
		onSelect:function(date){
			//Select .dateBegin -> bloque la date minimum de .dateEnd (mais pas inversement!)
			if($(this).hasClass("dateBegin"))	{$(".dateEnd").datepicker("option","minDate",date);}
			//Trigger sur le champ concerné pour continuer l'action
			$(this).trigger("change");
		}
	});
	////	Init le plugin Timepicker (jquery-UI)
	$(".timeBegin, .timeEnd").timepicker({
		timeFormat:"H:i"
	});

	////	Controle les dates de début/fin												(ex "recup_dates()")
	$(".dateBegin, .dateEnd, .timeBegin, .timeEnd").change(function(){
		//Masque le champ H:M?
		if($(this).hasClass("dateBegin") || $(this).hasClass("dateEnd")){
			var timeClass=$(this).hasClass("dateBegin") ? ".timeBegin" : ".timeEnd";
			if($(this).isEmpty()==false)	{$(timeClass).show();}
			else							{$(timeClass).hide();  $(timeClass).val(null);}
		}
		//Controle des date/time
		if($(".dateBegin").isEmpty()==false || $(".dateEnd").isEmpty()==false)
		{
			//Controle des "H:M"
			if($(this).hasClass("timeBegin") || $(this).hasClass("timeEnd"))
			{
				//Champ à controler
				var timeClass=$(this).hasClass("timeBegin") ? ".timeBegin" : ".timeEnd";
				//controle Regex des H:M
				var timeRegex=/^[0-2][0-9][:][0-5][0-9]$/;
				if($(timeClass).isEmpty()==false && timeRegex.test($(timeClass).val())==false){
					displayNotif("H:m error");
					$(timeClass).val(null);
					return false;
				}
				//précise H:M de fin si vide et début précisé
				if($(".timeEnd").isEmpty())		{$(".timeEnd").val($(".timeBegin").val());}
			}
			//Début après Fin : message d'erreur
			if($(".dateBegin").isEmpty()==false && $(".dateEnd").isEmpty()==false)
			{
				var timestampBegin=$(".dateBegin").datepicker("getDate").getTime()/1000;//getTime() renvoie des millisecondes..
				var timestampEnd=$(".dateEnd").datepicker("getDate").getTime()/1000;//idem
				if($(".timeBegin").isEmpty()==false)	{var hourMinute=$(".timeBegin").val().split(":");	timestampBegin=timestampBegin + (hourMinute[0]*3600) + (hourMinute[1]*60);}
				if($(".timeEnd").isEmpty()==false)		{var hourMinute=$(".timeEnd").val().split(":");		timestampEnd=timestampEnd + (hourMinute[0]*3600) + (hourMinute[1]*60);}
				if(timestampBegin > timestampEnd){
					displayNotif(labelDateBeginEndControl);
					$(".dateEnd").val($(".dateBegin").val());
					$(".timeEnd").val($(".timeBegin").val());
				}
			}
		}
	});

	////	Controle la taille des fichiers des inputs "file"
	$("input[type='file']").change(function(){
		if($(this).isEmpty()==false && this.files[0].size > valueUploadMaxFilesize){
			$(this).val("");
			displayNotif(labelUploadMaxFilesize);
		}
	});

	////	Inputs de type "integer" -> doivent avoir une classe "integerValue"
	$(".integerValue").on("change keyup",function(){ 
		$(this).val($(this).val().replace(/\D/g,''));
	});

	////	Affecte une couleur à un input "select" (chaque option doit avoir un attribut "data-color")
	$("select option").each(function(){
		var optionColor=$(this).attr("data-color");
		if(typeof optionColor!="undefined")	{$(this).css("background-color",optionColor).css("color","#fff");}
	});
	$("select").change(function(){
		var optionColor=$("select[name='"+this.name+"'] option[value='"+this.value+"']").attr("data-color");
		if(typeof optionColor!="undefined")	{$("select[name='"+this.name+"']").css("background-color",optionColor).css("color","#fff");}
		else								{$("select[name='"+this.name+"']").css("background-color","#fff").css("color","#000");}
	});
}

/*
 * ONLOAD : Navigation par onglet des fieldsets
 * -> les onglets du menu ".fieldsetOptions" doivent avoir un div avec attribut "for" correspondant à leur blocks (à afficher/masquer)
 */
function fieldsetOptionsNav()
{
	//Change de menu
	$(".fieldsetOption").on("click",function(){
		//Masque les autres menus
		$(".fieldsetOption").not(this).each(function(){
			var blockToHide="#"+$(this).attr("for");
			$(blockToHide).css("display","none");
			$(this).removeClass("sFieldsetOptionSelect").addClass("sFieldsetOptionUnselect");
		});
		//Affiche le block sélectionné
		var blockToDisplay=$(this).attr("for");
		$("#"+blockToDisplay).fadeIn(200);
		$(this).removeClass("sFieldsetOptionUnselect").addClass("sFieldsetOptionSelect");
		//Scroll en bas de page après chargement de page.. et timeOut, le temps de l'action du "fancybox.update()" de "lightboxHeightResize()"
		if((Date.now()-pageLoadTimestamp)>1000){
			setTimeout(function(){toScroll();},400);
		}
	});
	//Affiche le menu du premier onglet
	$(".fieldsetOption:first").trigger("click");
}

/*
 * Controle s'il s'agit d'un mail (ex "controle_mail()")
 */
function isMail(mail)
{
	var mailRegex=/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return mailRegex.test(mail);
}

/*
 * Cherche une expression dans une chaine de caracteres  (ex "trouver()")
 */
function find(searchedExpression, string)
{
	//Convertir en texte (toString), puis en minuscule (toLowerCase). Rechercher enfin la chaine en récupérant sa position.
	return (string.toString().toLowerCase().indexOf(searchedExpression) >= 0 || string.toString().indexOf(searchedExpression) >= 0) ? true : false;
}

/*
 * Affiche un message de notification (via le plugin Jquery "toastmessage")
 */
function displayNotif(message, typeNotif)
{
	if(typeNotif=="success")		{toastType="showSuccessToast";}	//Type "success" (vert)
	else if(typeNotif=="warning")	{toastType="showWarningToast";}	//Type "warning" (jaune)
	else							{toastType="showNoticeToast";}	//Type "info" (bleu) => par défaut
	if(inIframe())	{parent.$().toastmessage(toastType, message);}
	else			{$().toastmessage(toastType, message);}
}

/*
 * Contrôle de connexion à l'agora
 */
function controlConnect()
{
	var inputLogin=$("[name=connectLogin]");
	var inputPassword=$("[name=connectPassword]");
	if(inputLogin.isEmpty() || inputLogin.val()==inputLogin.attr("placeholder") || inputPassword.isEmpty() || inputPassword.val()==inputPassword.attr("placeholder")){
		displayNotif(labelSpecifierLoginPassword);
		return false;
	}
}

/*
 * Redirection page
 */
function redir(adress, redirMainPage)
{
	if(redirMainPage===true)	{window.parent.location.href=adress;}
	else						{window.location.href=adress;}
}

/*
 * On est dans une iframe ?
 */
function inIframe()
{
	return (window.parent && window.parent.location!=window.location)  ?  true  :  false;
}

/*
 * ONLOAD : Initialise les galleries d'image ayant l'attribut "rel='lightboxGallery'"
 */
function lightboxGallery()
{
	$("[rel='lightboxGallery']").fancybox({
		openEffect: 'elastic',
    	closeEffect: 'elastic',
		padding:0,//image sans bordure
		helpers:{
			title:{type:'over'}
		}
	});
}

/*
 * Ouvre une lightbox
 * Utilise une fonction et non des "<a href>" comme préconisé dans la doc. : plus souple et cela n'interfère pas avec les "stopPropagation()" des menuContext() (sinon les lightboxes ne se lanceraient pas..)
 */
function lightboxOpen(url)
{
	//Lancé depuis une iframe : relance depuis la fenetre parente
	if(inIframe()){
		parent.lightboxOpen(url);
	}
	//Affiche un lecteur pour un fichier pdf/mp3/video
	else if(extension(url)=="pdf" || extension(url)=="mp3" || extension(url)=="mp4" || extension(url)=="webm" || extension(url)=="ogv")
	{
		var lightboxContent=null;
		if(extension(url)=="pdf")		{lightboxContent='<object data="'+url+'" type="application/pdf" height="'+Math.floor($(window).height()-100)+'px" width="'+Math.floor($(window).width()-100)+'px">PDF plugin is required</object>';}
		else if(extension(url)=="mp3")	{lightboxContent='<audio controls autoplay><source src="'+url+'" type="audio/mpeg">HTML5 browser is required</audio>';}
		else							{lightboxContent='<video controls autoplay><source src="'+url+'" type="video/'+extension(url)+'">HTML5 browser is required</video>';}
		$("#lightboxContent").fancybox({
			type:"html",
			content:lightboxContent
		}).click();
		//redimensionne en décalé, le temps que le pdf soit chargé
		setTimeout(function(){$.fancybox.update();},2000);
	}
	//Iframe
	else
	{
		$("#lightboxContent").fancybox({
			type:"iframe",
			href:url,
			padding:5,//padding entre le conteneur et le contenu
			margin:[20,20,20,5],//margin entre le fancybox et le bord de la page
			//Init le lightbox"
			beforeShow:function(){
				//Page d'édition d'objet & click/keyup sur le contenu : demande confirmation à la fermeture de page
				confirmCloseLightbox=false;
				if(find("edit",url))	{$(".fancybox-iframe").contents().find("body").on("click keyup",function(){ confirmCloseLightbox=true; });}
				//Width en fonction de "lightboxWidth()"
				this.width=$(".fancybox-iframe").contents().find("body").width();
			},
			//Demande confirmation de fermeture si on edite un element
			beforeClose:function(){
				if(confirmCloseLightbox==true)	{return confirm(labelConfirmCloseLightbox);}
			}
		}).click();
	}
}

/*
 * Largeur d'une Lightbox (appelé depuis le lightbox)					(ex "resizePopupLightbox()")
 */
function lightboxWidth(pageWidth)
{
	$(function(){
		if(find("px",pageWidth))		{pageWidth=pageWidth.replace("px","");}									//width en pixel
		else if(find("%",pageWidth))	{pageWidth=($(window.parent).width()/100) * pageWidth.replace("%","");}//idem
		if(typeof pageWidth!="undefined")	{$("body").css("width",pageWidth);}	//Applique le width
		parent.$.fancybox.update();												//Redimensionne le fancybox
	});
}

/*
 * Si besoin, agrandit la hauteur d'une Lightbox en fonction de son contenu et suite à un fadeIn(), FadeOut(), toogle(), etc.  (appelé depuis le lightbox)
 */
function lightboxHeightResize()
{
	//Agrandi qu'une fois la page chargée
	if(inIframe() && (Date.now()-pageLoadTimestamp)>1000){
		//Agrandit (si besoin) avec 0.5 secondes de latence, le temps que les "toggle" and Co fassent leur oeuvre
		var lightboxHeightOld=parent.$(".fancybox-iframe").contents().height();
		setTimeout(function(){
			//Agrandi uniquement si ya besoin
			if(parent.$(".fancybox-iframe").contents().height() > lightboxHeightOld)	{parent.$.fancybox.update();}
		},500);
	}
}

/*
 * Afficher un "prompt" via le lightbox
 */
function lightboxPrompt(promptLabel, actionJS, inputType, defaultValue)
{
	if(typeof inputType=="undefined" || inputType==null)		{inputType="text";}//"text" ou "password"
	if(typeof defaultValue=="undefined" || defaultValue==null)	{defaultValue="";}//valeur par défaut dans l'input
	// Construit le formulaire
	var promptForm= "<div class='lightboxTitle'>"+promptLabel+"</div>"+
					"<input type='"+inputType+"' class='promptInputText' value=\""+defaultValue+"\" onkeydown=\"if(event.keyCode==13){"+actionJS+"}\"> &nbsp;"+
					"<button type='button' onclick=\"if($('.promptInputText').isEmpty()==false){"+actionJS+"}\">OK</button>";
	// Affiche le prompt et met le focus sur l'input
	$("#lightboxContent").fancybox({width:400,height:200,content:promptForm}).click();
	setTimeout(function(){$.fancybox.update();},1000);
	$(".promptInputText").focus();
}

/*
 * Ferme le lightbox & reload si besoin la page principale (appelé depuis le lightbox)					(ex "reload_close(url)")
 */
function lightboxClose(reloadParentPage, reloadSpecificUrl, urlMsgNotif)
{
	//Reload la page principale ? Url => passé en paramètre OU .parent (lightbox)
	var reloadUrl=null;
	if(reloadParentPage===true){
		reloadUrl=(typeof reloadSpecificUrl!="undefined" && reloadSpecificUrl!=null)  ?  reloadSpecificUrl  :  window.parent.location.href;
		//Spécifie "urlMsgNotif" : enlève les anciens "msgNotif" déjà affichés et ajoute le "urlMsgNotif"
		if(typeof urlMsgNotif!="undefined"){
			if(find("msgNotif",reloadUrl))	{reloadUrl=reloadUrl.substring(0,reloadUrl.indexOf('&msgNotif'));}
			reloadUrl+=urlMsgNotif;
		}
	}
	//Ferme le lightbox
	if(reloadUrl!=null)	{window.parent.location.replace(reloadUrl);}
	else				{parent.$.fancybox.close();}
}

/*
 * Vérifie si on est sur un appareil mobile/tactile
 */
function tactileDevice()
{
	var pattern=/android|iphone|ipad|ipod|blackberry|windows phone|tablet|touch/i;
	return (pattern.test(navigator.userAgent)) ? true : false;
}

/*
 * Version obsolete d'IE (ex "version_ie()") ?
 */
function isObsoleteIE()
{
	var vBrowser=navigator.appVersion;
	return (find("MSIE 5.",vBrowser) || find("MSIE 6.",vBrowser) || find("MSIE 7.",vBrowser) || find("MSIE 8.",vBrowser));
}

/*
 * Confirmer une action (suppression ou autre) puis rediriger							(ex "confirmer()")
 */
function confirmRedir(confirmLabel, redirUrl, ajaxControlUrl, ajaxConfirmLabel)
{
	//Effectue un controle Ajax?
	if(typeof ajaxControlUrl!="undefined"){
		var ajaxResult=$.ajax({url:ajaxControlUrl,async:false}).responseText;//Retour Ajax obligatoire pour passer à la suite : async:false
		if(ajaxResult!="true" && confirm(ajaxConfirmLabel)==false)	{return false;}//exple : "Attention! certains sous-dossiers ne vous sont pas accessibles en lecture.."
	}
	//Lance la suppression?
	if(confirm(confirmLabel))	{redir(redirUrl);}
}

/*
 * Scroll vers un element OU en bas de page (idem ancre nommee)
 */
function toScroll(selector)
{
	var heightReference=(typeof selector=="undefined")  ?  $(document).height()  :  $(selector).offset().top;
	$("html,body").animate({scrollTop:heightReference},200);
}

/*
 * Extension d'un fichier (sans le point!)
 */
function extension(fileName)
{
	return fileName.split('.').pop().toLowerCase();
}

/*
 * Confirmation (ou pas) d'événement
 */
function confirmEventProposition(_idCal, _idEvt, divId)
{
	//Init
	var confirmed=false;
	var ajaxUrl="?ctrl=calendar&action=confirmEventProposition&targetObjId=calendar-"+_idCal+"&_idEvt="+_idEvt;
	//Demande de confirmation
	if(confirm(labelEvtConfirm))			{ajaxUrl+="&confirmed=1";  confirmed=true;}
	else if(confirm(labelEvtConfirmNot))	{ajaxUrl+="&confirmed=0";  confirmed=true;}
	//Lance la requête en Ajax
	if(confirmed==true){
		$.ajax(ajaxUrl).done(function(ajaxResult){
			if(find("true",ajaxResult)==false)	{displayNotif("Confirm event proposition error");}
			else								{$("#"+divId).fadeOut(200);}
		});
	}
}

/*
 * Affectations des Spaces<->Users : userEdit OU spaceEdit (Click de Label/Checkbox)
 */
function initSpaceAffectations()
{
	//Click de Label
	$(".spaceAffectTable label").on("click",function(){
		//init
		var _idTarget=this.id.replace("target","");
		var box1="[name='spaceAffect[]'][value='"+_idTarget+"_1']";
		var box2="[name='spaceAffect[]'][value='"+_idTarget+"_2']";
		//Bascule les checkboxes
		var boxToCheck=null;
		if(!$(box1).prop("disabled") && !$(box1).prop("checked") && !$(box2).prop("checked"))	{boxToCheck=box1;}
		else if($(box1).prop("checked") && !$(box2).prop("checked"))							{boxToCheck=box2;}
		//Uncheck les boxes (sauf si disabled), Check celle sélectionnée
		$("[name='spaceAffect[]'][value^='"+_idTarget+"_']").not(":disabled[name='spaceAffect[]']").prop("checked",false);
		if(boxToCheck!=null)	$(boxToCheck).prop("checked",true);
		//Style des labels
		spaceAffectStyle();
	});
	//Click de Checkbox
	$(".spaceAffectTable :checkbox").change(function(){
		var _idTarget=$(this).val().slice(0, $(this).val().lastIndexOf("_"));//exple "1_2" => "1"
		$("[name='spaceAffect[]'][value^='"+_idTarget+"_']").not(this).not(":disabled[name='spaceAffect[]']").prop("checked",false);//"uncheck" les autres checkbox du "target"
		spaceAffectStyle();//Style des labels
	});
	//Init le style des labels
	spaceAffectStyle();
};

/*
 * Applique un style aux labels avec une checkbox cochée
 */
function spaceAffectStyle()
{
	//Réinit le style des labels et rows
	$(".spaceAffectTable label").removeClass("sAccessRead sAccessWrite");
	$(".spaceAffectRow").removeClass("sTableRowSelect");
	//Stylise les labels && la ligne sélectionnées
	$("[name='spaceAffect[]']:checked").each(function(){
		//récupère l'id de la cible && le droit d'accès associé à la box
		var targetRight=this.value.split('_').pop();
		var targetId=this.value.replace('_'+targetRight, '');
		//Stylise le label et la ligne
		if(targetRight=="2")		{$("#target"+targetId).addClass("sAccessWrite");}
		else if(targetRight=="1")	{$("#target"+targetId).addClass("sAccessRead");}
		$("#rowTarget"+targetId).addClass("sTableRowSelect");
	});
}

/*
 * Calcul la hauteur disponible pour le contenu principal de la page
 */
function availableContentHeight()
{
	//Hauteur de la fenêtre (pas de la page!)  -  Position "top" du contenu de la page  -  Hauteur du footer de la page  -  10px de marge avec le footer
	return Math.round($(window).height() - $(".pageCenterContent,.pageFullContent,.pageEmptyContent").offset().top - $("#pageFooterShadow").outerHeight());
}