<script type="text/javascript">
$(function(){
	////	Affectations : Click du Label
	$("[id^='spaceBlock']:visible label").on("click",function(){
		//Init
		var boxRead		 ="#objectRightBox_"+this.id+"_1";
		var boxWriteLimit="#objectRightBox_"+this.id+"_15";
		var boxWrite	 ="#objectRightBox_"+this.id+"_2";
		var boxToCheck=null;
		//Bascule les checkbox : lecture / ecriture limité / écriture
		if(!$(boxRead).prop("disabled") && !$(boxRead).prop("checked") && !$(boxWriteLimit).prop("checked") && !$(boxWrite).prop("checked"))	{boxToCheck=boxRead;}		//"1" actif && tout est décochées
		else if(!$(boxWriteLimit).prop("disabled") && !$(boxWriteLimit).prop("checked") && !$(boxWrite).prop("checked"))						{boxToCheck=boxWriteLimit;}	//"1.5" actif && "1.5" décoché && "2" décoché
		else if(!$(boxWrite).prop("disabled")  &&  !$(boxWrite).prop("checked")  &&  ( ($(boxRead).prop("disabled") && $(boxWriteLimit).prop("disabled")) || ($(boxRead).prop("checked") && $(boxWriteLimit).prop("disabled")) || $(boxWriteLimit).prop("checked")))	{boxToCheck=boxWrite;}	//"2" actif && "2" décoché &&  ( ("1" inatif & "1.5" inatif) || ("1" coché & "1.5" inactif) || "1.5" coché)
		//Uncheck toutes les boxes, Check la box sélectionnée
		$("[id^='objectRightBox_"+this.id+"']").prop("checked",false);
		if(boxToCheck!=null)	{$(boxToCheck).prop("checked",true).trigger("change");}//"trigger" pour lancer si besoin des actions au changement de checkboxes
		//Style des labels & controle des droits
		labelStyleRightControl();
	});

	////	Affectations : Click de checkbox
	$("[id^='spaceBlock']:visible [id^='objectRightBox']").change(function(){
		var targetId=$(this).val().slice(0, $(this).val().lastIndexOf("_"));//exple "1_U2_1.5" => "1_U2"
		$("[id^='objectRightBox_"+targetId+"']").not(this).prop("checked",false);//"uncheck" les autres checkbox du "target"
		labelStyleRightControl();//Style des labels & controle des droits
	});

	////	Selectionne un nouveau fichier joint
	$("input[name^='newAttachedFile']").change(function(){
		//Fichier OK : affiche l'input suivant et affiche au besoin "insertion dans le text" (+ Check par défaut)
		if($(this).isEmpty()==false && this.files[0].size < <?= File::uploadMaxFilesize() ?>)
		{
			var cptFile=Math.round(this.name.replace("newAttachedFile",""));
			var fileExtension=extension($(this).val());
			$("#newAttachedFile"+(cptFile+1)).fadeIn(200);
			if($("#newAttachedFileOptions"+cptFile).exist() && $.inArray(fileExtension,['<?= implode("','",File::fileTypes("attachedFileInsert")) ?>'])!==-1){
				$("#newAttachedFileOptions"+cptFile).css("display","inline-block");
				$("#newAttachedFileInsert"+cptFile+":not(:checked)").trigger("click");
			}
		}
	});

	////	Affiche/Masque les blocks d'espaces
	//Masque les espaces sans affectations (sauf espace courant)
	$("[id^='spaceBlock']").each(function(){
		if(this.id!="spaceBlock<?= Ctrl::$curSpace->_id ?>" && $("#"+this.id+" [name='objectRight[]']:checked").length==0)	{$(this).css("display","none");}
	});
	//montre "Afficher tous les espaces"?
	if($("[id^='spaceBlock']:hidden").length>0)  {$("#showAllSpaces").fadeIn(200).effect("pulsate",{times:4},4000);}
	//Click sur "Afficher tous les espaces"
	$("#showAllSpaces").on("click",function(){
		$("#showAllSpaces").css("display","none");
		$("[id^='spaceBlock']").fadeIn(200);
	});

	////	Init la page
	//Masque et désactive les droits "boxWriteLimit"
	<?php if($curObj::isContainer()==false){ ?>
		$("[name='objectRight[]'][value$='_1.5']").prop("disabled",true);
		$(".vSpaceTargetWriteLimit").css("display","none");
	<?php } ?>
	//Init le style des labels
	labelStyleRightControl();
	//Focus sur le premier champ obligatoire (fin de text)
	<?php if(!empty($curObj::$requiredFields))  {echo "$('input[name=".$curObj::$requiredFields[0]."]').focus().val($('input[name=".$curObj::$requiredFields[0]."]').val());";} ?>
});

