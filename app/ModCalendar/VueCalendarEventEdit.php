<script type="text/javascript">
////	Resize
lightboxWidth(700);

//Init la page
$(function(){
	////	Changement de date/heure : controle des créneaux horaires occupés
	$("[name='dateBegin'],[name='timeBegin'],[name='dateEnd'],[name='timeEnd']").change(function(){ timeSlotBusy(); });

	////	Changement de date/heure/périodicité : Affiche les details de périodicité?
	$("[name='periodType'],[name='dateBegin']").change(function(){ displayPeriodType(); });

	////	Coche/décoche une proposition ou affectation!
	$("[name='proposedCalendars[]'],[name='affectationCalendars[]']").change(function(){
		var tmpSelection=find("propose",this.id) ? "#affectationCalendars" : "#proposedCalendars";
		$(tmpSelection+this.value).prop("checked",false);
		if(this.checked)	{$(this).parent().parent().addClass("sTableRowSelect");}
		else				{$(this).parent().parent().removeClass("sTableRowSelect");}
		timeSlotBusy();
	});

	////	Sélection d'un groupe d'users
	$("[name='groupList[]']").on("change",function(){
		//check/uncheck chaque agenda d'user (..activé)
		$("[name='affectationCalendars[]'][data-type='user']:enabled").each(function(){
			//init
			tmpUserChecked=false;
			tmpIdUser=$(this).attr("data-idUser");
			//vérifie que l'user courant est au moins sur un groupe (..sélectionné)
			$("[name='groupList[]']:checked").each(function(){
				var userIds=$(this).val().split(",");
				if($.inArray(tmpIdUser,userIds)!=-1)	{tmpUserChecked=true;}
			});
			//check/uncheck l'user
			$(this).prop("checked",tmpUserChecked);
		});
	});

	////	Init la page : Bloque les détails de l'agenda si pas un accès total à l'evt (evt modifié par un autre que son propriétaire)
	<?php if($curObj->fullRight()==false){ ?>
	$(".dateBegin,.dateEnd").datepicker("option","disabled",true);
	$(".vEventDetails input").prop("readonly",true);
	$(".vEventDetails select").prop("disabled",true);
	<?php } ?>
	////	Init la page : Les guests n'ont pas accès aux options d'evt
	<?php if(Ctrl::$curUser->isUser()==false){ ?>
		$("#evtPeriodType,#evtOptions,.vAffectations").hide();
	<?php } ?>

	////	Init la page : valeurs des options
	$("select[name='periodType']").val("<?= $curObj->periodType ?>");
	$("select[name='contentVisible']").val("<?= $curObj->contentVisible ?>");
	$("select[name='_idCat']").val("<?= $curObj->_idCat ?>").trigger("change");//"trigger" pour changer la couleur de l'input
	$("select[name='important']").val("<?= (int)$curObj->important ?>").trigger("change");//valeur au format "integer". "trigger" pour changer la couleur de l'input

	////	Init la page : Background des lignes avec affectation && Périodicité && Créneau occupés
	$("[name='proposedCalendars[]']:checked,[name='affectationCalendars[]']:checked").each(function(){
		$(this).parent().parent().addClass("sTableRowSelect");
	});
	displayPeriodType();
	timeSlotBusy();
});

////	Gère l'affichage de la périodicité
function displayPeriodType()
{
	//Réinitialise les options de périodicité & Affiche au besoin l'options sélectionnée
	$("[id^=periodTypeOption_").hide();
	$("#blockPeriodDateEndExceptions").hide();
	if($("[name='periodType']").isEmpty()==false){
		$("#periodTypeOption_"+$("[name='periodType']").val()).show();
		$("#blockPeriodDateEndExceptions").show();
	}
	//Affiche les détails de périodicité (exple : "le 15 du mois")
	var periodTypeOptionDetails="";
	if($("[name='periodType']").val()=="month")	{periodTypeOptionDetails="<?= Txt::trad("le") ?> "+$("[name='dateBegin']").val().substr(0,2)+" <?= Txt::trad("CALENDAR_period_month_xdumois") ?> ";}//"le 15 du mois"
	if($("[name='periodType']").val()=="year")	{periodTypeOptionDetails="<?= Txt::trad("le") ?> "+$("[name='dateBegin']").val().substr(0,5);}//"le 15/10"
	$("#periodTypeOptionDetails").html(periodTypeOptionDetails);
	//Masque les exceptions de périodicité vides
	$("[id^='divPeriodDateExceptions']").each(function(){
		if($("#"+this.id.replace("div","input")).isEmpty())	{$(this).hide();}
	});
}

