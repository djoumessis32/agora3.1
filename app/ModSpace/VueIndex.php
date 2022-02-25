<script>
////	SUPPRESSION DE L'ESPACE
function deleteSpace(deleteUrl)
{
	if(deleteUrl==false)	{displayNotif("<?= Txt::trad("MSG_NOTIF_suppr_espace_impossible") ?>");}
	else if(confirm("<?= Txt::trad("SPACE_supprimer_espace") ?>")){
		confirmRedir("<?= Txt::trad("SPACE_confirm_suppr_espace") ?>",deleteUrl);
	}
}
</script>

<style>
/* BLOCKS DE CONTENU */
.objDBlock				{max-width:600px; height:190px;}/*cf. "setObjBlockWidth()"*/
.vSpaceDetails			{margin:5px;}
.vSpaceEdit				{float:right; text-align:right;}
.vSpaceDescription		{font-weight:normal;}
.vModules				{margin-top:10px;}
.vModules img			{max-height:30px; margin-right:5px;}
.vLabelAffectations		{margin:5px 0px 5px 0px;}
.vSpaceAffectation		{display:inline-block; width:32%; margin:2px; font-size:85%;}
.vSpaceAffectation img	{max-height:18px;}
.vSpaceAffectationAll	{width:200px;}
</style>

<div class="pageFull">
	<div class="pageMenu">
		<div class="sBlock">
			<div class="moduleMenuLine sLink" onclick="lightboxOpen('<?= MdlSpace::getUrlNew() ?>');" title="<?= Txt::trad("SPACE_description_module_infos") ?>"><div class="moduleMenuIcon"><img src="app/img/plus.png"></div><div class="moduleMenuTxt"><?= Txt::trad("SPACE_ajouter_espace") ?></div></div><hr>
			<?= MdlSpace::menuSort() ?>
			<div class="moduleMenuLine"><div class="moduleMenuIcon"><img src="app/img/info.png"></div><div class="moduleMenuTxt"><?= count($spaceList)." ".Txt::trad(count($spaceList)>1?"SPACE_espaces":"SPACE_espace") ?></div></div>
		</div>
	</div>
	<div class="pageFullContent">
		<!--LISTE DES ESPACES-->
		<?php foreach($spaceList as $tmpSpace){ ?>
			<div class="sBlock objDBlock objScrollContent" <?= $tmpSpace->blockIdForMenuContext() ?>>
				<div class="vSpaceDetails">
					<div class="vSpaceEdit">
						<img src="app/img/params.png" class="sLink" title="<?= Txt::trad("SPACE_parametrage_infos") ?>" onclick="lightboxOpen('<?= $tmpSpace->getUrl("edit") ?>');"><br>
						<img src="app/img/delete.png" class="sLink" title="<?= Txt::trad("SPACE_supprimer_espace") ?>" onclick="deleteSpace(<?= $tmpSpace->deleteRight()?"'".$tmpSpace->getUrl("delete")."'":"false" ?>);">	
					</div>
					<!--DESCRIPTION & MODULES AFFECTES-->
					<?= $tmpSpace->name ?>
					<div class="vSpaceDescription"><?= $tmpSpace->description ?></div>
					<div class="vModules"><?php foreach($tmpSpace->moduleList(false) as $tmpModule){ ?><img src="app/img/<?= $tmpModule["moduleName"] ?>/icon.png" title="<?= $tmpModule["description"] ?>"><?php } ?></div>
					<hr>
					<div class="vLabelAffectations"><?= Txt::trad("EDIT_OBJET_accessRight") ?> :</div>
					<?php
					//DROITS D'ACCES A DEFINIR
					if(count($tmpSpace->getUsers())==0 && $tmpSpace->allUsersAffected()==false && empty($tmpSpace->public)){echo "<div class='labelInfos'>".Txt::trad("SPACE_definir_acces")."</div>";}
					//ESPACE PUBLIC  /  TOUS LES USERS AFFECTES
					if(!empty($tmpSpace->public))		{echo "<div class='vSpaceAffectation'><img src='app/img/public.png'> ".Txt::trad("SPACE_espace_public")."</div>";}
					if($tmpSpace->allUsersAffected())	{echo "<div class='vSpaceAffectation vSpaceAffectationAll'><img src='app/img/user/icon.png'> ".Txt::trad("SPACE_allUsers")."</div>";}
					//USERS AFFECTES
					foreach($tmpSpace->getUsers() as $tmpUser)
					{
						$userRightAcces=$tmpSpace->userAccessRight($tmpUser);
						if($tmpSpace->allUsersAffected() && $userRightAcces==1)	{continue;}//Pas d'affichage si simple user et tous les users sont affectés
						echo "<div class='vSpaceAffectation sLink' onclick=\"lightboxOpen('".$tmpUser->getUrl("vue")."');\">
								<img src='app/img/user/".($userRightAcces==2?'adminSpace.png':'accesUser.png')."'> ".$tmpUser->display().
							 "</div>";
					}
					?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>