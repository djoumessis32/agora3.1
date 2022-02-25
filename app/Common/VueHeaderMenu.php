<script type="text/javascript">	
////	Init la page
$(function(){
	//affiche les livecounters
	livecounterUpdate(true);
});

////	Menu des modules sur 2 ligne en mode "responsive" ?
$(window).resize(function(){
	if($(window).width()<800){
		var modulesMenuCellsWidth=0;
		$('.vModulesMenuCell').each(function() {modulesMenuCellsWidth+=$(this).width();});
		if($(window).width()<500)	{modulesMenuCellsWidth=(modulesMenuCellsWidth/2);}//Menu sur 2 lignes
		$("#headerBarRight").css("width",modulesMenuCellsWidth+40);
	}
});

////	Update des livecounters (principal/messenger) et nouveau message du messenger?
function livecounterUpdate(initLivecouters)
{
	if($(".vLivecounterPrincipal").exist())
	{
		$.ajax({url:"?ctrl=misc&action=LivecounterUpdate"+(initLivecouters==true?"&initLivecounter=true":""),dataType:"json"}).done(function(ajaxResult){
			//Livecounter principal
			if(typeof ajaxResult.livecounterPrincipal!="undefined"){
				$(".vLivecounterPrincipalUsers").html(ajaxResult.livecounterPrincipal);
				var displayLivecounter=(ajaxResult.livecounterPrincipal.length>0) ? "inline" : "none";
				$(".vLivecounterPrincipal").css("display",displayLivecounter);
			}
			//Livecounter du messenger
			if(typeof ajaxResult.livecounterMessenger!="undefined"){
				//refrech : on retiens dans un tableau les users deja cochées
				if(initLivecouters==false)    {var oldCheckedUsers=messengerPostUsersChecked();}
				//On affiche/refrech les users du messenger (checkbox)
				$(".vMessengerUsers").html(ajaxResult.livecounterMessenger);
				//refrech : on recoche les users sélectionnés avant refresh du livecounter
				if(initLivecouters==false){
					for(var userIdKey in oldCheckedUsers)	{$("#messengerUserBox"+oldCheckedUsers[userIdKey]).prop("checked",true);}
				}
			}
			//Nouveaux messages du messenger : son d'alerte & animation de l'icone
			if(typeof ajaxResult.messengerNewMessages!="undefined" && $(".vLivecounterPrincipalUsers").html().length>0){
				$(".vMessengerAlertSound").html("<audio controls autoplay><source src='app/misc/messengerAlert.mp3' type='audio/mpeg'></audio>");
				if($(".vMessengerBlock").css("display")!="none")	{messengerGetMessages();}
				else												{$(".vMessengerIcon").addClass("vMessengerIconPulsate").effect("pulsate",{times:50},50000);}
			}
			//relance après x secondes (microtime!)
			setTimeout(function(){livecounterUpdate(false);}, <?= LIVECOUNTER_REFRESH*1000 ?>);
		});
	}
}

////	Affichage / masquage du messenger
function showHideMessenger(_idUser)
{
	$(".vMessengerBlock").toggle(200,function(){
		if($(".vMessengerBlock").css("display")!="none")
		{
			//Recupère la liste des messages
			messengerGetMessages();
			//Preselectionne un utilisateur (si besoin)
			if(typeof _idUser!="undefined")	{$("#messengerUserBox"+_idUser).prop("checked",true);}
			// Repositionne le messenger (si besoin) : Messenger au dessus / en dessous de la page visible => on le rend accessible
			var messengerTopPosition=parseInt($(".vMessengerBlock").css("top").replace("px",""));
			if(($(".vMessengerBlock").outerHeight() + messengerTopPosition) < $(document).scrollTop())	{$(".vMessengerBlock").css("top", ($(document).scrollTop()+messengerTopPosition)+"px");}
			if(($(window).height() + $(document).scrollTop()) < messengerTopPosition)					{$(".vMessengerBlock").css("top", ($(document).scrollTop()+120)+"px");}
		}
	});
}

////	Recupère les messages du messenger
function messengerGetMessages()
{
	$.ajax("?ctrl=misc&action=MessengerGetMessages").done(function(ajaxResult){
		//Affiche les derniers messages en se placant en bas de page
		$(".vMessengerMessages").html(ajaxResult).scrollTop($(".vMessengerMessages")[0].scrollHeight);
		// Annule l'animation de l'icone (si besoin)
		$(".vMessengerIcon").stop(true,true).css("opacity",100).removeClass("vMessengerIconPulsate");
	});
}