////	Stylise les labels et controle les droits d'accès
function labelStyleRightControl()
{
	notifAdviceRight=null;
	//Réinitialise les class des lignes et labels
	$("[id^='spaceBlock']:visible label").removeClass("sAccessRead sAccessWriteLimit sAccessWrite");
	$("[id^='spaceBlock']:visible [id^=targetLine]").removeClass("sTableRowSelect");
	//Stylise les label des checkbox sélectionnées
	$(":checkbox[name='objectRight[]']:checked").each(function(){
		//Récupère le droit de la checkbox && l'id du label correspondant
		var targetRight=this.id.split('_').pop();
		var targetLabelId=this.id.substring(0, this.id.lastIndexOf('_')).replace('objectRightBox_','');
		//Stylise le label
		if(targetRight=="1")		{$("#"+targetLabelId).addClass("sAccessRead");}
		else if(targetRight=="15")	{$("#"+targetLabelId).addClass("sAccessWriteLimit");}
		else if(targetRight=="2")	{$("#"+targetLabelId).addClass("sAccessWrite");}
		//Ligne sélectionnée : surligne
		$("#targetLine"+targetLabelId).addClass("sTableRowSelect");
		//Sujet du forum & droit ecriture & pas l'user courant : "Le droit en écriture permet d'effacer TOUS les messages du sujet!"
		if(targetRight=="2" && targetLabelId!="2_U<?= Ctrl::$curUser->_id ?>" && "<?= $curObj::objectType ?>"=="forumSubject")
			{notifAdviceRight="<?= Txt::trad("EDIT_OBJET_alert_ecriture_deconseille") ?>";}
	});
	if(notifAdviceRight!==null)  {displayNotif(notifAdviceRight);}
}

////	Suppression d'un fichier joint
function deleteAttachedFile(_id)
{
	if(confirm("<?= Txt::trad("confirmDelete") ?>")){
		var ajaxUrl="?ctrl=object&action=deleteAttachedFile&_id="+_id;
		var ajaxResult=$.ajax({url:ajaxUrl,async:false}).responseText;//Retour Ajax obligatoire pour passer à la suite : async:false
		if(find("true",ajaxResult)){
			$("#menuAttachedFile"+_id).fadeOut(200);
			tinymce.activeEditor.dom.remove("tagAttachedFile"+_id);//pas besoin de "#" pour select l'id
		}
	}
}

