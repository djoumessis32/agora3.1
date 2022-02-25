<style>
/* BLOCKS DE CONTENU */
.objLink						{background-image:url(app/img/link/bgObj.png); background-repeat:no-repeat;}
.objDBlock						{max-width:350px; min-width:180px; height:65px;}/*cf. "setObjBlockWidth()"*/
.objDBlock .objLabelMain		{padding-left:10px;}
.objDBlock .objLink				{background-position:right center;}
.objDBlock .objLink .linkAdress	{margin-top:5px;}
.objDLine .objLink .objLabelMain{background-position:left center; padding-left:40px;}
.objLink .objLabelMain img		{float:left; padding:0px 8px 0px 0px;}
.objLink .linkAdress			{font-size:90%; color:#999; text-transform:lowercase;}
</style>

<div class="pageFull">
	<div class="pageMenu">
		<div class="sBlock"><?= CtrlObject::menuFolderTree() ?></div>
		<div class="sBlock">
			<?php if(Ctrl::$curContainer->editContentRight()){ ?>
				<div class="moduleMenuLine sLink" onclick="lightboxOpen('<?= MdlLink::getUrlNew() ?>');"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("LINK_ajouter_lien") ?></div></div>
				<?php if(Ctrl::$curContainer->addRight()){ ?><div class="moduleMenuLine sLink" onclick="lightboxOpen('?ctrl=object&action=FolderEdit&targetObjId=<?= Ctrl::$curContainer->getType()."&_idContainer=".Ctrl::$curContainer->_id ?>')"><div class="moduleMenuIcon"><img src="app/img/folderAdd.png"></div><div class="moduleMenuTxt"><?= Txt::trad("ajouter_dossier") ?></div></div><?php } ?>
				<hr>
			<?php } ?>
			<?= MdlLink::menuSelectObjects().MdlLink::menuDisplayMode().MdlLink::menuSort() ?>
			<div class="moduleMenuLine"><div class="moduleMenuIcon"><img src="app/img/info.png"></div><div class="moduleMenuTxt"><?= Ctrl::$curContainer->folderContentDescription() ?></div></div>
		</div>
	</div>
	<div class="pageFullContent">
		<!--CHEMIN DU DOSSIER & LISTE DES DOSSIERS & LISTE DES LIENS-->
		<?= CtrlObject::menuFolderPath().$foldersList ?>
		<?php foreach($linkList as $tmpLink){ ?>
			<div class="sBlock objScrollContent <?= (MdlLink::getDisplayMode()=="line"?"objDLine":"objDBlock") ?>" <?= $tmpLink->blockIdForMenuContext() ?>>
				<?= $tmpLink->menuContext(); ?>
				<div class="objTable objLink">
					<div class="objLabelMain">
						<a href="<?= $tmpLink->adress ?>" target="_blank">
							<img src="https://www.google.com/s2/favicons?domain=<?= $tmpLink->adress ?>">
							<?= !empty($tmpLink->description) ? "<div title=\"".$tmpLink->description."\">".Txt::reduce($tmpLink->description,80)."</div>" : null ?>
							<div class="linkAdress"><?= Txt::reduce($tmpLink->adress,40) ?></div>
						</a>
					</div>
					<div class="objLabelAutor"><?= $tmpLink->displayAutor() ?></div>
					<div class="objLabelDate"><?= $tmpLink->displayDate(true,"date") ?></div>
				</div>
			</div>
		<?php } ?>
		<!--AUCUN CONTENU-->
		<?php if(empty($foldersList) && empty($linkList)){ ?><div class="pageEmptyContent"><?= Txt::trad("LINK_aucun_lien") ?></div><?php } ?>
	</div>
</div>