////	Supprime une "PeriodDateExceptions"
function deletePeriodDateExceptions(exceptionCpt)
{
	var inputSelector="#inputPeriodDateExceptions"+exceptionCpt;
	if($(inputSelector).isEmpty() || ($(inputSelector).isEmpty()==false && confirm("<?= Txt::trad("supprimer") ?>?"))){
		$(inputSelector).val("");
		$("#divPeriodDateExceptions"+exceptionCpt).hide();
	}
}

////	Controle occupation créneaux horaires des agendas sélectionnés : en AJAX
function timeSlotBusy()
{
	//"timeSlotBusy()" lancé avec 2 seconde de décallage => pour pas lancer des dixaines de demandes Ajax en même temps si on check/unckeck plusieurs agendas!
	if(typeof timeSlotLastRequest=="undefined" || (Date.now()-timeSlotLastRequest)>2000)
	{
		//dernier lancement de "timeSlotBusy()"
		timeSlotLastRequest=Date.now();
		//Lance avec un décalage : le temps que d'éventuels mutiples checks se fassent
		setTimeout(function(){
			//Prépare la requete de controle Ajax, avec la liste des Agendas sélectionnés : affectations accessibles en écriture
			var dateTimeBegin=$("[name='dateBegin']").val()+" "+$("[name='timeBegin']").val();
			var dateTimeEnd=$("[name='dateEnd']").val()+" "+$("[name='timeEnd']").val();
			if(dateTimeBegin.length>0 && dateTimeEnd.length>0)
			{
				var ajaxUrl="?ctrl=calendar&action=timeSlotBusy&dateTimeBegin="+dateTimeBegin+"&dateTimeEnd="+dateTimeEnd+"&_evtId=<?= $curObj->_id ?>&targetObjects[calendar]=";
				$("[name='proposedCalendars[]']:checked,[name='affectationCalendars[]']:checked").each(function(){ ajaxUrl+=this.value+"-"; });
				//Lance le controle Ajax et renvoie les agendas où le créneau est occupé
				$.ajax(ajaxUrl).done(function(ajaxResult){
					if(ajaxResult.length>0)	{$("#timeSlotBusy").fadeIn();  $(".vTimeSlotBusyTable").html(ajaxResult); }
					else					{$("#timeSlotBusy").hide();}
				});
			}
		}, 1000);
	}
}

////	Contrôle du formulaire
function formControl()
{
	//Controle le nombre d'affectations
	if($("[name='proposedCalendars[]']:checked").isEmpty() && $("[name='affectationCalendars[]']:checked").isEmpty())
		{displayNotif("<?= Txt::trad("CALENDAR_verif_nb_agendas") ?>");  return false;}
	//Controle final (champs obligatoires, affectations/droits d'accès, etc)
	return finalFormControl();
}
</script>

