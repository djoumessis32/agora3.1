<script type="text/javascript">
////	Resize
lightboxWidth(430);

////	Contrôle du formulaire
function formControl()
{
	// Certains champs sont obligatoire
	if($("input[name='name']").isEmpty())		{displayNotif("<?= Txt::trad("USER_specifyName"); ?>");  return false;}
	if($("input[name='firstName']").isEmpty())	{displayNotif("<?= Txt::trad("USER_specifyFirstName"); ?>");  return false;}
	// Verif mail & verif Ajax s'il existe déjà (car utilisé comme identifiant..)
	if($("input[name='mail']").isEmpty() || !isMail($("input[name='mail']").val()))   {displayNotif("<?= Txt::trad("mail_pas_valide"); ?>");  return false;}
	var ajaxUrl="?ctrl=misc&action=UserAccountExist&mail="+encodeURIComponent($("input[name='mail']").val());
	var ajaxResult=$.ajax({url:ajaxUrl,async:false}).responseText;//Retour Ajax obligatoire pour passer à la suite : async:false
	if(find("true",ajaxResult))   {displayNotif("<?= Txt::trad("USER_mail_deja_present"); ?>");  return false;}
	// Verif mot de passe
	if($("input[name='password']").isEmpty() || $("input[name='password']").val()!=$("input[name='passwordVerif']").val())   {displayNotif("<?= Txt::trad("USER_specifier_password"); ?>");  return false;}
	// Vérif du captcha
	if(captchaControl()==false)    {return false;}
}
</script>

<style>
select, input[type=text], input[type=password], textarea	{margin-bottom:15px;}
.formValidate	{margin-top:15px;}
</style>


<div class="lightboxTitle"><?= ucfirst(Txt::trad("usersInscription_espace")) ?></div>

<form action="index.php" method="post" id="userInscription" OnSubmit="return formControl();">
	<select name="_idSpace">
		<?php foreach($objSpacesInscription as $tmpSpace){ ?>
		<option value="<?= $tmpSpace->_id ?>" title="<?= $tmpSpace->description ?>"><?= $tmpSpace->name ?></option>
		<?php } ?>
	</select><br>
	<input type="text" name="name" class="editInputText" placeholder="<?= Txt::trad("name"); ?>"><br>
	<input type="text" name="firstName" class="editInputText" placeholder="<?= Txt::trad("firstName"); ?>"><br>
	<input type="text" name="mail" class="editInputText" placeholder="<?= Txt::trad("mail"); ?>"><br>
	<input type="password" name="password" class="editInputPassword" placeholder="<?= Txt::trad("password"); ?>"><br>
	<input type="password" name="passwordVerif" class="editInputPassword" placeholder="<?= Txt::trad("passwordVerif"); ?>"><br>
	<textarea name="message" class="editInputText" placeholder="<?= Txt::trad("comment"); ?>"><?= Req::getParam("message") ?></textarea><br>
	<?= CtrlMisc::menuCaptcha() ?>
	<?= Txt::formValidate("valider") ?>
</form>