////	Controle final du formulaire	(ex "Controle_Menu_Objet()")
function finalFormControl()
{
	//Init
	var validForm=true;
	////	Verif des champs obligatoires (s'ils sont spécifiés)
	var notifRequiredFields="";
	<?php foreach($curObj::$requiredFields as $tmpField){?>
	var isEmptyField=<?= ($tmpField==$curObj::htmlEditorField)  ?  "isEmptyEditor('".$curObj::htmlEditorField."');"  :  "$(\"[name='".$tmpField."']\").isEmpty();" ?>//champs tinyMce OU input "text"
	if($("[name='<?= $tmpField ?>']").exist() && isEmptyField==true){
		notifRequiredFields+="<br><?= Txt::trad($tmpField) ?>";
		$("[name='<?= $tmpField ?>']").fieldFocus();
		validForm=false;
	}
	<?php } ?>
	//Notif pour les champs obligatoires vides
	if(notifRequiredFields.length>0)	{displayNotif("<?= Txt::trad("champs_obligatoire") ?> : "+notifRequiredFields);}
	////	Controle le formatage des dates
	$(".dateInput,.dateBegin,.dateEnd").each(function(){
		if(this.value.length>0)
		{
			var matches=/^\d{2}\/\d{2}\/\d{4}$/.exec(this.value);
			if(matches==null){
				displayNotif("<?= Txt::trad("dates_mauvais_format") ?>");
				validForm=false;
			}
		}
	});
	////	Controle d'un invité
	if($("[name='guest']").exist()){
		if($("[name='guest']").isEmpty())	{displayNotif("<?= Txt::trad("EDIT_OBJET_alert_guest") ?>");	validForm=false;}
		else if(captchaControl()==false)	{validForm=false;}
	}
	////	Controle les affectations
	if($("[name='objectRight[]']").length>0)
	{
		//Aucune affectation : false!
		if($(":checked[name='objectRight[]']").length==0)	{displayNotif("<?= Txt::trad("EDIT_OBJET_alert_aucune_selection") ?>");  validForm=false;}
		//Sujet du forum et uniquement des accès en lecture : false!
		if("<?= $curObj::objectType ?>"=="forumSubject" && $(":checked[name='objectRight[]'][value$='_1.5'], :checked[name='objectRight[]'][value$='_2']").length==0)
			{displayNotif("<?= Txt::trad("EDIT_OBJET_alert_ecriture_obligatoire") ?>");  validForm=false;}
		//Aucun accès pour l'user courant?
		var nbCurUserAccess=$(":checked[name='objectRight[]'][value*='spaceGuests'], :checked[name='objectRight[]'][value*='spaceUsers'], :checked[name='objectRight[]'][value*='allSpaces'], :checked[name='objectRight[]'][value*='_U<?= Ctrl::$curUser->_id ?>_']").length;
		if(nbCurUserAccess==0 && confirm("<?= Txt::trad("EDIT_OBJET_alert_pas_acces_perso") ?>")==false)
			{validForm=false;}
	}
	////	Controle un mail (si besoin)
	if($("input[name='mail']").isEmpty()==false && !isMail($("input[name='mail']").val()))   {displayNotif("<?= Txt::trad("mail_pas_valide"); ?>");  return false;}
	////	Controle OK
	if(validForm==true)	{$(".loadingImg").css("display","inline-block");}
	return validForm;
}
</script>


<style>
/***Divers*/
[id^='blockMenuEdit']:not([id='blockMenuEditMain'])	{text-align:left; padding:5px;}
/***Affectations aux espaces*/
[id^='spaceBlock']						{margin:10px; max-height:350px; overflow-y:auto; -moz-user-select:none; -webkit-user-select:none; -ms-user-select:none;}
.vSpaceTable							{display:inline-table; min-width:80%;}
.vSpaceTitle, .vSpaceTarget				{display:table-row;}
.vSpaceTitle label, .vSpaceTitle div	{cursor:help; padding-bottom:5px;}
.vSpaceTargetRead, .vSpaceTargetWrite, .vSpaceTargetWriteLimit	{display:table-cell; min-width:80px; padding-left:7px;}
.vSpaceTargetWriteLimit					{width:140px;}
.vSpaceTitle label, .vSpaceTarget label	{display:table-cell; min-width:220px; text-align:left; vertical-align:middle;}
.vSpaceTitle label						{cursor:auto;}
.vSpaceTarget label						{height:22px;}
.vSpaceTarget label img					{vertical-align:middle; max-height:15px;}
.vSpaceTarget label img[src*='dot']		{max-height:12px;}
.vSpaceTargetWriteLimit img[src*='edit']{opacity:0.6;}
.vRightsOptions							{margin:10px;}
#showAllSpaces							{display:none; cursor:pointer; text-align:center;}
/***Fichiers joints*/
[id^='newAttachedFile']							{display:none; text-align:left; margin-left:15px;}
[id='newAttachedFile1']							{display:block;}
[id^='newAttachedFileOptions']					{display:none;}
.attachedFiles									{display:table; text-align:left; margin-left:30px; margin-top:10px;}
.attachedFiles:empty							{display:none;}
[id^='menuAttachedFile']						{display:table-row;}
[id^='menuAttachedFile'] div:nth-child(1)		{display:table-cell; padding-top:5px;}
[id^='menuAttachedFile'] div:nth-child(2)		{display:table-cell; padding-top:5px; padding-left:10px;}
[id^='menuAttachedFile'] div:nth-child(2) img	{max-height:20px; margin-right:5px;}
/***Notification par mail*/
#notifMailUsersPlus, #notifMailUsersPlusList, .notifMailUserPlusHidden, .notifMailOptions	{display:none;}
.notifMailOptions	{float:right; text-align:right;}
</style>


<!--INITIALISE L'EDITEUR HTML POUR UN CHAMP?-->
<?php if($curObj::htmlEditorField!==null)	{echo CtrlMisc::initHtmlEditor($curObj::htmlEditorField);} ?>