<style>
#blockDescription						{margin-top:20px; <?= empty($curObj->description)?"display:none;":null ?>}
.vEvtOptions							{margin-left:15px; margin-top:5px; display:inline-block;}
#evtOptions .vEvtOptions:first-child	{margin-left:0px;}
.vEvtOptions select						{max-width:170px;}
/*PÉRIODICITÉ*/
#periodTypeOptionDetails				{font-size:90%; text-decoration:underline;}
[id^='periodTypeOption_']				{display:none; margin-top:15px;}
#periodTypeOption_monthDay, #periodTypeOption_month	{text-align:left;}
.vPeriodTypeOptionVal								{display:inline-block; margin-left:10px;}
#periodTypeOption_monthDay .vPeriodTypeOptionVal	{width:35px; font-size:90%;}
#periodTypeOption_month .vPeriodTypeOptionVal		{width:22%;}
#blockPeriodDateEndExceptions			{display:none; margin-top:10px; text-align:center;}
#blockPeriodDateExceptions				{display:inline-block; vertical-align:middle;}
[id^='divPeriodDateExceptions']			{margin-top:5px;}
#blockPeriodDateEnd						{display:inline-block; margin-left:15px;}
/*EVTOPTIONS*/
.vEventDetails .hrGradient	{margin:10px 0px 10px 0px;}/*surcharge*/
#evtOptions					{text-align:center;}
.vContentVisibleTitle		{text-align:left;}
/*AFFECTATION AUX AGENDAS*/
.vAffectationCalendars		{max-height:300px; overflow-y:auto;}
.vAffectationBlock			{display:inline-block; width:32%; padding-left:5px; font-size:90%;}
.vAffectationBlock img		{max-height:18px;}
.vAffectationBlockTable		{display:table; width:100%;}
.vAffectationBlockCell		{display:table-cell; vertical-align:middle;}
.vAffectationBlockCell:first-child	{width:50px; text-align:right;}
[name='proposedCalendars[]']{margin:2px;}
[name='groupList[]']		{display:none;}
/*DÉTAILS SUR L'AFFECTATION*/
#timeSlotBusy				{display:none; font-size:90%;}
.vTimeSlotBusyTable			{display:table; margin-top:6px;}
.vTimeSlotBusyRow			{display:table-row;}/*cf. "actionTimeSlotBusy()"*/
.vTimeSlotBusyCell			{display:table-cell; padding:4px; vertical-align:middle;}/*idem*/
</style>

