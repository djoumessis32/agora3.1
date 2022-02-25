<script type="text/javascript">
////	Switch la sélection d'un objet
function objSelect(objectBlockId)
{
	//Swich la sélection de la checkbox
	var selectBoxId="#"+objectBlockId+"_selectBox";
	$(selectBoxId).prop("checked", !$(selectBoxId).prop("checked"));
	//Change le style du block de l'objet
	if($(selectBoxId).prop("checked"))	{$("#"+objectBlockId).removeClass("sBlock");		$("#"+objectBlockId).addClass("sBlockSelect");}
	else								{$("#"+objectBlockId).removeClass("sBlockSelect");	$("#"+objectBlockId).addClass("sBlock");}
	//Affiche/Masque le menu de sélection
	if($(":checked[name='targetObjects[]']").length==0){
		$("#objSelectSubMenu").slideUp();
		$("#objSelectLabel").html("<?= Txt::trad("tout_selectionner") ?>");
	}else{
		$("#objSelectSubMenu").slideDown();
		$("#objSelectLabel").html("<?= Txt::trad("inverser_selection") ?>");
	}
}

////	Switch la sélection de tous les objets
function objSelectToggleAll()
{
	$("[name='targetObjects[]']").each(function(){
		objSelect(this.id.replace("_selectBox",""));
	});
}

////	Action sur les objets sélectionnés
function targetObjectsAction(urlRedir, openPage)
{
	//Ajoute les objets
	var tmpObjType=null;
	var objectSelector=":checked[name='targetObjects[]']";
	$(objectSelector).each(function(){
		var targetObjId=this.value.split("-");//"fileFolder-22" -> ['fileFolder',22]
		if(tmpObjType!=targetObjId[0])	{urlRedir+="&targetObjects["+targetObjId[0]+"]="+targetObjId[1];   tmpObjType=targetObjId[0];}
		else							{urlRedir+="-"+targetObjId[1];}
	});
	//Confirme une désaffectation d'espace?
	if(find("DeleteFromCurSpace",urlRedir)){
		if(!confirm("<?= Txt::trad("USER_confirm_desaffecter_utilisateur") ?> ("+$(objectSelector).length+" elements)"))	{return false;}
	}
	//Confirme une suppression?
	else if(find("delete",urlRedir)){
		var confirmDelete="<?= Txt::trad("confirmDelete") ?> ("+$(objectSelector).length+" elements)";
		var confirmDeleteBis="<?= Txt::trad("confirmDeleteBis") ?>";
		if(!confirm(confirmDelete) || !confirm(confirmDeleteBis))	{return false;}
	}
	//Ouvre une page ou redirige
	if(openPage=="newPage")			{window.open(urlRedir);}
	else if(openPage=="lightbox")	{lightboxOpen(urlRedir);}
	else							{redir(urlRedir);}
}
</script>

<style>
#objSelectSubMenu						{display:none;}
#objSelectSubMenu .moduleMenuIcon		{width:40px; text-align:right;}
#objSelectSubMenu .moduleMenuIcon img	{max-height:22px;}
</style>


<div class="moduleMenuLine sLink" onclick="objSelectToggleAll();"><div class="moduleMenuIcon"><img src="app/img/check.png"></div><div class="moduleMenuTxt" id="objSelectLabel"><?= Txt::trad("tout_selectionner") ?></div></div>

<div id="objSelectSubMenu">
	<!--TELECHARGER FICHIERS-->
	<?php if(Req::$curCtrl=="file"){ ?>
	<div class="moduleMenuLine sLink" onclick="targetObjectsAction('?ctrl=file&action=downloadArchive','newPage');"><div class="moduleMenuIcon"><img src="app/img/download.png"></div><div class="moduleMenuTxt"><?= Txt::trad("FILE_telecharger_selection") ?></div></div>
	<?php } ?>
	<!--VOIR DES CONTACTS SUR UNE CARTE-->
	<?php if(Req::$curCtrl=="contact" || Req::$curCtrl=="user"){ ?>
		<div class="moduleMenuLine sLink" onclick="targetObjectsAction('?ctrl=misc&action=PersonsMap','lightbox');"><div class='moduleMenuIcon'><img src="app/img/map.png"></div><div class='moduleMenuTxt'><?= Txt::trad("voir_sur_carte") ?></div></div>
	<?php } ?>
	<!--DEPLACER/SUPPRIMER OBJETS-->
	<?php if(is_object($containerObj) && $containerObj->editContentRight()){ ?>
	<div class="moduleMenuLine sLink" onclick="targetObjectsAction('?ctrl=object&action=FolderMove&targetObjId=<?= $containerObj->_targetObjId ?>','lightbox');"><div class="moduleMenuIcon"><img src="app/img/folderMove.png"></div><div class="moduleMenuTxt"><?= Txt::trad("deplacer_elements") ?></div></div>
	<div class="moduleMenuLine sLink" onclick="targetObjectsAction('?ctrl=object&action=Delete');"><div class="moduleMenuIcon"><img src="app/img/delete.png"></div><div class="moduleMenuTxt"><?= Txt::trad("suppr_elements") ?></div></div>
	<?php } ?>
	<!--SUPPRIMER/DESAFFECTER DES USERS-->
	<?php if(Req::$curCtrl=="user"){ ?>
		<?php if($_SESSION["displayUsers"]=="space" && Ctrl::$curUser->isAdminCurSpace() && self::$curSpace->allUsersAffected()==false){ ?>
		<div class="moduleMenuLine sLink" onclick="targetObjectsAction('?ctrl=user&action=DeleteFromCurSpace');"><div class='moduleMenuIcon'><img src="app/img/delete.png"></div><div class='moduleMenuTxt'><?= Txt::trad("USER_desaffecter") ?></div></div>
		<?php } ?>
		<?php if(Ctrl::$curUser->isAdminGeneral()){ ?>
		<div class="moduleMenuLine sLink" onclick="targetObjectsAction('?ctrl=object&action=delete');"><div class='moduleMenuIcon'><img src="app/img/delete.png"></div><div class='moduleMenuTxt'><?= Txt::trad("USER_suppr_definitivement") ?></div></div>
		<?php } ?>
	<?php } ?>
	<hr>
</div>