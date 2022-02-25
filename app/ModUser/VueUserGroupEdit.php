<script type="text/javascript">
////	Resize
lightboxWidth(700);

////	Controle du formulaire
function formControl(targetObjId)
{
	//Vérif la présence du titre
	if($("#form-"+targetObjId+" [name='title']").isEmpty()){
		displayNotif("<?= Txt::trad("champs_obligatoire")." : ".Txt::trad("title") ?>");
		$("#form-"+targetObjId+" [name='title']").fieldFocus();
		return false;
	}
	// Au moins 2 utilisateurs sélectionnés
	if($("#form-"+targetObjId+" [name='userList[]']:checked").length<2){
		displayNotif("<?= Txt::trad("selectionner_2users") ?>");
		return false;
	}
}
</script>

<style>
form				{margin:30px 0px 0px 10px; width:93%; padding:10px; border: #999 1px solid;}
#form-userGroup-0	{display:none;}/*masque le premier groupe : nouveau groupe*/
.labelDetail		{font-size:95%; margin-top:8px;}
.labelSpaceName		{font-style:italic;}
input[name='title']	{width:50%; margin-bottom:5px;}
.vGroupAutor				{float:right; font-style:italic;}
[id^='userListMenu']{overflow:auto; max-height:150px;}
.userListUser		{display:inline-block; width:32%; font-size:95%;}
.formButtons		{margin-top:10px; text-align:right;}
button				{width:120px;}
[src*='delete.png']	{margin-left:10px;}
#addGroup			{margin-top:30px; margin-right:10px; text-align:center;}
</style>

<div class="lightboxTitle">
	<img src="app/img/user/userGroup.png"> <?= Txt::trad("USER_groupe_espace") ?> : <span class="labelSpaceName"><?= Ctrl::$curSpace->name ?></span>
	<div class="labelDetail"><?= Txt::trad("USER_droit_gestion_groupes") ?></div>
</div>

<!--LISTE LES GROUPES-->
<?php foreach($groupList as $cptGroup=>$tmpGroup){ ?>
<form action="index.php" method="post" class="sBlock" id="form-<?= $tmpGroup->tmpId ?>" OnSubmit="return formControl('<?= $tmpGroup->tmpId ?>');">
	<!--AUTEUR & TITRE-->
	<div class="vGroupAutor"><?= $tmpGroup->createdBy ?></div>
	<input type="text" name="title" value="<?= $tmpGroup->title ?>" placeholder="<?= Txt::trad("title") ?>">
	<!--USERS-->
	<hr class="hrGradient">
	<div id="userListMenu<?= $tmpGroup->tmpId ?>">
		<?php foreach($usersList as $tmpUser){ ?>
			<div class="userListUser">
				<input type="checkbox" name="userList[]" value="<?= $tmpUser->_id ?>" id="box<?= $tmpGroup->tmpId.$tmpUser->_targetObjId ?>" <?= in_array($tmpUser->_id,$tmpGroup->userIds)?"checked":null ?>>
				<label for="box<?= $tmpGroup->tmpId.$tmpUser->_targetObjId ?>"><?= $tmpUser->display() ?></label>
			</div>
		<?php } ?>
	</div>
	<!--VALIDATION/SUPPRESSION-->
	<div class="formButtons">
		<input type="hidden" name="targetObjId" value="<?= $tmpGroup->tmpId ?>">
		<?= ($tmpGroup->isNew()) ? Txt::formValidate("ajouter",false) : Txt::formValidate("modifier",false) ?>
		<?php if($tmpGroup->isNew()==false){ ?><img src="app/img/delete.png" class="sLink" title="<?= Txt::trad("supprimer") ?>" onclick="if(confirm('<?= Txt::trad("confirmDelete",true) ?>')) {lightboxClose(true,'<?= $tmpGroup->getUrl("delete") ?>');}"><?php } ?> 
	</div>
</form>
<?php } ?>

<div id="addGroup">
	<button onclick="$('#form-userGroup-0').slideToggle(100);$('#form-userGroup-0 [name=title]').focus();"><?= Txt::trad("ajouter") ?></button>	
</div>