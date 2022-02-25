                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               <script type="text/javascript">
lightboxWidth(550);//Resize

////	Init la page
$(function(){
	////	Affiche le password si l'espace est public
	$("[name='public']").change(function(){
		(this.checked) ? $("#divPassword").fadeIn() : $("#divPassword").fadeOut();
	});
	////	Sélection "allUsers" : Check tous les users et désactive la box de lecture
	$("label[for='allUsers'],#allUsers").change(function(){
		allUsersChecked=$("#allUsers").prop("checked");
		$("[name='spaceAffect[]'][value$='_1']").each(function(){
			$(this).prop("disabled",allUsersChecked).prop("checked",allUsersChecked);
		});
	});	
	////	Sélection de module : Affiche les options
	$("[name='moduleList[]']").change(function(){ diplayModuleOptions(); });
	diplayModuleOptions();//Init de la page
	////	Initialise le tri des modules ('placeholder'=>affiche un module "fantome" durant le déplacement. "sortableHandle"=>le tri n'est possible que depuis l'icone "sortableHandle")
	$("#sortableModules").sortable({placeholder:'highlight',handle: ".sortableHandle"}).disableSelection();
	////	Init la gestion des affectations aux espaces
	initSpaceAffectations();
});

////	Affiche/masque les options des modules
function diplayModuleOptions()
{
	$("[name='moduleList[]']").each(function(){
		var optionsSelector=".moduleOptions"+this.id.replace("module","");
		if(this.checked)	{$(optionsSelector).show();}
		else				{$(optionsSelector).hide();}
	});
}

////	Contrôle du formulaire
function formControl()
{
   //Controle le nb de modules cochés
   if($("input[name='moduleList[]']:checked").length==0)	{displayNotif("<?= Txt::trad("SPACE_selectionner_module") ?>"); return false; }
	//Controle final (champs obligatoires, affectations/droits d'accès, etc)
	return finalFormControl();
}
</script>

<style>
[name='name']						{margin-bottom:10px;}
textarea[name='description']		{<?= empty($curObj->description)?"display:none;":null ?>}
.objField img						{max-height:24px;}
#divPassword						{margin:5px 0px 5px 20px; font-style:italic; <?= empty($curObj->public)?"display:none;":null ?>}
#divPassword input					{width:100px;}
form .fieldWallpaper				{margin-top:10px;}
form .fieldWallpaper .fieldLabel	{width:110px;}
form .fieldWallpaper .fieldValue img{max-height:90px;}
[for='allUsers']					{font-style:italic;}
.vSpaceAffectations					{max-height:300px; overflow:auto;}
.vfieldsetModule					{padding:0px;}
#sortableModules					{list-style-type:none; margin:0px; padding:0px; width:100%;}
#sortableModules li					{padding:5px;}
#sortableModules li.highlight		{border:1px dashed #aaa; height:20px; }/*module "fantome" durant le déplacement*/
.vSortableModuleLine				{display:table; width:100%;}
.vSortableModuleCell				{display:table-cell; font-weight:bold; padding:0px;}
.vSortableModuleCell:nth-child(1)	{width:25px; cursor:move;}/*icone de déplacement*/
.vSortableModuleCell:nth-child(3)	{width:25px; text-align:right;}/*icone du module*/
.vSortableModuleCell img			{max-height:20px;}
.vSortableModuleCell img[src*='folderDependance']	{opacity:0.5;}
.moduleOptions						{font-size:90%; display:none;}
</style>


