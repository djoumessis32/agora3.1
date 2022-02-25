<script type="text/javascript">
////	Resize
lightboxWidth(650);

////	Contrôle du formulaire
function formControl()
{
	//Controle final (champs obligatoires, affectations/droits d'accès, etc)
	return finalFormControl();
}
</script>

<style>
textarea[name='description']	{<?= empty($curObj->description)?"display:none;":null ?>}
</style>

<form action="index.php" method="post" onsubmit="return formControl()" enctype="multipart/form-data">

	<!--TITRE & DESCRIPTION (sauf type "user")-->
	<?php if($curObj->type!="user"){ ?>
	<input type="text" name="title" value="<?= $curObj->title ?>" class="editInputTextBig" placeholder="<?= Txt::trad("title") ?>">
	<img src="app/img/description.png" class="sLink" title="<?= Txt::trad("description") ?>" onclick="$('textarea[name=description]').slideToggle(200);">
	<textarea name="description" placeholder="<?= Txt::trad("description") ?>"><?= $curObj->description ?></textarea>
	<?php } ?>

	<fieldset  class="<?= $curObj->type!="user"?"fieldsetMarginTop":null ?> sBlock">
		<!--PLAGE HORAIRE-->
		<div class="objField">
			<?= Txt::trad("CALENDAR_timeSlot") ?>
			<select name="timeSlotBegin">
				<?php for($h=1; $h<24; $h++){ ?>
				<option value="<?= $h ?>" <?= ($curObj->timeSlotBegin==$h)?"selected":null ?>><?= $h ?>h</option>
				<?php } ?>
			</select>
			<?= Txt::trad("a") ?>
			<select name="timeSlotEnd">
				<?php for($h=1; $h<24; $h++){ ?>
				<option value="<?= $h ?>" <?= ($curObj->timeSlotEnd==$h)?"selected":null ?>><?= $h ?>h</option>
				<?php } ?>
			</select>
		</div>
		<!--AFFICHAGE DES EVENEMENTS-->
		<div class="objField">
		<?= Txt::trad("CALENDAR_affichage_evt") ?>
			<select name="evtColorDisplay">
				<option value="background"><?= Txt::trad("CALENDAR_affichage_evt_background") ?></option>
				<option value="border" <?= ($curObj->evtColorDisplay=="border")?"selected":null ?>><?= Txt::trad("CALENDAR_affichage_evt_border") ?></option>
			</select>
		</div>
	</fieldset>
	
	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>