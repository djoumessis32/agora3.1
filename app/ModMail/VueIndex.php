<script>
$(function(){
	////	Init l'affichage de l'arborescence de contacts
	$(".vPersonsContainer").each(function(){
		var folderTreeLevel=$(this).attr("data-treeLevel");
		if(typeof folderTreeLevel!=="undefined" && folderTreeLevel>0)
			{$(this).css("padding-left",(folderTreeLevel*22)+"px");}
	});

	////	Affiche/masque les users d'un espace (sauf espace courant)
	$(".vPersonsLabel").on("click",function(){
		$("#personList"+$(this).attr("data-targetObjId")).slideToggle(200);
	});

	////	Fixe la hauteur de l'éditeur && Préselectionne le titre du mail
	$(window).resize(function(){ htmlEditorHeight(); });
	$("[name='title']").focus();
});

////	Resize la hauteur de l'éditeur en fonction de la hauteur de la page (lancé aussi depuis "VueHtmlEditor.php")
function htmlEditorHeight()
{
	//Hauteur inutilisé : distance entre le haut du footer (position fixe) et le bas du formulaire. Enlève 25px pour les box-shadows and co
	var unusedSpace=Math.round($("#pageFooter").offset().top - ($(".vMailForm").offset().top + $(".vMailForm").outerHeight(true))) -25;
	//Nouvelle hauteur de l'éditeur?
	var editorNewHeight=Math.round($(".mce-edit-area").height() + unusedSpace);
	tinymce.activeEditor.theme.resizeTo("100%", editorNewHeight);
}

////    On contrôle le formulaire
function formControl()
{
	//Sélection d'une personne, d'un titre et d'un message
	if($("[name='personList[]']:checked").length==0 && $("[name='groupList[]']:checked").length==0)	{displayNotif("<?= Txt::trad("MAIL_specifier_mail") ?>");	return false;}
	else if($("[name='title']").isEmpty())															{displayNotif("<?= Txt::trad("champs_obligatoire")." : ".Txt::trad("title") ?>");	 return false;}
	else if(isEmptyEditor("description"))															{displayNotif("<?= Txt::trad("champs_obligatoire")." : ".Txt::trad("description") ?>");	 return false;}
}
</script>

<style>
/*Users / Contacts*/
.vIconsModule				{max-width:33px; float:right; opacity:0.4; margin-top:-7px;}
.vIconsModule:first-child	{margin-top:2px;}
.vPersonsContainer			{padding:3px 0px 3px 6px;}
.vPersonsContainer img		{max-height:22px;}
.vPersonsContainer img[src*='userGroup']				{max-height:18px;}
.vPersonsContainerTable									{display:table;}
.vPersonsContainerCell									{display:table-cell; vertical-align:top;}
.vPersonsContainerCell:first-child						{width:30px;}
.vPersonsContainerCell img[src*=developp]				{width:10px;}
.vGroupSelectTable			{display:table; margin-bottom:2px;}
.vGroupSelectCell			{display:table-cell; vertical-align:top;}
.vGroupSelectCell:nth-child(1),.vGroupSelectCell:nth-child(2)	{width:22px;}
[id^='personList']			{padding:2px; padding-left:20px; display:none;}
[id='personList<?= Ctrl::$curSpace->_targetObjId ?>']	{display:block;}/*par défaut, on n'affiche que les users de l'espace courant*/
.vMenuHistory				{padding:10px 0px 10px 0px;}
/*Formulaire principal*/
.vMailForm					{padding:10px;}
[name='title']				{width:98%; margin-bottom:15px;}
.vMailOptions				{display:table; width:100%; margin-top:30px;}
.vMailOptionsLeft, .vMailOptionsRight					{display:table-cell;}
.vMailOptionsRight			{text-align:right;}
[id^='files']				{display:none;}
[id='files1']				{display:block;}
.formValidateMain			{margin:0px;}/*surcharge*/
</style>

