<script type="text/javascript">
////	Resize
lightboxWidth(400);

////	Confirme l'envoi?
function formControl()
{
	if(!confirm("<?= TXT::trad("USER_envoi_coordonnees_confirm") ?>"))
		{return false;}
}
</script>

<style>
.vUserLine	{margin-bottom:10px;}
</style>

<div class="lightboxTitle"><?= Txt::trad("USER_envoi_coordonnees_info") ?></div>

<form action="index.php" method="post" OnSubmit="return formControl();">
	<?php
	////	LISTE DES UTILISATEURS AVEC MAIL
	foreach($usersList as $tmpUser){
		echo "<div class='vUserLine'>
				<input type='checkbox' name='usersList[]' value='".$tmpUser->_id."' id='usersBox".$tmpUser->_id."'>
				<label for='usersBox".$tmpUser->_id."' title=\"".$tmpUser->mail."\">".$tmpUser->display()."</label>
			  </div>";
	}
	//Validation du formulaire
	echo Txt::formValidate("envoyer");
	?>
</form>