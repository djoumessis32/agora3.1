                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <script type="text/javascript">
lightboxWidth(600);//Resize

////	Init la page
$(function(){
	////	Si l'identifiant de connexion est vide, on le préremplit avec le mail
	$("input[name='mail']").on("blur",function(){
		if($("[name='login']").isEmpty())	{$("[name='login']").val($("input[name='mail']").val());}
	});
	////	Vérif la présence d'un mail si "envoyer une notification" est coché
	$("input[name='notifMail']").on("change",function(){
		if(this.checked && $("input[name='mail']").isEmpty()){
			$("input[name='mail']").fieldFocus();
			displayNotif("<?= Txt::trad("USER_alert_notification_mail") ?>");
		}
	});
	////	Adresses Ip de controle (masque les champs vides)
	$("[id^='divIpControl']").each(function(){
		if(this.id!="divIpControl_0" && $("#"+this.id+" input[name^='ipControlAdresses']").isEmpty())
			{$(this).css("display","none");}
	});
	////	Init la gestion des affectations aux espaces
	initSpaceAffectations();
});

////	Ajoute un champ d'addresse Ip de Controle
function addIpControl()
{
	var newDivIpControl=null;
	$("[id^='divIpControl']").each(function(){
		if($(this).css("display")=="none" && newDivIpControl==null)   {newDivIpControl=this.id;}
	});
	$("#"+newDivIpControl).css("display","inline-block");
}

////	Supprime une adrese Ip de controle
function deleteIpControl(cpt)
{
	if(confirm("<?= Txt::trad("confirmDelete") ?>")){
		$("#divIpControl_"+cpt+" input[name^='ipControlAdresses']").val("");
		$("#divIpControl_"+cpt).fadeOut();
	}
}

////	Contrôle du formulaire
function formControl()
{
	//Controle si un autre user utilise le même login
	var ajaxUrl="?ctrl=user&action=ControlDuplicateLogin&targetObjId=<?= $curObj->_targetObjId ?>&controledLogin="+encodeURIComponent($("[name='login']").val());
	var ajaxResult=$.ajax({url:ajaxUrl,async:false}).responseText;//Attend la réponse Ajax pour passer à la suite (async:false)
	if(find("true",ajaxResult))  {displayNotif("<?= Txt::trad("USER_identifiant_deja_present"); ?>");  return false;}//Doublon: retourne false
	//Password obligatoire pour un nouvel user
	if(<?= (int)$curObj->_id ?>==0 && $("[name='password']").isEmpty())
		{displayNotif("<?= Txt::trad("USER_specifier_password"); ?>");  return false;}
	//Controle du password
	if($("[name='password']").isEmpty()==false && $("[name='password']").val()!=$("[name='passwordVerif']").val())
		{displayNotif("<?= Txt::trad("passwordVerifError"); ?>");  return false;}
	//Demande si on utilise le mail comme login..
	if($("[name='mail']").isEmpty()==false && isMail($("[name='login']").val())==false){
		$("input[name='login']").fieldFocus();
		if(confirm("<?= Txt::trad("USER_specifyMailAsLogin") ?>")==false){
			$("[name='login']").val($("input[name='mail']").val());
			return false;//relance la validation..
		}
	}
	//Controle final (champs obligatoires, affectations/droits d'accès, etc)
	return finalFormControl();
}
</script>