<!--ONGLETS DES MENUS-->
<div class="fieldsetOptions">
	<?php
	if($mainMenu!=false)		{echo "<div class='fieldsetOption noSelect' for='blockMenuEditMain'><img src='app/img/edit.png'> ".$mainMenuLabel." ".($curObj::isContainer()?"<img src='app/img/info.png' title=\"".$curObj->tradObject("ecriture_auteur_admin")."\">":null)."</div>";}
	if(!empty($notifMail))		{echo "<div class='fieldsetOption noSelect' for='blockMenuEditNotifMail' title=\"".Txt::trad("EDIT_OBJET_notif_mail_info")."\"><img src='app/img/mail.png'> ".Txt::trad("EDIT_OBJET_notif_mail")."</div>";}
	if(!empty($attachedFiles))	{echo "<div class='fieldsetOption noSelect' for='blockMenuEditAttachedFiles' title=\"".Txt::trad("EDIT_OBJET_fichier_joint_info")."\"><img src='app/img/attachment.png'> ".Txt::trad("EDIT_OBJET_fichier_joint").(!empty($attachedFilesList)?"&nbsp;<img src='app/img/dotG.png'>":null)."</div>";}
	if(!empty($shortcut))		{echo "<div class='fieldsetOption noSelect' for='blockMenuEditShortcut' title=\"".Txt::trad("EDIT_OBJET_shortcut_info")."\"><img src='app/img/shortcut.png'> ".Txt::trad("EDIT_OBJET_shortcut").(!empty($shortcutChecked)?"&nbsp;<img src='app/img/dotG.png'>":null)."</div>";}
	?>
</div>

