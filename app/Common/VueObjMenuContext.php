<!--LAUCHER DU MENU & CHECKBOX DE SELECTION-->
<div class="menuContextLauncher <?= empty($inlineLauncher)?"floatLauncher":null ?>" for="<?= $curObj->menuContextId("menu") ?>">
	<!--LAUCHER ICONE-->
	<?php if(!empty($inlineLauncher)){ ?><img src="app/img/inlineMenu.png">
	<!--LAUCHER LABEL-->
	<?php }else{ ?>
		<?php if(!empty($isPersoAccess)){ ?><img src="app/img/user/accesPerso.png" title="<?= Txt::trad("acces_perso") ?>"><?php } ?><!--ACCES PERSO-->
		<?php if(!empty($newObjectSinceConnection)){ ?><img src="app/img/newObj.png"><?php } ?><!--NOUVEL OBJET-->
		<?php if(!empty($isSelectable)){ ?><input type="checkbox" name="targetObjects[]" value="<?= $curObj->_targetObjId ?>" id="<?= $curObj->menuContextId("block") ?>_selectBox"><?php } ?><!--CHECKBOX DE SELECTION D'OBJET-->
		<label><?= Txt::trad("menu") ?></label>
	<?php } ?>
</div>


<!--MENU CONTEXTUEL!-->
<div class="menuContext sBlock" id="<?= $curObj->menuContextId("menu") ?>">
	<!--ICONE DE FERMETURE : MODE "RESPONSIVE"-->
	<img src="app/img/close.png" class="menuContextClose sLink" onclick="$('#<?= $curObj->menuContextId("menu") ?>').fadeOut(200);">

	<!--DIVERSES OPTIONS (HORS MENU PRINCIPAL)-->
	<?php foreach($specificOptions as $tmpOption){
		if(empty($tmpOption["inMainMenu"])){	$isSpecialMenu=true;
	?>
	<div class="menuContextLine sLink" <?= !empty($tmpOption["actionJs"])?'onclick="'.$tmpOption["actionJs"].'"':null ?>  <?= !empty($tmpOption["tooltip"])?'title="'.$tmpOption["tooltip"].'"':null ?>>
		<?php if(!empty($tmpOption["iconSrc"])){ ?><div class="menuContextIcon"><img src="<?= $tmpOption["iconSrc"] ?>"></div><?php } ?>
		<div class="menuContextTxt"><?= $tmpOption["label"] ?></div>
	</div>
	<?php } } ?>

	<!--SEPARATEUR?-->
	<?php if(!empty($isSpecialMenu))  {echo "<hr>";} ?>

	<!--SELECTION/DESELECTION-->
	<?php if($isSelectable==true){ $isMainMenu=true; ?>
	<div class="menuContextLine sLink" onclick="objSelect('<?= $curObj->menuContextId("block") ?>')">
		<div class="menuContextIcon"><img src="app/img/check.png"></div>
		<div class="menuContextTxt"><?= Txt::trad("select_deselect") ?></div>
	</div>
	<?php } ?>

	<!--EDITER-->
	<?php if(!empty($editObjUrl)){ $isMainMenu=true; ?>
	<div class="menuContextLine sLink" onclick="lightboxOpen('<?= $editObjUrl ?>')">
		<div class="menuContextIcon"><img src="app/img/edit.png"></div>
		<div class="menuContextTxt"><?= $editLabel ?></div>
	</div>
	<?php } ?>

	<!--SUPPRIMER-->
	<?php if(!empty($deleteConfirmRedir)){ $isMainMenu=true; ?>
	<div class="menuContextLine sLink" onclick="<?= $deleteConfirmRedir ?>">
		<div class="menuContextIcon"><img src="app/img/delete.png"></div>
		<div class="menuContextTxt"><?= $deleteLabel ?></div>
	</div>
	<?php } ?>

	<!--CHANGER DE DOSSIER-->
	<?php if(!empty($moveObjectUrl)){ $isMainMenu=true; ?>
	<div class="menuContextLine sLink" onclick="lightboxOpen('<?= $moveObjectUrl ?>')">
		<div class="menuContextIcon"><img src="app/img/folderMove.png"></div>
		<div class="menuContextTxt"><?= Txt::trad("deplacer_elements") ?></div>
	</div>
	<?php } ?>
	
	<!--HISTORIQUE/LOGS-->
	<?php if(!empty($logUrl)){ $isMainMenu=true; ?>
	<div class="menuContextLine sLink" onclick="lightboxOpen('<?= $logUrl ?>')">
		<div class="menuContextIcon"><img src="app/img/log.png"></div>
		<div class="menuContextTxt"><?= Txt::trad("historique_element") ?></div>
	</div>
	<?php } ?>

	<!--DIVERSES OPTIONS (DANS MENU PRINCIPAL)-->
	<?php foreach($specificOptions as $tmpOption){
		if(!empty($tmpOption["inMainMenu"])){	$isSpecialMenu=true;
	?>
	<div class="menuContextLine sLink" <?= !empty($tmpOption["actionJs"])?'onclick="'.$tmpOption["actionJs"].'"':null ?>  <?= !empty($tmpOption["tooltip"])?'title="'.$tmpOption["tooltip"].'"':null ?>>
		<?php if(!empty($tmpOption["iconSrc"])){ ?><div class="menuContextIcon"><img src="<?= $tmpOption["iconSrc"] ?>"></div><?php } ?>
		<div class="menuContextTxt"><?= $tmpOption["label"] ?></div>
	</div>
	<?php } } ?>

	<!--SEPARATEUR?-->
	<?php if(!empty($isMainMenu))  {echo "<hr>";} ?>

	<!--CONTENU D'UN DOSSIER-->
	<?php if(!empty($folderContentDescription)){ ?>
	<div class="menuContextLine sLink">
		<div class="menuContextTxtLeft"><?= Txt::trad("contenu_dossier") ?> :</div>
		<div class="menuContextTxt"><?= $folderContentDescription ?></div>
	</div>
	<hr>
	<?php } ?>

	<!--AFFECTATIONS-->
	<?php if(!empty($affectLabels["2"])){ ?>
	<div class="menuContextLine cursorHelp sAccessWrite" title="<?= $affectTooltips["2"] ?>">
		<div class="menuContextTxtLeft"><?= Txt::trad("ecriture") ?> :</div>
		<div class="menuContextTxt"><?= $affectLabels["2"] ?></div>
	</div>
	<?php } ?>
	<?php if(!empty($affectLabels["1.5"])){ ?>
	<div class="menuContextLine cursorHelp sAccessWriteLimit" title="<?= $affectTooltips["1.5"] ?>">
		<div class="menuContextTxtLeft"><?= Txt::trad("ecriture_limit") ?> :</div>
		<div class="menuContextTxt"><?= $affectLabels["1.5"] ?></div>
	</div>
	<?php } ?>
	<?php if(!empty($affectLabels["1"])){ ?>
	<div class="menuContextLine cursorHelp sAccessRead" title="<?= Txt::trad("lecture_infos") ?>">
		<div class="menuContextTxtLeft"><?= Txt::trad("lecture") ?> :</div>
		<div class="menuContextTxt"><?= $affectLabels["1"] ?></div>
	</div>
	<?php } ?>
	
	<!--SEPARATEUR?-->
	<?php if(!empty($affectLabels) && (!empty($infosCrea) || !empty($infosModif)))  {echo "<hr>";} ?>

	<!--AUTEUR & DATE-->
	<?php if(!empty($infosCrea)){ ?>
	<div class="menuContextLine sLink" <?= $infosCrea["autorLightbox"] ?>>
		<div class="menuContextTxtLeft"><?= Txt::trad("cree_par") ?> :</div>
		<div class="menuContextTxt"><?= $infosCrea["autor"].$infosCrea["date"].(!empty($newObjectSinceConnection)?"<br>".Txt::trad("objNew")." <img src='app/img/newObj.png'>":null) ?></div>
	</div>
	<?php } ?>
	<?php if(!empty($infosModif)){ ?>
	<div class="menuContextLine sLink" <?= $infosModif["autorLightbox"] ?>>
		<div class="menuContextTxtLeft"><?= Txt::trad("modif_par") ?> :</div>
		<div class="menuContextTxt"><?= $infosModif["autor"].$infosModif["date"] ?></div>
	</div>
	<?php } ?>

	<!--FICHIERS JOINTS-->
	<?php if(!empty($menuAttachedFiles))  {echo $menuAttachedFiles;} ?>
</div>