<script type="text/javascript">
////	Resize
lightboxWidth(700);
//Init la page
$(function(){
	//Au changement du thème ou d'affectation d'un espace : Vérifie la dispo du thème pour l'espace
	$("select[name='_idTheme']").on("change",function(){ checkThemeSpace(); });
	$("[id^='spaceBlock'] input[type=checkbox]").on("change",function(){ checkThemeSpace(); });//Cf "VueObjMenuEdit.php"
	//"trigger" pour initialiser la couleur de l'input
	$("select[name='_idTheme']").val("<?= $curObj->_idTheme ?>").trigger("change");
});

////	Vérifie que le thème courant est accessible sur tous les espaces affectés !
function checkThemeSpace()
{
	//Vérifie sur chaque espace affecté, que le thème courant y est bien disponible!
	notifThemeSpace=null;
	$("[id^='spaceBlock']").each(function(){
		var _idSpaceTmp=this.id.replace("spaceBlock","");
		var themeSpaceIds=$("select[name='_idTheme'] option:selected").attr("data-spaceIds");
		if($("#"+this.id+" input:checked").length>0 && typeof themeSpaceIds!="undefined" && themeSpaceIds.length>0 && themeSpaceIds.split(",").indexOf(_idSpaceTmp)==-1)
			{notifThemeSpace="<?= Txt::trad("FORUM_theme_espaces") ?> : <i>"+$("select[name='_idTheme'] option:selected").attr("data-spaceLabels")+"</i>";}
	});
	if(notifThemeSpace!==null)	{displayNotif(notifThemeSpace,"warning");}
}
</script>

<style>
[name='title']			{width:350px; max-width:60%; margin-right:20px; margin-bottom:20px;}
.vEvtOptionsLabel img	{max-height:15px;}
.labelInfos				{display:none;}
</style>

<form action="index.php" method="post" onsubmit="return finalFormControl()" enctype="multipart/form-data">
	<!--TITRE & THEME & DESCRIPTION (EDITOR)-->
	<input type="text" name="title" value="<?= $curObj->title ?>" class="editInputText" placeholder="<?= Txt::trad("title") ?>">
	<?php if(!empty($themesList)){ ?>
	<span class="vEvtOptionsLabel"><img src="app/img/category.png"><?= Txt::trad("FORUM_theme_sujet") ?></span>
		<select name="_idTheme">
			<option value=""></option>
			<?php foreach($themesList as $tmpTheme){ ?>
			<option value="<?= $tmpTheme->_id ?>" data-color="<?= $tmpTheme->color ?>" data-spaceIds="<?= implode(",",$tmpTheme->spaceIds) ?>" data-spaceLabels="<?= $tmpTheme->spaceLabels() ?>"><?= $tmpTheme->title ?></option>
			<?php } ?>
		</select>
	<?php } ?>
	<textarea name="description"><?= $curObj->description ?></textarea>

	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>