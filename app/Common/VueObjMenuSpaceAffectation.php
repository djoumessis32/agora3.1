<script>
////	Selectionne un espace
$(function(){
	var DivSpaces="#spaceListMenu<?= $curObj->_targetObjId ?>";//Selecteur du menu des espaces
	$(DivSpaces+" :checkbox").change(function(){
		if($(this).val()=="all")	{$(DivSpaces+" :checkbox").not(this).prop("checked",false);}	//Déselectionne chaque espace si "tous les espaces" est sélectionné
		else						{$(DivSpaces+" [value='all']:checkbox").prop("checked",false);}	//Déselectionne "tous les espaces" si un espace est sélectionné
	});
});
</script>

<style>
[id^='spaceListMenu']	{<?= $displayMenu==false?"display:none;":null ?>}
.spaceListMenuDetails	{overflow:auto; max-height:100px;}
.spaceListMenuLabel		{margin-bottom:8px;}
.spaceListAffectTable	{display:inline-table; width:48%;}
.spaceListAffectCell	{display:table-cell; vertical-align:top;}
.spaceListAffectCell:first-child	{width:15px;}
.spaceListAffectCell label[data-value='all']	{font-style:italic;}
</style>

<div id="spaceListMenu<?= $curObj->_targetObjId ?>">
	<!--TITRE MENU-->
	<hr class="hrGradient">
	<div class="spaceListMenuLabel"><?= Txt::trad("visible_espaces") ?></div>
	<!--"LISTE DES ESPACES"-->
	<div class="spaceListMenuDetails">
		<?php foreach($spaceList as $tmpSpace){ ?>
		<div class="spaceListAffectTable">
			<div class="spaceListAffectCell"><input type="checkbox" name="spaceList[]" value="<?= $tmpSpace->_id ?>" data-targetObjIdContainer="<?= $curObj->_targetObjId ?>" id="box<?= $curObj->_targetObjId.$tmpSpace->_targetObjId ?>" <?= $tmpSpace->checked ?>></div>
			<div class="spaceListAffectCell"><label for="box<?= $curObj->_targetObjId.$tmpSpace->_targetObjId ?>" data-value="<?= $tmpSpace->_id ?>"><?= $tmpSpace->name ?></label></div>
		</div>
		<?php } ?>
	</div>
</div>