<style>
hr					{margin:8px 0px 8px 0px;}
.vSpaceAffectations	{max-height:300px; overflow:auto;}
.vFieldConnexion	{color:#a00; font-style:italic; font-size:105%;}
.vNotifMail			{text-align:center;}
.vNotifMail	img		{max-height:20px;}
[for='generalAdmin']{text-decoration:underline;}
</style>

<form action="index.php" method="post" onsubmit="return formControl()" enctype="multipart/form-data">
	<!--CHAMPS PRINCIPAUX-->
	<?= $curObj->getFields("edit") ?>
	<hr class="hrGradient">
	<div class="objField"><div class="fieldLabel vFieldConnexion"><img src="app/img/person/connection.png"><?= Txt::trad("login2") ?></div><div class="fieldValue"><input type="text" name="login" value="<?= $curObj->login ?>"></div></div>
	<div class="objField" title="<?= !empty($curObj->_id)?Txt::trad("passwordInfo"):null ?>"><div class="fieldLabel vFieldConnexion"><img src="app/img/person/connection.png"><span <?= !empty($curObj->_id)?"class='abbr'":null ?>><?= Txt::trad("password") ?></span></div><div class="fieldValue"><input type="password" name="password"></div></div>
	<div class="objField" title="<?= !empty($curObj->_id)?Txt::trad("passwordInfo"):null ?>"><div class="fieldLabel vFieldConnexion"><img src="app/img/person/connection.png"><span <?= !empty($curObj->_id)?"class='abbr'":null ?>><?= Txt::trad("passwordVerif") ?></span></div><div class="fieldValue"><input type="password" name="passwordVerif"></div></div>
	<hr class="hrGradient">

	<!--NOTIFICATION DE CREATION-->
	<?php if(empty($curObj->_id) && function_exists("mail")){ ?>
	<div class="vNotifMail"><label class="fieldLabel" for="notifMail"><img src="app/img/mail.png"> <?= Txt::trad("USER_notification_mail") ?></label><input type="checkbox" name="notifMail" id="notifMail" value="1"></div>
	<hr class="hrGradient">
	<?php } ?>

	<!--IMAGE-->
	<div class="objField personImgSelect">
		<div class="fieldLabel"><?= $curObj->getImg() ?></div>
		<div class="fieldValue"><?= $curObj->displayImgMenu() ?></div>
	</div>

	<!--DIVERSES OPTIONS-->
	<fieldset class="fieldsetMarginTop sBlock">
		<!--ADMIN GENERAL & AGENDA PERSO DESACTIVE-->
		<?php if($curObj->editAdminGeneralRight()){ ?><div class="objField"><label class="fieldLabel" for="generalAdmin"><img src="app/img/user/adminGeneral.png"><?= Txt::trad("USER_adminGeneral") ?></label><div class="fieldValue"><input type="checkbox" name="generalAdmin" id="generalAdmin" value="1" <?= !empty($curObj->generalAdmin)?'checked':null ?> ></div></div><?php } ?>
		<?php if(Ctrl::$curUser->isAdminGeneral()){ ?><div class="objField" title="<?= Txt::trad("USER_agenda_perso_desactive_infos") ?>"><label class="fieldLabel" for="calendarDisabled"><img src="app/img/user/userCalendar.png"><?= Txt::trad("USER_agenda_perso_desactive") ?></label><div class="fieldValue"><input type="checkbox" name="calendarDisabled" id="calendarDisabled" value="1" <?= (!empty($curObj->calendarDisabled))?'checked':null ?> ></div></div><?php } ?>
		<!--ESPACE DE CONNEXION-->
		<?php if(count($curObj->getSpaces())>0){ ?>
		<div class="objField">
			<div class="fieldLabel"><img src="app/img/person/connection.png"><?= Txt::trad("USER_connectionSpace") ?></div>
			<div class="fieldValue">
				<select name="connectionSpace"><?php foreach($curObj->getSpaces() as $tmpSpace)  {echo "<option value='".$tmpSpace->_id."' ".($tmpSpace->_id==$curObj->connectionSpace?'selected':null).">".$tmpSpace->name."</option>";} ?></select>
			</div>
		</div>
		<?php } ?>
		<!--LANGUE DE L'USER & ADRESSE IP DE CONTROLE-->
		<div class="objField"><div class="fieldLabel"><img src="app/img/country.png"><?= Txt::trad("USER_langs") ?></div><div class="fieldValue"><?= Txt::menuTrad("user",$curObj->lang) ?></div></div>
		<div class="objField" title="<?= Txt::trad("USER_info_ipAdress") ?>">
			<div class="fieldLabel"><img src="app/img/user/userIp.png"><?= Txt::trad("USER_adresses_ip") ?> <img src="app/img/plusSmall.png" class="sLink" title="<?= Txt::trad("ajouter") ?>" onclick="addIpControl()"></div>
			<div class="fieldValue">
				<?php
				$ipControlAdresses=Txt::txt2tab($curObj->ipControlAdresses);
				for($cpt=0; $cpt<10; $cpt++){
					$tmpIpValue=(!empty($ipControlAdresses[$cpt])) ? $ipControlAdresses[$cpt] : null;
					$tmpDeleteButton=($cpt>0)  ?  "<img src=\"app/img/delete.png\" onclick=\"deleteIpControl('".$cpt."');\" class='sLink' title=\"".Txt::trad("supprimer")."\">" : null;
					echo "<div id='divIpControl_".$cpt."'><input name=\"ipControlAdresses[".$cpt."]\" value=\"".$tmpIpValue."\"> ".$tmpDeleteButton."</div>";
				}
				?>
			</div>
		</div>
	</fieldset>

	<!--ESPACES AFFECTES A L'UTILISATEUR-->
	<?php if(Ctrl::$curUser->isAdminGeneral()){ ?>
	<div class="fieldsetLabel"><?= Txt::trad("USER_liste_espaces") ?></div>
	<fieldset class="vSpaceAffectations fieldsetMarginTop sBlock">
		<div class="spaceAffectTable spaceAffectRow">
			<div class="spaceAffectRow">
				<label class="spaceAffectCell">&nbsp;</label>
				<div class="spaceAffectCell" title="<?= Txt::trad("SPACE_utilisation_info") ?>"><img src="app/img/user/accesUser.png"> <?= Txt::trad("SPACE_utilisation") ?></div>
				<div class="spaceAffectCell" title="<?= Txt::trad("SPACE_administration_info") ?>"><img src="app/img/user/adminSpace.png"> <?= Txt::trad("SPACE_administration") ?></div>
			</div>
			<?php
			foreach(Db::getObjTab("space","select * from ap_space") as $tmpSpace){
				$userAccessRight=Db::getVal("SELECT accessRight FROM ap_joinSpaceUser WHERE _idSpace=".$tmpSpace->_id." AND _idUser=".$curObj->_id);
				if($tmpSpace->allUsersAffected())	{$AllUsersInfos="title=\"".Txt::trad("USER_tous_user_affecte_espace")."\"";	$AllUsersStar=" *";}
				else								{$AllUsersInfos=$AllUsersStar=null;}
			?>
			<div class="spaceAffectRow sTableRow">
				<label class="spaceAffectCell" id="target<?= $tmpSpace->_id ?>" <?= $AllUsersInfos ?>><?= $tmpSpace->name.$AllUsersStar ?></label>
				<div class="spaceAffectCell" title="<?= Txt::trad("SPACE_utilisation_info") ?>"><input type="checkbox" name="spaceAffect[]" value="<?= $tmpSpace->_id ?>_1" <?= ($userAccessRight==1||$tmpSpace->allUsersAffected())?'checked':null ?> <?= $tmpSpace->allUsersAffected()?'disabled':null ?>></div>
				<div class="spaceAffectCell" title="<?= Txt::trad("SPACE_administration_info") ?>"><input type="checkbox" name="spaceAffect[]" value="<?= $tmpSpace->_id ?>_2" <?= $userAccessRight==2?'checked':null ?>></div>
			</div>
			<?php } ?>
		</div>
	</fieldset>
	<?php } ?>

	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>