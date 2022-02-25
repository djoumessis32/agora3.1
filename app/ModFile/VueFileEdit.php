<script type="text/javascript">
////	Resize
lightboxWidth(<?= (isset($fileContent)) ? 750 : 550 ?>);

////	Contrôle du formulaire
function formControl()
{
	//Controle si un autre fichier porte le même nom (dans le même dossier conteneur)
	if($("[name='name']").isEmpty()==false){
		var ajaxUrl="?ctrl=object&action=ControlDuplicateName&targetObjId=<?= $curObj->_targetObjId ?>&targetObjIdContainer=<?= $curObj->containerObj()->_targetObjId ?>&controledName="+encodeURIComponent($("[name='name']").val()+$("[name='dotExtension']").val());
		var ajaxResult=$.ajax({url:ajaxUrl,async:false}).responseText;//Attend la réponse Ajax pour passer à la suite (async:false)
		if(find("true",ajaxResult))  {displayNotif("<?= Txt::trad("MSG_NOTIF_duplicateName"); ?>");  return false;}//Doublon: retourne false
	}
	//Controle final (champs obligatoires, affectations/droits d'accès, etc)}
	return finalFormControl();
}
</script>


<style>
[name='dotExtension']	{width:30px; margin-right:10px;}
[name='description']	{width:98%; height:50px; margin-top:10px;}
.fileContentLabel		{margin-top:10px; font-style:italic;}
[name='fileContent']	{width:98%; height:400px;}
[name='fileContentOld']	{display:none;}
</style>


<form action="index.php" method="post" onsubmit="return formControl()" id="filesForm" enctype="multipart/form-data">

	<fieldset class="fieldsetMarginTop sBlock">
		<!--NOM & DESCRIPTION-->
		<input type="text" name="name" value="<?= basename($curObj->name,strstr($curObj->name,'.')) ?>" class="editInputText" placeholder="<?= Txt::trad("name") ?>">
		<input type="text" name="dotExtension" value="<?= strstr($curObj->name,'.') ?>" readonly>
		<textarea name="description" placeholder="<?= Txt::trad("description") ?>"><?= $curObj->description ?></textarea>
		<!--CONTENU MODIFIABLE : FICHIER TXT/HTML-->
		<?php if(isset($fileContent)){ ?>
			<div class="fileContentLabel"><?= Txt::trad("FILE_contenu") ?>:</div>
			<textarea name="fileContent"><?= $fileContent ?></textarea>
			<textarea name="fileContentOld"><?= $fileContent ?></textarea>
			<?php if(isset($initHtmlEditor))	{echo CtrlMisc::initHtmlEditor("fileContent");} ?>
		<?php } ?>
	</fieldset>

	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>