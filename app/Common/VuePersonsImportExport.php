<script type="text/javascript">
lightboxWidth("90%");//Resize

////	Init la page
$(function(){
	//Affiche le formulaire d'import ou d'export
	var actionImportExport="<?= Req::getParam("actionImportExport")=="import"?"import":"export" ?>";
	$("select[name='actionImportExport']").val(actionImportExport);
	$("#"+actionImportExport+"Form").css("display","block");
	$("[name=actionImportExport]").change(function(){
		$("#exportForm").toggle();
		$("#importForm").toggle();
	});
	//Formulaire d'import : Affiche le champ "importFile" ou "ldapBaseDn"
	$("[name=importType]").change(function(){
		$("input[name=importFile]").toggle();
		$("input[name=ldapBaseDn]").toggle();
	});
	//Tableau d'import: masque le menu principal
	if($("[name^='personsImport']").exist()){
		$("#actionImportExport").css("display","none");
		$("#importForm").css("display","none");
	}
	//Tableau d'import: init le switch la sélection de personnes
	$(".switchSelection").on("click",function(){
		$("input[name^='personsImport']").each(function(){ $(this).trigger('click'); });
	});
	//Tableau d'import: init le background des lignes sélectionnées
	$("[id^=rowPerson]:has(input[name^='personsImport']:checked)").addClass("sTableRowSelect");
	//Tableau d'import: Change le background des ligne sélectionnées
	$("input[name^='personsImport']").change(function(){
		(this.checked)  ?  $("#rowPerson"+$(this).val()).addClass("sTableRowSelect")  :  $("#rowPerson"+$(this).val()).removeClass("sTableRowSelect");
	});
	//Tableau d'import: vérifie que le champ agora (<select>) n'est pas déjà sélectionné sur une autre colonne
	$("select[name^='agoraFields']").change(function(){
		curField=$(this).val();
		curFieldCpt=$(this).attr("data-fieldCpt");
		$("select[name^='agoraFields']").each(function(){
			if(curField==$(this).val()  &&  $(this).val()!=""  &&  $(this).attr("data-fieldCpt")!=curFieldCpt){
				displayNotif("<?= Txt::trad("import_alert3") ?>");
				$("select[name='agoraFields["+curFieldCpt+"]']").val(null);
				return false;
			}
		});
	});
});

////	Contrôle du formulaire
function formControl()
{
	//Fichier Import au format csv
	if($('#importForm').css('display')!="none" && $("[name='importType']").val()=="csv" && extension($("input[name='importFile']").val())!="csv"){
		displayNotif("<?= Txt::trad("extension_fichier") ?> CSV");
		return false;
	}
	//Controle le tableau d'import?
	if($("[name^='personsImport']").exist()){
		// Le champ Agora "nom" doit être sélectionné
		fieldNameSelected=false;
		$("select[name^='agoraFields']").each(function(){
			if($(this).val()=="name")	{fieldNameSelected=true;}
		});
		if(fieldNameSelected==false){
			displayNotif("<?= Txt::trad("import_alert") ?>");
			return false;
		}
		// Au moins une personne doit être sélectionné
		if($("input[name^='personsImport']:checked").length==0){
			displayNotif("<?= Txt::trad("import_alert2") ?>");
			return false;
		}
	}
}
</script>

<style>
form						{text-align:center;}
#exportForm, #importForm	{display:none; margin:20px;}
.vTableImport				{font-size:8.5px;}
img[src*='switch']			{cursor:pointer;}
.vTableImport select		{width:120px; font-size:9px;}
#divImportTable				{width:100%; overflow-x:scroll; margin-top:10px;}
input[name='ldapBaseDn']	{display:none; width:300px;}
/*affectation des espaces*/
.vImportUserOptions			{display:inline-block; text-align:left; margin-top:20px;}
.vSpaceAffect				{margin-left:20px; margin-top:5px;}
</style>

