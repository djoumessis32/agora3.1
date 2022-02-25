<script type="text/javascript">
////	Resize
lightboxWidth(600);

////	Contrôle du formulaire
function formControl()
{
	//Controle si un autre dossier porte le même nom (dans le même dossier Container)
	if($("[name='name']").isEmpty()==false)
	{
		var ajaxUrl="?ctrl=object&action=ControlDuplicateName&targetObjId=<?= $curObj->_targetObjId ?>&controledName="+encodeURIComponent($("[name='name']").val());
		<?php if($curObj->isRootFolder()==false){ ?>
		var ajaxUrl=ajaxUrl+"&targetObjIdContainer=<?= $curObj->containerObj()->_targetObjId ?>";
		<?php } ?>
		var ajaxResult=$.ajax({url:ajaxUrl,async:false}).responseText;//Attend la réponse Ajax pour passer à la suite (async:false)
		if(find("true",ajaxResult))  {displayNotif("<?= Txt::trad("MSG_NOTIF_duplicateName"); ?>");  return false;}//Doublon: retourne false
	}
	//Controle final (champs obligatoires, affectations/droits d'accès, etc)
	return finalFormControl();
}
</script>

<style>
textarea[name='description']	{<?= empty($curObj->description)?"display:none;":null ?>}
</style>

<form action="index.php" method="post" onsubmit="return formControl()" enctype="multipart/form-data">

	<!--NOM & DESCRIPTION-->
	<input type="text" name="name" value="<?= $curObj->name ?>" class="editInputTextBig" placeholder="<?= Txt::trad("name") ?>">
	<img src="app/img/description.png" class="sLink" title="<?= Txt::trad("description") ?>" onclick="$('textarea[name=description]').slideToggle(200);">
	<textarea name="description" placeholder="<?= Txt::trad("description") ?>"><?= $curObj->description ?></textarea>

	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>