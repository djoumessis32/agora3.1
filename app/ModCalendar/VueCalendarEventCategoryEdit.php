<script type="text/javascript">
////	Resize
lightboxWidth(600);

////	Init la page
$(function(){
	//Applique les couleurs de chaque titre, en fonction du champ color "hidden"
	$("input[name='title']").each(function(){
		$(this).css("background-color", $("#color-"+this.id.replace("title-","")).val());
	});
});

////	Controle du formulaire
function formControl(targetObjId)
{
	//Vérif la présence du titre
	if($("#form-"+targetObjId+" [name='title']").isEmpty()){
		displayNotif("<?= Txt::trad("champs_obligatoire")." : ".Txt::trad("title") ?>");
		$("#form-"+targetObjId+" [name='title']").fieldFocus();
		return false;
	}
	//Au moins un espace sélectionné
	if($("#form-"+targetObjId+" [name='spaceList[]']:checked").length==0){
		displayNotif("<?= Txt::trad("selectionner_espace") ?>");
		return false;
	}
}
</script>

<style>
form							{margin:30px 0px 0px 10px; width:93%; padding:10px; border: #999 1px solid;}
#form-calendarEventCategory-0	{display:none;}/*masque la premiere categorie : nouvelle categorie*/
.labelDetail					{font-size:90%; margin-top:8px;}
input[name='title']				{width:40%; margin-bottom:5px; color:#fff;}
input[name='description']		{width:99%; margin-bottom:5px;}
.vCategoryAutor		{float:right; font-style:italic;}
.formButtons		{margin-top:10px; text-align:right;}
button				{width:120px;}
[src*='delete.png']	{margin-left:10px;}
#addCategory		{margin-top:30px; margin-right:10px; text-align:center;}
</style>

<div class="lightboxTitle">
	<img src="app/img/category.png"> <?= Txt::trad("CALENDAR_gerer_categories") ?>
	<div class="labelDetail"><?= Txt::trad("CALENDAR_droit_gestion_categories") ?></div>
</div>

<!--LISTE LES CATEGORIES-->
<?php foreach($categoriesList as $tmpCategory){ ?>
	<form action="index.php" method="post" class="sBlock" id="form-<?= $tmpCategory->tmpId ?>" OnSubmit="return formControl('<?= $tmpCategory->tmpId ?>');">
		<!--AUTEUR & TITRE-->
		<div class="vCategoryAutor"><?= $tmpCategory->createdBy ?></div>
		<input type="text" name="title" value="<?= $tmpCategory->title ?>" id="title-<?= $tmpCategory->tmpId ?>" placeholder="<?= Txt::trad("title") ?>">
		<!--COLOR PICKER & DESCRIPTION-->
		<?= Tool::colorPicker("title-".$tmpCategory->tmpId,"color-".$tmpCategory->tmpId,"background-color") ?>
		<input type="hidden" name="color" id="color-<?= $tmpCategory->tmpId ?>" value="<?= $tmpCategory->color ?>">
		<input type="text" name="description" value="<?= $tmpCategory->description ?>" placeholder="<?= Txt::trad("description") ?>">
		<!--ESPACES-->
		<?= $tmpCategory->menuSpaceAffectation() ?>
		<!--VALIDATION/SUPPRESSION-->
		<div class="formButtons">
			<input type="hidden" name="targetObjId" value="<?= $tmpCategory->tmpId ?>">
			<?= ($tmpCategory->isNew()) ? Txt::formValidate("ajouter",false) : Txt::formValidate("modifier",false) ?>
			<?php if($tmpCategory->isNew()==false){ ?><img src="app/img/delete.png" class="sLink" title="<?= Txt::trad("supprimer") ?>" onclick="if(confirm('<?= Txt::trad("confirmDelete",true) ?>')) {lightboxClose(true,'<?= $tmpCategory->getUrl("delete") ?>');}"><?php } ?> 
		</div>
	</form>
<?php } ?>

<div id="addCategory">
	<button onclick="$('#form-calendarEventCategory-0').slideToggle(100);$('#form-calendarEventCategory-0 [name=title]').focus();"><?= Txt::trad("ajouter") ?></button>	
</div>