<script>
////	Création d'un user à partir d'un contact
function contactAddUser(targetObjId)
{
	if(confirm("<?= Txt::trad("CONTACT_creer_user_infos") ?>"))
		{redir("?ctrl=contact&action=contactAddUser&targetObjId="+targetObjId);}
}
</script>

<style>
/* BLOCKS DE CONTENU */
.objDBlock		{max-width:350px; min-width:220px; height:110px;}/*cf. "setObjBlockWidth()"*/
.objLabelMain	{padding:5px 5px 5px 10px;}
</style>

<div class="pageFull">
	<div class="pageMenu">
		<div class="sBlock"><?= CtrlObject::menuFolderTree() ?></div>
		<div class="sBlock">
			<?php if(Ctrl::$curContainer->editContentRight()){ ?>
				<div class="moduleMenuLine sLink" onclick="lightboxOpen('<?= MdlContact::getUrlNew() ?>');"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("CONTACT_ajouter_contact") ?></div></div>
				<?php if(Ctrl::$curContainer->addRight()){ ?><div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=object&action=FolderEdit&targetObjId=<?= Ctrl::$curContainer->getType()."&_idContainer=".Ctrl::$curContainer->_id ?>')"><div class="moduleMenuIcon"><img src="app/img/folderAdd.png"></div><div class="moduleMenuTxt"><?= Txt::trad("ajouter_dossier") ?></div></div><?php } ?>
				<hr>
			<?php } ?>
			<?php if(Ctrl::$curContainer->editContentRight() && Ctrl::$curUser->isUser()){ ?><div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=contact&action=EditPersonsImportExport&targetObjId=<?= Ctrl::$curContainer->_targetObjId ?>');"><div class="moduleMenuIcon"><img src="app/img/exportImport.png"></div><div class="moduleMenuTxt"><?= Txt::trad("importer")."/".Txt::trad("exporter")." ".Txt::trad("import_export_contact") ?></div></div><hr><?php } ?>
			<?= MdlContact::menuSelectObjects().MdlContact::menuDisplayMode().MdlContact::menuSort() ?>
			<div class="moduleMenuLine"><div class="moduleMenuIcon"><img src="app/img/info.png"></div><div class="moduleMenuTxt"><?= Ctrl::$curContainer->folderContentDescription() ?></div></div>
		</div>
	</div>
	<div class="pageFullContent">
		<!--CHEMIN DU DOSSIER & LISTE DES DOSSIERS & LISTE DES CONTACTS-->
		<?= CtrlObject::menuFolderPath().$foldersList ?>
		<?php foreach($contactList as $tmpContact){ ?>
			<div class="sBlock objScrollContent <?= (MdlContact::getDisplayMode()=="line"?"objDLine":"objDBlock") ?>" <?= $tmpContact->blockIdForMenuContext() ?>>
				<?= $tmpContact->menuContext(); ?>
				<div class="objTable objContact">
					<div class="objLabelIcon"><?= $tmpContact->getImg(true) ?></div>
					<div class="objLabelMain">
						<div class="personLabelDetails">
							<div class="personLabel"><a href="javascript:lightboxOpen('<?= $tmpContact->getUrl("vue") ?>');"><?= $tmpContact->display("all") ?></a></div>
							<div class="personDetails"><?= $tmpContact->getFields(MdlContact::getDisplayMode()) ?></div>
						</div>
					</div>
					<div class="objLabelAutor"><?= $tmpContact->displayAutor() ?></div>
					<div class="objLabelDate"><?= $tmpContact->displayDate(true,"date") ?></div>
				</div>
			</div>
		<?php } ?>
		<!--AUCUN CONTENU-->
		<?php if(empty($foldersList) && empty($contactList)){ ?><div class="pageEmptyContent"><?= Txt::trad("CONTACT_aucun_contact") ?></div><?php } ?>
	</div>
</div>