<form action="index.php" method="post" onsubmit="return formControl()" enctype="multipart/form-data">

	<!--NOM/DESCRIPTION-->
	<input type="text" name="name" value="<?= $curObj->name ?>" class="editInputTextBig" placeholder="<?= Txt::trad("name") ?>">
	<img src="app/img/description.png" class="sLink" title="<?= Txt::trad("description") ?>" onclick="$('textarea[name=description]').slideToggle(200);">
	<textarea name="description" placeholder="<?= Txt::trad("description") ?>"><?= $curObj->description ?></textarea>
	<!--ESPACE PUBLIC-->
	<div class="objField" title="<?= Txt::trad("SPACE_public_infos") ?>">
		<input type="checkbox" name="public" id="public" value="1" <?= (!empty($curObj->public))?'checked':null ?>>
		<label for="public"><?= Txt::trad("SPACE_espace_public") ?> <img src="app/img/public.png"></label>
		<div id="divPassword">
			<img src="app/img/arrowRight.png"> <?= Txt::trad("password") ?>
			<input type="text" name="password" value="<?= $curObj->password ?>">
		</div>
	</div>
	<!--INSCRIPTION A L'ESPACE-->
	<div class="objField" title="<?= Txt::trad("usersInscription_option_espace_info") ?>">
		<input type="checkbox" name="usersInscription" id="usersInscription" value="1" <?= (!empty($curObj->usersInscription))?'checked':null ?>>
		<label for="usersInscription"><?= Txt::trad("usersInscription_option_espace") ?> <img src="app/img/edit.png"></label>
	</div>
	<!--INVITATIONS PAR MAIL-->
	<div class="objField" title="<?= Txt::trad("SPACE_usersInvitation_infos") ?>">
		<input type="checkbox" name="usersInvitation" id="usersInvitation" value="1" <?= (!empty($curObj->usersInvitation))?'checked':null ?>>
		<label for="usersInvitation"><?= Txt::trad("SPACE_usersInvitation") ?> <img src="app/img/mail.png"></label>
	</div>
	<!--WALLPAPER-->
	<div class="objField fieldWallpaper">
		<div class="fieldLabel"><?= Txt::trad("wallpaper") ?></div>
		<div class="fieldValue"><?= CtrlMisc::menuWallpaper($curObj->wallpaper) ?></div>
	</div>

	<!--MODULES DE L'ESPACE-->
	<div class="fieldsetLabel"><?= Txt::trad("SPACE_modules_espace") ?></div>
	<fieldset class="fieldsetMarginTop sBlock vfieldsetModule">
		<ul id="sortableModules">
		<?php foreach($moduleList as $moduleName=>$tmpModule){ ?>
			<li class="ui-state-default">
				<!--SELECTION DU MODULE-->
				<div class="vSortableModuleLine">
					<div class="vSortableModuleCell sortableHandle" title="<?= Txt::trad("SPACE_modules_rank") ?>"><img src="app/img/move.png"></div>
					<div class="vSortableModuleCell">
						<input type="checkbox" name="moduleList[]" value="<?= $moduleName ?>" id="module<?= $moduleName ?>" <?= empty($tmpModule["disabled"])?"checked":null ?>>
						<label for="module<?= $moduleName ?>" title="<?= $tmpModule["description"] ?>"><?= $tmpModule["label"] ?></label>
					</div>
					<div class="vSortableModuleCell"><img src="app/img/<?= $moduleName ?>/icon.png"></div>
				</div>
				<!--CREATION D'UN AGENDA SUR L'ESPACE-->
				<?php if($moduleName=="calendar" && $curObj->isNew()){ ?>
				<div class="vSortableModuleLine moduleOptions moduleOptions<?= $moduleName ?>" title="<?= Txt::trad("SPACE_creer_agenda_espace_info") ?>">
					<div class="vSortableModuleCell">&nbsp;</div>
					<div class="vSortableModuleCell">
						<img src="app/img/folderDependance.png">
						<input type="checkbox" name="addSpaceCalendar" id="addSpaceCalendar" value="1" checked>
						<label for="addSpaceCalendar"><?= Txt::trad("SPACE_creer_agenda_espace") ?> <img src="app/img/plusSmall.png"></label>
					</div>
				</div>
				<?php } ?>
				<!--OPTIONS DU MODULE-->
				<?php foreach($tmpModule["ctrl"]::$moduleOptions as $optionName){ ?>
				<div class="vSortableModuleLine moduleOptions moduleOptions<?= $moduleName ?>">
					<div class="vSortableModuleCell">&nbsp;</div>
					<div class="vSortableModuleCell">
						<img src="app/img/folderDependance.png">
						<input type="checkbox" name="<?= $moduleName ?>Options[]" value="<?= $optionName ?>" id="<?= $moduleName."Option".$optionName ?>" <?= stristr($tmpModule["options"],$optionName)?"checked":null ?>>
						<label for="<?= $moduleName."Option".$optionName ?>"><?= Txt::trad(strtoupper($moduleName)."_".$optionName) ?></label>
					</div>
				</div>
				<?php } ?>
			</li>
		<?php } ?>
		</ul>
	</fieldset>

	<!--USERS DE L'ESPACE-->
	<?php if(Ctrl::$curUser->isAdminGeneral()){ ?>
	<div class="fieldsetLabel"><?= Txt::trad("SPACE_gestion_acces") ?></div>
	<fieldset class="vSpaceAffectations fieldsetMarginTop sBlock">
		<div class="spaceAffectTable">
			<div class="spaceAffectRow">
				<label class="spaceAffectCell">&nbsp;</label>
				<div class="spaceAffectCell" title="<?= Txt::trad("SPACE_utilisation_info") ?>"><img src="app/img/user/accesUser.png"> <?= Txt::trad("SPACE_utilisation") ?></div>
				<div class="spaceAffectCell" title="<?= Txt::trad("SPACE_administration_info") ?>"><img src="app/img/user/adminSpace.png"> <?= Txt::trad("SPACE_administration") ?></div>
			</div>
			<div class="spaceAffectRow sTableRow">
				<label class="spaceAffectCell" for="allUsers"><?= strtoupper(Txt::trad("SPACE_allUsers")) ?></label>
				<div class="spaceAffectCell" title="<?= Txt::trad("SPACE_utilisation_info") ?>"><input type="checkbox" name="allUsers" id="allUsers" value="allUsers" <?= ($curObj->allUsersAffected())?'checked':null ?>></div>
				<div class="spaceAffectCell">&nbsp;</div>
			</div>
			<?php
			foreach($userList as $tmpUser){
				$userAccessRight=Db::getVal("SELECT accessRight FROM ap_joinSpaceUser WHERE _idSpace=".$curObj->_id." AND _idUser=".$tmpUser->_id);
			?>
			<div class="spaceAffectRow sTableRow" id="rowTarget<?= $tmpUser->_id ?>">
				<label class="spaceAffectCell" id="target<?= $tmpUser->_id ?>"><?= $tmpUser->display() ?></label>
				<div class="spaceAffectCell" title="<?= Txt::trad("SPACE_utilisation_info") ?>"><input type="checkbox" name="spaceAffect[]" value="<?= $tmpUser->_id ?>_1" <?= ($userAccessRight==1||$curObj->allUsersAffected())?'checked':null ?> <?= $curObj->allUsersAffected()?'disabled':null ?>></div>
				<div class="spaceAffectCell" title="<?= Txt::trad("SPACE_administration_info") ?>"><input type="checkbox" name="spaceAffect[]" value="<?= $tmpUser->_id ?>_2" <?= $userAccessRight==2?'checked':null ?>></div>
			</div>
			<?php } ?>
		</div>
	</fieldset>
	<?php } ?>

	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>