<script>
////	Resize
<?php if($context=="move"){ ?>
lightboxWidth(450);
<?php } ?>

////	Init l'affichage de l'arborescnce
$(function(){
	$(".vFolderTreeLine").each(function(){
		var folderTreeLevel=Math.round($(this).attr("data-treeLevel"));
		var folderId=Math.round($(this).attr("data-folderId"));
		var folderIdContainer=Math.round($(this).attr("data-folderIdContainer"));
		var curFolderPath=[<?= implode(",",Ctrl::$curContainer->folderPath("id")) ?>];
		//Ajoute une marge de gauche si on est à un niveau 2 ou + (prend en compte largeur de "vIcons")
		if(folderTreeLevel>1)    {$(this).css("padding-left",((folderTreeLevel-1)*17)+"px");}
		//Affiche les dossiers : au niveau 0 et 1  avec un dossier conteneur dans l'arborescence courante
		if(folderTreeLevel<=1 || $.inArray(folderIdContainer,curFolderPath)!==-1)    {$(this).css("display","block");}
		//Masque le "open.png" des sous-dossiers qui n'ont pas de sous-dossiers  OU  Affiche le "open.png" des dossiers du "path" courant
		var curFolderSelector=".vFolderTreeLine[data-folderId='"+folderId+"']";
		var subFoldersSelector=".vFolderTreeLine[data-folderIdContainer='"+folderId+"']";
		if($(subFoldersSelector).exist()==false)		{$(curFolderSelector+" .vIconOpen").css("display","none");}
		else if($.inArray(folderId,curFolderPath)!==-1)	{$(curFolderSelector+" .vIconOpen").addClass("vIconOpened");}
	});
});

////	Ouvre/Ferme un dossier
function folderTreeDisplay(folderId, init)
{
	var curFolderSelector=".vFolderTreeLine[data-folderId='"+folderId+"']";
	var subFoldersSelector=".vFolderTreeLine[data-folderIdContainer='"+folderId+"']";
	//Ouvre: affiche le premier niveau de sous-dossiers & modifie "open.png" (rotate)
	if(init==true && $(subFoldersSelector).css("display")=="none"){
		$(subFoldersSelector).slideDown(100);
		setTimeout(function(){ $(curFolderSelector+" .vIconOpen").addClass("vIconOpened"); },100);
	}
	//Ferme: ferme tous les sous-dossiers & modifie "open.png" (rotate)
	else{
		$(subFoldersSelector).each(function(){  folderTreeDisplay($(this).attr("data-folderId"));  });
		$(subFoldersSelector).slideUp(100);
		$(curFolderSelector+" .vIconOpen").removeClass("vIconOpened");
	}
}

////	Déplacement d'objet(s) dans un autre dossier
function folderMove(newFolderId)
{
	//Réinitialise sous les dossiers
	$(".vFolderTreeLineLabel").each(function(){  $(this).removeClass("sLinkSelect");  });
	$(".vFolderTreeLine input[name='newFolderId']").each(function(){  $(this).prop("checked",false);  });
	//Sélectionne le dossier
	$(".vFolderTreeLine[data-folderId='"+newFolderId+"'] .vFolderTreeLineLabel").addClass("sLinkSelect");
	$(".vFolderTreeLine[data-folderId='"+newFolderId+"'] input[name='newFolderId']").prop("checked",true);
}

////	Réactive les input "newFolderId" à la validation du formulaire de changement de dossier
function formControl(){
	$(".vFolderTreeLine input[name='newFolderId']").each(function(){  $(this).prop("disabled",false);  });
}
</script>

<style>
.vFolderTree		{padding:8px 4px 8px 10px;}
.vFolderTreeLine	{display:none; padding:2px;}
.vFolderTreeLineTab	{display:inline-table;}
.vFolderTreeLineIcons, .vFolderTreeLineLabel		{display:table-cell; padding:1px; vertical-align:middle;}
.vFolderTreeLineIcons								{width:34px; vertical-align:top;}
.vFolderTreeLineIcons .vIconOpen					{position:absolute; margin-top:5px; margin-left:-5px;}
.vFolderTreeLineIcons .vIconFolder					{margin-left:-6px;}
.vIconOpened										{transform:rotate(45deg);}
.vFolderTreeLineIconsRoot							{width:20px;}
.vFolderTreeLineIconsRoot .vFolderTreeLineIconsDep	{display:none;}
</style>

<?php if($context=="move"){ ?><form action="index.php" method="post" onsubmit="return formControl()"><?php }?>
<div class="vFolderTree noSelect">
	<?php foreach($folderTree as $tmpFolder){ ?>
		<div class="vFolderTreeLine" data-folderId="<?= $tmpFolder->_id ?>" data-folderIdContainer="<?= $tmpFolder->_idContainer ?>" data-treeLevel="<?= $tmpFolder->treeLevel ?>">
			<div class="vFolderTreeLineTab" title="<?= (($tmpFolder->isRootFolder() && Ctrl::$curUser->isAdminCurSpace())?Txt::trad('rootFolderEditInfo'):$tmpFolder->name)."<br>".$tmpFolder->description ?>">
				<!--icone "dossier" & affichage des sous-dossiers-->
				<div class="vFolderTreeLineIcons <?= $tmpFolder->isRootFolder()?"vFolderTreeLineIconsRoot":null ?>" onclick="folderTreeDisplay(<?= $tmpFolder->_id ?>,true)">
					<span class="vFolderTreeLineIconsDep"><img src="app/img/open.png" class="vIconOpen sLink"><img src="app/img/folderDependance.png"></span>
					<img src='app/img/folderSmall.png' class='vIconFolder'>
				</div>
				<!--Libelle du dossiers  &&  Menu de déplacement de dossier (input de sélection )-->
				<div class="vFolderTreeLineLabel sLink <?= $tmpFolder->_id==Ctrl::$curContainer->_id?"sLinkSelect":null ?>" onclick="<?= $context=="nav"?"redir('".$tmpFolder->getUrl()."')":"folderMove(".$tmpFolder->_id.")" ?>">
					<?= Txt::reduce($tmpFolder->name,60) ?>
					<?php if($context=="move"){ ?><input type="checkbox" name="newFolderId" value="<?= $tmpFolder->_id ?>" <?= $tmpFolder->_id==Ctrl::$curContainer->_id?"checked":null ?> disabled><?php }?>
				</div>
			</div>
		</div>
	<?php }?>
	<!--FORMULAIRE DE DEPLACEMENT DE DOSSIER-->
	<?php
	if($context=="move"){
		foreach(Req::getParam("targetObjects") as $tmpObjectType=>$tmpObjectIds)	{echo "<input type='hidden' name=\"targetObjects[".$tmpObjectType."]\" value=\"".$tmpObjectIds."\">";}
		echo Txt::formValidate();
	}
	?>
</div>
<?php if($context=="move"){ ?></form><?php }?>