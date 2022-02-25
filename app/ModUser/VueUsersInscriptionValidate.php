<script type="text/javascript">
////	Resize
lightboxWidth(400);
</script>

<div class="lightboxTitle"><?= Txt::trad("usersInscription_validation_title") ?></div>

<form action="index.php" method="post" OnSubmit="return formControl();">
	<?php
	// DEMANDES D'INSCRIPTION
	foreach($inscriptionList as $tmpInscription){
	?>
	<div>
		<input type="checkbox" name="inscriptionValidation[]" value="<?= $tmpInscription["_id"] ?>" id="inscription<?= $tmpInscription["_id"] ?>">
		<label for="inscription<?= $tmpInscription["_id"] ?>" title="<?= Txt::displayDate($tmpInscription["date"])."<br>".$tmpInscription["message"] ?>">
			<?= $tmpInscription["name"]." ".$tmpInscription["firstName"]." (".$tmpInscription["mail"].")" ?>
		</label>
	</div>
	<?php
	}
	// BOUTONS DE VALIDATION/INVALIDATION
	echo Txt::formValidate("valider", true, Txt::trad("usersInscription_invalider"));
	?>
</form>