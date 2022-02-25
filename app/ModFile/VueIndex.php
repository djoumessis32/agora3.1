<style>
/* BLOCKS DE CONTENU */
.objDBlock								{max-width:230px; min-width:160px; height:130px;}/*cf. "setObjBlockWidth()"*/
.objDBlock .objLabelIcon				{max-width:100%;}/*Surchage de common.css*/
.objDBlock .hasThumb .objLabelIcon img	{margin-top:0%; border-radius:4px;}/*Surchage de common.css*/
.objDBlock .thumbLandscape .objLabelIcon img				{height:100%; width:100%;}
.objDBlock .thumbPortrait:not(.thumbPdf) .objLabelIcon img	{width:100%; margin-top:-20%;}
.objDBlock .thumbPdf .objLabelIcon img						{max-width:70%;}
.objDBlock .hasThumb .objLabelMain		{border-radius:0px 0px 3px 3px;}
.objDBlock .pdfIcon						{position:absolute; top:-2px; right:-2px;}
.objDLine .pdfIcon						{display:none;}
.objFile .objLabelMain a, .objFile .objLabelIcon a	{cursor:url("app/img/download.png"),pointer;}
.objFile .objLabelMain .vVersionsMenu	{margin-left:5px;}
.objLabelIcon a[rel='lightboxGallery']	{cursor:url("app/img/search.png"),all-scroll;}
</style>

<div class="pageFull">
	<div class="pageMenu">
		<div class="sBlock"><?= CtrlObject::menuFolderTree() ?></div>
		<div class="sBlock">
			<?php if(Ctrl::$curContainer->editContentRight()){ ?>
				<div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=file&action=AddEditFiles&targetObjId=file&_idContainer=<?= Ctrl::$curContainer->_id ?>');"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("FILE_ajouter_fichier") ?></div></div>
				<?php if(Ctrl::$curContainer->addRight()){ ?><div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=object&action=FolderEdit&targetObjId=<?= Ctrl::$curContainer->getType()."&_idContainer=".Ctrl::$curContainer->_id ?>')"><div class="moduleMenuIcon"><img src="app/img/folderAdd.png"></div><div class="moduleMenuTxt"><?= Txt::trad("ajouter_dossier") ?></div></div><?php } ?>
				<hr>
			<?php } ?>
			<?= MdlFile::menuSelectObjects().MdlFile::menuDisplayMode().MdlFile::menuSort() ?>
			<div class="moduleMenuLine"><div class="moduleMenuIcon"><img src="app/img/info.png"></div><div class="moduleMenuTxt"><?= Ctrl::$curContainer->folderContentDescription() ?></div></div>
			<?php if(!empty($fillRateBar)){ ?><div class="moduleMenuLine"><div class="moduleMenuIcon"><img src="app/img/diskSpaceLevel<?= $fillRateLevel ?>.png"></div><div class="moduleMenuTxt"><?= $fillRateBar ?></div></div><?php } ?>
		</div>
	</div>
	<div class="pageFullContent">
		<!--CHEMIN DU DOSSIER & LISTE DES DOSSIERS & LISTE DES FICHIERS-->
		<?= CtrlObject::menuFolderPath().$foldersList ?>
		<?php foreach($filesList as $tmpFile){ ?>
			<div class="sBlock <?= (MdlFile::getDisplayMode()=="line"?"objDLine":"objDBlock objDBlockCenter") ?>" <?= $tmpFile->blockIdForMenuContext() ?>>
				<?= $tmpFile->menuContext(); ?>
				<div class="objTable objFile <?= $tmpFile->thumbClass ?>">
					<?php if($tmpFile->fileType=="pdf") {echo "<img src='app/img/file/fileType/pdf2.png' class='pdfIcon'>";} ?>
					<div class="objLabelIcon"><a <?= $tmpFile->iconHref ?> title="<?= $tmpFile->iconTooltip ?>"><img src="<?= $tmpFile->typeIcon() ?>"></a></div><!--"_blank" : télécharger dans nouvelle fenêtre / "lightoxGallery" : afficher plusieurs images à la suite-->
					<div class="objLabelMain"><a href="<?= $tmpFile->urlDownloadDisplay() ?>" target="_blank" title="<?= $tmpFile->tooltip ?>"><?= Txt::reduce($tmpFile->name,60) ?></a><?= $tmpFile->versionsMenu("icon") ?></div>
					<div class="objLabelDetails"><?= File::displaySize($tmpFile->octetSize) ?></div>
					<div class="objLabelAutor"><?= $tmpFile->displayAutor() ?></div>
					<div class="objLabelDate"><?= $tmpFile->displayDate(true,"date") ?></div>
				</div>
			</div>
		<?php } ?>
		<!--AUCUN CONTENU-->
		<?php if(empty($foldersList) && empty($filesList)){ ?><div class="pageEmptyContent"><?= Txt::trad("FILE_aucun_fichier") ?></div><?php } ?>
	</div>
</div>