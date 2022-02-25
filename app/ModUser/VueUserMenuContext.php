<!--LAUCHER DU MENU-->
<div class="menuContextLauncher floatLauncher" for="<?= $curObj->menuContextId("menu") ?>">
	<?= isset($adminIcon) ? "<img src='app/img/user/".$userStatusIcon."' title=\"".$userStatusLabel."\">" : null ?>
	<label><?= Txt::trad("menu") ?></label>
</div>


<!--MENU CONTEXTUEL!-->
<div class="menuContext sBlock" id="<?= $curObj->menuContextId("menu") ?>">
	<!--ICONE DE FERMETURE : MODE "RESPONSIVE"-->
	<img src="app/img/close.png" class="menuContextClose sLink" onclick="$('#<?= $curObj->menuContextId("menu") ?>').fadeOut(200);">

	<!--HIDDEN : SELECTION DE L'OBJET ET LE DBLCLICK SUR LE BLOCK DE L'OBJET-->
	<input type="checkbox" name="targetObjects[]" value="<?= $curObj->_targetObjId ?>" id="<?= $curObj->menuContextId("block") ?>_selectBox">
	<?php if(!empty($editObjUrl)){ ?><input type="hidden" value="<?= $curObj->getUrl("edit") ?>" id="<?= $curObj->menuContextId("block") ?>_DblClickUrl"><?php } ?>
	
	<!--EDITER L'USER & LE MESSENGER-->
	<?php if(!empty($editObjUrl)){ $isMainMenu=true; ?>
	<div class="menuContextLine sLink" onclick="lightboxOpen('<?= $editObjUrl ?>')">
		<div class="menuContextIcon"><img src="app/img/edit.png"></div>
		<div class="menuContextTxt"><?= Txt::trad("USER_modifier") ?></div>
	</div>
	<div class="menuContextLine sLink" onclick="lightboxOpen('<?= $editMessengerObjUrl ?>')">
		<div class="menuContextIcon"><img src="app/img/messengerSmall.png"></div>
		<div class="menuContextTxt"><?= Txt::trad("USER_gestion_messenger_livecounter") ?></div>
	</div>
	<?php } ?>

	<!--SUPPRIMER DE L'ESPACE ("désaffecter")-->
	<?php if(!empty($deleteFromCurSpaceConfirmRedir)){ $isMainMenu=true; ?>
	<div class="menuContextLine sLink" onclick="<?= $deleteFromCurSpaceConfirmRedir ?>">
		<div class="menuContextIcon"><img src="app/img/delete.png"></div>
		<div class="menuContextTxt"><?= Txt::trad("USER_desaffecter") ?></div>
	</div>
	<?php } ?>

	<!--SUPPRIMER DEFINITIVEMENT-->
	<?php if(!empty($deleteConfirmRedir)){ $isMainMenu=true; ?>
	<div class="menuContextLine sLink" onclick="<?= $deleteConfirmRedir ?>">
		<div class="menuContextIcon"><img src="app/img/delete.png"></div>
		<div class="menuContextTxt"><?= Txt::trad("USER_suppr_definitivement") ?></div>
	</div>
	<?php } ?>

	<!--ESPACES AFFECTES A L'UTILISATEUR-->
	<?php if($_SESSION["displayUsers"]=="all"){ ?>
	<hr>
	<div class="menuContextLine">
		<div class="menuContextIcon"><img src="app/img/space.png"></div>
		<div class="menuContextTxt">
			<?php
			echo Txt::trad("USER_liste_espaces")." :";
			if(count($curObj->getSpaces())==0)		{echo Txt::trad("USER_aucun_espace");}
			else{
				foreach($curObj->getSpaces() as $tmpSpace)	{echo "<br>".$tmpSpace->name;}
			}
			?>
		</div>
	</div>
	<?php } ?>

	<!--STATUT DE L'USER-->
	<?php if(!empty($isMainMenu))	{echo "<hr>";} ?>
	<div class="menuContextLine">
		<div class="menuContextIcon"><img src="app/img/user/<?= $userStatusIcon ?>"></div>
		<div class="menuContextTxt"><?= $userStatusLabel ?></div>
	</div>
</div>