<!--CONTENU DES MENUS-->
<?php if(!empty($mainMenu) || !empty($attachedFiles) || !empty($notifMail) || !empty($shortcut)){ ?>
<fieldset class="fieldsetCenter fieldsetMarginTop sBlock">

	<!--MENU PRINCIPAL-->
	<?php if(!empty($mainMenu)){ ?>
	<div id="blockMenuEditMain">
		<!--MENU GUEST : IDENTIFICATION-->
		<?php if($mainMenu=="identification"){ ?>
			<?= Txt::trad("EDIT_OBJET_guest") ?> <input type="text" name="guest" onkeyup="this.value=this.value.slice(0,150)"><hr>
			<?= CtrlMisc::menuCaptcha() ?>
		<?php } ?>
		<!--MENU USER : (OBJETS INDEPENDANTS)-->
		<?php if($mainMenu=="accessRights"){ ?>
			<!--DROIT D'ACCES DES BLOCK D'ESPACES-->
			<?php foreach($blocksAccessRight as $spaceCpt=>$tmpSpace){ ?>
			<div id="spaceBlock<?= $tmpSpace->_id ?>">
				<?= $spaceCpt>0?"<hr>":null ?>
				<div class="vSpaceTable">
					<!--ENTETE DE L'ESPACE-->
					<div class="vSpaceTitle">
						<label title="<?= $tmpSpace->description ?>">
							<?= $tmpSpace->name ?>
							<?php if($tmpSpace->curModuleEnabled==false){ ?><img src="app/img/important.png" title="<?= Txt::trad("EDIT_OBJET_espace_pas_module") ?>"><?php } ?>
						</label>
						<div class="vSpaceTargetRead" title="<?= Txt::trad("lecture_infos") ?>"><?= Txt::trad("lecture") ?> <img src="app/img/eye.png"></div>
						<div class="vSpaceTargetWriteLimit" title="<?= $WriteLimitInfos ?>"><?= Txt::trad("ecriture_limit") ?> <img src="app/img/edit.png"></div>
						<div class="vSpaceTargetWrite" title="<?= Txt::trad("ecriture_infos") ?>"><?= Txt::trad("ecriture") ?> <img src="app/img/edit.png"></div>
					</div>
					<!--TARGETS DE L'ESPACE (NE PAS AJOUTER D'ID AU CHECKBOXES. CF. "boxProp")-->
					<?php foreach($tmpSpace->targetsLines as $tmpTarget){ ?>
					<div class="vSpaceTarget sTableRow sAccessDefault" id="targetLine<?= $tmpTarget["targetId"] ?>">
						<label id="<?= $tmpTarget["targetId"] ?>" <?= !empty($tmpTarget["tooltip"]) ? 'title="'.Txt::reduce($tmpTarget["tooltip"],400).'"' : null ?>>
							<?= !empty($tmpTarget["labelIcon"]) ? "<img src='app/img/".$tmpTarget["labelIcon"]."'>" : null ?>
							<?= $tmpTarget["labelText"] ?>
							<?= !empty($tmpTarget["labelIconBis"]) ? "<img src='app/img/".$tmpTarget["labelIconBis"]."'>" : null ?>
						</label>
						<div class="vSpaceTargetRead" title="<?= Txt::trad("lecture_infos") ?>"><input type="checkbox" name="objectRight[]" <?= $tmpTarget["boxProp"]["1"] ?>></div>
						<div class="vSpaceTargetWriteLimit" title="<?= $WriteLimitInfos ?>"><input type="checkbox" name="objectRight[]" <?= $tmpTarget["boxProp"]["1.5"] ?>></div>
						<div class="vSpaceTargetWrite" title="<?= Txt::trad("ecriture_infos") ?>"><input type="checkbox" name="objectRight[]" <?= $tmpTarget["boxProp"]["2"] ?>></div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
			<!--OPTION "AFFICHER TOUS LES ESPACES"-->
			<?php if(count($blocksAccessRight)>1){ ?><div id="showAllSpaces"><?= Txt::trad("EDIT_OBJET_tous_espaces") ?> <img src="app/img/developp.png"></div><?php } ?>
			<!-- OPTIONS : ETENDRE LES DROITS AUX SOUS-DOSSIERS / INFOS SUR LES DROITS D'ACCES DES SUJETS / INFOS SUR LES DROITS D'ACCES DES DOSSIERS RACINE-->
			<?php if(!empty($extendToSubfolders)){ ?><hr><div class="vRightsOptions"><label for="extendToSubfolders" title="<?= Txt::trad("EDIT_OBJET_accessRightSubFolders_info") ?>"><?= Txt::trad("EDIT_OBJET_accessRightSubFolders") ?></label><input type="checkbox" name="extendToSubfolders" id="extendToSubfolders" value="1"></div><?php } ?>
			<?php if($curObj::objectType=="forumSubject"){ ?><hr><div class="vRightsOptions"><img src="app/img/important.png"> <?= Txt::trad("FORUM_accessRightInfos") ?></div><?php } ?>
		<?php } ?>
	</div>
	<?php } ?>

	<!--MENU FICHIER JOINTS-->
	<?php if(!empty($attachedFiles)){ ?>
	<div id="blockMenuEditAttachedFiles">
		<!--Fichiers à ajouter (10 maxi)-->
		<?php for($cptFile=1; $cptFile<=10; $cptFile++){ ?>
		<div id="newAttachedFile<?= $cptFile ?>">
			<input type="file" name="newAttachedFile<?= $cptFile ?>">
			<?php if($curObj::htmlEditorField!==null){ ?>
			<div id="newAttachedFileOptions<?= $cptFile ?>" title="<?= Txt::trad("EDIT_OBJET_inserer_fichier_info") ?>">
				<img src="app/img/arrowRight.png"> <label for="newAttachedFileInsert<?= $cptFile ?>"><?= Txt::trad("EDIT_OBJET_inserer_fichier") ?></label>
				<input type="checkbox" name="newAttachedFileInsert<?= $cptFile ?>" id="newAttachedFileInsert<?= $cptFile ?>" value="1">
				<img src="app/img/attachmentInsertText.png">	
			</div>
			<?php } ?>
		</div>
		<?php } ?>
		<!--Fichiers déjà enregistrés-->
		<div class="attachedFiles">
			<?php foreach($attachedFilesList as $tmpFile){ ?>
			<div id="menuAttachedFile<?= $tmpFile["_id"] ?>">
				<div><img src="app/img/dotW.png"> <?= $tmpFile["name"] ?></div>
				<div>
					<img src="app/img/delete.png" class="sLink" title="<?= Txt::trad("supprimer") ?>" onclick="deleteAttachedFile(<?= $tmpFile["_id"] ?>);">
					<?php if($curObj::htmlEditorField!==null && File::controlType("attachedFile",$tmpFile["name"])){ ?>
					<img src="app/img/attachmentInsertText.png" class="sLink" title="<?= Txt::trad("EDIT_OBJET_inserer_fichier_info") ?>" <?= MdlObject::attachedFileInsert($tmpFile["_id"],true) ?>>
					<?php } ?>
				</div>
			</div>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<!--MENU NOTIFICATIONS PAR MAIL-->
	<?php if(!empty($notifMail)){ ?>
	<div id="blockMenuEditNotifMail">
		<label for="boxNotifMail" title="<?= Txt::trad("EDIT_OBJET_notif_mail_info") ?>"> <?= Txt::trad("EDIT_OBJET_notif_mail_label") ?></label>
		<input type="checkbox" name="notifMail" id="boxNotifMail" value="1" onChange="$('#notifMailUsersPlus,.notifMailOptions').slideToggle(200);">
		<img src="app/img/plus.png" id="notifMailUsersPlus" class="sLink" title="<?= Txt::trad("EDIT_OBJET_notif_mail_selection") ?>" onclick="$('#notifMailUsersPlusList').slideToggle(200);toScroll();">
		<!--OPTIONS DU MAIL-->
		<div class="notifMailOptions">
			<!--MONTRER LES DESTINATAIRES DANS LE MESSAGE-->
			<div>
				<label for="boxhideRecipients" title="<?= Txt::trad("MAIL_hideRecipients_info") ?>"><?= Txt::trad("MAIL_hideRecipients") ?></label>
				<input type="checkbox" name="hideRecipients" id="boxhideRecipients" value="1">
			</div>
			<!--ACCUSE DE RECEPTION-->
			<div>
				<label for="boxReceptionNotif" title="<?= Txt::trad("MAIL_receptionNotif_info") ?>"><?= Txt::trad("MAIL_receptionNotif") ?></label>
				<input type="checkbox" name="receptionNotif" id="boxReceptionNotif" value="1">
			</div>
			<!--JOINDRE L'OBJET FICHIER A LA NOTIFICATION ?-->
			<?php if($curObj::objectType=="file" && $curObj->_id==0){ ?>
			<div>
				<label for="boxNotifMailAddFiles" title="<?= Txt::trad("FILE_limite_chaque_fichier")." ".File::displaySize(File::mailMaxFilesSize) ?>"><?= Txt::trad("EDIT_OBJET_notif_mail_joindre_fichiers") ?></label>
				<input type="checkbox" name="notifMailAddFiles" id="boxNotifMailAddFiles" value="1">
			</div>
			<?php } ?>
		</div>
		<!--LISTE DETAILLE DES UTILISATEURS (masque d'abord les users absent de l'espace courant)-->
		<div id="notifMailUsersPlusList">
			<?php foreach($notifMailUsers as $tmpUser){ ?>
			<div id="divNotifMailUser<?= $tmpUser->_id ?>" <?= (!in_array($tmpUser->_id,$notifMailCurSpaceUsersIds)?"class='notifMailUserPlusHidden'":"") ?>>
				<input type="checkbox" name="notifMailUsers[]" value="<?= $tmpUser->_id ?>" id="boxNotifMailUsers<?= $tmpUser->_id ?>">
				<label for="boxNotifMailUsers<?= $tmpUser->_id ?>" title="<?= $tmpUser->mail ?>"><?= $tmpUser->display() ?></label>
			</div>
			<?php } ?>
			<?php if(count($notifMailUsers)>count($notifMailCurSpaceUsersIds)){ ?>
				<p onclick="$('[id^=divNotifMailUser]').fadeIn(200);$(this).fadeOut(200);" class="sLink"><?= Txt::trad("EDIT_OBJET_notif_tous_users") ?></p>
			<?php } ?>
		</div>
	</div>
	<?php } ?>

	<!--MENU RACCOURCIS-->
	<?php if(!empty($shortcut)){ ?>
	<div id="blockMenuEditShortcut">
		<label for="boxShortcut"><img src="app/img/shortcut.png"> <?= Txt::trad("EDIT_OBJET_shortcut_info") ?></label>
		<input type="checkbox" name="shortcut" id="boxShortcut" value="1" <?= $shortcutChecked ?>>
	</div>
	<?php } ?>

</fieldset>
<?php } ?>


<!--VALIDATION & INPUTS HIDDEN DU FORMULAIRE & ICONE "LOADING"-->
<?= Txt::formValidate() ?>
<?php if(!empty($curObj->_idContainer)){ ?><input type="hidden" name="_idContainer" value="<?= $curObj->_idContainer ?>"><?php } ?>
<div class="loadingImg">&nbsp;</div>