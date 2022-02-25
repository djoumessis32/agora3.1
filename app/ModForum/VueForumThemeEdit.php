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
form				{margin:30px 0px 0px 10px; width:93%; padding:10px; border: #999 1px solid;}
#form-forumTheme-0	{display:none;}/*masque le premier theme : nouveau theme*/
.labelDetail		{font-size:90%; margin-top:8px;}
input[name='title']	{width:40%; margin-bottom:5px; color:#fff;}
input[name='description']	{width:99%; margin-bottom:5px;}
.vThemeAutor		{float:right; font-style:italic;}
.formButtons		{margin-top:10px; text-align:right;}
button				{width:120px;}
[src*='delete.png']	{margin-left:10px;}
#addTheme			{margin-top:40px; margin-right:10px; text-align:center;}
</style>


<div class="lightboxTitle">
	<img src="app/img/category.png"> <?= Txt::trad("FORUM_themes_gestion") ?>
	<div class="labelDetail"><?= Txt::trad("FORUM_droit_gestion_themes") ?></div>
</div>

<!--LISTE LES THEMES-->
<?php foreach($themesList as $tmpTheme){ ?>
	<form action="index.php" method="post" class="sBlock" id="form-<?= $tmpTheme->tmpId ?>" OnSubmit="return formControl('<?= $tmpTheme->tmpId ?>');">
		<!--AUTEUR & TITRE-->
		<div class="vThemeAutor"><?= $tmpTheme->createdBy ?></div>
		<input type="text" name="title" value="<?= $tmpTheme->title ?>" id="title-<?= $tmpTheme->tmpId ?>" placeholder="<?= Txt::trad("title") ?>">
		<!--COLOR PICKER & DESCRIPTION-->
		<?= Tool::colorPicker("title-".$tmpTheme->tmpId,"color-".$tmpTheme->tmpId,"background-color") ?>
		<input type="hidden" name="color" id="color-<?= $tmpTheme->tmpId ?>" value="<?= $tmpTheme->color ?>">
		<input type="text" name="description" value="<?= $tmpTheme->description ?>" placeholder="<?= Txt::trad("description") ?>">
		<!--ESPACES-->
		<?= $tmpTheme->menuSpaceAffectation() ?>
		<!--VALIDATION/SUPPRESSION-->
		<div class="formButtons">
			<input type="hidden" name="targetObjId" value="<?= $tmpTheme->tmpId ?>">
			<?= ($tmpTheme->isNew()) ? Txt::formValidate("ajouter",false) : Txt::formValidate("modifier",false) ?>
			<?php if($tmpTheme->isNew()==false){ ?><img src="app/img/delete.png" class="sLink" title="<?= Txt::trad("supprimer") ?>" onclick="if(confirm('<?= Txt::trad("confirmDelete",true) ?>')) {lightboxClose(true,'<?= $tmpTheme->getUrl("delete") ?>');}"><?php } ?> 
		</div>
	</form>
<?php } ?>

<div id="addTheme">
	<button onclick="$('#form-forumTheme-0').slideToggle(200);$('#form-forumTheme-0 [name=title]').focus();"><?= Txt::trad("ajouter") ?></button>	
</div>