////	Controle & post du message du messenger
function messengerPost()
{
	// Message spécifié?
	if($("#messengerPostMessage").val()=="" || $("#messengerPostMessage").val()=="<?= Txt::trad("HEADER_MENU_ajouter_message") ?>")
		{displayNotif("<?= Txt::trad("HEADER_MENU_specifier_message") ?>");  return false;}
	// Vérif des utilisateurs cochés
	var checkedUsers=messengerPostUsersChecked();
	if(checkedUsers.length==0)	{displayNotif("<?= Txt::trad("selectionner_user") ?>"); return false;}
	// On poste le message, relance l'affichage des messages et récupère les messages
	$.ajax({
		url:"?ctrl=misc&action=MessengerPostMessage",
		data:{message:$("#messengerPostMessage").val(),color:$("#messengerPostColor").val(),messengerPostUsers:checkedUsers},
		type:"POST"
	}).done(function(){
		$("#messengerPostMessage").val("");
		$("#messengerPostMessage").focus();
		messengerGetMessages();
	});
}

////	Liste des "messengerPostUsers" checked
function messengerPostUsersChecked()
{
	return $("[name='messengerPostUsers']:checked").map(function(){ return $(this).val(); }).get();
}

////	Animation des icones de chaque module (sous IE8, prob. de transparences des png)  +  redirection vers le module
function moduleAnimeIconRedir(thisIcon, redirUrl)
{
	$(thisIcon).effect("pulsate",{times:1},300);
	setTimeout(function(){ redir(redirUrl); },400);
}
</script>


