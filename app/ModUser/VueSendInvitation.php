<script type="text/javascript">
////	Resize
lightboxWidth(500);

////	Confirme l'envoi?
function formControl()
{
	//Vérifie que les champs obligatoires sont spécifiés
	if($("input[name='name']").isEmpty() || $("input[name='firstName']").isEmpty() || $("input[name='mail']").isEmpty())	{displayNotif("<?= Txt::trad("remplir_tous_champs") ?>");  return false;}
	// Verif mail & verif Ajax s'il existe déjà (car utilisé comme identifiant..)
	if(!isMail($("input[name='mail']").val()))  {displayNotif("<?= Txt::trad("mail_pas_valide"); ?>");  return false;}
	var ajaxUrl="?ctrl=misc&action=UserAccountExist&mail="+encodeURIComponent($("input[name='mail']").val());
	var ajaxResult=$.ajax({url:ajaxUrl,async:false}).responseText;//Retour Ajax obligatoire pour passer à la suite : async:false
	if(find("true",ajaxResult))	{displayNotif("<?= Txt::trad("USER_mail_deja_present"); ?>");  return false;}
}
</script>

<style>
.fieldValue					{margin-bottom:10px;}
form input, form textarea	{width:100%;}
#vInvitationList			{display:none;}
.vSpaceLabel					{font-size:90%;}
</style>

<div class="lightboxTitle"><?= Txt::trad("USER_envoi_invitation") ?></div>

<form action="index.php" method="post" OnSubmit="return formControl();">
	<!--ENVOI D'UNE INVITATION-->
	<?php foreach($userFields as $tmpField){ ?><div class="fieldValue"><input type="text" name="<?= $tmpField ?>" placeholder="<?= Txt::trad($tmpField) ?>"></div><?php } ?>
	<textarea name="comment" placeholder="<?= Txt::trad("comment") ?>"><?= Req::getParam("comment") ?></textarea>
	<?= Txt::formValidate("envoyer") ?>
	
	<!--INVITATIONS EN ATTENTES ENVOYEES PAR L'USER COURANT-->
	<?php if(!empty($invitationList)){ ?>
	<br><hr><div class="sLink" onclick="$('#vInvitationList').fadeToggle();"><img src="app/img/mail.png"> <?= count($invitationList)." ".Txt::trad("USER_invitation_a_confirmer") ?></div>
	<ul id="vInvitationList">
		<?php
		//Invitations déjà envoyées
		foreach($invitationList as $tmpInvitation){
			$objSpace=Ctrl::getObj("space",$tmpInvitation["_idSpace"]);
			$deleteInvitationImg="<img src='app/img/delete.png' class='sLink' title=\"".txt::trad("supprimer")."\" onclick=\"confirmRedir('".Txt::trad("confirmDelete",true)."', '?ctrl=user&action=sendInvitation&deleteInvitation=true&_idInvitation=".$tmpInvitation["_idInvitation"]."')\" >";
			echo "<li>".$tmpInvitation["name"]." ".$tmpInvitation["firstName"]." - ".$tmpInvitation["mail"]." - ".Txt::displayDate($tmpInvitation["dateCrea"])." ".$deleteInvitationImg."<div class='vSpaceLabel'>".$objSpace->name."</div></li>";
		}
		?>
	</ul>
	<?php } ?>
</form>