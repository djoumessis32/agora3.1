                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <script type="text/javascript">
lightboxWidth(450);//Resize

////	Init la page
$(function(){
	//Affiche/masque la liste des utilisateurs
	$("[name='messengerDisplay']").on("change click",function(){
		if($(this).val()=="some")	{$(".vDivSomeUsers").fadeIn();}
		else						{$(".vDivSomeUsers").fadeOut();}
	});
});
</script>

<style>
.vDivRadio		{margin-bottom:10px;}
.vDivSomeUsers	{display:<?= empty($someUsers)?"none":"inline-block" ?>;}
.vDivSomeUser	{margin:5px 0px 5px 30px;}
</style>

<div class="lightboxTitle"><?= ucfirst(Txt::trad("USER_visibilite_messenger_livecounter")) ?></div>

<form action="index.php" method="post">
	<div class="vDivRadio">
		<input type="radio" name="messengerDisplay" value="none" id="messengerDisplayNone" <?= (empty($allUsers) && empty($someUsers))?"checked":null ?>>
		<label for="messengerDisplayNone"><?= Txt::trad("USER_voir_aucun_utilisateur") ?></label>
	</div>
	<div class="vDivRadio">
		<input type="radio" name="messengerDisplay" value="all" id="messengerDisplayAll" <?= $allUsers==true?"checked":null ?>>
		<label for="messengerDisplayAll"><?= Txt::trad("USER_voir_tous_utilisateur") ?></label>
	</div>
	<div class="vDivRadio">
		<input type="radio" name="messengerDisplay" value="some" id="messengerDisplaySome" <?= !empty($someUsers)?"checked":null ?>>
		<label for="messengerDisplaySome"><?= Txt::trad("USER_voir_certains_utilisateur") ?></label>
		<div class="vDivSomeUsers">
			<?php
			if(count($curObj->usersVisibles())==0)   {echo "<div class='vDivSomeUser'>".Txt::trad("USER_aucun_utilisateur_messenger")."</div>";}
			foreach($curObj->usersVisibles() as $tmpUser){
			?>
			<div class="vDivSomeUser">
				<input type="checkbox" name="messengerSomeUsers[]" value="<?= $tmpUser->_id ?>" id="someUser<?= $tmpUser->_id ?>" <?= in_array($tmpUser->_id,$someUsers)?"checked":null ?>>
				<label for="someUser<?= $tmpUser->_id ?>"><?= $tmpUser->display() ?></label>
			</div>
			<?php } ?>
		</div>
	</div>

	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>