<div class="lightboxTitle"><?= Txt::trad("rechercher_espace") ?></div>

<script type="text/javascript">
////	Resize et sé"lection par défaut du champ de recherche
lightboxWidth(600);
$(function(){
	$("[name='searchText']").focus();
});


////	Contrôle du formulaire
function formControl()
{
	if($("[name=searchText]").val().length<3){
		displayNotif("<?= Txt::trad("preciser_text") ?>");
		return false;
	}
}

////	Recherche avancée
function displayAdvancedSearch()
{
	$(".vDivAdvancedSearch").toggle(200);
	if($("[name=advancedSearch]").val()==1)	{$("[name=advancedSearch]").val(0);}
	else									{$("[name=advancedSearch]").val(1);}
}
</script>

<style>
.vSearchMainField			{padding:10px;}
.vSearchTab					{display:table; padding:10px;}
.vSearchTabLeft				{display:table-cell; width:110px;}
.vSearchTablRight			{display:table-cell;}
.vSearchText				{width:220px; margin-right:5px;}
.vAdvancedSearchLabel		{margin-left:20px;}
.vDivAdvancedSearch			{display:<?= Req::getParam("advancedSearch")?"block":"none" ?>;}
.vDivModules,.vDivFields	{display:inline-block; width:32%; float:left; font-size:95%;}
.vSearchWordResult			{color:#900; text-decoration:underline;}
</style>

<form action="index.php" method="post" OnSubmit="return formControl()">

	<div class="vSearchMainField">
		<?= Txt::trad("keywords") ?>
		<input type="text" name="searchText" class="vSearchText" value="<?= Req::getParam("searchText") ?>">
		<?= Txt::formValidate("rechercher",false) ?>
		<label onclick="displayAdvancedSearch();" class="vAdvancedSearchLabel noSelect sLink"><?= Txt::trad("recherche_avancee") ?> <img src="app/img/plusSmall.png"></label>
		<input type="hidden" name="advancedSearch" value="<?= Req::getParam("advancedSearch") ?>">
	</div>

	<div class="vDivAdvancedSearch">
		<!--MODE DE RECHERCHE-->
		<div class="vSearchTab">
			<div class="vSearchTabLeft"><?= Txt::trad("rechercher") ?></div>
			<div class="vSearchTablRight">
				<select name="searchMode">
					<option value="someWords"><?= Txt::trad("recherche_avancee_mots_certains") ?></option>
					<option value="allWords"><?= Txt::trad("recherche_avancee_mots_tous") ?></option>
					<option value="exactPhrase"><?= Txt::trad("recherche_avancee_expression_exacte") ?></option>
				</select>
				<?php if(Req::isParam("searchMode")) {echo "<script>$('[name=searchMode]').val('".Req::getParam("searchMode")."');</script>";} ?>
			</div>
		</div>
		<!--DATE DE CREATION-->
		<div class="vSearchTab">
			<div class="vSearchTabLeft">
				<?= Txt::trad("rechercher_dateCrea") ?>
			</div>
			<div class="vSearchTablRight">
				<select name="creationDate">
					<option value="all"><?= Txt::trad("tous") ?></option>
					<option value="day"><?= Txt::trad("rechercher_dateCrea_jour") ?></option>
					<option value="week"><?= Txt::trad("rechercher_dateCrea_semaine") ?></option>
					<option value="month"><?= Txt::trad("rechercher_dateCrea_mois") ?></option>
					<option value="year"><?= Txt::trad("rechercher_dateCrea_annee") ?></option>
				</select>
				<?php if(Req::isParam("creationDate")) {echo "<script>$('[name=creationDate]').val('".Req::getParam("creationDate")."');</script>";} ?>
			</div>
		</div>
		<!--SELECTION DE MODULES-->
		<div class="vSearchTab">
			<div class="vSearchTabLeft">
				<?= Txt::trad("liste_modules") ?>
			</div>
			<div class="vSearchTablRight">
				<?php
				foreach(self::$curSpace->moduleList() as $tmpModule)
				{
					if(method_exists($tmpModule["ctrl"],"plugin")){
						$moduleChecked=(Req::isParam("searchModules")==false || in_array($tmpModule["moduleName"],Req::getParam("searchModules"))) ? "checked='checked'" : "";
						$moduleName=ucfirst(Txt::trad(strtoupper($tmpModule["moduleName"])."_headerModuleName"));
						echo "<div class='vDivModules'><input type='checkbox' name='searchModules[]' value='".$tmpModule["moduleName"]."' ".$moduleChecked."> ".$moduleName."</div>";
					}
				}
				?>
			</div>
		</div>
		<!--SELECTION DES CHAMPS DE RECHERCHE-->
		<div class="vSearchTab">
			<div class="vSearchTabLeft">
				<?= Txt::trad("liste_champs") ?>
			</div>
			<div class="vSearchTablRight">
				<?php foreach($searchFields as $fieldName=>$fieldParams){ ?>
				<div class="vDivFields" title="<?= Txt::trad("liste_champs_elements")." :<br>".$fieldParams["title"] ?>">
					<input type="checkbox" name="searchFields[]" value="<?= $fieldName ?>" <?= $fieldParams["checked"] ?>> <?= ucfirst(Txt::trad($fieldName)) ?>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</form>

<!--RESULTATS DE LA RECHERCHE-->
<?php
if(Req::isParam("searchText"))
{
	//Résultats à afficher
	$searchTexts=explode(" ",Req::getParam("searchText"));
	foreach($pluginsSearchResult as $pluginObj)
	{
		//Entête du module courant
		if(empty($curModule) || (isset($pluginObj->pluginModule) && $curModule!=$pluginObj->pluginModule)){
			echo "<div class='pluginModule'><hr><img src=\"app/img/".$pluginObj->pluginModule."/icon.png\"></div>";
			$curModule=$pluginObj->pluginModule;
		}
		//ligne de l'element de résultat
		$pluginObj->pluginIcon=(!empty($pluginObj->pluginIsFolder)) ? "folder.png" : "dotY.png";
		foreach($searchTexts as $searchText)	{$pluginObj->pluginLabel=preg_replace("/".$searchText."/i", "<span class='vSearchWordResult'>".$searchText."</span>", $pluginObj->pluginLabel);}
		echo "<div class='menuContextLine sLink'><div class='menuContextIcon' onclick=\"".$pluginObj->pluginJsIcon."\"><img src='app/img/".$pluginObj->pluginIcon."' class='pluginIcon'></div><div class='menuContextTxt' title=\"".$pluginObj->pluginTitle."\" onclick=\"".$pluginObj->pluginJsLabel."\">".$pluginObj->pluginLabel."</div></div>";
	}
	//Aucun résultat à afficher
	if(empty($pluginsSearchResult))	{echo "<div class='pluginEmpty'>".Txt::trad("aucun_resultat")."</div>";}
}