<style>
/*Header Bar*/
.vHeaderBarHrMarginTop		{visibility:hidden; height:60px;}
.vHeaderBar					{height:50px;}
.vHeaderBarLogo				{display:table-cell; width:70px;}
.vHeaderBarMenus			{display:table-cell;}
.vHeaderBarMenusTop			{display:table-cell; height:30px; vertical-align:middle;}
.vHeaderBarMenusbottom		{display:table-cell; height:25px; vertical-align:top;}
.headerBarLeft				{background-repeat:repeat-x;}
#headerBarCenter			{display:table-cell; width:65px; background-repeat:repeat-x;}
#headerBarRight				{display:table-cell; width:10%; text-align:right; background-repeat:repeat-x;}/*ajusté automatiquement*/
.vHeaderLogo				{position:fixed; height:65px; z-index:22; left:2px; top:2px;}
#headerMainMenu				{padding-bottom:0px;}
.headerMainMenuLogo			{display:inline-block; width:100%; height:15px; margin-top:10px; background-repeat:no-repeat; background-position:center;}
img[src*='developp'],img[src*='shortcut'],img[src*='check']  {margin-left:3px;}
.vMenuUserBlock, .vMenuSpaceBlock, .vMenuShortcutBlock, .vRegisterUserBlock	{display:inline-block; margin-left:10px;}
#menuSpace					{max-width:500px;}
.vMenuContextSpaceList .menuContextIcon	{text-align:right;}
.vConnectForm				{display:inline;}
.vConnectLogin				{width:130px;}
.vConnectPassword			{width:80px;}
.vLiveCounterUsers			{cursor:pointer; color:#fd4;}
.vLabelCurModule			{float:right; font-style:italic; text-transform:uppercase; line-height:20px; color:#eee; text-shadow:2px 1px 1px #777;}

/*Messenger*/
.vLivecounterPrincipal		{display:none;}
.vMessengerIcon				{margin-left:10px; cursor:pointer; position:absolute; height:33px;}
.vMessengerIconPulsate		{height:45px;}
.vMessengerBlock			{display:none; width:595px; height:445px; position:absolute; z-index:23; left:200px; top:100px;}
.vMessengerBlockTable		{display:table; width:100%; height:100%; background-image:url(app/img/messengerBackground.png); background-repeat:no-repeat;}
.vMessengerHeader			{display:table-row; height:25px; cursor:move;}
.vMessengerHeaderClose		{float:right; margin:-7px; height:35px; cursor:pointer;}
.vMessengerBody				{display:table-row;}
.vMessengerBodyTable		{display:table; width:100%; height:100%;}
.vMessengerMessagesDiv		{display:table-cell; width:72%;}
.vMessengerUsersDiv			{display:table-cell; width:28%;}
.vMessengerMessages,.vMessengerUsers	{height:370px; overflow:auto;}
.vMessengerMessageLine		{display:table; cursor:help;}
.vMessengerMessageTitle		{display:table-row; vertical-align:middle; padding:5px; text-align:center;}
.vMessengerMessageTitle .personPicture	{max-width:30px; max-height:30px;}
.vMessengerMessageUser		{display:table-cell; padding:4px; width:80px; text-align:right;}
.vMessengerMessageContent	{display:table-cell; padding:4px;}
.vMessengerUser				{display:table; width:100%;}
.vMessengerUserImg			{display:table-cell; width:25px; padding:2px;}
.vMessengerUserImg .personPicture	{max-width:25px; max-height:25px;}
.vMessengerUserBox			{display:table-cell; vertical-align:middle; width:20px;}
.vMessengerUserLabel		{display:table-cell; vertical-align:middle;}
.vMessengerPost				{display:table-row; height:35px; text-align:center;}
#messengerPostMessage		{width:400px; height:18px; margin-right:5px; font-weight:bold; background:transparent; border:solid 1px #fff; color:<?= $messengerFormMessageColor ?>;}
.vMessengerAlertSound		{height:1px; visibility:hidden;}

/*Menu des modules*/
.vModulesMenu				{display:inline-table;}
.vModulesMenuCell			{display:table-cell; cursor:pointer; text-align:center; padding-top:3px; padding-left:3px; padding-right:3px;}
.vModulesMenu label			{display:block; margin:1px; margin-top:0px; font-size:85%; font-style:italic; opacity:0.7; white-space:nowrap; <?= (Ctrl::$agora->moduleLabelDisplay!="icones"?"display:none;":"") ?>}
.vModulesMenuIcon			{opacity:0.85; max-height:<?= (Ctrl::$agora->moduleLabelDisplay=="icones"?"40px":"50px") ?>}
.vModulesMenuIcon:hover		{opacity:1;}
.vModulesMenuIconMask		{opacity:0.5;}
.vModulesMenuIconMask:hover	{opacity:1;}
.vModulesMenuIconSelect		{opacity:1; <?= (Ctrl::$agora->moduleLabelDisplay=="icones"?"max-height:45px":"margin-top:8px;") ?>}

/*Responsive*/
@media screen and (max-width:800px)
{
	.vHeaderBarLogo			{width:40px;}/*logo principal*/
	.vHeaderLogo			{height:50px;}/*idem*/
	.headerBarLeft, #headerBarRight	{background-image:url(app/img/headerRightW.png); background-repeat:repeat-x;}/*Image de fond du HeaderMenu*/
	.vMenuUserBlock, .vMenuSpaceBlock	{margin-top:8px;}/*libellé de l'user & de l'espace*/
	img[src*='developp']	{margin-left:1px;}/*idem*/
	.vMenuShortcutBlock, .vRegisterUserBlock, #headerBarCenter	{display:none;}/*le menu des raccourcis, inscriptions d'users, HeaderMenu du centre */
	/*Menu des modules*/
	.vModulesMenu			{display:block; text-align:center;}
	.vModulesMenuCell		{display:inline-block; padding:0px; padding-top:3px;}
	.vModulesMenu label		{display:none;}
	.vModulesMenuIcon		{opacity:0.8; max-height:30px;}
	.vModulesMenuIconSelect	{margin-top:0px; opacity:1;}
}
</style>


<hr class="vHeaderBarHrMarginTop noPrint">
<div class="headerBar vHeaderBar noPrint">
	<div class="vHeaderBarLogo headerBarLeft">
		<div class="menuContext sBlock" id="headerMainMenu">
			<!--ESPACE : DECONNEXION + RECHERCHE + ENVOI INVITATION + PARAMETRAGE DU SITE-->
			<div class="menuContextLine"><div class="menuContextIcon"><img src="app/img/logout.png"></div><a href="?disconnect=1" class="menuContextTxt"><?= Txt::trad("HEADER_MENU_sortie_agora") ?></a></div>
			<div class="menuContextLine sLink" onclick="lightboxOpen('?ctrl=object&action=Search');"><div class="menuContextIcon"><img src="app/img/search.png"></div><div class="menuContextTxt"><?= Txt::trad("HEADER_MENU_recherche_elem") ?></div></div>
			<?php if(Ctrl::$curUser->sendInvitationRight()){ ?><div class="menuContextLine sLink" onclick="lightboxOpen('?ctrl=user&action=SendInvitation');" title="<?= Txt::trad("USER_envoi_invitation_info") ?>"><div class="menuContextIcon"><img src="app/img/mail.png"></div><div class="menuContextTxt"><?= Txt::trad("USER_envoi_invitation") ?></div></div><?php } ?>
			<div class="menuContextLine sLink" onclick="lightboxOpen('docs/DOCUMENTATION_<?= Txt::trad("CURLANG")=="fr"?"FR":"EN"?>.pdf');"><div class="menuContextIcon"><img src="app/img/info.png"></div><div class="menuContextTxt"><?= Txt::trad("HEADER_MENU_documentation") ?></div></div>
			<!--MENU ADMINISTRATEUR GENERAL-->
			<?php if(Ctrl::$curUser->isAdminGeneral()){ ?>
				<hr>
				<div class="menuContextLine"><div class="menuContextIcon"><img src="app/img/paramsGeneral.png"></div><a href="?ctrl=agora" class="menuContextTxt"><?= Txt::trad("AGORA_description_module") ?></a></div>
				<div class="menuContextLine"><div class="menuContextIcon"><img src="app/img/paramsGeneral.png"></div><a href="?ctrl=space" class="menuContextTxt" title="<?= Txt::trad("SPACE_description_module_infos") ?>"><?= Txt::trad("SPACE_gerer_espaces") ?></a></div>
				<div class="menuContextLine"><div class="menuContextIcon"><img src="app/img/user/icon.png"></div><a href="?ctrl=user&displayUsers=all" class="menuContextTxt"><?= Txt::trad("USER_gerer_utilisateurs_site") ?></a></div>
				<hr>
				<div class="menuContextLine"><div class="menuContextIcon"><img src="app/img/log.png"></div><a href="?ctrl=log" class="menuContextTxt"><?= Txt::trad("LOG_description_module") ?></a></div>
				<div class="menuContextLine cursorHelp" title="<?= $diskSpacePercent. " % ".Txt::trad("de")." ".File::displaySize(limite_espace_disque) ?>"><div class="menuContextIcon"><img src="app/img/diskSpaceLevel<?= $diskSpaceLevel ?>.png"></div><div class="menuContextTxt"><?= Txt::trad("espace_disque_utilise")." : ".File::displaySize(File::datasFolderSize()) ?></div></div>
			<?php } ?>
			<a href="<?= AGORA_PROJECT_URL ?>" target="_blank" title="<?= AGORA_PROJECT_URL_DISPLAYED ?>" class="headerMainMenuLogo">&nbsp;</a>
		</div>
		<img src="app/img/logo.png" class="vHeaderLogo menuContextLauncher" for="headerMainMenu">
	</div>
	<div class="vHeaderBarMenus headerBarLeft">
		<div class="vHeaderBarMenusTop">

			<!--USER : NOM/PRENOM + MENU PROFIL + PARAM MESSENGER + AFFICHAGE NORMAL/AUTEUR/ADMIN-->
			<?php if(Ctrl::$curUser->isUser()){ ?>
			<span class="vMenuUserBlock">
				<div class="menuContext sBlock" id="vMenuUser">
					<div class="menuContextLine sLink" onclick="lightboxOpen('<?= Ctrl::$curUser->getUrl("edit") ?>');"><div class="menuContextIcon"><img src="app/img/user/profilEdit.png"></div><div class="menuContextTxt"><?= Txt::trad("USER_modifier_mon_profil") ?></div></div>
					<?php if(Ctrl::$curUser->messengerEnabled()){ ?>
						<div class="menuContextLine sLink" onclick="lightboxOpen('?ctrl=user&action=UserEditMessenger&targetObjId=<?= Ctrl::$curUser->_targetObjId ?>');" title="<?= Txt::trad("USER_visibilite_messenger_livecounter") ?>"><div class="menuContextIcon"><img src="app/img/messengerSmall.png"></div><div class="menuContextTxt"><?= Txt::trad("USER_gestion_messenger_livecounter") ?></div></div>
					<?php } ?>
					<hr>
					<div><?= Txt::trad("HEADER_MENU_display_elem") ?></div>
					<?php foreach($displayObjects as $tmpDisplay){ ?>
						<div class="menuContextLine"><div class="menuContextIcon">&nbsp;</div><div class="menuContextTxt"><img src="app/img/arrowRight.png"> <a href="?ctrl=<?= Req::$curCtrl ?>&displayObjects=<?= $tmpDisplay ?>" class="<?= ($_SESSION["displayObjects"]==$tmpDisplay)?'sLinkSelect':'sLink' ?>" title="<?= Txt::trad("HEADER_MENU_display_title_".$tmpDisplay) ?>"><?= Txt::trad("HEADER_MENU_display_".$tmpDisplay) ?></a></div></div>
					<?php } ?>
				</div>
				<span class="sLink menuContextLauncher" for="vMenuUser"><?= Ctrl::$curUser->firstName." ".Ctrl::$curUser->name ?><img src="app/img/developp.png"></span>
			</span>
			<?php }else{ ?>
			<!--INVITE : CONNEXION-->
			<span class="vMenuUserBlock">
				<form action="index.php" method="post" OnSubmit="return controlConnect()" class="vConnectForm">
					<input type="text" name="connectLogin" class="vConnectLogin" placeholder="<?= Txt::trad("placeholderLogin") ?>">
					<input type="password" name="connectPassword" class="vConnectPassword" placeholder="<?= Txt::trad("password") ?>">
					<?= Txt::formValidate("connexion",false) ?>
				</form>
			</span>
			<?php } ?>

			<!--NOM DE L'ESPACE COURANT (+EDITION?) + LISTE DES ESPACES DISPO-->
			<span class="vMenuSpaceBlock">
				<?php if($displayMenuSpaces==true){ ?>
				<div class="menuContext sBlock" id="menuSpace">
					<?php if($isAdminCurSpace==true){ ?><div class="menuContextLine sLink" onclick="lightboxOpen('<?= Ctrl::$curSpace->getUrl("edit"); ?>');"><div class="menuContextIcon"><img src="app/img/params.png"></div><div class="menuContextTxt"><?= Txt::trad("SPACE_parametrage") ?></div></div><?php } ?>
					<?php if(!empty($menuSpaces)){ ?>
						<div class="menuContextLine">
							<div class="menuContextIcon"><img src="app/img/space.png"></div>
							<div class="menuContextTxt"><?= Txt::trad("HEADER_MENU_espaces_dispo") ?> :</div>
						</div>
					<?php } ?>
					<?php foreach($menuSpaces as $tmpSpace){ ?>
					<div class="menuContextLine vMenuContextSpaceList">
						<div class="menuContextIcon"><img src="app/img/arrowRight.png"></div>
						<div class="menuContextTxt"><a href="?_idSpaceAccess=<?= $tmpSpace->_id ?>" title="<?= $tmpSpace->description ?>"> <?= $tmpSpace->name ?></a></div>
					</div>
					<?php } ?>
				</div>
				<?php } ?>
				<span class="menuContextLauncher" for="menuSpace"><?= Ctrl::$curSpace->name ?><?php if($displayMenuSpaces==true){ ?><img src="app/img/developp.png"><?php } ?></span>
			</span>

			<!--LISTE DES RACCOURCIS DES MODULES-->
			<?php if(count($pluginsShortcut)>0){ ?>
			<span class="vMenuShortcutBlock">
				<div class="menuContext sBlock" id="vMenuShortcut">
				<?php foreach($pluginsShortcut as $tmpPlugin){ ?>
					<div class="menuContextLine sLink"><div class="menuContextIcon" onclick="<?= $tmpPlugin->pluginJsIcon ?>"><img src="app/img/<?= $tmpPlugin->pluginIcon ?>" class="pluginIcon"></div><div class="menuContextTxt" title="<?= @$tmpPlugin->pluginTitle ?>" onclick="<?= $tmpPlugin->pluginJsLabel ?>"><?= $tmpPlugin->pluginLabel ?></div></div>
				<?php }?>
				</div>
				<span class="sLink menuContextLauncher" for="vMenuShortcut"><img src="app/img/shortcut.png"> <?= Txt::trad("HEADER_MENU_shortcuts") ?></span>
			</span>
			<?php } ?>

			<!--VALIDE L'INSCRIPTION D'UTILISATEURS ?-->
			<?php if($isAdminCurSpace==true && Db::getVal("SELECT count(*) FROM ap_userInscription WHERE _idSpace=".(int)Ctrl::$curSpace->_id)>0){ ?>
			<span class="vRegisterUserBlock">
				<span class="labelRegisterUser sLink" title="<?= Txt::trad("usersInscription_validation_title") ?>" onclick="lightboxOpen('?ctrl=user&action=registerUser');"><?= Txt::trad("usersInscription_validation") ?><img src="app/img/check.png"></span>
				<script> $(".labelRegisterUser").effect("pulsate",{times:10},10000); </script>
			</span>
			<?php } ?>
		</div>
		<div class="vHeaderBarMenusBottom">
			<!--LIVECOUNTER PRINCIPAL + ICONE MESSENGER-->
			<?php if(Ctrl::$curUser->messengerEnabled()){ ?>
			<span class="vLivecounterPrincipal">
				<span class="vLivecounterPrincipalUsers"></span>
				<img src="app/img/messenger.png" class="vMessengerIcon" onclick="showHideMessenger();" title="<?= Txt::trad("HEADER_MENU_messenger") ?>">
			</span>
			<?php } ?>
			<span class="vLabelCurModule"><?= $labelCurModule ?></span>
		</div>
	</div>
	<div id="headerBarCenter">
		&nbsp;
	</div>
	<div id="headerBarRight" class="no_selection">
		<!--MENU DES MODULES-->
		<div class="vModulesMenu">
			<?php foreach($moduleList as $tmpModule){ ?>
			<div class="vModulesMenuCell" onclick="moduleAnimeIconRedir(this,'<?= $tmpModule["url"] ?>');">
				<label><?= $tmpModule["label"] ?></label>
				<img src="app/img/<?= $tmpModule["moduleName"] ?>/icon.png" class="vModulesMenuIcon <?= $tmpModule["iconClass"] ?>" title="<?= $tmpModule["description"] ?>">
			</div>
			<?php } ?>
		</div>
	</div>
</div>


<!--  MESSENGER  -->
<?php if(Ctrl::$curUser->messengerEnabled()){ ?>
<div class="vMessengerAlertSound"></div>
<div class="vMessengerBlock">
	<div class="vMessengerBlockTable" onMouseOver="$(this).draggable({handle:'.messengerDrag',opacity:0.8});">
		<div class="vMessengerHeader messengerDrag" title="<?= Txt::trad("deplacer") ?>">
			<img src="app/img/close.png" class="vMessengerHeaderClose" OnClick="showHideMessenger();" title="<?= Txt::trad("fermer") ?>">
		</div>
		<div class="vMessengerBody">
			<div class="vMessengerBodyTable">
				<div class="vMessengerMessagesDiv"><div class="vMessengerMessages">&nbsp;</div></div>
				<div class="vMessengerUsersDiv"><div class="vMessengerUsers">&nbsp;</div></div>
			</div>
		</div>
		<div class="vMessengerPost">
			<a href="?ctrl=misc&action=MessengerDownloadMessages"><img src="app/img/download.png" title="<?= Txt::trad("HEADER_MENU_enregistrer_conversation") ?>"></a>
			<?= Tool::colorPicker("messengerPostMessage","messengerPostColor","color") ?>&nbsp;
			<input type="text" name="message" id="messengerPostMessage" value="<?= Txt::trad("HEADER_MENU_ajouter_message") ?>" maxlength="500" onFocus="if(this.value=='<?= Txt::trad("HEADER_MENU_ajouter_message",true) ?>'){this.value='';}" onKeyUp="if(event.keyCode==13){messengerPost();}">
			<input type="hidden" name="color" id="messengerPostColor" value="<?= $messengerFormMessageColor ?>">
			<button OnClick="messengerPost();"><?= Txt::trad("envoyer") ?></button>
			<?php if(!empty($_SESSION["messengerPostColor"]))  {echo "<script>$('#messengerPostMessage').css('color','".$_SESSION["messengerPostColor"]."');  $('#messengerPostColor').val('".$_SESSION["messengerPostColor"]."');</script>";} ?>
		</div>
	</div>
</div>
<?php } ?>