<div class="fancyboxContent">
	<form action="index.php" method="post" enctype="multipart/form-data" onsubmit="return formControl()">

		<!--FORMULAIRE DE BASE D'IMPORT/EXPORT-->
		<div id="actionImportExport">
			<select name="actionImportExport">
				<option value="export"><?= Txt::trad("exporter") ?></option>
				<option value="import"><?= Txt::trad("importer") ?></option>
			</select>&nbsp; <?= Txt::trad("import_export_".Req::$curCtrl) ?>
		</div>
		<div id="exportForm">
			<?= Txt::trad("export_format") ?>
			<select name="exportType">
				<?php foreach(MdlPerson::$csvFormats as $csvFormatKey=>$csvFormat)   {echo "<option value='".$csvFormatKey."'>".strtoupper(str_replace('_',' ',$csvFormatKey))."</option>";} ?>
				<option value="ldif">LDIF</option>
			</select>
		</div>
		<div id="importForm">
			<select name="importType">
				<option value="csv">CSV</option>
				<?= (!empty(Ctrl::$agora->ldap_server)) ? "<option value='ldap'>LDAP</option>" : null ?>
			</select>
			<input type="file" name="importFile">
			<input type="text" name="ldapBaseDn" value="<?= Ctrl::$agora->ldap_base_dn ?>" title="Base Dn">
		</div>

		<?php
		////	TABLEAU D'IMPORT
		if(Req::getParam("importType")=="ldap" || (Req::getParam("importType")=="csv" && is_file($_FILES["importFile"]["tmp_name"])))
		{
			//Init
			$getLoginPassword=($curObjClass::objectType=="user") ? true : false;
			$importPersons=array();

			//IMPORT CSV
			if(Req::getParam("importType")=="csv")
			{
				//Liste des champs (en fonction de la premiere ligne) + delimiteur des champs + nb de champs
				$tmpCpt=0;
				$csvHeader=file($_FILES["importFile"]["tmp_name"]);
				$csvHeader=str_replace(array("\r","\n"),null,$csvHeader[0]);
				if(substr_count($csvHeader,";") > $tmpCpt)		{$delimiter=";";	$tmpCpt=substr_count($csvHeader,";");}
				if(substr_count($csvHeader,",") > $tmpCpt)		{$delimiter=",";	$tmpCpt=substr_count($csvHeader,",");}
				if(substr_count($csvHeader,"	") > $tmpCpt)	{$delimiter="	";	$tmpCpt=substr_count($csvHeader,"	");}
				$headerFields=array();
				foreach(explode($delimiter,$csvHeader) as $tmpVal){
					if(!empty($tmpVal))	 {$headerFields[]=trim($tmpVal,"'\"");}
				}
				$nbFields=count($headerFields);
				//Place le contenu du csv dans un tableau
				$handle=fopen($_FILES["importFile"]["tmp_name"],"r");
				while(($data=fgetcsv($handle,10000,$delimiter))!==false)	{$importPersons[]=$data;}
			}
			//IMPORT LDAP
			elseif(Req::getParam("importType")=="ldap")
			{
				$ldapSearch=MdlPerson::ldapSearch($getLoginPassword);
				$importPersons=$ldapSearch["ldapPersons"];
				$headerFields=$ldapSearch["headerFields"];
				$nbFields=count($headerFields);
			}

			////	TABLEAU D'IMPORT
			if(empty($importPersons))	{echo "<div class='pageEmptyContent'>".Txt::trad("aucun_resultat")."</div>";}
			else
			{
				////	INFOS
				echo Txt::trad("import_infos")."<hr class='hrGradient'>";
				////	TABLEAU DES PERSONNES A IMPORTER
				echo "<div id='divImportTable'><table class='vTableImport'>";
					//HEADER
					echo "<tr>";
						echo "<th><img src='app/img/switch.png' class='sLink switchSelection' title=\"".Txt::trad("inverser_selection")."\"></th>";
						for($colFieldCpt=0; $colFieldCpt < $nbFields; $colFieldCpt++)
						{
							echo "<th><select name='agoraFields[".$colFieldCpt."]' data-fieldCpt='".$colFieldCpt."'><option></option>";
							// Affiche chaque champs de type "csv_agora" & sélectionne celle correspondante à la colonne courante (si besoin)
							foreach(MdlPerson::$csvFormats["csv_agora"]["fieldKeys"] as $agoraFieldKey)
							{
								$selectField="";
								$colFieldLabel=strtolower(Txt::clean($headerFields[$colFieldCpt],"mini"));//label du champ du fichier importé
								$colFieldLabel2=strtolower(Txt::clean(Txt::trad($agoraFieldKey),"mini"));//label du champ Agora courant
								if($colFieldLabel==$colFieldLabel2 || $colFieldLabel==$agoraFieldKey)	{$selectField="selected";}
								if(!preg_match("/(login|password)/i",$agoraFieldKey) || $getLoginPassword==true)	{echo "<option value='".$agoraFieldKey."' ".$selectField.">".Txt::trad($agoraFieldKey)."</option>";}
							}
							echo "</select></th>";
						}
					echo "</tr>";
					//PERSONNES IMPORTEES
					foreach($importPersons as $personCpt=>$personValues)
					{
						$checkedPerson=($personCpt>0 || Req::getParam("importType")!="csv") ? "checked" : null;
						echo "<tr id='rowPerson".$personCpt."'>";
							echo "<td><input type='checkbox' name='personsImport[]' value='".$personCpt."' ".$checkedPerson."></td>";
							//Affiche chaque champ de chaque personnes
							foreach($personValues as $colFieldCpt=>$fieldValue){
								//Valeur en UTF8
								$tmpLabel=$tmpValue=Txt::utf8Encode($fieldValue);
								//Masque ou vide la valeur du password?
								if(preg_match("/^pass/i",$headerFields[$colFieldCpt]) && !empty($checkedPerson)){
									if(strlen($tmpValue)>=30)	{$tmpLabel=$tmpValue=null;}//password crypté: sans intéret
									else						{$tmpLabel=preg_replace("/./","*",$tmpLabel);}
								}
								//Affiche le champ
								echo "<td>".$tmpLabel."<input type='hidden' name=\"personFields[".$personCpt."][".$colFieldCpt."]\" value=\"".$tmpValue."\"></td>";
								//"fgetcsv()" ajoute un champ vide de trop..
								if(Req::getParam("importType")=="csv" && $colFieldCpt==$nbFields-1)	{break;}
							}
						echo "</tr>";
					}
				echo "</table></div>";
				////	OPTIONS D'IMPORT D'USER
				if($curObjClass::objectType=="user")
				{
					echo "<div class='vImportUserOptions'>";
						//ENVOI DE NOTIF MAIL
						echo "<input type='checkbox' name='notifCreaUser' value='1' id='notifCreaUser'><label for='notifCreaUser' title=\"".Txt::trad("USER_envoi_coordonnees_info2")."\">".Txt::trad("USER_envoi_coordonnees")."</label><hr>";
						//ESPACES D'AFFECTATION (SI ADMIN)
						echo "<div>".Txt::trad("USER_liste_espaces")." :</div>";
						foreach(Ctrl::$curUser->getSpaces() as $tmpSpace)
						{
							if($tmpSpace->userAccessRight(Ctrl::$curUser)==2){
								$tmpChecked=($tmpSpace->_id==Ctrl::$curSpace->_id || $tmpSpace->allUsersAffected()) ? "checked" : null;//Affecté à tous les users / espace courant
								$tmpDisabled=($tmpSpace->allUsersAffected()) ? "disabled" : null;//Affecté à tous les users
								echo "<div class='vSpaceAffect'><input type=\"checkbox\" name=\"spaceAffectList[]\" value=\"".$tmpSpace->_id."\" id='spaceAffect".$tmpSpace->_id."' ".$tmpChecked." ".$tmpDisabled."><label for='spaceAffect".$tmpSpace->_id."'>".$tmpSpace->name."</label></div>";
							}
						}
					echo "</div>";
				}
			}
		}

		////	VALIDATION DU FORMULAIRE (AJOUTE LE DOSSIER CONTENEUR?)
		if(Req::isParam("targetObjId"))   {echo "<input type='hidden' name='_idContainer' value='".Ctrl::getTargetObj()->_id."'>";}
		echo Txt::formValidate();
		?>
	</form>
</div>