<script type="text/javascript">
////	Resize
lightboxWidth(400);

////	Confirme l'envoi?
function formControl()
{
	var allEmpty=true;
	//Vérifie si tous les champs de recherche sont vides
	$("input[name^='searchFields']").each(function(){
		if($(this).isEmpty()==false)	{allEmpty=false;}
	});
	//S'ils sont tous vides : erreur
	if(allEmpty==true)	{displayNotif("<?= Txt::trad("USER_preciser_recherche") ?>"); return false;}
	//Confirmer les affectations?
	if($("[name='usersList[]']").isEmpty()==false && confirm("<?= Txt::trad("USER_affecter_user_confirm") ?>")==false)	{return false;}
}
</script>

<style>
form .objField .fieldLabel	{width:100px;}
</style>

<div class="lightboxTitle"><?= Txt::trad("USER_rechercher_user") ?></div>

<form action="index.php" method="post" OnSubmit="return formControl();">
	<?php
	//Liste des champs de recherche
	foreach($searchFields as $tmpField){
		echo "<div class='objField'>
				<div class='fieldLabel'>".Txt::trad($tmpField)."</div>
				<div class='fieldValue'><input type='text' name='searchFields[".$tmpField."]' value=\"".(isset($searchFieldsValues[$tmpField])?$searchFieldsValues[$tmpField]:null)."\"></div>
			  </div>";
	}
	//Liste des users à affecter
	if(isset($usersList))
	{
		echo "<hr>";
		echo (empty($usersList)) ? Txt::trad("USER_aucun_users_recherche") : Txt::trad("USER_affecter_user");
		foreach($usersList as $tmpUser){
			echo "<div class='objField' title=\"".$tmpUser->mail."\">
					<input type='checkbox' name='usersList[]' value='".$tmpUser->_id."' id='userId".$tmpUser->_id."'>
					<label for='userId".$tmpUser->_id."'>".$tmpUser->display()."</label>
				</div>";
		}
	}
	//Validation du form
	echo Txt::formValidate("envoyer")
	?>
</form>