<div class="pageCenter">
	<form action="index.php" method="post" onsubmit="return formControl()" enctype="multipart/form-data" class="mainPageForm">
		<div class="pageMenu">
			<div class="sBlock">
				<?php
				////	ESPACES DU SITE & DOSSIERS DE CONTACT DE L'ESPACE
				foreach($containerList as $tmpContainer)
				{
					//Séparation du conteneur  &&  Eventuellement, icone du module (à droite du block)
					if(isset($tmpObjectType) && $tmpObjectType!=$tmpContainer::objectType)	{echo "<hr>";}
					if(!isset($tmpObjectType) || $tmpObjectType!=$tmpContainer::objectType)	{echo "<img src='app/img/".($tmpContainer::objectType=="space"?"user":"contact")."/icon.png' class='vIconsModule'>";}
					$tmpObjectType=$tmpContainer::objectType;//Change $tmpObjectType
				?>
				<div class="vPersonsContainer" <?= $tmpContainer::objectType=="contactFolder"?"data-treeLevel='".$tmpContainer->treeLevel."'":null ?>>
					<!--ICONE & LABEL DU CONTENEUR (espace ou dossier de contacts)-->
					<div class="vPersonsContainerTable">
						<div class="vPersonsContainerCell"><img src="app/img/<?= $tmpObjectType=="space"?"space.png":"folderSmall.png" ?>"></div>
						<div class="vPersonsContainerCell"><label class="vPersonsLabel" data-targetObjId="<?= $tmpContainer->_targetObjId ?>"><?= $tmpContainer->name."<img src='app/img/developp.png'>" ?></label></div>
					</div>
					<!--PERSONNES DU CONTENEUR-->
					<div id="personList<?= $tmpContainer->_targetObjId ?>">
						<?php
						////	GROUPES D'USERS (contenur "espace")
						if($tmpObjectType=="space")
						{
							foreach(MdlUserGroup::getGroups($tmpContainer) as $tmpGroup)
							{
								$tmpBoxId=$tmpContainer->_targetObjId.$tmpGroup->_targetObjId;
								echo "<div class='vGroupSelectTable' title=\"".$tmpGroup->usersLabel."\">
										<div class='vGroupSelectCell'><input type='checkbox' name=\"groupList[]\" value=\"".$tmpGroup->_targetObjId."\" id=\"".$tmpBoxId."\"></div>
										<div class='vGroupSelectCell'><img src='app/img/user/userGroup.png'></div>
										<div class='vGroupSelectCell'><label for=\"".$tmpBoxId."\">".$tmpGroup->title."</label></div>
									  </div>";
							}
						}
						////	PERSONNES DU CONTAINER
						if($tmpContainer::objectType=="space" || count($tmpContainer->personList)<500)
						{
							foreach($tmpContainer->personList as $tmpPerson)
							{
								if(empty($tmpPerson->mail)) {continue;}
								$tmpBoxId=$tmpContainer->_targetObjId.$tmpPerson->_targetObjId;
								echo "<div title=\"".$tmpPerson->mail."\">
										<input type='checkbox' name=\"personList[]\" value=\"".$tmpPerson->_targetObjId."\" id=\"".$tmpBoxId."\">
										<label for=\"".$tmpBoxId."\">".$tmpPerson->display()."</label>
									  </div>";
							}
						}
						?>
					</div>
				</div>
				<?php } ?>
			</div>
			<div class="sBlock vMenuHistory">
				<div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=mail&action=mailHistory');">
					<div class="moduleMenuIcon"><img src="app/img/mail/mailHistory.png"></div>
					<div class="moduleMenuTxt"><?= Txt::trad("MAIL_historique_mail") ?></div>
				</div>
			</div>
		</div>
		<div class="pageCenterContent">
			<div class="vMailForm sBlock">
				<!--TITRE-->
				<input type="text" name="title" placeholder="<?= txt::trad("MAIL_title") ?>">
				<!--DESCRIPTION (EDITOR)-->
				<?= txt::trad("description") ?>
				<textarea name="description"></textarea>
				<?= CtrlMisc::initHtmlEditor("description") ?>
				<!--OPTIONS-->
				<div class="vMailOptions">
					<div class="vMailOptionsLeft">
						<?php if(!empty(Ctrl::$curUser->mail)){ ?><div title="<?= Txt::trad("MAIL_receptionNotif_info") ?>"><input type="checkbox" name="receptionNotif" value="1" id="receptionNotif"><label for="receptionNotif"><?= Txt::trad("MAIL_receptionNotif") ?></label></div><?php } ?>
						<div title="<?= Txt::trad("MAIL_hideRecipients_info") ?>"><input type="checkbox" name="hideRecipients" value="1" id="hideRecipients" <?= $checkhideRecipients ?>><label for="hideRecipients"><?= Txt::trad("MAIL_hideRecipients") ?></label></div>
						<div title="<?= Txt::trad("MAIL_noFooter_info") ?>"><input type="checkbox" name="noFooter" value="1" id="noFooter"><label for="noFooter"><?= Txt::trad("MAIL_noFooter") ?></label></div>
					</div>
					<div class="vMailOptionsRight" title="<?= File::uploadMaxFilesize("info") ?>">
						<!--SELECTION DE FICHIERS-->
						<?php for($i=1; $i<=10; $i++){ ?>
							<div id="files<?= $i ?>"><?= Txt::trad("MAIL_fichier_joint") ?>  <input type="file" name="files<?= $i ?>" onChange="$('#files<?= $i+1 ?>').fadeIn();"></div>
						<?php } ?>
					</div>
				</div>
				<!--VALIDATION-->
				<?= Txt::formValidate("envoyer") ?>
			</div>
		</div>
	</form>
</div>