<form action="index.php" method="post" onsubmit="return formControl()" enctype="multipart/form-data">

	<!--PAS D'ACCES AUX D2TAILS A L'EVT : MESSAGE-->
	<?php if($curObj->fullRight()==false){ ?>
		<div class="labelInfos"><img src="app/img/infoSmall.png"> <?= Txt::trad("CALENDAR_edit_limite") ?></div><br>
	<?php } ?>

	<!--TITRE & DESCRIPTION (EDITOR)-->
	<div class="vEventDetails">
		<input type="text" name="title" value="<?= $curObj->title ?>" class="editInputTextBig" placeholder="<?= Txt::trad("title") ?>">
		<img src="app/img/description.png" class="sLink" title="<?= Txt::trad("description") ?>" onclick="$('#blockDescription').slideToggle(200)">
		<div id="blockDescription">
			<textarea name="description" placeholder="<?= Txt::trad("description") ?>"><?= $curObj->description ?></textarea>
		</div>
	</div>

	<fieldset class="fieldsetCenter fieldsetMarginTop sBlock vEventDetails">
		<!--DATE DEBUT & FIN-->
		<input type="text" name="dateBegin" class="dateBegin" value="<?= Txt::formatDate($curObj->dateBegin,"dbDatetime","inputDate") ?>" placeholder="<?= Txt::trad("debut") ?>">
		<input type="text" name="timeBegin" class="timeBegin" value="<?= Txt::formatDate($curObj->dateBegin,"dbDatetime","inputHM") ?>" placeholder="H:m">
		<img src="app/img/arrowRight.png">
		<input type="text" name="dateEnd" class="dateEnd" value="<?= Txt::formatDate($curObj->dateEnd,"dbDatetime","inputDate") ?>" placeholder="<?= Txt::trad("fin") ?>">
		<input type="text" name="timeEnd" class="timeEnd" value="<?= Txt::formatDate($curObj->dateEnd,"dbDatetime","inputHM") ?>" placeholder="H:m">
		<!--EVENEMENT PERIODIQUE (pas pour les guests)-->
		<span id="evtPeriodType">
			<span class="vEvtOptions">
				<?= Txt::trad("CALENDAR_periodicite") ?>
				<select name="periodType">
					<option value=""><?= Txt::trad("non") ?></option>
					<option value="weekDay"><?= Txt::trad("CALENDAR_period_weekDay") ?></option>
					<option value="month"><?= Txt::trad("CALENDAR_period_month") ?></option>
					<option value="monthDay"><?= Txt::trad("CALENDAR_period_monthDay") ?></option>
					<option value="year"><?= Txt::trad("CALENDAR_period_year") ?></option>
				</select>
			</span>
		</span>
		<!--DETAIL DE PERIODICITE (exple: "le 15 du mois"-->
		<span id="periodTypeOptionDetails"></span>
		<!--JOURS DE LA SEMAINE-->
		<div id="periodTypeOption_weekDay">
			<?php for($cpt=1; $cpt<=7; $cpt++){ ?>
			<div class="vPeriodTypeOptionVal">
				<input type="checkbox" name="periodValues_weekDay[]" value="<?= $cpt ?>" id="periodValues_weekDay<?= $cpt ?>" <?= ($curObj->periodType=="weekDay" && in_array($cpt,$tabPeriodValues))?"checked":null ?> >
				<label for="periodValues_weekDay<?= $cpt ?>"><?= Txt::trad("jour_".$cpt) ?></label>
			</div>
			<?php } ?>
		</div>
		<!--JOURS DU MOIS-->
		<div id="periodTypeOption_monthDay">
			<?php for($cpt=1; $cpt<=31; $cpt++){ ?>
			<div class="vPeriodTypeOptionVal">
				<input type="checkbox" name="periodValues_monthDay[]" value="<?= $cpt ?>" id="periodValues_monthDay<?= $cpt ?>" <?= ($curObj->periodType=="monthDay" && in_array($cpt,$tabPeriodValues))?"checked":null ?>>
				<label for="periodValues_monthDay<?= $cpt ?>"><?= $cpt ?></label>
			</div>
			<?= ($cpt==10 || $cpt==20)?"<br>":null ?>
			<?php } ?>
		</div>
		<!--MOIS DE L'ANNEE-->
		<div id="periodTypeOption_month">
			<?php for($cpt=1; $cpt<=12; $cpt++){ ?>
			<div class="vPeriodTypeOptionVal">
				<input type="checkbox" name="periodValues_month[]" value="<?= $cpt ?>" id="periodValues_month<?= $cpt ?>" <?= ($curObj->periodType=="month" && in_array($cpt,$tabPeriodValues))?"checked":null ?>>
				<label for="periodValues_month<?= $cpt ?>"><?= Txt::trad("mois_".$cpt) ?></label>
			</div>
			<?php } ?>
		</div>
		<!--FIN DE PERIODICITE & EXCEPTIONS DE PERIODICITE (10 maxi)-->
		<div id="blockPeriodDateEndExceptions">
			<div id="blockPeriodDateExceptions">
				<span class="vEvtOptions">
					<span class="sLink" onclick="$('[id^=divPeriodDateExceptions]:hidden').first().show()"><?= Txt::trad("CALENDAR_exception_periodicite") ?> <img src="app/img/plusSmall.png"></span>
					<?php for($cpt=1; $cpt<=10; $cpt++){ ?>
					<div id="divPeriodDateExceptions<?= $cpt ?>">
						<input type="text" name="periodDateExceptions[]" value="<?= isset($periodDateExceptions[$cpt])?$periodDateExceptions[$cpt]:null ?>" class="dateInput" id="inputPeriodDateExceptions<?= $cpt ?>">
						<img src="app/img/delete.png" title="<?= Txt::trad("supprimer") ?>" class="sLink" onclick="deletePeriodDateExceptions(<?= $cpt ?>)">
					</div>
					<?php } ?>
				</span>
			</div>
			<div id="blockPeriodDateEnd">
				<?= Txt::trad("CALENDAR_periodDateEnd") ?> <input type="text" name="periodDateEnd" class="dateInput" value="<?= Txt::formatDate($curObj->periodDateEnd,"dbDate","inputDate") ?>">
			</div>
		</div>
		<hr class="hrGradient">
		<!--IMPORTANT / VISIBILITE / CATEGORIE-->
		<div id="evtOptions">
			<span class="vEvtOptions">
				<?= Txt::trad("CALENDAR_categorie") ?>
				<select name="_idCat">
					<option value=""></option>
					<?php foreach(MdlCalendarEventCategory::getCategories() as $tmpCat){ ?>
					<option value="<?= $tmpCat->_id ?>" data-color="<?= $tmpCat->color ?>"><?= $tmpCat->title ?></option>
					<?php } ?>
				</select>
			</span>
			<span class="vEvtOptions">
				<?= Txt::trad("important") ?>
				<select name="important">
					<option value="0"><?= Txt::trad("non") ?></option>
					<option value="1" data-color="#900"><?= Txt::trad("oui") ?></option>
				</select>
			</span>
			<span class="vEvtOptions">
				<?= Txt::trad("CALENDAR_visibilite") ?>
				<select name="contentVisible" title="<div class='vContentVisibleTitle'><?= Txt::trad("CALENDAR_visibilite_info") ?></div>">
					<option value="public"><?= Txt::trad("CALENDAR_visibilite_public") ?></option>
					<option value="public_cache"><?= Txt::trad("CALENDAR_visibilite_public_cache") ?></option>
					<option value="prive"><?= Txt::trad("CALENDAR_visibilite_prive") ?></option>
				</select>
			</span>
		</div>
	</fieldset>

	<!--AFFECTATIONS-->
	<div class="fieldsetLabel vAffectations"><?= Txt::trad("CALENDAR_agendas_affectations") ?></div>
	<fieldset class="fieldsetMarginTop sBlock vAffectations">
		<div class="vAffectationCalendars">
		<!--LISTE DES AGENDAS-->
		<?php
		foreach(MdlCalendar::affectationCalendars() as $tmpCalendar){
			if(!empty($tmpType) && $tmpType!=$tmpCalendar->type)	{echo "<hr class='hrGradient'>";}
			$tmpType=$tmpCalendar->type;
		?>
			<div class="vAffectationBlock sTableRow">
				<div class="vAffectationBlockTable">
					<div class="vAffectationBlockCell">
						<?php if($tmpCalendar->reinitAffect==true){ ?><input type="hidden" name="reinitAffect[]" value="<?= $tmpCalendar->_id ?>"><?php } ?>
						<?php if($tmpCalendar->isMyPerso()==false){ ?><label for="proposedCalendars<?= $tmpCalendar->_id ?>">?</label><input type="checkbox" name="proposedCalendars[]" value="<?= $tmpCalendar->_id ?>" <?= $tmpCalendar->checkProposed." ".$tmpCalendar->disablePropose ?> id="proposedCalendars<?= $tmpCalendar->_id ?>" title="<?= Txt::trad("CALENDAR_input_proposer")?>"><?php } ?>
						<input type="checkbox" name="affectationCalendars[]" value="<?= $tmpCalendar->_id ?>" <?= $tmpCalendar->checkAffectation." ".$tmpCalendar->disableAffect ?> id="affectationCalendars<?= $tmpCalendar->_id ?>" data-type="<?= $tmpCalendar->type ?>" data-idUser="<?= $tmpCalendar->_idUser ?>" title="<?= Txt::trad("CALENDAR_input_affecter")?>">
					</div>
					<div class="vAffectationBlockCell">
						<label for="affectationCalendars<?= $tmpCalendar->_id ?>" title="<?= $tmpCalendar->tooltip ?>"><?= $tmpCalendar->title ?></label>
					</div>
				</div>
			</div>
		<?php } ?>
		</div>
		<?php
		//GROUPES d'UTILISATEURS
		if(!empty($userGroups))  {echo "<hr>";}
		foreach($userGroups as $tmpGroup){
			echo "<div class='vAffectationBlock sLink' title=\"".Txt::trad("select_deselect")." :<br>".$tmpGroup->usersLabel."\">
					<label for='box".$tmpGroup->_targetObjId."'><img src='app/img/user/userGroup.png'> ".$tmpGroup->title."</label>
					<input type='checkbox' name=\"groupList[]\" value=\"".implode(",",$tmpGroup->userIds)."\" id='box".$tmpGroup->_targetObjId."'>
				  </div>";
		}
		?>
		<!--CRENEAU HORAIRE OCCUPE?-->
		<div id="timeSlotBusy" class="sAccessWriteLimit">
			<hr><?= Txt::trad("CALENDAR_creneau_occupe") ?>
			<div class="vTimeSlotBusyTable"></div>
		</div>
	</fieldset>

	<!--MENU COMMUN-->
	<?= $curObj->menuEditValidate() ?>
</form>